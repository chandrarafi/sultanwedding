<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Jalankan seeder dalam urutan yang benar untuk menjaga integritas referensial

        // 1. Kategori harus dijalankan pertama karena menjadi referensi untuk barang dan paket
        $this->call('KategoriSeeder');

        // 2. Barang dijalankan setelah kategori karena bergantung pada kategori
        $this->call('BarangSeeder');

        // 3. Paket dijalankan terakhir karena bergantung pada barang dan kategori
        $this->call('PaketSeeder');

        echo "Database seeding berhasil dijalankan.\n";
    }
}
