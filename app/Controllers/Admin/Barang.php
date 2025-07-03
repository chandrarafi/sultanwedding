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
            'data' => $data
        ]);
    }

    public function getById($kdbarang)
    {
        $data = $this->barangModel->find($kdbarang);

        if ($data) {
            return $this->response->setJSON([
                'status' => true,
                'data' => $data
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang tidak ditemukan'
            ]);
        }
    }

    public function create()
    {
        $data = $this->request->getPost();

        if ($this->barangModel->save($data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil ditambahkan'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal ditambahkan',
                'errors' => $this->barangModel->errors()
            ]);
        }
    }

    public function update($kdbarang)
    {
        $data = $this->request->getPost();

        if ($this->barangModel->update($kdbarang, $data)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil diperbarui'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal diperbarui',
                'errors' => $this->barangModel->errors()
            ]);
        }
    }

    public function delete($kdbarang)
    {
        if ($this->barangModel->delete($kdbarang)) {
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Data barang berhasil dihapus'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Data barang gagal dihapus'
            ]);
        }
    }
}
