<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PaketModel;
use App\Models\KategoriModel;
use App\Models\BarangModel;
use App\Models\DetailPaketModel;
use CodeIgniter\API\ResponseTrait;

class Paket extends BaseController
{
    use ResponseTrait;

    protected $paketModel;
    protected $kategoriModel;
    protected $barangModel;
    protected $detailPaketModel;
    protected $db;

    public function __construct()
    {
        $this->paketModel = new PaketModel();
        $this->kategoriModel = new KategoriModel();
        $this->barangModel = new BarangModel();
        $this->detailPaketModel = new DetailPaketModel();
        $this->db = \Config\Database::connect();
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

    public function getAllBarang()
    {
        if ($this->request->isAJAX()) {
            $barangs = $this->barangModel->findAll();

            return $this->respond([
                'status' => true,
                'data' => $barangs
            ]);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Paket',
            'kategori' => $this->kategoriModel->findAll(),
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

        // Mulai transaksi database
        $this->db->transBegin();

        try {
            // Data paket untuk disimpan
            $data = [
                'namapaket' => $this->request->getPost('namapaket'),
                'detailpaket' => $this->request->getPost('detailpaket'),
                'harga' => $this->request->getPost('harga'),
                'kdkategori' => $this->request->getPost('kdkategori'),
            ];

            // Upload foto jika ada
            if ($this->request->getFile('foto') && $this->request->getFile('foto')->isValid()) {
                $foto = $this->request->getFile('foto');
                $namaFoto = $foto->getRandomName();
                $foto->move(ROOTPATH . 'public/uploads/paket', $namaFoto);
                $data['foto'] = $namaFoto;
            }

            // Simpan data paket
            $this->paketModel->insert($data);
            $kdpaket = $this->db->insertID();

            // Simpan detail paket jika ada
            $barangIds = $this->request->getPost('detail_barang') ?? [];
            $jumlah = $this->request->getPost('detail_jumlah') ?? [];
            $harga = $this->request->getPost('detail_harga') ?? [];
            $keterangan = $this->request->getPost('detail_keterangan') ?? [];

            for ($i = 0; $i < count($barangIds); $i++) {
                if (empty($barangIds[$i])) continue;

                $detailData = [
                    'kdpaket' => $kdpaket,
                    'kdbarang' => $barangIds[$i],
                    'jumlah' => $jumlah[$i] ?? 1,
                    'harga' => $harga[$i] ?? 0,
                    'keterangan' => $keterangan[$i] ?? '',
                ];

                $this->detailPaketModel->insert($detailData);
            }

            $this->db->transCommit();

            return redirect()->to('admin/paket')->with('success', 'Paket berhasil ditambahkan');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function edit($kdpaket)
    {
        $paket = $this->paketModel->getPaketWithItems($kdpaket);

        if (!$paket) {
            return redirect()->to('admin/paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Edit Paket',
            'paket' => $paket,
            'kategori' => $this->kategoriModel->findAll(),
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

        // Mulai transaksi database
        $this->db->transBegin();

        try {
            // Data untuk diupdate
            $data = [
                'namapaket' => $this->request->getPost('namapaket'),
                'detailpaket' => $this->request->getPost('detailpaket'),
                'harga' => $this->request->getPost('harga'),
                'kdkategori' => $this->request->getPost('kdkategori'),
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

            // Update data paket
            $this->paketModel->update($kdpaket, $data);

            // Hapus semua detail paket yang lama
            $this->detailPaketModel->deleteByPaket($kdpaket);

            // Simpan detail paket baru
            $barangIds = $this->request->getPost('detail_barang') ?? [];
            $jumlah = $this->request->getPost('detail_jumlah') ?? [];
            $harga = $this->request->getPost('detail_harga') ?? [];
            $keterangan = $this->request->getPost('detail_keterangan') ?? [];

            for ($i = 0; $i < count($barangIds); $i++) {
                if (empty($barangIds[$i])) continue;

                $detailData = [
                    'kdpaket' => $kdpaket,
                    'kdbarang' => $barangIds[$i],
                    'jumlah' => $jumlah[$i] ?? 1,
                    'harga' => $harga[$i] ?? 0,
                    'keterangan' => $keterangan[$i] ?? '',
                ];

                $this->detailPaketModel->insert($detailData);
            }

            $this->db->transCommit();

            return redirect()->to('admin/paket')->with('success', 'Paket berhasil diperbarui');
        } catch (\Exception $e) {
            $this->db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($kdpaket)
    {
        if ($this->request->isAJAX()) {
            $paket = $this->paketModel->find($kdpaket);

            if (!$paket) {
                return $this->fail('Paket tidak ditemukan');
            }

            // Mulai transaksi database
            $this->db->transBegin();

            try {
                // Hapus detail paket terlebih dahulu
                $this->detailPaketModel->deleteByPaket($kdpaket);

                // Hapus foto jika ada
                if ($paket['foto'] && file_exists(ROOTPATH . 'public/uploads/paket/' . $paket['foto'])) {
                    unlink(ROOTPATH . 'public/uploads/paket/' . $paket['foto']);
                }

                // Hapus paket
                $this->paketModel->delete($kdpaket);

                $this->db->transCommit();

                return $this->respond([
                    'status' => true,
                    'message' => 'Paket berhasil dihapus'
                ]);
            } catch (\Exception $e) {
                $this->db->transRollback();
                return $this->fail('Gagal menghapus paket: ' . $e->getMessage());
            }
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function detail($kdpaket)
    {
        $paket = $this->paketModel->getPaketWithItems($kdpaket);

        if (!$paket) {
            return redirect()->to('admin/paket')->with('error', 'Paket tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Paket',
            'paket' => $paket
        ];

        return view('admin/paket/detail', $data);
    }

    public function getDetailPaket($kdpaket)
    {
        if ($this->request->isAJAX()) {
            $details = $this->detailPaketModel->getDetailPaket($kdpaket);

            return $this->respond([
                'status' => true,
                'data' => $details
            ]);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }

    public function getBarang($kdbarang = null)
    {
        if ($this->request->isAJAX()) {
            if ($kdbarang) {
                $barang = $this->barangModel->find($kdbarang);
                if (!$barang) {
                    return $this->fail('Barang tidak ditemukan');
                }
                return $this->respond([
                    'status' => true,
                    'data' => $barang
                ]);
            }

            $search = $this->request->getGet('search');
            $limit = $this->request->getGet('limit') ?? 10;

            $barangModel = $this->barangModel;
            if ($search) {
                $barangModel = $barangModel->like('namabarang', $search);
            }

            $barangs = $barangModel->findAll($limit);

            return $this->respond([
                'status' => true,
                'data' => $barangs
            ]);
        }

        return $this->failUnauthorized('Tidak diizinkan');
    }
}
