<?php

namespace App\Controllers;

use App\Models\TransactionModel;

class Home extends BaseController
{
    public function index(): string
    {
        if (!session()->get('username')) {
            $productModel = new \App\Models\ProductModel();
            $data['products'] = $productModel->findAll();
            
            $db = \Config\Database::connect();
            $data['top_products'] = $db->query("
                SELECT p.id, p.nama, p.foto, p.harga, p.jumlah as stok, SUM(td.jumlah) terjual
                FROM transaction_detail td  
                JOIN product p ON p.id = td.product_id  
                JOIN transaction t ON t.id = td.transaction_id
                WHERE t.status >= 1
                GROUP BY p.id  
                ORDER BY terjual DESC
                LIMIT 3
            ")->getResultArray();

            return view('v_preview', $data);
        }

        $model = new TransactionModel();
        
        if (session()->get('role') == 'admin') {
            $transactions = $model->orderBy('created_at', 'DESC')->findAll(10);
        } else {
            $transactions = $model->where('username', session()->get('username'))->orderBy('created_at', 'DESC')->findAll(10);
        }
        
        $db = \Config\Database::connect();
        $details = [];
        
        foreach ($transactions as $trx) {
            $trxDetails = $db->table('transaction_detail')
                ->select('transaction_detail.*, product.nama, product.foto')
                ->join('product', 'product.id = transaction_detail.product_id', 'left')
                ->where('transaction_id', $trx['id'])
                ->get()->getResultArray();
            $details[$trx['id']] = $trxDetails;
        }

        return view('v_home', [
            'transactions' => $transactions,
            'details' => $details
        ]);
    }

    public function profile()
    {
        helper('number');
        $transaction = new \App\Models\TransactionModel();
        $transaction_detail = new \App\Models\TransactionDetailModel();
        $username = session()->get('username');
        $data['username'] = $username;
        
        if (session()->get('role') == 'admin') {
            $buy = $transaction->orderBy('created_at', 'DESC')->findAll();
        } else {
            $buy = $transaction->where('username', $username)->orderBy('created_at', 'DESC')->findAll();
        }
        
        $data['buy'] = $buy;
        $product = [];
        if (!empty($buy)) {
            foreach ($buy as $item) {
                $detail = $transaction_detail
                    ->select('transaction_detail.*, product.nama, product.harga, product.foto')
                    ->join('product', 'transaction_detail.product_id=product.id', 'left')
                    ->where('transaction_id', $item['id'])
                    ->findAll();
                if (!empty($detail)) {
                    $product[$item['id']] = $detail;
                }
            }
        }
        $data['product'] = $product;
        return view('v_profile', $data);
    }

    public function invoice($id)
    {
        helper('number');
        $transaction = new \App\Models\TransactionModel();
        $transaction_detail = new \App\Models\TransactionDetailModel();
        
        $username = session()->get('username');
        
        $buy = $transaction->where('id', $id)->where('username', $username)->first();
        if (!$buy) {
            return redirect()->to('profile')->with('error', 'Transaksi tidak ditemukan');
        }

        $detail = $transaction_detail
            ->select('transaction_detail.*, product.nama, product.harga, product.foto')
            ->join('product', 'transaction_detail.product_id=product.id', 'left')
            ->where('transaction_id', $id)
            ->findAll();

        $addressFile = WRITEPATH . 'store_address.json';
        $storeAddress = ['name' => 'Gegerkalong, Sukasari, Kota Bandung (Pusat)'];
        if (file_exists($addressFile)) {
            $storeAddress = json_decode(file_get_contents($addressFile), true);
        }

        $data = [
            'transaction' => $buy,
            'details' => $detail,
            'username' => $username,
            'store_address' => $storeAddress
        ];

        return view('v_invoice', $data);
    }

    public function completeTransaction($id)
    {
        $transaction = new \App\Models\TransactionModel();
        $username = session()->get('username');
        
        $buy = $transaction->where('id', $id)->where('username', $username)->first();
        if ($buy) {
            $transaction->update($id, ['status' => 3]);
        }
        
        return redirect()->to('profile')->with('success', 'Status pesanan berhasil diselesaikan.');
    }

    public function payTransaction($id)
    {
        $transaction = new \App\Models\TransactionModel();
        $username = session()->get('username');
        
        $buy = $transaction->where('id', $id)->where('username', $username)->first();
        if ($buy && $buy['status'] == 0) {
            $transaction->update($id, ['status' => 1]); // Status 1: Sudah Dibayar

            // --- INTEGRASI LAPORAN KEUANGAN (AKUNTANSI) ---
            $db = \Config\Database::connect();
            
            // Hitung HPP
            $details = $db->table('transaction_detail')
                ->select('transaction_detail.jumlah, product.harga_beli')
                ->join('product', 'product.id = transaction_detail.product_id', 'left')
                ->where('transaction_id', $id)
                ->get()->getResultArray();
            
            $totalHpp = 0;
            foreach ($details as $d) {
                // Konversi null jadi 0 kalau ada produk terhapus
                $hargaBeli = $d['harga_beli'] ?? 0;
                $totalHpp += ($hargaBeli * $d['jumlah']);
            }

            // 1. Catat Penjualan (Kas Masuk)
            $db->table('tabel_jurnal_kas')->insert([
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis' => 'masuk',
                'keterangan' => 'Penjualan TRX-' . $id,
                'nominal' => $buy['total_harga']
            ]);

            // 2. Catat HPP (Kas Keluar - Harga Beli/Modal)
            if ($totalHpp > 0) {
                $db->table('tabel_jurnal_kas')->insert([
                    'tanggal' => date('Y-m-d H:i:s'),
                    'jenis' => 'keluar',
                    'keterangan' => 'Pembelian Barang (Modal) TRX-' . $id,
                    'nominal' => $totalHpp
                ]);
            }
            // ----------------------------------------------

            return redirect()->to('profile')->with('success', 'Pembayaran berhasil dikonfirmasi! Pesanan Anda kini berstatus "Sudah Dibayar".');
        }
        
        return redirect()->to('profile')->with('error', 'Pembayaran gagal atau pesanan tidak valid.');
    }

    public function penjualan()
    {
        if (session()->get('role') != 'admin') {
            return redirect()->to(base_url('/'));
        }
        $transaction = new \App\Models\TransactionModel();
        
        $statusFilter = $this->request->getGet('status');
        
        if ($statusFilter !== null && $statusFilter !== '') {
            if ($statusFilter === 'validasi') {
                $transaction->where('status', 0)->where('bukti_pembayaran !=', null)->where('bukti_pembayaran !=', '');
            } else if ($statusFilter === '0') {
                $transaction->where('status', 0)->groupStart()->where('bukti_pembayaran', null)->orWhere('bukti_pembayaran', '')->groupEnd();
            } else {
                $transaction->where('status', $statusFilter);
            }
        }
        
        // Ambil semua transaksi urutkan dari yang terlama ke yang terbaru
        $data['transactions'] = $transaction->orderBy('created_at', 'ASC')->findAll();
        $data['current_status_filter'] = $statusFilter;

        $db = \Config\Database::connect();
        $reviews = $db->table('product_reviews')
                      ->select('product_reviews.*, product.nama as product_name')
                      ->join('product', 'product.id = product_reviews.product_id', 'left')
                      ->get()->getResultArray();
                      
        $groupedReviews = [];
        foreach ($reviews as $r) {
            $groupedReviews[$r['transaction_id']][] = $r;
        }
        $data['reviews'] = $groupedReviews;

        return view('v_penjualan', $data);
    }
}
