<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PemesananBarangModel;
use CodeIgniter\HTTP\ResponseInterface;

class Dashboard extends BaseController
{
    protected $pemesananBarangModel;

    public function __construct()
    {
        $this->pemesananBarangModel = new PemesananBarangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Dashboard',
            'pemesananForReturn' => $this->pemesananBarangModel->getPemesananForReturn()
        ];
        return view('admin/dashboard', $data);
    }
}
