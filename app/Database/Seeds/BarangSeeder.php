<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class BarangSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'namabarang' => 'Kursi Plastik',
                'satuan'     => 'Lusin',
                'jumlah'     => 100,
                'hargasewa'  => 50000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Meja Bundar',
                'satuan'     => 'Unit',
                'jumlah'     => 20,
                'hargasewa'  => 75000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Karpet Merah',
                'satuan'     => 'Meter',
                'jumlah'     => 50,
                'hargasewa'  => 25000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Kotak Tamu',
                'satuan'     => 'Unit',
                'jumlah'     => 10,
                'hargasewa'  => 100000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Backdrop Pelaminan',
                'satuan'     => 'Set',
                'jumlah'     => 5,
                'hargasewa'  => 2500000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Lighting',
                'satuan'     => 'Set',
                'jumlah'     => 8,
                'hargasewa'  => 1500000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Sound System',
                'satuan'     => 'Set',
                'jumlah'     => 3,
                'hargasewa'  => 2000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Paket Catering Premium',
                'satuan'     => 'Porsi',
                'jumlah'     => 1000,
                'hargasewa'  => 100000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
            [
                'namabarang' => 'Kamera DSLR + Videografer',
                'satuan'     => 'Set',
                'jumlah'     => 2,
                'hargasewa'  => 3000000,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ],
        ];

        // Using Query Builder
        $this->db->table('barang')->insertBatch($data);
    }
}
