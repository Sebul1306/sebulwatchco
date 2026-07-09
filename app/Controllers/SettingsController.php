<?php

namespace App\Controllers;

class SettingsController extends BaseController
{
    public function index(): string
    {
        $addressFile = WRITEPATH . 'store_address.json';
        $storeAddress = ['id' => '3273141004', 'name' => 'Gegerkalong, Sukasari, Kota Bandung (Pusat)'];
        if (file_exists($addressFile)) {
            $storeAddress = json_decode(file_get_contents($addressFile), true);
        }

        $data = [
            'shipping_key' => env('COST_KEY', 'ffecd68fbfb82b40792a758ae5e688b7'),
            'payment_key'  => env('PAYMENT_API_KEY', 'qAmhl63y464802a43a79e861HFlCBgxA'),
            'qris_key'     => env('QRIS_API_KEY', 'qAmhl63y464802a43a79e861HFlCBgxA'),
            'store_address' => $storeAddress
        ];
        return view('v_settings', $data);
    }

    public function uploadQris()
    {
        $file = $this->request->getFile('qris_image');

        if ($file->isValid() && !$file->hasMoved()) {
            // Kita pakai nama statis 'qris.png' agar selalu menimpa yang lama
            $file->move(FCPATH . 'uploads/', 'qris.png', true);
            return redirect()->to('/settings')->with('success', 'QRIS berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload gambar QRIS.');
    }

    public function uploadLogo()
    {
        $file = $this->request->getFile('logo_image');

        if ($file->isValid() && !$file->hasMoved()) {
            // Nama statis agar logo lama tertimpa
            $file->move(FCPATH . 'NiceAdmin/assets/img/', 'logo51.png', true);
            return redirect()->to('/settings')->with('success', 'Logo berhasil diperbarui!');
        }

        return redirect()->back()->with('error', 'Gagal mengupload logo toko.');
    }

    public function updateAddress()
    {
        $id = $this->request->getPost('store_address_id');
        $name = $this->request->getPost('store_address_name');

        if ($id && $name) {
            $data = ['id' => $id, 'name' => $name];
            file_put_contents(WRITEPATH . 'store_address.json', json_encode($data));
            return redirect()->back()->with('success', 'Alamat toko default berhasil diperbarui.');
        }

        return redirect()->back()->with('error', 'Data alamat tidak valid.');
    }
}
