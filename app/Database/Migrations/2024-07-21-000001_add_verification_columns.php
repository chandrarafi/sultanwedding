<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVerificationColumns extends Migration
{
    public function up()
    {
        $this->forge->addColumn('users', [
            'is_verified' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => 0,
                'comment'    => '0=unverified, 1=verified',
                'after'      => 'status',
            ],
            'verification_code' => [
                'type'       => 'VARCHAR',
                'constraint' => 6,
                'null'       => true,
                'after'      => 'is_verified',
            ],
            'verification_expiry' => [
                'type'       => 'DATETIME',
                'null'       => true,
                'after'      => 'verification_code',
            ],
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('users', 'is_verified');
        $this->forge->dropColumn('users', 'verification_code');
        $this->forge->dropColumn('users', 'verification_expiry');
    }
}
