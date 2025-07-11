<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PaketSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namapaket' => 'Paket Pernikahan Gold',
                'detailpaket' => "Paket pernikahan mewah dengan dekorasi premium, catering untuk 500 tamu, dokumentasi profesional, dan hiburan live music.\n\n- Dekorasi pelaminan premium\n- Catering 500 porsi\n- Dokumentasi foto dan video\n- MC profesional\n- Live music\n- Sewa gedung 1 hari",
                'harga' => 50000000,
                'foto' => null,
                'kdkategori' => 1, // Pernikahan
                'kdbarang' => null, // Tidak terkait dengan barang spesifik
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namapaket' => 'Paket Pernikahan Silver',
                'detailpaket' => "Paket pernikahan menengah dengan dekorasi standar, catering untuk 300 tamu, dan dokumentasi.\n\n- Dekorasi pelaminan standar\n- Catering 300 porsi\n- Dokumentasi foto\n- MC\n- Sewa gedung 1 hari",
                'harga' => 30000000,
                'foto' => null,
                'kdkategori' => 1, // Pernikahan
                'kdbarang' => null, // Tidak terkait dengan barang spesifik
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namapaket' => 'Paket Dekorasi Premium',
                'detailpaket' => "Dekorasi pelaminan premium dengan tema modern atau tradisional sesuai pilihan.\n\n- Dekorasi pelaminan mewah\n- Dekorasi VIP area\n- Lighting profesional\n- Bunga segar premium\n- Karpet merah",
                'harga' => 15000000,
                'foto' => null,
                'kdkategori' => 2, // Dekorasi
                'kdbarang' => 5, // Backdrop Pelaminan
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namapaket' => 'Paket Catering Deluxe',
                'detailpaket' => "Paket catering premium dengan menu internasional dan tradisional.\n\n- Menu utama 10 pilihan\n- Appetizer 5 pilihan\n- Dessert 5 pilihan\n- Free flow minuman\n- Termasuk kru dan peralatan",
                'harga' => 100000,
                'foto' => null,
                'kdkategori' => 3, // Catering
                'kdbarang' => 8, // Paket Catering Premium
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namapaket' => 'Paket Dokumentasi Lengkap',
                'detailpaket' => "Paket dokumentasi foto dan video profesional.\n\n- Tim fotografer profesional\n- Tim videografer profesional\n- Drone shooting\n- Album foto premium\n- Video cinematic\n- Pra-wedding\n- Unlimited shot",
                'harga' => 12000000,
                'foto' => null,
                'kdkategori' => 4, // Dokumentasi
                'kdbarang' => 9, // Kamera DSLR + Videografer
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namapaket' => 'Paket Hiburan Premium',
                'detailpaket' => "Paket hiburan lengkap untuk memeriahkan acara pernikahan.\n\n- Live music/band\n- MC profesional\n- Sound system berkualitas\n- Lighting efek\n- Unlimited jam",
                'harga' => 8000000,
                'foto' => null,
                'kdkategori' => 5, // Hiburan
                'kdbarang' => 7, // Sound System
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Using Query Builder
        $this->db->table('paket')->insertBatch($data);
    }
}
