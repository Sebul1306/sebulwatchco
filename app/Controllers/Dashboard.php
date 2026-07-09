<?php 
namespace App\Controllers;  
use CodeIgniter\Controller;  

class Dashboard extends BaseController  
{ 
    public function index()  
    { 
        $db = \Config\Database::connect();  
 
        // CARD SUMMARY  
        $totalProduk = $db->table('product')->countAllResults();  
        $totalUser = $db->table('user')->countAllResults();  
        $totalTransaksi = $db->table('transaction')->countAllResults();  
 
        $omzet = $db->table('transaction')  
            ->selectSum('total_harga')  
            ->where('status >=', 1)  // Status paid/shipped/completed
            ->get() 
            ->getRow();  
   
        $totalOmzet = $omzet->total_harga ?? 0;  
 
        // GRAFIK PENJUALAN  
        $penjualan = $db->query("  
            SELECT 
                MONTH(created_at) bulan,  
                SUM(total_harga) total  
            FROM transaction  
            WHERE status >= 1  
            GROUP BY MONTH(created_at)  
            ORDER BY bulan  
        ")->getResultArray();  
 
        $bulan = [];  
        $totalPenjualan = [];  
 
        foreach ($penjualan as $row) {  
            $bulan[] = date('M', mktime(0,0,0,$row['bulan'],1));  
            $totalPenjualan[] = $row['total'];  
        } 
 
        // PRODUK TERLARIS  
        $produk = $db->query("  
            SELECT 
                p.nama, 
                SUM(td.jumlah) qty  
            FROM transaction_detail td  
            JOIN product p ON p.id = td.product_id  
            GROUP BY p.id  
            ORDER BY qty DESC  
            LIMIT 10  
        ")->getResultArray();  
 
        $namaProduk = [];  
        $qtyProduk = [];  
 
        foreach ($produk as $row) {  
            $namaProduk[] = $row['nama'];  
            $qtyProduk[] = $row['qty'];  
        } 
 
        // STATUS PESANAN  
        $status = $db->query("  
            SELECT 
                status, 
                COUNT(*) jumlah  
            FROM transaction  
            GROUP BY status  
        ")->getResultArray();  
   
        $labelStatus = [];  
        $jumlahStatus = [];  
 
        foreach ($status as $row) {  
            switch ($row['status']) {  
                case 0: $label = 'Belum Bayar'; break; 
                case 1: $label = 'Sudah Dibayar'; break; 
                case 2: $label = 'Sedang Dikirim'; break; 
                case 3: $label = 'Selesai'; break; 
                case 4: $label = 'Dibatalkan'; break; 
                default: $label = 'Lainnya';  
            } 
            $labelStatus[] = $label;  
            $jumlahStatus[] = $row['jumlah'];  
        } 
 
        // TRANSAKSI TERBARU  
        $transaksiTerbaru = $db->table('transaction')  
            ->orderBy('id', 'DESC')  
            ->limit(5)  
            ->get() 
            ->getResultArray();  
 
        // PELANGGAN BELUM BAYAR (PIUTANG)
        $pelangganBelumBayar = $db->table('transaction')
            ->select('username, total_harga, created_at, id')
            ->where('status', 0)
            ->orderBy('created_at', 'ASC')
            ->limit(5)
            ->get()
            ->getResultArray();

        return view('dashboard', [  
            'totalProduk' => $totalProduk,  
            'totalUser' => $totalUser,  
            'totalTransaksi' => $totalTransaksi,  
            'totalOmzet' => $totalOmzet,  
            'bulan' => json_encode($bulan),  
            'totalPenjualan' => json_encode($totalPenjualan),  
            'namaProduk' => json_encode($namaProduk),  
            'qtyProduk' => json_encode($qtyProduk),  
            'labelStatus' => json_encode($labelStatus),  
            'jumlahStatus' => json_encode($jumlahStatus),  
            'transaksiTerbaru' => $transaksiTerbaru,
            'pelangganBelumBayar' => $pelangganBelumBayar
        ]); 
    } 
}
