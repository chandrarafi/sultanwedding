<?php

namespace App\Controllers;

use CodeIgniter\Controller;

class MigrateFileUploads extends Controller
{
    public function index()
    {
        // Pastikan hanya admin yang bisa menjalankan ini
        if (!session()->get('isLoggedIn') || session()->get('role') !== 'admin') {
            return redirect()->to(base_url('auth/login'))->with('error', 'Anda tidak memiliki akses');
        }

        $result = [
            'status' => true,
            'message' => 'Berhasil migrasi file',
            'detail' => []
        ];

        // Migrasi file barang
        $oldDir = WRITEPATH . 'uploads/barang';
        $newDir = ROOTPATH . 'public/uploads/barang';

        // Buat direktori baru jika belum ada
        if (!is_dir($newDir)) {
            mkdir($newDir, 0777, true);
        }

        // Pastikan direktori lama ada
        if (is_dir($oldDir)) {
            $files = scandir($oldDir);
            foreach ($files as $file) {
                if ($file != '.' && $file != '..') {
                    $oldPath = $oldDir . '/' . $file;
                    $newPath = $newDir . '/' . $file;

                    if (file_exists($oldPath) && !file_exists($newPath)) {
                        if (copy($oldPath, $newPath)) {
                            $result['detail'][] = "Berhasil migrasi: $file";
                        } else {
                            $result['detail'][] = "Gagal migrasi: $file";
                            $result['status'] = false;
                        }
                    } else if (file_exists($newPath)) {
                        $result['detail'][] = "File sudah ada: $file";
                    }
                }
            }
        } else {
            $result['detail'][] = "Direktori lama tidak ditemukan: $oldDir";
        }

        // Update database jika diperlukan
        $barangModel = new \App\Models\BarangModel();
        $barangs = $barangModel->findAll();

        foreach ($barangs as $barang) {
            if (!empty($barang['foto'])) {
                // Cek apakah file ada di direktori baru
                if (file_exists($newDir . '/' . $barang['foto'])) {
                    $result['detail'][] = "Data barang OK: " . $barang['namabarang'];
                } else {
                    $result['detail'][] = "Data barang perlu dicek: " . $barang['namabarang'] . " (foto: " . $barang['foto'] . ")";
                }
            }
        }

        return view('admin/utility/migration_result', ['result' => $result]);
    }
}
