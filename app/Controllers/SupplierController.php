<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class SupplierController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $suppliers = $db->table('supplier')->orderBy('id', 'ASC')->get()->getResultArray();
        
        $data = [
            'suppliers' => $suppliers
        ];
        
        return view('v_supplier', $data);
    }
    
    public function add()
    {
        $db = \Config\Database::connect();
        $db->table('supplier')->insert([
            'nama' => $this->request->getPost('nama'),
            'kontak' => $this->request->getPost('kontak'),
            'alamat' => $this->request->getPost('alamat'),
            'created_at' => date('Y-m-d H:i:s')
        ]);
        
        return redirect()->to(base_url('supplier'))->with('success', 'Supplier berhasil ditambahkan.');
    }
    
    public function edit($id)
    {
        $db = \Config\Database::connect();
        $db->table('supplier')->where('id', $id)->update([
            'nama' => $this->request->getPost('nama'),
            'kontak' => $this->request->getPost('kontak'),
            'alamat' => $this->request->getPost('alamat')
        ]);
        
        return redirect()->to(base_url('supplier'))->with('success', 'Supplier berhasil diubah.');
    }
    
    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->table('supplier')->where('id', $id)->delete();
        
        return redirect()->to(base_url('supplier'))->with('success', 'Supplier berhasil dihapus.');
    }
}
