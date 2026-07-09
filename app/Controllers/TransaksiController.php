<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;
use Dompdf\Dompdf;

class TransaksiController extends BaseController
{
    public function index(): string
    {
        $cart = session()->get('cart') ?? [];
        return view('v_keranjang', ['cart' => $cart]);
    }

    private function _saveCartToDB($cart = null)
    {
        if (session()->get('id')) {
            $userModel = new \App\Models\UserModel();
            $cartData = $cart !== null ? $cart : (session()->get('cart') ?? []);
            $userModel->update(session()->get('id'), ['cart_data' => json_encode($cartData)]);
        }
    }

    public function add($id)
    {
        $productModel = new ProductModel();
        $product = $productModel->find($id);

        if ($product) {
            // Cek stok tersedia
            if ($product['jumlah'] <= 0) {
                return redirect()->to(base_url('produk'))->with('error', 'Maaf, stok produk "' . $product['nama'] . '" sudah habis!');
            }

            $cart = session()->get('cart') ?? [];
            
            $found = false;
            foreach ($cart as &$item) {
                if ($item['id'] == $id) {
                    // Cek apakah qty di keranjang sudah melebihi stok
                    if ($item['qty'] >= $product['jumlah']) {
                        return redirect()->to(base_url('keranjang'))->with('error', 'Stok produk "' . $product['nama'] . '" tidak mencukupi! Tersisa ' . $product['jumlah'] . ' buah.');
                    }
                    $item['qty'] += 1;
                    $found = true;
                    break;
                }
            }

            if (!$found) {
                $cart[] = [
                    'id' => $product['id'],
                    'nama' => $product['nama'],
                    'harga' => $product['harga'],
                    'qty' => 1,
                    'foto' => $product['foto']
                ];
            }

            session()->set('cart', $cart);
            $this->_saveCartToDB($cart);
            return redirect()->to(base_url('keranjang'))->with('success', 'Produk "' . $product['nama'] . '" berhasil ditambahkan ke keranjang!');
        }

        return redirect()->to(base_url('produk'));
    }

    public function update()
    {
        $cart = session()->get('cart') ?? [];
        $qty = $this->request->getPost('qty');

        if ($qty) {
            foreach ($cart as $key => &$item) {
                if (isset($qty[$item['id']])) {
                    $item['qty'] = $qty[$item['id']];
                }
            }
            session()->set('cart', $cart);
            $this->_saveCartToDB($cart);
        }
        return redirect()->to(base_url('keranjang'));
    }

    public function remove($id)
    {
        $cart = session()->get('cart') ?? [];
        
        foreach ($cart as $key => $item) {
            if ($item['id'] == $id) {
                unset($cart[$key]);
                break;
            }
        }
        
        session()->set('cart', array_values($cart));
        $this->_saveCartToDB(array_values($cart));
        return redirect()->to(base_url('keranjang'));
    }

    public function clear()
    {
        session()->remove('cart');
        $this->_saveCartToDB([]);
        return redirect()->to(base_url('keranjang'));
    }

    public function checkout()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('keranjang'));
        }
        return view('v_checkout', ['cart' => $cart]);
    }

    public function download()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('keranjang'));
        }

        $html = view("v_keranjangPDF", ["cart" => $cart]);
        $filename = "Invoice-" . date("Y-m-d-H-i-s");

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "portrait");
        $dompdf->render();
        $dompdf->stream($filename);
    }

    public function getLocation()
    {
        $search = $this->request->getGet('search');
        $client = new \GuzzleHttp\Client();
        $apiKey = env('COST_KEY', 'ffecd68fbfb82b40792a758ae5e688b7');

        $response = $client->request(
            'GET',
            'https://rajaongkir.komerce.id/api/v1/destination/domestic-destination?search='.$search.'&limit=50', [
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $apiKey,
                ],
                'http_errors' => false
            ]
        );

        $body = json_decode($response->getBody(), true);
        
        if (isset($body['data'])) {
            return $this->response->setJSON($body['data']);
        }
        return $this->response->setJSON([]);
    }

    public function getCost()
    {
        $destination = $this->request->getGet('destination');
        $client = new \GuzzleHttp\Client();
        $apiKey = env('COST_KEY', 'ffecd68fbfb82b40792a758ae5e688b7');

        // Dapatkan origin dari pengaturan toko
        $addressFile = WRITEPATH . 'store_address.json';
        $originId = '17473'; // Default: GROGOL, GROGOL PETAMBURAN
        if (file_exists($addressFile)) {
            $storeAddress = json_decode(file_get_contents($addressFile), true);
            if (isset($storeAddress['id'])) {
                $originId = $storeAddress['id'];
            }
        }

        $response = $client->request(
            'POST',
            'https://rajaongkir.komerce.id/api/v1/calculate/domestic-cost', [
                'multipart' => [
                    [ 'name' => 'origin', 'contents' => $originId ],
                    [ 'name' => 'destination', 'contents' => $destination ],
                    [ 'name' => 'weight', 'contents' => '1000' ],
                    [ 'name' => 'courier', 'contents' => 'jne' ]
                ],
                'headers' => [
                    'accept' => 'application/json',
                    'key' => $apiKey,
                ],
                'http_errors' => false
            ]
        );

        $body = json_decode($response->getBody(), true);
        
        if (isset($body['data'])) {
            return $this->response->setJSON($body['data']);
        }
        return $this->response->setJSON([]);
    }

    public function buy()
    {
        $cart = session()->get('cart') ?? [];
        if (empty($cart)) {
            return redirect()->to(base_url('keranjang'));
        }

        $transactionModel = new TransactionModel();
        $detailModel = new TransactionDetailModel();
        $productModel = new ProductModel();

        $subtotal = 0;
        foreach ($cart as $item) {
            $subtotal += $item['harga'] * $item['qty'];
        }

        $ongkir = $this->request->getPost('ongkir') ?? 0;
        $totalHarga = $subtotal + $ongkir;

        $alamatLengkap = $this->request->getPost('alamat');
        $kelurahanNama = $this->request->getPost('kelurahan_nama');
        if (!empty($kelurahanNama)) {
            $alamatLengkap .= "\n" . $kelurahanNama;
        }

        $layanan = $this->request->getPost('layanan');

        $transactionData = [
            'username' => session()->get('username'),
            'total_harga' => $totalHarga,
            'alamat' => $alamatLengkap,
            'ongkir' => $ongkir,
            'layanan' => $layanan,
            'status' => 0
        ];

        $transactionId = $transactionModel->insert($transactionData);

        $totalHpp = 0;

        foreach ($cart as $item) {
            $detailData = [
                'transaction_id' => $transactionId,
                'product_id' => $item['id'],
                'jumlah' => $item['qty'],
                'diskon' => 0,
                'subtotal_harga' => $item['harga'] * $item['qty']
            ];
            $detailModel->insert($detailData);

            $product = $productModel->find($item['id']);
            if ($product) {
                $newStock = $product['jumlah'] - $item['qty'];
                $productModel->update($item['id'], ['jumlah' => $newStock]);
            }
        }

        session()->remove('cart');
        $this->_saveCartToDB([]);

        // Midtrans Integration
        $serverKey = env('MIDTRANS_SERVER_KEY', 'SB-Mid-server-xW1v_H2550T_73mKq68N6Q8r');
        $isProduction = false;
        
        $transaction_details = [
            'order_id' => 'TRX-' . time() . '-' . $transactionId,
            'gross_amount' => $totalHarga,
        ];

        $customer_details = [
            'first_name'    => session()->get('username'),
            'email'         => session()->get('username') . '@example.com', // Placeholder email
            'phone'         => '081234567890',
        ];

        $payload = [
            'transaction_details' => $transaction_details,
            'customer_details'    => $customer_details
        ];

        $auth = base64_encode($serverKey . ':');
        $url = $isProduction ? 'https://app.midtrans.com/snap/v1/transactions' : 'https://app.sandbox.midtrans.com/snap/v1/transactions';
        
        $client = \Config\Services::curlrequest();
        try {
            $response = $client->post($url, [
                'headers' => [
                    'Authorization' => 'Basic ' . $auth,
                    'Content-Type'  => 'application/json',
                    'Accept'        => 'application/json'
                ],
                'json' => $payload,
                'http_errors' => false
            ]);
            
            $result = json_decode($response->getBody());
            
            if (isset($result->token)) {
                $transactionModel->update($transactionId, [
                    'payment_reference' => $result->token,
                    'checkout_url'      => $result->redirect_url
                ]);
                
                session()->setFlashdata('success', 'Silakan selesaikan pembayaran pesanan Anda.');
                return redirect()->to($result->redirect_url); // Redirect to Midtrans Hosted Checkout
            }
        } catch (\Exception $e) {
            // Log error
        }

        session()->setFlashdata('success', 'Checkout berhasil! Silakan lakukan pembayaran pada pesanan Anda.');
        return redirect()->to(base_url('profile'));
    }

    public function paymentCallback()
    {
        $json = $this->request->getBody();
        $notification = json_decode($json);

        if (!$notification) {
            return $this->response->setStatusCode(400);
        }

        $orderId = $notification->order_id;
        $status = $notification->transaction_status;
        $paymentType = $notification->payment_type ?? '';
        
        $parts = explode('-', $orderId);
        $transactionId = end($parts);
        
        $transactionModel = new TransactionModel();
        $buy = $transactionModel->find($transactionId);
        
        if ($buy) {
            if ($status == 'capture' || $status == 'settlement') {
                if ($buy['status'] == 0) {
                    $transactionModel->update($transactionId, [
                        'status' => 1,
                        'payment_method' => $paymentType
                    ]);
                    
                    // Integrasi Akuntansi
                    $db = \Config\Database::connect();
                    
                    // 1. Catat Penjualan (Kas Masuk)
                    $db->table('tabel_jurnal_kas')->insert([
                        'tanggal' => date('Y-m-d H:i:s'),
                        'jenis' => 'masuk',
                        'keterangan' => 'Penjualan TRX-' . $transactionId . ' (Otomatis Midtrans)',
                        'nominal' => $buy['total_harga']
                    ]);
                    
                    // 2. Otomatis mencatat beban ongkir (Kas Keluar)
                    if (isset($buy['ongkir']) && $buy['ongkir'] > 0) {
                        $db->table('tabel_beban')->insert([
                            'tanggal' => date('Y-m-d H:i:s'),
                            'nama_beban' => 'Beban Ongkos Kirim TRX-' . $transactionId,
                            'nominal' => $buy['ongkir']
                        ]);
                        $db->table('tabel_jurnal_kas')->insert([
                            'tanggal' => date('Y-m-d H:i:s'),
                            'jenis' => 'keluar',
                            'keterangan' => 'Pengeluaran Operasional: Beban Ongkos Kirim TRX-' . $transactionId,
                            'nominal' => $buy['ongkir']
                        ]);
                    }
                }
            } else if ($status == 'cancel' || $status == 'deny' || $status == 'expire') {
                $transactionModel->update($transactionId, ['status' => 4]); // Batal
            }
        }
        
        return $this->response->setJSON(['status' => 'ok']);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        $resi = $this->request->getPost('resi');
        $transaction = new TransactionModel();
        
        $buy = $transaction->find($id);
        if ($buy) {
            $oldStatus = $buy['status'];
            
            if ($transaction->updateStatus($id, $status, $resi)) {
                
                // Integrasi Akuntansi: Jika admin mengubah status dari 0 (Menunggu Pembayaran) ke 1 (Sudah Dibayar)
                if ($oldStatus == 0 && $status == 1) {
                    $db = \Config\Database::connect();

                    // 1. Catat Penjualan (Kas Masuk)
                    $db->table('tabel_jurnal_kas')->insert([
                        'tanggal' => date('Y-m-d H:i:s'),
                        'jenis' => 'masuk',
                        'keterangan' => 'Penjualan TRX-' . $id,
                        'nominal' => $buy['total_harga']
                    ]);
                    
                    // 2. Otomatis mencatat beban ongkir (Kas Keluar)
                    if (isset($buy['ongkir']) && $buy['ongkir'] > 0) {
                        $db->table('tabel_beban')->insert([
                            'tanggal' => date('Y-m-d H:i:s'),
                            'nama_beban' => 'Beban Ongkos Kirim TRX-' . $id,
                            'nominal' => $buy['ongkir']
                        ]);
                        $db->table('tabel_jurnal_kas')->insert([
                            'tanggal' => date('Y-m-d H:i:s'),
                            'jenis' => 'keluar',
                            'keterangan' => 'Pengeluaran Operasional: Beban Ongkos Kirim TRX-' . $id,
                            'nominal' => $buy['ongkir']
                        ]);
                    }
                }

                // Refund: Jika dibatalkan (4) dan sebelumnya sudah dibayar (1, 2, 3)
                if ($oldStatus >= 1 && $status == 4) {
                    $db = \Config\Database::connect();
                    $db->table('tabel_jurnal_kas')->insert([
                        'tanggal' => date('Y-m-d H:i:s'),
                        'jenis' => 'keluar',
                        'keterangan' => 'Refund Pembatalan Pesanan TRX-' . $id,
                        'nominal' => $buy['total_harga']
                    ]);
                }

                // --- KIRIM NOTIFIKASI EMAIL ---
                try {
                    $db = \Config\Database::connect();
                    $user = $db->table('user')->where('username', $buy['username'])->get()->getRow();
                    
                    if ($user && !empty($user->email)) {
                        $emailService = \Config\Services::email();
                        $emailService->setTo($user->email);
                        $emailService->setFrom('no-reply@sebulwatch.co', 'Sebul Watch Co.');
                        
                        $stLabels = [
                            0 => 'Menunggu Pembayaran',
                            1 => 'Sudah Dibayar (Diproses)',
                            2 => 'Sedang Dikirim',
                            3 => 'Sudah Selesai',
                            4 => 'Dibatalkan'
                        ];
                        
                        $subject = "Pembaruan Status Pesanan #" . $id . " - Sebul Watch Co.";
                        $message = "Halo " . $user->username . ",<br><br>";
                        $message .= "Status pesanan Anda (Order ID: #" . $id . ") telah diperbarui menjadi: <strong>" . ($stLabels[$status] ?? 'Tidak Diketahui') . "</strong>.<br><br>";
                        
                        if ($status == 2 && !empty($resi)) {
                            $message .= "Nomor Resi Pengiriman Anda: <strong>" . $resi . "</strong><br><br>";
                        }
                        
                        $message .= "Terima kasih telah berbelanja di Sebul Watch Co.!";
                        
                        $emailService->setSubject($subject);
                        $emailService->setMessage($message);
                        
                        // Ignore errors if SMTP is not configured locally
                        @$emailService->send();
                    }
                } catch (\Exception $e) {
                    // Fail silently so it doesn't break the app in local development
                }
                // ------------------------------

                return redirect()->back()->with('success', 'Status transaksi berhasil diperbarui. Notifikasi email terkirim (jika SMTP aktif).');
            }
        }
        return redirect()->back()->with('error', 'Gagal memperbarui status transaksi.');
    }
    public function uploadBukti()
    {
        $id = $this->request->getPost('id_pembelian');
        $file = $this->request->getFile('bukti');

        if ($file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $file->move(FCPATH . 'uploads/bukti/', $newName);

            $transactionModel = new \App\Models\TransactionModel();
            
            $buy = $transactionModel->find($id);
            if ($buy) {
                $transactionModel->update($id, [
                    'bukti_pembayaran' => $newName,
                    'updated_at' => date('Y-m-d H:i:s')
                ]);
            }

            return redirect()->back()->with('success', 'Bukti pembayaran berhasil diupload.');
        }

        return redirect()->back()->with('error', 'Upload bukti gagal.');
    }

    public function exportPdf()
    {
        $model = new TransactionModel();
        $transactions = $model->orderBy('created_at', 'ASC')->findAll();
        
        $html = view("v_penjualanPDF", ["transactions" => $transactions]);
        $filename = "Data-Penjualan-" . date("Y-m-d-His");
        
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "landscape"); // Landscape better for many columns
        $dompdf->render();
        $dompdf->stream($filename);
    }

    public function exportExcel()
    {
        $model = new TransactionModel();
        $transactions = $model->orderBy('created_at', 'ASC')->findAll();
        
        $data = [
            'transactions' => $transactions
        ];
        
        $filename = "Data-Penjualan-" . date("Y-m-d-His") . ".xls";
            
        return $this->response
            ->setContentType('application/vnd-ms-excel')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(view('v_penjualanExcel', $data));
    }
}
