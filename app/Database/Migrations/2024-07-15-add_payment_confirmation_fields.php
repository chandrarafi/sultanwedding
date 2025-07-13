<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPaymentConfirmationFields extends Migration
{
    public function up()
    {
        $this->forge->addColumn('pembayaran', [
            'dp_confirmed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
                'after'      => 'status'
            ],
            'dp_confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'dp_confirmed'
            ],
            'dp_confirmed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'dp_confirmed_at'
            ],
            'h1_paid' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
                'after'      => 'dp_confirmed_by'
            ],
            'h1_confirmed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
                'after'      => 'h1_paid'
            ],
            'h1_confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'h1_confirmed'
            ],
            'h1_confirmed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'h1_confirmed_at'
            ],
            'full_confirmed' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'null'       => true,
                'default'    => 0,
                'after'      => 'h1_confirmed_by'
            ],
            'full_confirmed_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'full_confirmed'
            ],
            'full_confirmed_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'full_confirmed_at'
            ],
            'rejected_reason' => [
                'type'       => 'TEXT',
                'null'       => true,
                'after'      => 'full_confirmed_by'
            ],
            'rejected_at' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'rejected_reason'
            ],
            'rejected_by' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
                'null'       => true,
                'after'      => 'rejected_at'
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('pembayaran', [
            'dp_confirmed',
            'dp_confirmed_at',
            'dp_confirmed_by',
            'h1_paid',
            'h1_confirmed',
            'h1_confirmed_at',
            'h1_confirmed_by',
            'full_confirmed',
            'full_confirmed_at',
            'full_confirmed_by',
            'rejected_reason',
            'rejected_at',
            'rejected_by'
        ]);
    }
}
