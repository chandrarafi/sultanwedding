<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdatePemesananbarangTable extends Migration
{
    public function up()
    {
        // Hapus foreign key dulu
        $this->db->disableForeignKeyChecks();

        // Ubah tipe kolom kdpemesananbarang menjadi VARCHAR
        $fields = [
            'kdpemesananbarang' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
                'auto_increment' => false,
            ],
        ];

        $this->forge->modifyColumn('pemesananbarang', $fields);

        // Modifikasi kolom kdpelanggan agar bisa NULL (untuk walk-in)
        $fields2 = [
            'kdpelanggan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
            ],
        ];

        $this->forge->modifyColumn('pemesananbarang', $fields2);

        // Aktifkan kembali foreign key checks
        $this->db->enableForeignKeyChecks();
    }

    public function down()
    {
        // Hapus foreign key dulu
        $this->db->disableForeignKeyChecks();

        // Kembalikan tipe kolom kdpemesananbarang menjadi INT
        $fields = [
            'kdpemesananbarang' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
        ];

        $this->forge->modifyColumn('pemesananbarang', $fields);

        // Kembalikan kolom kdpelanggan menjadi NOT NULL
        $fields2 = [
            'kdpelanggan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => false,
            ],
        ];

        $this->forge->modifyColumn('pemesananbarang', $fields2);

        // Aktifkan kembali foreign key checks
        $this->db->enableForeignKeyChecks();
    }
}
