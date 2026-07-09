<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'nama'       => 'Nasi Goreng Spesial',
                'harga'      => 15000,
                'jumlah'     => 50,
                'foto'       => 'product-1.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Mie Ayam Bakso',
                'harga'      => 12000,
                'jumlah'     => 40,
                'foto'       => 'product-2.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Ayam Bakar',
                'harga'      => 20000,
                'jumlah'     => 30,
                'foto'       => 'product-3.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Soto Ayam',
                'harga'      => 13000,
                'jumlah'     => 35,
                'foto'       => 'product-4.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'nama'       => 'Es Teh Manis',
                'harga'      => 5000,
                'jumlah'     => 100,
                'foto'       => 'product-5.jpg',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('product')->insertBatch($data);
    }
}
