<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePelangganTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpelanggan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'namapelanggan' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'alamat' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'nohp' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'iduser' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('kdpelanggan', true);
        $this->forge->addForeignKey('iduser', 'users', 'id', 'CASCADE', 'SET NULL');
        $this->forge->createTable('pelanggan');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pelanggan', 'pelanggan_iduser_foreign');
        $this->forge->dropTable('pelanggan');
    }
}
