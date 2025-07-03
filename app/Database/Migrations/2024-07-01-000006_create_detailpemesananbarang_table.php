<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateDetailpemesananbarangTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kddetailpemesananbarang' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'kdpemesananbarang' => [
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
                'default'    => 1,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'subtotal' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
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
        $this->forge->addKey('kddetailpemesananbarang', true);
        $this->forge->addForeignKey('kdpemesananbarang', 'pemesananbarang', 'kdpemesananbarang', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kdbarang', 'barang', 'kdbarang', 'CASCADE', 'RESTRICT');
        $this->forge->createTable('detailpemesananbarang');
    }

    public function down()
    {
        $this->forge->dropForeignKey('detailpemesananbarang', 'detailpemesananbarang_kdpemesananbarang_foreign');
        $this->forge->dropForeignKey('detailpemesananbarang', 'detailpemesananbarang_kdbarang_foreign');
        $this->forge->dropTable('detailpemesananbarang');
    }
}
