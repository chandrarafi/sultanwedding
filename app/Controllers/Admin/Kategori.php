<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KategoriModel;
use CodeIgniter\API\ResponseTrait;

class Kategori extends BaseController
{
    use ResponseTrait;

    protected $kategoriModel;

    public function __construct()
    {
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Kategori',
        ];

        return view('admin/kategori/index', $data);
    }

    public function getAll()
    {
        if ($this->request->isAJAX()) {
            $postData = $this->request->getPost();

            $response = $this->kategoriModel->getDataTables($postData);

            return $this->respond($response);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'namakategori' => $this->request->getPost('namakategori'),
            ];

            $kdkategori = $this->request->getPost('kdkategori');

            if (!empty($kdkategori)) {
                // Update
                try {
                    $result = $this->kategoriModel->update($kdkategori, $data);

                    if ($result) {
                        return $this->respond([
                            'status' => true,
                            'message' => 'Data kategori berhasil diperbarui'
                        ]);
                    } else {
                        return $this->fail('Gagal memperbarui data kategori');
                    }
                } catch (\Exception $e) {
                    return $this->fail($e->getMessage());
                }
            } else {
                // Insert
                try {
                    $result = $this->kategoriModel->insert($data);

                    if ($result) {
                        return $this->respond([
                            'status' => true,
                            'message' => 'Data kategori berhasil ditambahkan'
                        ]);
                    } else {
                        return $this->fail('Gagal menambahkan data kategori');
                    }
                } catch (\Exception $e) {
                    return $this->fail($e->getMessage());
                }
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function edit()
    {
        if ($this->request->isAJAX()) {
            $kdkategori = $this->request->getPost('kdkategori');

            $data = $this->kategoriModel->find($kdkategori);

            if ($data) {
                return $this->respond([
                    'status' => true,
                    'data' => $data
                ]);
            } else {
                return $this->fail('Data kategori tidak ditemukan');
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function delete()
    {
        if ($this->request->isAJAX()) {
            $kdkategori = $this->request->getPost('kdkategori');

            try {
                $result = $this->kategoriModel->delete($kdkategori);

                if ($result) {
                    return $this->respond([
                        'status' => true,
                        'message' => 'Data kategori berhasil dihapus'
                    ]);
                } else {
                    return $this->fail('Gagal menghapus data kategori');
                }
            } catch (\Exception $e) {
                return $this->fail($e->getMessage());
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }
}
