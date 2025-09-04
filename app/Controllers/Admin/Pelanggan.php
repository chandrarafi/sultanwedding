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


            $response['csrf_token'] = csrf_hash();

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
                    'data' => $data,
                    'csrf_token' => csrf_hash()
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


            if (!$this->pelangganModel->validate($data)) {
                return $this->respond([
                    'status' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $this->pelangganModel->errors()
                ], 400);
            }


            $this->db->transBegin();

            try {

                if ($createUser && empty($userId)) {
                    $userData = [
                        'username' => $this->request->getPost('username'),
                        'email' => $this->request->getPost('email'),
                        'password' => $this->request->getPost('password'),
                        'name' => $data['namapelanggan'],
                        'role' => 'pelanggan',
                        'status' => 'active',
                        'is_verified' => 1 // Admin membuat akun langsung terverifikasi
                    ];


                    if (!$this->userModel->validate($userData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => false,
                            'message' => 'Validasi gagal',
                            'errors' => $this->userModel->errors()
                        ]);
                    }


                    $this->userModel->save($userData);
                    $userId = $this->userModel->getInsertID();
                    $data['iduser'] = $userId;
                } elseif ($createUser && !empty($userId)) {
                    $userData = [
                        'id' => $userId,
                        'name' => $data['namapelanggan']
                    ];


                    if ($password = $this->request->getPost('password')) {
                        $userData['password'] = $password;
                    }


                    if ($email = $this->request->getPost('email')) {
                        $userData['email'] = $email;
                    }


                    if ($username = $this->request->getPost('username')) {
                        $userData['username'] = $username;
                    }


                    if (!$this->userModel->validate($userData)) {
                        $this->db->transRollback();
                        return $this->response->setStatusCode(400)->setJSON([
                            'status' => false,
                            'message' => 'Validasi gagal',
                            'errors' => $this->userModel->errors()
                        ]);
                    }


                    $this->userModel->save($userData);
                    $data['iduser'] = $userId;
                } elseif (!$createUser && !empty($kdpelanggan)) {
                    $data['iduser'] = null;
                }


                if (!empty($kdpelanggan)) {

                    $this->pelangganModel->update($kdpelanggan, $data);
                    $message = 'Data pelanggan berhasil diperbarui';
                } else {

                    $this->pelangganModel->insert($data);
                    $message = 'Data pelanggan berhasil ditambahkan';
                }


                $this->db->transCommit();

                return $this->respond([
                    'status' => true,
                    'message' => $message
                ]);
            } catch (\Exception $e) {

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


            $pelanggan = $this->pelangganModel->find($kdpelanggan);
            if (!$pelanggan) {
                return $this->fail('Data pelanggan tidak ditemukan');
            }


            $this->db->transBegin();

            try {

                if (!empty($pelanggan['iduser'])) {
                    $this->userModel->delete($pelanggan['iduser']);
                }


                $this->pelangganModel->delete($kdpelanggan);


                $this->db->transCommit();

                return $this->respond([
                    'status' => true,
                    'message' => 'Data pelanggan berhasil dihapus'
                ]);
            } catch (\Exception $e) {

                $this->db->transRollback();

                return $this->fail('Gagal menghapus data pelanggan: ' . $e->getMessage());
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }
}
