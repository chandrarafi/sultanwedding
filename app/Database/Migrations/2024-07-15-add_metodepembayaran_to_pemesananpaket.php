<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMetodepembayaranToPemesananpaket extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pemesananpaket', [
            'metodepembayaran' => [
                'type'       => 'ENUM',
                'constraint' => ['transfer', 'cash'],
                'default'    => 'transfer',
                'after'      => 'kdpembayaran'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pemesananpaket', 'metodepembayaran');
    }
}
