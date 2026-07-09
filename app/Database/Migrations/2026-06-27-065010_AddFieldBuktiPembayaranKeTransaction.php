<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFieldBuktiPembayaranKeTransaction extends Migration
{
    public function up()
    {
        $fields = [
            'bukti_pembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => '255',
                'null'       => true,
                'after'      => 'status'
            ]
        ];
        $this->forge->addColumn('transaction', $fields);
    }

    public function down()
    {
        $this->forge->dropColumn('transaction', ['bukti_pembayaran']);
    }
}
