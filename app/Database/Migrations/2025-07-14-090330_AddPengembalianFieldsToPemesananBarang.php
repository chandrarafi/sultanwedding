<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPengembalianFieldsToPemesananBarang extends Migration
{
    public function up()
    {
        // Tambahkan kolom untuk pengembalian barang
        $fields = [
            'tgl_kembali' => [
                'type' => 'DATE',
                'null' => true,
                'after' => 'status'
            ],
            'status_pengembalian' => [
                'type' => 'ENUM',
                'constraint' => ['baik', 'rusak', 'hilang'],
                'null' => true,
                'after' => 'tgl_kembali'
            ],
            'catatan_pengembalian' => [
                'type' => 'TEXT',
                'null' => true,
                'after' => 'status_pengembalian'
            ],
        ];

        $this->forge->addColumn('pemesananbarang', $fields);
    }

    public function down()
    {
        // Hapus kolom jika rollback
        $this->forge->dropColumn('pemesananbarang', ['tgl_kembali', 'status_pengembalian', 'catatan_pengembalian']);
    }
}
