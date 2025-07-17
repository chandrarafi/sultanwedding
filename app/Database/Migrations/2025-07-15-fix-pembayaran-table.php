<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPembayaranTable extends Migration
{
    public function up()
    {
        // Ubah tipe data kolom full_paid dari char(20) ke tinyint(1)
        $this->db->query("ALTER TABLE `pembayaran` CHANGE `full_paid` `full_paid` TINYINT(1) NULL DEFAULT '0'");

        // Log perubahan
        log_message('info', 'Migrasi: Kolom full_paid pada tabel pembayaran diubah dari char(20) ke tinyint(1)');
    }

    public function down()
    {
        // Kembalikan tipe data kolom full_paid ke char(20)
        $this->db->query("ALTER TABLE `pembayaran` CHANGE `full_paid` `full_paid` CHAR(20) NULL DEFAULT NULL");

        // Log perubahan
        log_message('info', 'Migrasi rollback: Kolom full_paid pada tabel pembayaran diubah dari tinyint(1) ke char(20)');
    }
}
