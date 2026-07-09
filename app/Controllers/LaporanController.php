<?php
namespace App\Controllers;
use App\Controllers\BaseController;
use App\Models\TransactionModel;

class LaporanController extends BaseController {

    private function getFilterLabel($filter_type) {
        $labels = [
            'today' => 'Hari Ini',
            'this_week' => 'Minggu Ini',
            'this_month' => 'Bulan Ini',
            '3_months' => '3 Bulan Terakhir',
            '6_months' => '6 Bulan Terakhir',
            'this_year' => 'Tahun Ini',
            'all_time' => 'Selama Ini'
        ];
        return isset($labels[$filter_type]) && !empty($filter_type) ? $labels[$filter_type] : 'Kustom';
    }

    public function pendapatan()
    {
        $model = new TransactionModel();
        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        
        if (empty($tanggal_awal)) {
            $tanggal_awal = date('Y-01-01');
        }
        if (empty($tanggal_akhir)) {
            $tanggal_akhir = date('Y-m-d');
        }
        
        $laporan = $model
            ->where('status >=', 1) // Only fetch transactions that are 'Paid' and above
            ->where('created_at >=', $tanggal_awal . ' 00:00:00')
            ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
            ->findAll();

        return view('laporan_pendapatan', [
            'laporan' => $laporan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }
    
    public function exportPdf()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        
        if (empty($tanggal_awal)) {
            $tanggal_awal = date('Y-01-01');
        }
        if (empty($tanggal_akhir)) {
            $tanggal_akhir = date('Y-m-d');
        }
        
        $model = new \App\Models\TransactionModel();
        $laporan = $model
            ->where('status >=', 1)
            ->where('created_at >=', $tanggal_awal . ' 00:00:00')
            ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('created_at', 'ASC')
            ->findAll();
            
        $html = view('laporan_pdf', ['laporan' => $laporan, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir]);
        $options = new \Dompdf\Options(); 
        $options->set('isRemoteEnabled', true); 
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Pendapatan_{$dateStr}_{$label}.pdf";
        $dompdf->stream($filename);
    }
    
    public function exportExcel()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        
        if (empty($tanggal_awal)) {
            $tanggal_awal = date('Y-01-01');
        }
        if (empty($tanggal_akhir)) {
            $tanggal_akhir = date('Y-m-d');
        }
        
        $model = new \App\Models\TransactionModel();
        $laporan = $model
            ->where('status >=', 1)
            ->where('created_at >=', $tanggal_awal . ' 00:00:00')
            ->where('created_at <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('created_at', 'ASC')
            ->findAll();
            
        $data = [
            'laporan' => $laporan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ];
            
                $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Pendapatan_{$dateStr}_{$label}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(view('laporan_excel', $data));
    }

    public function produkTerlaris()
    {
        $db = \Config\Database::connect();
        $produk = $db->query("
            SELECT 
                p.nama, 
                SUM(td.jumlah) qty,
                SUM(td.jumlah * p.harga) omzet
            FROM transaction_detail td  
            JOIN product p ON p.id = td.product_id  
            JOIN transaction t ON t.id = td.transaction_id
            WHERE t.status >= 1
            GROUP BY p.id  
            ORDER BY qty DESC
        ")->getResultArray();

        return view('laporan_produk_terlaris', ['produk' => $produk]);
    }

    public function piutang()
    {
        $db = \Config\Database::connect();
        // Ambil transaksi yang belum dibayar (status = 0)
        $piutang = $db->table('transaction')
            ->select("CONCAT('INV-', id) as invoice, username as pelanggan, total_harga as total, total_harga as sisa")
            ->where('status', 0)
            ->get()->getResultArray();
            
        return view('laporan_piutang', ['piutang' => $piutang]);
    }

    public function hutang()
    {
        $db = \Config\Database::connect();
        
        $tanggal_awal = $this->request->getGet('tanggal_awal');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir');
        
        if (empty($tanggal_awal)) {
            $tanggal_awal = date('Y-01-01');
        }
        if (empty($tanggal_akhir)) {
            $tanggal_akhir = date('Y-m-d');
        }
        
        // Ambil data hutang dengan status = 0 (Belum Lunas) beserta nama supplier dan produk
        $hutang = $db->table('tabel_hutang th')
            ->select('th.*, s.nama as nama_supplier, p.nama as nama_produk')
            ->join('supplier s', 's.id = th.supplier_id', 'left')
            ->join('product p', 'p.id = th.product_id', 'left')
            ->where('th.status', 0)
            ->where('th.tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('th.tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('th.tanggal', 'DESC')
            ->get()->getResultArray();
            
        return view('laporan_hutang', [
            'hutang' => $hutang,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }

    public function exportHutangPdf()
    {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');
        
        $hutang = $db->table('tabel_hutang th')
            ->select('th.*, s.nama as nama_supplier, p.nama as nama_produk')
            ->join('supplier s', 's.id = th.supplier_id', 'left')
            ->join('product p', 'p.id = th.product_id', 'left')
            ->where('th.status', 0)
            ->where('th.tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('th.tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('th.tanggal', 'DESC')
            ->get()->getResultArray();
            
        $html = view('laporan_hutang_pdf', [
            'hutang' => $hutang,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
        $options = new \Dompdf\Options(); 
        $options->set('isRemoteEnabled', true); 
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        $dateStr = date("d-m-Y");
        $filename = "Laporan_Hutang_{$dateStr}.pdf";
        $dompdf->stream($filename);
    }
    
    public function exportHutangExcel()
    {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');
        
        $hutang = $db->table('tabel_hutang th')
            ->select('th.*, s.nama as nama_supplier, p.nama as nama_produk')
            ->join('supplier s', 's.id = th.supplier_id', 'left')
            ->join('product p', 'p.id = th.product_id', 'left')
            ->where('th.status', 0)
            ->where('th.tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('th.tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('th.tanggal', 'DESC')
            ->get()->getResultArray();
            
        $data = [
            'hutang' => $hutang,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ];
        
        $dateStr = date("d-m-Y");
        $filename = "Laporan_Hutang_{$dateStr}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody(view('laporan_hutang_excel', $data));
    }

    public function bayarHutang($id)
    {
        $db = \Config\Database::connect();
        
        // Cek data hutang
        $hutang = $db->table('tabel_hutang')->where('id', $id)->get()->getRowArray();
        
        if ($hutang) {
            // Update status menjadi lunas (1)
            $db->table('tabel_hutang')->where('id', $id)->update(['status' => 1]);
            
            // Catat pengeluaran kas
            $db->table('tabel_jurnal_kas')->insert([
                'tanggal' => date('Y-m-d H:i:s'),
                'jenis' => 'keluar',
                'keterangan' => 'Pembayaran Hutang: ' . $hutang['keterangan'],
                'nominal' => $hutang['total_harga']
            ]);
            
            return redirect()->back()->with('success', 'Hutang berhasil dilunasi dan kas keluar telah dicatat.');
        }
        
        return redirect()->back()->with('error', 'Data hutang tidak ditemukan.');
    }

    public function arusKas()
    {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');

        $arusKas = $db->table('tabel_jurnal_kas')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get()->getResultArray();
        
        $totalMasuk = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'masuk')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;
            
        $totalKeluar = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'keluar')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;
        
        return view('laporan_arus_kas', [
            'arusKas' => $arusKas,
            'totalMasuk' => $totalMasuk,
            'totalKeluar' => $totalKeluar,
            'saldoAkhir' => $totalMasuk - $totalKeluar,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }

    public function labaRugi()
    {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');
        
        $penjualan = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'masuk')
            ->like('keterangan', 'Penjualan')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;

        $pembelian = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'keluar')
            ->groupStart()
                ->like('keterangan', 'Pembelian')
                ->orLike('keterangan', 'Restock')
                ->orLike('keterangan', 'Stok Awal')
            ->groupEnd()
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;

        $bebanData = $db->table('tabel_beban')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getResultArray();
        
        $totalBeban = 0;
        foreach($bebanData as $b) {
            $totalBeban += $b['nominal'];
        }

        $labaKotor = $penjualan - $pembelian;
        $labaBersih = $labaKotor - $totalBeban;

        return view('laporan_laba_rugi', [
            'penjualan' => $penjualan,
            'hpp' => $pembelian,
            'labaKotor' => $labaKotor,
            'beban' => $bebanData,
            'totalBeban' => $totalBeban,
            'labaBersih' => $labaBersih,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ]);
    }

    public function exportTerlarisPdf() {
        $db = \Config\Database::connect();
        $produk = $db->query("SELECT p.nama, SUM(td.jumlah) qty, SUM(td.jumlah * p.harga) omzet FROM transaction_detail td JOIN product p ON p.id = td.product_id JOIN transaction t ON t.id = td.transaction_id WHERE t.status >= 1 GROUP BY p.id ORDER BY qty DESC")->getResultArray();
        $html = view('laporan_terlaris_pdf', ['produk' => $produk]);
        $options = new \Dompdf\Options(); $options->set('isRemoteEnabled', true); $dompdf = new \Dompdf\Dompdf($options); $dompdf->loadHtml($html); $dompdf->setPaper('A4', 'portrait'); $dompdf->render(); 
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Produk Terlaris_{$dateStr}_{$label}.pdf";
        $dompdf->stream($filename);
    }

    public function exportTerlarisExcel() {
        $db = \Config\Database::connect();
        $produk = $db->query("SELECT p.nama, SUM(td.jumlah) qty, SUM(td.jumlah * p.harga) omzet FROM transaction_detail td JOIN product p ON p.id = td.product_id JOIN transaction t ON t.id = td.transaction_id WHERE t.status >= 1 GROUP BY p.id ORDER BY qty DESC")->getResultArray();
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Produk Terlaris_{$dateStr}_{$label}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')->setBody(view('laporan_terlaris_excel', ['produk' => $produk]));
    }

    public function exportPiutangPdf() {
        $db = \Config\Database::connect(); 
        $piutang = $db->table('transaction')->select("CONCAT('INV-', id) as invoice, username as pelanggan, total_harga as total, total_harga as sisa")->where('status', 0)->get()->getResultArray();
        $html = view('laporan_piutang_pdf', ['piutang' => $piutang]);
        $options = new \Dompdf\Options(); $options->set('isRemoteEnabled', true); $dompdf = new \Dompdf\Dompdf($options); $dompdf->loadHtml($html); $dompdf->setPaper('A4', 'portrait'); $dompdf->render(); 
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Piutang_{$dateStr}_{$label}.pdf";
        $dompdf->stream($filename);
    }

    public function exportPiutangExcel() {
        $db = \Config\Database::connect(); 
        $piutang = $db->table('transaction')->select("CONCAT('INV-', id) as invoice, username as pelanggan, total_harga as total, total_harga as sisa")->where('status', 0)->get()->getResultArray();
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Piutang_{$dateStr}_{$label}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')->setBody(view('laporan_piutang_excel', ['piutang' => $piutang]));
    }

    private function getArusKasData() {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');
        
        $arusKas = $db->table('tabel_jurnal_kas')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->orderBy('tanggal', 'DESC')->orderBy('id', 'DESC')->get()->getResultArray();
            
        $totalMasuk = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'masuk')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;
            
        $totalKeluar = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')
            ->where('jenis', 'keluar')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')
            ->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;
            
        return ['arusKas' => $arusKas, 'totalMasuk' => $totalMasuk, 'totalKeluar' => $totalKeluar, 'saldoAkhir' => $totalMasuk - $totalKeluar, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir];
    }

    public function exportArusKasPdf() {
        $data = $this->getArusKasData();
        $html = view('laporan_arus_kas_pdf', $data);
        $options = new \Dompdf\Options(); $options->set('isRemoteEnabled', true); $dompdf = new \Dompdf\Dompdf($options); $dompdf->loadHtml($html); $dompdf->setPaper('A4', 'portrait'); $dompdf->render(); 
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Arus Kas_{$dateStr}_{$label}.pdf";
        $dompdf->stream($filename);
    }

    public function exportArusKasExcel() {
        $data = $this->getArusKasData();
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Arus Kas_{$dateStr}_{$label}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')->setBody(view('laporan_arus_kas_excel', $data));
    }

    private function getLabaRugiData() {
        $db = \Config\Database::connect();
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?: date('Y-01-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?: date('Y-m-d');
        
        $penjualan = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')->where('jenis', 'masuk')->like('keterangan', 'Penjualan')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;

        $pembelian = $db->table('tabel_jurnal_kas')
            ->selectSum('nominal')->where('jenis', 'keluar')
            ->groupStart()->like('keterangan', 'Pembelian')->orLike('keterangan', 'Restock')->orLike('keterangan', 'Stok Awal')->groupEnd()
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getRow()->nominal ?? 0;

        $bebanData = $db->table('tabel_beban')
            ->where('tanggal >=', $tanggal_awal . ' 00:00:00')->where('tanggal <=', $tanggal_akhir . ' 23:59:59')
            ->get()->getResultArray();
        
        $totalBeban = 0; foreach($bebanData as $b) { $totalBeban += $b['nominal']; }
        $labaKotor = $penjualan - $pembelian; $labaBersih = $labaKotor - $totalBeban;

        return ['penjualan' => $penjualan, 'hpp' => $pembelian, 'labaKotor' => $labaKotor, 'beban' => $bebanData, 'totalBeban' => $totalBeban, 'labaBersih' => $labaBersih, 'tanggal_awal' => $tanggal_awal, 'tanggal_akhir' => $tanggal_akhir];
    }

    public function exportLabaRugiPdf() {
        $data = $this->getLabaRugiData();
        $html = view('laporan_laba_rugi_pdf', $data);
        $options = new \Dompdf\Options(); $options->set('isRemoteEnabled', true); $dompdf = new \Dompdf\Dompdf($options); $dompdf->loadHtml($html); $dompdf->setPaper('A4', 'portrait'); $dompdf->render(); 
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Laba Rugi_{$dateStr}_{$label}.pdf";
        $dompdf->stream($filename);
    }

    public function exportLabaRugiExcel() {
        $data = $this->getLabaRugiData();
        $filter_type = $this->request->getGet("filter_type");
        $label = $this->getFilterLabel($filter_type);
        $dateStr = date("d-m-Y");
        $filename = "Laporan Laba Rugi_{$dateStr}_{$label}.xls";
        return $this->response->setContentType('application/vnd-ms-excel')->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')->setBody(view('laporan_laba_rugi_excel', $data));
    }
}