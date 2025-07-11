<?php

namespace App\Controllers;

use App\Models\PaketModel;

class Home extends BaseController
{
    protected $paketModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
    }

    public function index()
    {
        // Ambil beberapa paket untuk ditampilkan di landing page
        $data = [
            'title' => 'Beranda',
            'pakets' => $this->paketModel->findAll(3)
        ];

        return view('home/index', $data);
    }

    public function about()
    {
        $data = [
            'title' => 'Tentang Kami'
        ];

        return view('home/about', $data);
    }

    public function paket()
    {
        $data = [
            'title' => 'Paket Wedding',
            'pakets' => $this->paketModel->findAll()
        ];

        return view('home/paket', $data);
    }

    public function paketDetail($id)
    {
        $paket = $this->paketModel->find($id);

        if (!$paket) {
            return redirect()->to('paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Paket: ' . $paket['namapaket'],
            'paket' => $paket
        ];

        return view('home/paket_detail', $data);
    }

    public function galeri()
    {
        $data = [
            'title' => 'Galeri'
        ];

        return view('home/galeri', $data);
    }

    public function kontak()
    {
        $data = [
            'title' => 'Kontak Kami'
        ];

        return view('home/kontak', $data);
    }
}
