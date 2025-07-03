<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePemesananpaketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpemesananpaket' => [
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
            'kdpaket' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'hargapaket' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'alamatpesanan' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'jumlahhari' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 1,
                'comment'    => 'dalam hari',
            ],
            'luaslokasi' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
                'null'       => true,
            ],
            'grandtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'kdpembayaran' => [
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
        $this->forge->addKey('kdpemesananpaket', true);
        $this->forge->addForeignKey('kdpelanggan', 'pelanggan', 'kdpelanggan', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('kdpaket', 'paket', 'kdpaket', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('pemesananpaket');
    }

    public function down()
    {
        $this->forge->dropForeignKey('pemesananpaket', 'pemesananpaket_kdpelanggan_foreign');
        $this->forge->dropForeignKey('pemesananpaket', 'pemesananpaket_kdpaket_foreign');
        $this->forge->dropTable('pemesananpaket');
    }
}
