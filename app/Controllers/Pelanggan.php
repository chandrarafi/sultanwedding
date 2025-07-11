<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\UserModel;

class Pelanggan extends BaseController
{
    protected $pelangganModel;
    protected $userModel;

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->userModel = new UserModel();
    }

    public function index()
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Beranda',
            'user' => $this->userModel->find($userId)
        ];

        // Cek apakah user memiliki data pelanggan
        $pelanggan = $this->pelangganModel->where('iduser', $userId)->first();
        $data['pelanggan'] = $pelanggan;

        // Jika role pelanggan, gunakan view home/index
        if (session()->get('role') === 'pelanggan') {
            return view('home/index', $data);
        }

        // Jika role admin, redirect ke admin
        return redirect()->to('admin');
    }

    public function profile()
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Profil Saya',
            'user' => $this->userModel->find($userId)
        ];

        // Cek apakah user memiliki data pelanggan
        $pelanggan = $this->pelangganModel->where('iduser', $userId)->first();
        $data['pelanggan'] = $pelanggan;

        return view('pelanggan/profile', $data);
    }

    public function updateProfile()
    {
        $userId = session()->get('user_id');
        $pelanggan = $this->pelangganModel->where('iduser', $userId)->first();

        if (!$pelanggan) {
            return redirect()->back()->with('error', 'Data pelanggan tidak ditemukan');
        }

        $data = [
            'namapelanggan' => $this->request->getPost('namapelanggan'),
            'alamat' => $this->request->getPost('alamat'),
            'nohp' => $this->request->getPost('nohp')
        ];

        // Update data pelanggan
        $this->pelangganModel->update($pelanggan['kdpelanggan'], $data);

        // Update data user jika ada perubahan
        $userData = [
            'id' => $userId,
            'name' => $data['namapelanggan']
        ];

        // Update email jika diisi
        if ($email = $this->request->getPost('email')) {
            $userData['email'] = $email;
        }

        // Update password jika diisi
        if ($password = $this->request->getPost('password')) {
            $userData['password'] = $password;
        }

        // Validasi dan update data user
        if ($this->userModel->validate($userData)) {
            $this->userModel->save($userData);
            return redirect()->to('profile')->with('success', 'Profil berhasil diperbarui');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->userModel->errors());
        }
    }

    // Tambahkan fungsi pemesanan dan lainnya di sini
    public function pemesanan()
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Pemesanan Saya',
            'user' => $this->userModel->find($userId)
        ];

        return view('pelanggan/pemesanan', $data);
    }

    public function createPemesanan()
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Buat Pemesanan',
            'user' => $this->userModel->find($userId)
        ];

        return view('pelanggan/create_pemesanan', $data);
    }

    public function storePemesanan()
    {
        // Logika penyimpanan pemesanan
        return redirect()->to('pemesanan')->with('success', 'Pemesanan berhasil dibuat');
    }

    public function detailPemesanan($id)
    {
        $userId = session()->get('user_id');
        $data = [
            'title' => 'Detail Pemesanan',
            'user' => $this->userModel->find($userId),
            'id' => $id
        ];

        return view('pelanggan/detail_pemesanan', $data);
    }
}
