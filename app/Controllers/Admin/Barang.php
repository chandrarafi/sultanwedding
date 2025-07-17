<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BarangModel;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Barang'
        ];
        return view('admin/barang/index', $data);
    }

    public function getAll()
    {
        $data = $this->barangModel->findAll();
        return $this->response->setJSON([
            'status' => true,
            'data' => $data,
            'csrf_token' => csrf_hash()
        ]);
    }

    public function getById($kdbarang)
    {
        $data = $this->barangModel->find($kdbarang);

        if ($data) {
            return $this->response->setJSON([
                'status' => true,
                'data' => $data,
                'csrf_token' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang tidak ditemukan',
                'csrf_token' => csrf_hash()
            ]);
        }
    }

    public function create()
    {
        $data = $this->request->getPost();

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/barang', $newName);
            $data['foto'] = $newName;
        }

        if ($this->barangModel->save($data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil ditambahkan',
                'csrf_token' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal ditambahkan',
                'errors' => $this->barangModel->errors(),
                'csrf_token' => csrf_hash()
            ]);
        }
    }

    public function update($kdbarang)
    {
        $data = $this->request->getPost();

        // Handle upload foto
        $foto = $this->request->getFile('foto');
        if ($foto && $foto->isValid() && !$foto->hasMoved()) {
            // Hapus foto lama jika ada
            $barang = $this->barangModel->find($kdbarang);
            if (!empty($barang['foto']) && file_exists(ROOTPATH . 'public/uploads/barang/' . $barang['foto'])) {
                unlink(ROOTPATH . 'public/uploads/barang/' . $barang['foto']);
            }

            // Upload foto baru
            $newName = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/barang', $newName);
            $data['foto'] = $newName;
        }

        if ($this->barangModel->update($kdbarang, $data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil diperbarui',
                'csrf_token' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal diperbarui',
                'errors' => $this->barangModel->errors(),
                'csrf_token' => csrf_hash()
            ]);
        }
    }

    public function delete($kdbarang)
    {
        // Hapus foto jika ada
        $barang = $this->barangModel->find($kdbarang);
        if ($barang && !empty($barang['foto']) && file_exists(ROOTPATH . 'public/uploads/barang/' . $barang['foto'])) {
            unlink(ROOTPATH . 'public/uploads/barang/' . $barang['foto']);
        }

        if ($this->barangModel->delete($kdbarang)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil dihapus',
                'csrf_token' => csrf_hash()
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal dihapus',
                'csrf_token' => csrf_hash()
            ]);
        }
    }

    /**
     * Menampilkan history stok barang (peminjaman dan pengembalian)
     * 
     * @param int $kdbarang
     * @return mixed
     */
    public function history($kdbarang)
    {
        $barangModel = new \App\Models\BarangModel();
        $detailPemesananModel = new \App\Models\DetailPemesananBarangModel();

        // Ambil data barang
        $barang = $barangModel->find($kdbarang);

        if (!$barang) {
            return redirect()->to('admin/barang')->with('error', 'Barang tidak ditemukan');
        }

        // Ambil history peminjaman dan pengembalian
        $db = \Config\Database::connect();

        // Query untuk mendapatkan peminjaman dan pengembalian
        $query = $db->query("
            SELECT 
                dpb.kddetailpemesananbarang,
                dpb.kdpemesananbarang,
                dpb.jumlah,
                dpb.created_at as tgl_peminjaman,
                pb.tgl_kembali,
                pb.status_pengembalian,
                pb.catatan_pengembalian,
                p.namapelanggan
            FROM detailpemesananbarang dpb
            JOIN pemesananbarang pb ON dpb.kdpemesananbarang = pb.kdpemesananbarang
            LEFT JOIN pelanggan p ON pb.kdpelanggan = p.kdpelanggan
            WHERE dpb.kdbarang = ? 
            ORDER BY dpb.created_at DESC
        ", [$kdbarang]);

        $history = $query->getResultArray();

        $data = [
            'title' => 'History Stok Barang',
            'barang' => $barang,
            'history' => $history
        ];

        return view('admin/barang/history', $data);
    }
}
