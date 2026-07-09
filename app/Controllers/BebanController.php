<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class BebanController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $beban = $db->table('tabel_beban')->orderBy('tanggal', 'DESC')->get()->getResultArray();
        return view('v_beban', ['beban' => $beban]);
    }

    public function add()
    {
        $db = \Config\Database::connect();
        
        $tanggal = $this->request->getPost('tanggal') . ' ' . date('H:i:s');
        $nama_beban = $this->request->getPost('nama_beban');
        $nominal = $this->request->getPost('nominal');
        
        $db->table('tabel_beban')->insert([
            'tanggal' => $tanggal,
            'nama_beban' => $nama_beban,
            'nominal' => $nominal
        ]);

        // Catat juga di Arus Kas sebagai Kas Keluar
        $db->table('tabel_jurnal_kas')->insert([
            'tanggal' => $tanggal,
            'jenis' => 'keluar',
            'keterangan' => 'Pengeluaran Operasional: ' . $nama_beban,
            'nominal' => $nominal
        ]);

        return redirect()->to(base_url('beban'))->with('success', 'Data pengeluaran operasional berhasil ditambahkan dan dicatat di Arus Kas.');
    }

    public function delete($id)
    {
        $db = \Config\Database::connect();
        
        // Dapatkan data beban sebelum dihapus
        $beban = $db->table('tabel_beban')->where('id', $id)->get()->getRow();
        if ($beban) {
            // Hapus dari tabel_beban
            $db->table('tabel_beban')->where('id', $id)->delete();
            
            // Opsional: kita tidak menghapus arus kas untuk menjaga history, 
            // atau menghapus jurnal kas terkait jika diperlukan. 
            // Untuk kesederhanaan, kita bisa biarkan arus kas historis, 
            // atau hapus jurnal dengan keterangan yang sama.
            // $db->table('tabel_jurnal_kas')->where('keterangan', 'Pengeluaran Operasional: ' . $beban->nama_beban)->where('nominal', $beban->nominal)->where('tanggal', $beban->tanggal)->delete();
            
            return redirect()->to(base_url('beban'))->with('success', 'Data pengeluaran operasional berhasil dihapus.');
        }
        return redirect()->to(base_url('beban'))->with('error', 'Data tidak ditemukan.');
    }
}
