<?php

namespace App\Controllers;

use App\Models\ProductModel;
use Dompdf\Dompdf;

class ProdukController extends BaseController
{
    public function index(): string
    {
        $model = new ProductModel();
        
        $query = $this->request->getGet('query');
        
        $db = \Config\Database::connect();
        $suppliers = $db->table('supplier')->get()->getResultArray();

        // Gabung data produk dengan nama supplier jika perlu
        $queryBuilder = $db->table('product')
                           ->select('product.*, supplier.nama as supplier_nama')
                           ->join('supplier', 'supplier.id = product.supplier_id', 'left');
                           
        if (!empty($query)) {
            $produk = $queryBuilder->like('product.nama', $query)->get()->getResultArray();
        } else {
            $produk = $queryBuilder->get()->getResultArray();
        }

        $data = [
            "produk" => $produk,
            "query" => $query,
            "suppliers" => $suppliers
        ];

        return view("v_produk", $data);
    }

    public function create()
    {
        $model = new ProductModel();

        $foto = $this->request->getFile('foto');
        $namaFoto = '';

        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            // Pindahkan file ke folder NiceAdmin/assets/img/
            $foto->move(FCPATH . 'NiceAdmin/assets/img/', $namaFoto);
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'harga_beli' => $this->request->getPost('harga_beli') ?? 0,
            'jumlah' => $this->request->getPost('jumlah'),
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'foto' => $namaFoto
        ];

        $model->insert($data);

        // Catat Kas Keluar (Pembelian Stok Awal)
        $harga_beli = (int) $this->request->getPost('harga_beli');
        $jumlah = (int) $this->request->getPost('jumlah');
        if ($harga_beli > 0 && $jumlah > 0) {
            $db = \Config\Database::connect();
            $db->table('tabel_jurnal_kas')->insert([
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis' => 'keluar',
                'keterangan' => 'Stok Awal: ' . $this->request->getPost('nama') . ' (' . $jumlah . ' pcs)',
                'nominal' => $harga_beli * $jumlah
            ]);
        }

        return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil ditambahkan dan stok awal dicatat sebagai Kas Keluar.');
    }

    public function edit($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to(base_url('produk'))->with('error', 'Produk tidak ditemukan.');
        }

        $foto = $this->request->getFile('foto');
        $namaFoto = $product['foto']; // Gunakan foto lama secara default

        if ($foto->isValid() && !$foto->hasMoved()) {
            $namaFoto = $foto->getRandomName();
            $foto->move(FCPATH . 'NiceAdmin/assets/img/', $namaFoto);
            
            // Opsional: Hapus foto lama jika ada
            if ($product['foto'] && file_exists(FCPATH . 'NiceAdmin/assets/img/' . $product['foto'])) {
                unlink(FCPATH . 'NiceAdmin/assets/img/' . $product['foto']);
            }
        }

        $data = [
            'nama' => $this->request->getPost('nama'),
            'harga' => $this->request->getPost('harga'),
            'harga_beli' => $this->request->getPost('harga_beli') ?? 0,
            'jumlah' => $this->request->getPost('jumlah'),
            'supplier_id' => $this->request->getPost('supplier_id') ?: null,
            'foto' => $namaFoto
        ];

        $model->update($id, $data);

        return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if ($product) {
            // Hapus foto jika ada
            if ($product['foto'] && file_exists('NiceAdmin/assets/img/' . $product['foto'])) {
                unlink('NiceAdmin/assets/img/' . $product['foto']);
            }
            $model->delete($id);
            return redirect()->to(base_url('produk'))->with('success', 'Produk berhasil dihapus.');
        }

        return redirect()->to(base_url('produk'))->with('error', 'Produk gagal dihapus.');
    }

    public function reset_casio()
    {
        $db = \Config\Database::connect();
        
        // 1. Kosongkan tabel produk dan reset ID ke 1
        $db->table('product')->truncate();
        // 2. Kosongkan keranjang di session
        session()->remove('cart');

        // 3. Masukkan jam Casio
        $casio_products = [
            ['nama' => 'Casio G-Shock GA-2100', 'harga' => 1500000, 'jumlah' => 10, 'foto' => 'casio_gshock_1779677957096.png', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Casio Edifice EFR-552', 'harga' => 1800000, 'jumlah' => 5, 'foto' => 'casio_edifice_1779677993374.png', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Casio Vintage A158WA', 'harga' => 350000, 'jumlah' => 20, 'foto' => 'casio_vintage_1779678023068.png', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['nama' => 'Casio Pro Trek PRG-270', 'harga' => 2500000, 'jumlah' => 3, 'foto' => 'casio_protrek_1779678140286.png', 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')]
        ];

        foreach ($casio_products as $p) {
            $db->table('product')->insert($p);
        }

        return redirect()->to(base_url('produk'))->with('success', 'Toko berhasil diubah menjadi Toko Jam Casio!');
    }

    public function exportPdf()
    {
        $model = new ProductModel();
        $product = $model->findAll();
        $html = view("v_produkPDF", ["product" => $product]);
        $filename = "Katalog-Produk-" . date("Y-m-d-His");
        
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper("A4", "portrait");
        $dompdf->render();
        $dompdf->stream($filename);
    }

    public function exportExcel()
    {
        $model = new ProductModel();
        $product = $model->findAll();
        
        $data = [
            'product' => $product
        ];
        
        $filename = "Katalog-Produk-" . date("Y-m-d-His") . ".xls";
            
        return $this->response
            ->setContentType('application/vnd-ms-excel')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(view('v_produkExcel', $data));
    }

    public function review()
    {
        $db = \Config\Database::connect();
        
        $productId = $this->request->getPost('product_id');
        $transactionId = $this->request->getPost('transaction_id');
        $username = session()->get('username');

        // Check for existing review
        $existing = $db->table('product_reviews')
                       ->where('product_id', $productId)
                       ->where('transaction_id', $transactionId)
                       ->where('username', $username)
                       ->countAllResults();

        if ($existing > 0) {
            return redirect()->back()->with('error', 'Anda sudah memberikan ulasan untuk produk ini pada transaksi tersebut.');
        }

        $db->table('product_reviews')->insert([
            'product_id' => $productId,
            'transaction_id' => $transactionId,
            'username' => $username,
            'rating' => $this->request->getPost('rating'),
            'comment' => $this->request->getPost('comment'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        return redirect()->back()->with('success', 'Terima kasih! Ulasan dan penilaian Anda berhasil disimpan.');
    }

    public function restock($id)
    {
        $model = new ProductModel();
        $product = $model->find($id);

        if (!$product) {
            return redirect()->to(base_url('produk'))->with('error', 'Produk tidak ditemukan.');
        }

        $jumlah_tambah = (int) $this->request->getPost('jumlah_tambah');
        $harga_beli_sekarang = (int) $this->request->getPost('harga_beli_sekarang');

        if ($jumlah_tambah <= 0) {
            return redirect()->to(base_url('produk'))->with('error', 'Jumlah stok yang ditambahkan tidak valid.');
        }

        // 1. Update stok dan harga beli terbaru di tabel product
        $new_stok = $product['jumlah'] + $jumlah_tambah;
        $model->update($id, [
            'jumlah' => $new_stok,
            'harga_beli' => $harga_beli_sekarang
        ]);

        // 2. Catat sebagai Hutang (Accounts Payable) ke Supplier
        $total_bayar_supplier = $jumlah_tambah * $harga_beli_sekarang;
        
        $db = \Config\Database::connect();
        $db->table('tabel_hutang')->insert([
            'supplier_id' => $product['supplier_id'],
            'product_id' => $id,
            'jumlah_restock' => $jumlah_tambah,
            'total_harga' => $total_bayar_supplier,
            'status' => 0, // 0 = Belum Lunas
            'tanggal' => date('Y-m-d H:i:s'),
            'keterangan' => 'Restock ' . $product['nama'] . ' (' . $jumlah_tambah . ' pcs)'
        ]);

        return redirect()->to(base_url('produk'))->with('success', 'Berhasil melakukan restock! Transaksi dicatat sebagai Hutang ke Supplier.');
    }
} // <-- INI ADALAH KURUNG KURAWAL PENUTUP CLASS YANG SEBELUMNYA HILANG
