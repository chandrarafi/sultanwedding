<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFotoToBarang extends Migration
{
    public function up()
    {
        $this->forge->addColumn('barang', [
            'foto' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'after'      => 'hargasewa'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('barang', 'foto');
    }
}
