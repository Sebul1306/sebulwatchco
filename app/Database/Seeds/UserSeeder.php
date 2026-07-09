<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'username'   => 'admin',
                'email'      => 'admin@warungsebul.com',
                'password'   => password_hash('admin123', PASSWORD_DEFAULT),
                'role'       => 'admin',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'pelanggan1',
                'email'      => 'pelanggan1@gmail.com',
                'password'   => password_hash('pelanggan123', PASSWORD_DEFAULT),
                'role'       => 'pelanggan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
            [
                'username'   => 'pelanggan2',
                'email'      => 'pelanggan2@gmail.com',
                'password'   => password_hash('pelanggan123', PASSWORD_DEFAULT),
                'role'       => 'pelanggan',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('user')->insertBatch($data);
    }
}
