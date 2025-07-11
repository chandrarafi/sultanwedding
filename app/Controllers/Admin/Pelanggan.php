<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PelangganModel;
use App\Models\UserModel;
use CodeIgniter\API\ResponseTrait;

class Pelanggan extends BaseController
{
    use ResponseTrait;

    protected $pelangganModel;
    protected $userModel;
    protected $db;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->userModel = new UserModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Pelanggan',
        ];

        return view('admin/pelanggan/index', $data);
    }

    public function getAll()
    {
        if ($this->request->isAJAX()) {
            $postData = $this->request->getPost();
            $response = $this->pelangganModel->getDataTables($postData);
            return $this->respond($response);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function getById($kdpelanggan)
    {
        if ($this->request->isAJAX()) {
            $data = $this->pelangganModel->getPelangganWithUser($kdpelanggan);

            if ($data) {
                return $this->respond([
                    'status' => true,
                    'data' => $data
                ]);
            } else {
                return $this->fail('Data pelanggan tidak ditemukan');
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function save()
    {
        if ($this->request->isAJAX()) {
            $data = [
                'namapelanggan' => $this->request->getPost('namapelanggan'),
                'alamat' => $this->request->getPost('alamat'),
                'nohp' => $this->request->getPost('nohp'),
            ];

            $kdpelanggan = $this->request->getPost('kdpelanggan');
            $createUser = $this->request->getPost('create_user') == 'yes';
            $userId = $this->request->getPost('iduser');

            // Validasi data pelanggan
            if (!$this->pelangganModel->validate($data)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->pelangganModel->errors()
                ], 400);
            }

            // Mulai transaksi database
            $this->db->transBegin();

            try {
                // Jika membuat user baru
                if ($createUser && empty($userId)) {
                    $userData = [
                        'username' => $this->request->getPost('username'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'name' => $data['namapelanggan'],
                        'role' => 'pelanggan',
                        'status' => 'active'
                    ];

                    // Validasi data user
                    if (!$this->userModel->validate($userData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => false,
                            'message' => 'Validasi gagal',
                            'errors' => $this->userModel->errors()
                        ]);
                    }

                    // Simpan data user
                    $this->userModel->save($userData);
                    $userId = $this->userModel->getInsertID();
                    $data['iduser'] = $userId;
                }
                // Jika update user yang sudah ada
                elseif ($createUser && !empty($userId)) {
                    $userData = [
                        'id' => $userId,
                        'name' => $data['namapelanggan']
                    ];

                    // Update password jika diisi
                    if ($password = $this->request->getPost('password')) {
                        $userData['password'] = $password;
                    }

                    // Update email jika diisi
                    if ($email = $this->request->getPost('email')) {
                        $userData['email'] = $email;
                    }

                    // Update username jika diisi
                    if ($username = $this->request->getPost('username')) {
                        $userData['username'] = $username;
                    }

                    // Validasi data user
                    if (!$this->userModel->validate($userData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => false,
                            'message' => 'Validasi gagal',
                            'errors' => $this->userModel->errors()
                        ]);
                    }

                    // Update data user
                    $this->userModel->save($userData);
                    $data['iduser'] = $userId;
                }
                // Jika tidak membuat user, hapus relasi jika ada
                elseif (!$createUser && !empty($kdpelanggan)) {
                    $data['iduser'] = null;
                }

                // Update atau insert data pelanggan
                if (!empty($kdpelanggan)) {
                    // Update
                    $this->pelangganModel->update($kdpelanggan, $data);
                    $message = 'Data pelanggan berhasil diperbarui';
                } else {
                    // Insert
                    $this->pelangganModel->insert($data);
                    $message = 'Data pelanggan berhasil ditambahkan';
                }

                // Commit transaksi jika semua berhasil
                $this->db->transCommit();

                return $this->respond([
                    'status' => true,
                    'message' => $message
                ]);
            } catch (\Exception $e) {
                // Rollback transaksi jika ada error
                $this->db->transRollback();

                return $this->response->setStatusCode(400)->setJSON([
                    'status' => false,
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function delete($kdpelanggan = null)
    {
        if ($this->request->isAJAX() || $this->request->getMethod() === 'delete') {
            if (!$kdpelanggan) {
                $kdpelanggan = $this->request->getPost('kdpelanggan');
            }

            // Cek apakah data pelanggan ada
            $pelanggan = $this->pelangganModel->find($kdpelanggan);
            if (!$pelanggan) {
                return $this->fail('Data pelanggan tidak ditemukan');
            }

            // Mulai transaksi database
            $this->db->transBegin();

            try {
                // Jika pelanggan memiliki user, hapus juga user
                if (!empty($pelanggan['iduser'])) {
                    $this->userModel->delete($pelanggan['iduser']);
                }

                // Hapus data pelanggan
                $this->pelangganModel->delete($kdpelanggan);

                // Commit transaksi jika semua berhasil
                $this->db->transCommit();

                return $this->respond([
                    'status' => true,
                    'message' => 'Data pelanggan berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                // Rollback transaksi jika ada error
                $this->db->transRollback();

                return $this->fail('Gagal menghapus data pelanggan: ' . $e->getMessage());
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }
}
