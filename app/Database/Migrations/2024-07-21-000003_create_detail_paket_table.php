<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailPaketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kdpaket' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kdbarang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'default'    => 1,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'keterangan' => [
                'type'       => 'TEXT',
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
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('kdpaket', 'paket', 'kdpaket', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kdbarang', 'barang', 'kdbarang', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_paket');
    }

    public function down()
    {
        $this->forge->dropForeignKey('detail_paket', 'detail_paket_kdpaket_foreign');
        $this->forge->dropForeignKey('detail_paket', 'detail_paket_kdbarang_foreign');
        $this->forge->dropTable('detail_paket');
    }
}
