<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemesananbarangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpemesananbarang' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tgl' => [
                'type'       => 'DATE',
            ],
            'kdpelanggan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'alamatpesanan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'lamapemesanan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'dalam hari',
            ],
            'grandtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'kdpembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
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
        $this->forge->addKey('kdpemesananbarang', true);
        $this->forge->addForeignKey('kdpelanggan', 'pelanggan', 'kdpelanggan', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('pemesananbarang');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemesananbarang', 'pemesananbarang_kdpelanggan_foreign');
        $this->forge->dropTable('pemesananbarang');
    }
}
