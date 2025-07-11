<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class RemoveKdbarangFromPaket extends Migration
{
    public function up()
    {
        // Hapus kolom kdbarang dari tabel paket
        $this->forge->dropColumn('paket', 'kdbarang');
    }

    public function down()
    {
        // Tambahkan kembali kolom kdbarang
        $fields = [
            'kdbarang' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'kdkategori',
            ],
        ];

        $this->forge->addColumn('paket', $fields);
    }
}
