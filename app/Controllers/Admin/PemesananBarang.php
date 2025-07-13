<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\PelangganModel;
use App\Models\PemesananBarangModel;
use App\Models\DetailPemesananBarangModel;
use App\Models\PembayaranModel;
use CodeIgniter\I18n\Time;

class PemesananBarang extends BaseController
{
    protected $barangModel;
    protected $pelangganModel;
    protected $pemesananModel;
    protected $detailPemesananModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->pelangganModel = new PelangganModel();
        $this->pemesananModel = new PemesananBarangModel();
        $this->detailPemesananModel = new DetailPemesananBarangModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pemesanan Barang'
        ];

        return view('admin/pemesananbarang/index', $data);
    }

    /**
     * Mendapatkan semua data pemesanan barang
     */
    public function getAll()
    {
        $data = $this->pemesananModel->getPemesananBarang();
        return $this->response->setJSON([
            'status' => true,
            'data' => $data
        ]);
    }

    /**
     * Menampilkan form tambah pemesanan barang (walk-in)
     */
    public function create()
    {
        $data = [
            'title' => 'Tambah Pemesanan Barang (Walk-in)'
        ];

        return view('admin/pemesananbarang/create', $data);
    }

    /**
     * Menyimpan pemesanan barang baru (walk-in)
     */
    public function store()
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Data pemesanan
            $pemesananData = [
                'tgl' => $this->request->getPost('tgl'),
                'kdpelanggan' => $this->request->getPost('kdpelanggan'),
                'alamatpesanan' => $this->request->getPost('alamatpesanan'),
                'lamapemesanan' => $this->request->getPost('lamapemesanan'),
                'status' => 'process', // Langsung process karena walk-in
            ];

            // Buat pemesanan
            $kdpemesananbarang = $this->pemesananModel->insert($pemesananData);

            // Data barang yang dipesan
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');
            $harga = $this->request->getPost('harga');
            $subtotal = $this->request->getPost('subtotal');

            $grandTotal = 0;

            // Simpan detail pemesanan
            for ($i = 0; $i < count($kdbarang); $i++) {
                $detailData = [
                    'kdpemesananbarang' => $kdpemesananbarang,
                    'kdbarang' => $kdbarang[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $harga[$i],
                    'subtotal' => $subtotal[$i]
                ];

                $this->detailPemesananModel->insert($detailData);
                $grandTotal += $subtotal[$i];
            }

            // Update grand total pada pemesanan
            $this->pemesananModel->update($kdpemesananbarang, ['grandtotal' => $grandTotal]);

            // Jika pembayaran langsung lunas
            if ($this->request->getPost('is_paid') == '1') {
                // Buat data pembayaran
                $kdpembayaran = $this->pembayaranModel->generatePaymentCode();
                $pembayaranData = [
                    'kdpembayaran' => $kdpembayaran,
                    'tgl' => date('Y-m-d'),
                    'metodepembayaran' => $this->request->getPost('metodepembayaran'),
                    'tipepembayaran' => 'lunas',
                    'jumlahbayar' => $grandTotal,
                    'sisa' => 0,
                    'totalpembayaran' => $grandTotal,
                    'status' => 'confirmed',
                    'dp_confirmed' => 1,
                    'dp_confirmed_at' => date('Y-m-d H:i:s'),
                    'dp_confirmed_by' => session()->get('user_id'),
                    'full_paid' => 1,
                    'full_confirmed' => 1,
                    'full_confirmed_at' => date('Y-m-d H:i:s'),
                    'full_confirmed_by' => session()->get('user_id'),
                ];

                // Simpan pembayaran
                $this->pembayaranModel->insert($pembayaranData);

                // Update kdpembayaran di pemesanan
                $this->pemesananModel->update($kdpemesananbarang, ['kdpembayaran' => $kdpembayaran]);
            }

            $db->transCommit();
            return redirect()->to('admin/pemesananbarang')->with('success', 'Pemesanan barang berhasil ditambahkan');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail pemesanan barang
     */
    public function detail($id)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan) {
            return redirect()->to('admin/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($id);

        $data = [
            'title' => 'Detail Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'detail' => $detailPemesanan
        ];

        return view('admin/pemesananbarang/detail', $data);
    }

    /**
     * Menampilkan form edit pemesanan barang
     */
    public function edit($id)
    {
        $pemesanan = $this->pemesananModel->find($id);
        if (!$pemesanan) {
            return redirect()->to('admin/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($id);

        $data = [
            'title' => 'Edit Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'detail' => $detailPemesanan,
            'pelanggan' => $this->pelangganModel->find($pemesanan['kdpelanggan'])
        ];

        return view('admin/pemesananbarang/edit', $data);
    }

    /**
     * Menyimpan perubahan pemesanan barang
     */
    public function update($id)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Data pemesanan
            $pemesananData = [
                'tgl' => $this->request->getPost('tgl'),
                'alamatpesanan' => $this->request->getPost('alamatpesanan'),
                'lamapemesanan' => $this->request->getPost('lamapemesanan'),
            ];

            // Update pemesanan
            $this->pemesananModel->update($id, $pemesananData);

            // Hapus detail pemesanan lama
            $this->detailPemesananModel->where('kdpemesananbarang', $id)->delete();

            // Data barang yang dipesan
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');
            $harga = $this->request->getPost('harga');
            $subtotal = $this->request->getPost('subtotal');

            $grandTotal = 0;

            // Simpan detail pemesanan baru
            for ($i = 0; $i < count($kdbarang); $i++) {
                $detailData = [
                    'kdpemesananbarang' => $id,
                    'kdbarang' => $kdbarang[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $harga[$i],
                    'subtotal' => $subtotal[$i]
                ];

                $this->detailPemesananModel->insert($detailData);
                $grandTotal += $subtotal[$i];
            }

            // Update grand total pada pemesanan
            $this->pemesananModel->update($id, ['grandtotal' => $grandTotal]);

            // Jika ada pembayaran, update jumlah pembayaran
            $pemesanan = $this->pemesananModel->find($id);
            if (!empty($pemesanan['kdpembayaran'])) {
                $pembayaran = $this->pembayaranModel->find($pemesanan['kdpembayaran']);
                if ($pembayaran) {
                    // Hitung ulang sisa pembayaran
                    $jumlahBayar = $pembayaran['jumlahbayar'];
                    $sisa = $grandTotal - $jumlahBayar;

                    $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                        'totalpembayaran' => $grandTotal,
                        'sisa' => $sisa > 0 ? $sisa : 0
                    ]);
                }
            }

            $db->transCommit();
            return redirect()->to('admin/pemesananbarang')->with('success', 'Pemesanan barang berhasil diperbarui');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menghapus pemesanan barang
     */
    public function delete($id)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Cek apakah pemesanan memiliki pembayaran
            $pemesanan = $this->pemesananModel->find($id);
            if (!empty($pemesanan['kdpembayaran'])) {
                // Hapus pembayaran
                $this->pembayaranModel->delete($pemesanan['kdpembayaran']);
            }

            // Hapus detail pemesanan
            $this->detailPemesananModel->where('kdpemesananbarang', $id)->delete();

            // Hapus pemesanan
            $this->pemesananModel->delete($id);

            $db->transCommit();
            return $this->response->setJSON([
                'status' => true,
                'message' => 'Pemesanan barang berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON([
                'status' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Mendapatkan data barang untuk modal
     */
    public function getBarang()
    {
        $barang = $this->barangModel->findAll();
        return $this->response->setJSON([
            'status' => true,
            'data' => $barang
        ]);
    }

    /**
     * Mendapatkan data pelanggan untuk modal
     */
    public function getPelanggan()
    {
        $pelanggan = $this->pelangganModel->findAll();
        return $this->response->setJSON([
            'status' => true,
            'data' => $pelanggan
        ]);
    }

    /**
     * Proses pembayaran H-1 (walk-in)
     */
    public function bayarH1($id)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan) {
            return redirect()->to('admin/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $metodepembayaran = $this->request->getPost('metodepembayaran');
        $jumlahBayar = $this->request->getPost('jumlah_bayar');

        // Jika belum ada pembayaran, buat pembayaran baru
        if (empty($pemesanan['kdpembayaran'])) {
            $kdpembayaran = $this->pembayaranModel->generatePaymentCode();

            // Data pembayaran
            $pembayaranData = [
                'kdpembayaran' => $kdpembayaran,
                'tgl' => date('Y-m-d'),
                'metodepembayaran' => $metodepembayaran,
                'tipepembayaran' => 'dp',
                'jumlahbayar' => $jumlahBayar,
                'sisa' => $pemesanan['grandtotal'] - $jumlahBayar,
                'totalpembayaran' => $pemesanan['grandtotal'],
                'status' => 'partial',
                'dp_confirmed' => 1,
                'dp_confirmed_at' => date('Y-m-d H:i:s'),
                'dp_confirmed_by' => session()->get('user_id'),
                'h1_paid' => 1,
                'h1_confirmed' => 1,
                'h1_confirmed_at' => date('Y-m-d H:i:s'),
                'h1_confirmed_by' => session()->get('user_id'),
            ];

            // Simpan pembayaran
            $this->pembayaranModel->insert($pembayaranData);

            // Update kdpembayaran di pemesanan
            $this->pemesananModel->update($id, ['kdpembayaran' => $kdpembayaran]);

            return redirect()->to('admin/pemesananbarang/detail/' . $id)->with('success', 'Pembayaran H-1 berhasil diproses');
        } else {
            // Update pembayaran yang sudah ada
            $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                'jumlahbayar' => $pemesanan['jumlahbayar'] + $jumlahBayar,
                'sisa' => $pemesanan['sisa'] - $jumlahBayar,
                'h1_paid' => 1,
                'h1_confirmed' => 1,
                'h1_confirmed_at' => date('Y-m-d H:i:s'),
                'h1_confirmed_by' => session()->get('user_id'),
            ]);

            return redirect()->to('admin/pemesananbarang/detail/' . $id)->with('success', 'Pembayaran H-1 berhasil diproses');
        }
    }

    /**
     * Proses pelunasan (walk-in)
     */
    public function bayarPelunasan($id)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan) {
            return redirect()->to('admin/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $metodepembayaran = $this->request->getPost('metodepembayaran');
        $jumlahBayar = $this->request->getPost('jumlah_bayar');

        // Jika belum ada pembayaran, buat pembayaran baru (langsung lunas)
        if (empty($pemesanan['kdpembayaran'])) {
            $kdpembayaran = $this->pembayaranModel->generatePaymentCode();

            // Data pembayaran
            $pembayaranData = [
                'kdpembayaran' => $kdpembayaran,
                'tgl' => date('Y-m-d'),
                'metodepembayaran' => $metodepembayaran,
                'tipepembayaran' => 'lunas',
                'jumlahbayar' => $jumlahBayar,
                'sisa' => 0,
                'totalpembayaran' => $pemesanan['grandtotal'],
                'status' => 'confirmed',
                'dp_confirmed' => 1,
                'dp_confirmed_at' => date('Y-m-d H:i:s'),
                'dp_confirmed_by' => session()->get('user_id'),
                'full_paid' => 1,
                'full_confirmed' => 1,
                'full_confirmed_at' => date('Y-m-d H:i:s'),
                'full_confirmed_by' => session()->get('user_id'),
            ];

            // Simpan pembayaran
            $this->pembayaranModel->insert($pembayaranData);

            // Update kdpembayaran di pemesanan
            $this->pemesananModel->update($id, ['kdpembayaran' => $kdpembayaran, 'status' => 'completed']);

            return redirect()->to('admin/pemesananbarang/detail/' . $id)->with('success', 'Pelunasan berhasil diproses');
        } else {
            // Update pembayaran yang sudah ada
            $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                'jumlahbayar' => $pemesanan['grandtotal'],
                'sisa' => 0,
                'tipepembayaran' => 'lunas',
                'status' => 'confirmed',
                'full_paid' => 1,
                'full_confirmed' => 1,
                'full_confirmed_at' => date('Y-m-d H:i:s'),
                'full_confirmed_by' => session()->get('user_id'),
            ]);

            // Update status pemesanan
            $this->pemesananModel->update($id, ['status' => 'completed']);

            return redirect()->to('admin/pemesananbarang/detail/' . $id)->with('success', 'Pelunasan berhasil diproses');
        }
    }
}
