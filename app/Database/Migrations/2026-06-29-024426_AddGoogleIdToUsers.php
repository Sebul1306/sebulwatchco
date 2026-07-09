<?php
namespace App\Database\Migrations;
use CodeIgniter\Database\Migration;

class AddGoogleIdToUsers extends Migration
{
    public function up()
    {
        $this->forge->addColumn('user', [
            'google_id' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'password',
            ],
        ]);
        $this->db->query('ALTER TABLE user ADD UNIQUE unique_email (email)');
    }

    public function down()
    {
        $this->forge->dropColumn('user', 'google_id');
        $this->db->query('ALTER TABLE user DROP INDEX unique_email');
    }
}
