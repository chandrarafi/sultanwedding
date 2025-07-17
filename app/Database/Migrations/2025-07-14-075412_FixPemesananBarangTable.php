<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class FixPemesananBarangTable extends Migration
{
    public function up()
    {
        // Nonaktifkan foreign key checks terlebih dahulu
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Menghapus foreign key constraint di tabel detailpemesananbarang
        $this->db->query("ALTER TABLE detailpemesananbarang DROP FOREIGN KEY detailpemesananbarang_kdpemesananbarang_foreign");

        // Mengubah tipe kolom dengan SQL langsung
        $this->db->query("ALTER TABLE pemesananbarang MODIFY COLUMN kdpemesananbarang VARCHAR(20) NOT NULL");
        $this->db->query("ALTER TABLE pemesananbarang MODIFY COLUMN kdpelanggan INT(11) UNSIGNED NULL");

        // Ubah tipe kolom di tabel detail
        $this->db->query("ALTER TABLE detailpemesananbarang MODIFY COLUMN kdpemesananbarang VARCHAR(20) NOT NULL");

        // Buat ulang foreign key
        $this->db->query("ALTER TABLE detailpemesananbarang ADD CONSTRAINT detailpemesananbarang_kdpemesananbarang_foreign FOREIGN KEY (kdpemesananbarang) REFERENCES pemesananbarang(kdpemesananbarang) ON DELETE CASCADE ON UPDATE CASCADE");

        // Aktifkan kembali foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        // Nonaktifkan foreign key checks terlebih dahulu
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Menghapus foreign key constraint di tabel detailpemesananbarang
        $this->db->query("ALTER TABLE detailpemesananbarang DROP FOREIGN KEY detailpemesananbarang_kdpemesananbarang_foreign");

        // Mengembalikan tipe kolom jika rollback
        $this->db->query("ALTER TABLE pemesananbarang MODIFY COLUMN kdpemesananbarang INT(11) UNSIGNED NOT NULL AUTO_INCREMENT");
        $this->db->query("ALTER TABLE pemesananbarang MODIFY COLUMN kdpelanggan INT(11) UNSIGNED NOT NULL");

        // Mengembalikan tipe kolom di tabel detail
        $this->db->query("ALTER TABLE detailpemesananbarang MODIFY COLUMN kdpemesananbarang INT(11) UNSIGNED NOT NULL");

        // Buat ulang foreign key dengan tipe yang sesuai
        $this->db->query("ALTER TABLE detailpemesananbarang ADD CONSTRAINT detailpemesananbarang_kdpemesananbarang_foreign FOREIGN KEY (kdpemesananbarang) REFERENCES pemesananbarang(kdpemesananbarang) ON DELETE CASCADE ON UPDATE CASCADE");

        // Aktifkan kembali foreign key checks
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
