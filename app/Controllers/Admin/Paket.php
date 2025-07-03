<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\KategoriModel;
use App\Models\BarangModel;
use CodeIgniter\API\ResponseTrait;

class Paket extends BaseController
{
    use ResponseTrait;

    protected $paketModel;
    protected $kategoriModel;
    protected $barangModel;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Paket',
        ];

        return view('admin/paket/index', $data);
    }

    public function getAll()
    {
        if ($this->request->isAJAX()) {
            $pakets = $this->paketModel->getPaketWithKategori();

            return $this->respond([
                'status' => true,
                'data' => $pakets
            ]);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Paket',
            'kategori' => $this->kategoriModel->findAll(),
            'barang' => $this->barangModel->findAll(),
        ];

        return view('admin/paket/create', $data);
    }

    public function store()
    {
        // Validasi input
        $rules = $this->paketModel->validationRules;

        // Jika ada upload foto
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,2048]|mime_in[foto,image/png,image/jpg,image/jpeg]';
        }

        if (!$this->validate($rules, $this->paketModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data untuk disimpan
        $data = [
            'namapaket' => $this->request->getPost('namapaket'),
            'detailpaket' => $this->request->getPost('detailpaket'),
            'harga' => $this->request->getPost('harga'),
            'kdkategori' => $this->request->getPost('kdkategori'),
            'kdbarang' => $this->request->getPost('kdbarang') ?: null,
        ];

        // Upload foto jika ada
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
            $foto = $this->request->getFile('foto');
            $namaFoto = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/paket', $namaFoto);
            $data['foto'] = $namaFoto;
        }

        // Simpan data
        $this->paketModel->insert($data);

        return redirect()->to('admin/paket')->with('success', 'Paket berhasil ditambahkan');
    }

    public function edit($kdpaket)
    {
        $paket = $this->paketModel->find($kdpaket);

        if (!$paket) {
            return redirect()->to('admin/paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Paket',
            'paket' => $paket,
            'kategori' => $this->kategoriModel->findAll(),
            'barang' => $this->barangModel->findAll(),
        ];

        return view('admin/paket/edit', $data);
    }

    public function update($kdpaket)
    {
        // Validasi input
        $rules = $this->paketModel->validationRules;

        // Jika ada upload foto
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,2048]|mime_in[foto,image/png,image/jpg,image/jpeg]';
        }

        if (!$this->validate($rules, $this->paketModel->validationMessages)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Data untuk diupdate
        $data = [
            'namapaket' => $this->request->getPost('namapaket'),
            'detailpaket' => $this->request->getPost('detailpaket'),
            'harga' => $this->request->getPost('harga'),
            'kdkategori' => $this->request->getPost('kdkategori'),
            'kdbarang' => $this->request->getPost('kdbarang') ?: null,
        ];

        // Upload foto jika ada
        if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
            $foto = $this->request->getFile('foto');
            $namaFoto = $foto->getRandomName();
            $foto->move(ROOTPATH . 'public/uploads/paket', $namaFoto);

            // Hapus foto lama jika ada
            $paket = $this->paketModel->find($kdpaket);
            if ($paket['foto'] && file_exists(ROOTPATH . 'public/uploads/paket/' . $paket['foto'])) {
                unlink(ROOTPATH . 'public/uploads/paket/' . $paket['foto']);
            }

            $data['foto'] = $namaFoto;
        }

        // Update data
        $this->paketModel->update($kdpaket, $data);

        return redirect()->to('admin/paket')->with('success', 'Paket berhasil diperbarui');
    }

    public function delete($kdpaket)
    {
        if ($this->request->isAJAX()) {
            $paket = $this->paketModel->find($kdpaket);

            if (!$paket) {
                return $this->fail('Paket tidak ditemukan');
            }

            // Hapus foto jika ada
            if ($paket['foto'] && file_exists(ROOTPATH . 'public/uploads/paket/' . $paket['foto'])) {
                unlink(ROOTPATH . 'public/uploads/paket/' . $paket['foto']);
            }

            $this->paketModel->delete($kdpaket);

            return $this->respond([
                'status' => true,
                'message' => 'Paket berhasil dihapus'
            ]);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function detail($kdpaket)
    {
        $paket = $this->paketModel->getPaketDetail($kdpaket);

        if (!$paket) {
            return redirect()->to('admin/paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Paket',
            'paket' => $paket
        ];

        return view('admin/paket/detail', $data);
    }
}
