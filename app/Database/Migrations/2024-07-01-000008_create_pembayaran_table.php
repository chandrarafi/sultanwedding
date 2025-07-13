<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePembayaranTable extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'kdpembayaran' => [
                'type'           => 'VARCHAR',
                'constraint'     => 20,
            ],
            'tgl' => [
                'type'       => 'DATE',
            ],
            'metodepembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 50,
                'comment'    => 'transfer, cash, dll',
            ],
            'tipepembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
                'comment'    => 'dp, dp2, lunas',
            ],
            'jumlahbayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'jumlah yang dibayarkan pada transaksi ini',
            ],
            'sisa' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
            ],
            'totalpembayaran' => [
                'type'       => 'DECIMAL',
                'constraint' => '15,2',
                'default'    => 0,
                'comment'    => 'total yang sudah dibayarkan',
            ],
            'buktipembayaran' => [
                'type'       => 'VARCHAR',
                'constraint' => 255,
                'null'       => true,
                'comment'    => 'bukti pembayaran (foto/file)',
            ],
            'status' => [
                'type'       => 'ENUM',
                'constraint' => ['pending', 'partial', 'success', 'failed'],
                'default'    => 'pending',
                'comment'    => 'status pembayaran',
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
        $this->forge->addKey('kdpembayaran', true);
        $this->forge->createTable('pembayaran');

        // Tambahkan foreign key ke tabel pemesananbarang dan pemesananpaket
        $this->db->query('ALTER TABLE pemesananbarang ADD CONSTRAINT pemesananbarang_kdpembayaran_foreign FOREIGN KEY (kdpembayaran) REFERENCES pembayaran(kdpembayaran) ON DELETE SET NULL ON UPDATE CASCADE');
        $this->db->query('ALTER TABLE pemesananpaket ADD CONSTRAINT pemesananpaket_kdpembayaran_foreign FOREIGN KEY (kdpembayaran) REFERENCES pembayaran(kdpembayaran) ON DELETE SET NULL ON UPDATE CASCADE');
    }

    public function down()
    {
        $this->db->query('ALTER TABLE pemesananbarang DROP FOREIGN KEY pemesananbarang_kdpembayaran_foreign');
        $this->db->query('ALTER TABLE pemesananpaket DROP FOREIGN KEY pemesananpaket_kdpembayaran_foreign');
        $this->forge->dropTable('pembayaran');
    }
}
