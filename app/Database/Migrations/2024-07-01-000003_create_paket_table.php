<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePaketTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpaket' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'namapaket' => [
                'type'       => 'VARCHAR',
                'constraint' => 100,
            ],
            'detailpaket' => [
                'type'       => 'TEXT',
                'null'       => true,
            ],
            'harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
            ],
            'kdkategori' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kdbarang' => [
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
        $this->forge->addKey('kdpaket', true);
        $this->forge->addForeignKey('kdkategori', 'kategori', 'kdkategori', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('kdbarang', 'barang', 'kdbarang', 'CASCADE', 'SET NULL');
        $this->forge->createTable('paket');
    }

    public function down()
    {
        $this->forge->dropForeignKey('paket', 'paket_kdkategori_foreign');
        $this->forge->dropForeignKey('paket', 'paket_kdbarang_foreign');
        $this->forge->dropTable('paket');
    }
}
