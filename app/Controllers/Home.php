<?php

namespace App\Controllers;

use App\Models\PaketModel;
use App\Models\DetailPaketModel;
use App\Models\KategoriModel;
use App\Models\BarangModel;

class Home extends BaseController
{
    protected $paketModel;
    protected $detailPaketModel;
    protected $kategoriModel;
    protected $barangModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->detailPaketModel = new DetailPaketModel();
        $this->kategoriModel = new KategoriModel();
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        // Ambil 3 paket terbaru dengan kategori
        $pakets = $this->paketModel->getPaketWithKategori();
        $pakets = array_slice($pakets, 0, 3); // Ambil 3 paket terbaru

        // Ambil 6 barang terbaru dengan kategori
        $barangs = $this->barangModel->getBarangWithKategori();
        $barangs = array_slice($barangs, 0, 6); // Ambil 6 barang terbaru

        $data = [
            'title' => 'Beranda',
            'pakets' => $pakets,
            'barangs' => $barangs
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
        // Ambil semua paket dengan kategori
        $pakets = $this->paketModel->getPaketWithKategori();

        $data = [
            'title' => 'Paket Wedding',
            'pakets' => $pakets,
            'kategori' => $this->kategoriModel->findAll()
        ];

        return view('home/paket', $data);
    }

    public function paketDetail($id)
    {
        // Ambil detail paket dengan barang
        $paket = $this->paketModel->getPaketWithItems($id);

        if (!$paket) {
            return redirect()->to('paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Paket: ' . $paket['namapaket'],
            'paket' => $paket
        ];

        return view('home/paket_detail', $data);
    }

    public function barang()
    {
        // Ambil semua barang
        $barangs = $this->barangModel->getBarangWithKategori();

        $data = [
            'title' => 'Sewa Barang',
            'barangs' => $barangs
        ];

        return view('home/barang', $data);
    }

    public function barangDetail($id)
    {
        // Ambil detail barang
        $barang = $this->barangModel->getBarangWithKategori($id);

        if (!$barang) {
            return redirect()->to('barang')->with('error', 'Barang tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Barang: ' . $barang['namabarang'],
            'barang' => $barang
        ];

        return view('home/barang_detail', $data);
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
