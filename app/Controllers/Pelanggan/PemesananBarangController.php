<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\PemesananBarangModel;
use App\Models\DetailPemesananBarangModel;
use App\Models\PembayaranModel;
use CodeIgniter\I18n\Time;

class PemesananBarangController extends BaseController
{
    protected $barangModel;
    protected $pemesananModel;
    protected $detailPemesananModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->pemesananModel = new PemesananBarangModel();
        $this->detailPemesananModel = new DetailPemesananBarangModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    /**
     * Menampilkan form pemesanan barang
     */
    public function index()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $data = [
            'title' => 'Pemesanan Barang',
            'barang' => $this->barangModel->findAll()
        ];

        return view('pelanggan/pemesananbarang/form', $data);
    }

    /**
     * Menampilkan form pemesanan barang dengan barang yang sudah dipilih
     */
    public function pemesanBarang($kdbarang)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $barang = $this->barangModel->find($kdbarang);
        if (!$barang) {
            return redirect()->to(site_url('barang'))->with('error', 'Barang tidak ditemukan');
        }

        $data = [
            'title' => 'Pemesanan Barang',
            'barang' => $this->barangModel->findAll(),
            'selected_barang' => $barang
        ];

        return view('pelanggan/pemesananbarang/form', $data);
    }

    /**
     * Menyimpan pemesanan barang
     */
    public function store()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Data pemesanan
            $pemesananData = [
                'tgl' => $this->request->getPost('tgl'),
                'kdpelanggan' => $kdpelanggan,
                'alamatpesanan' => $this->request->getPost('alamatpesanan'),
                'lamapemesanan' => $this->request->getPost('lamapemesanan'),
                'status' => 'pending',
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

            // Buat pembayaran
            $kdpembayaran = $this->pembayaranModel->generatePaymentCode();
            $pembayaranData = [
                'kdpembayaran' => $kdpembayaran,
                'tgl' => date('Y-m-d'),
                'metodepembayaran' => 'transfer',
                'tipepembayaran' => 'dp',
                'jumlahbayar' => 0,
                'sisa' => $grandTotal,
                'totalpembayaran' => $grandTotal,
                'status' => 'pending',
            ];

            // Simpan pembayaran
            $this->pembayaranModel->insert($pembayaranData);

            // Update kdpembayaran di pemesanan
            $this->pemesananModel->update($kdpemesananbarang, ['kdpembayaran' => $kdpembayaran]);

            $db->transCommit();
            return redirect()->to('pelanggan/pemesananbarang/pembayaran/' . $kdpemesananbarang)
                ->with('success', 'Pemesanan barang berhasil dibuat, silahkan lakukan pembayaran');
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan halaman pembayaran
     */
    public function pembayaran($id)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Cek apakah pemesanan milik pelanggan ini
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan || $pemesanan['kdpelanggan'] != $kdpelanggan) {
            return redirect()->to('pelanggan/pemesanan')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($id);

        $data = [
            'title' => 'Pembayaran Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'detail' => $detailPemesanan
        ];

        return view('pelanggan/pemesananbarang/pembayaran', $data);
    }

    /**
     * Proses pembayaran DP
     */
    public function bayarDP($id)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Cek apakah pemesanan milik pelanggan ini
        $pemesanan = $this->pemesananModel->find($id);
        if (!$pemesanan || $pemesanan['kdpelanggan'] != $kdpelanggan) {
            return redirect()->to('pelanggan/pemesanan')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Upload bukti pembayaran
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
        if ($buktiPembayaran && $buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            $newName = $buktiPembayaran->getRandomName();
            $buktiPembayaran->move(WRITEPATH . 'uploads/pembayaran', $newName);

            // Hitung jumlah DP (50% dari total)
            $dpAmount = $pemesanan['grandtotal'] * 0.5;

            // Update pembayaran
            $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                'jumlahbayar' => $dpAmount,
                'sisa' => $pemesanan['grandtotal'] - $dpAmount,
                'buktipembayaran' => $newName
            ]);

            return redirect()->to('pelanggan/pemesananbarang/pembayaran/' . $id)
                ->with('success', 'Bukti pembayaran DP berhasil diunggah, menunggu konfirmasi admin');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran')->withInput();
    }

    /**
     * Proses pembayaran H-1
     */
    public function bayarH1($id)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Cek apakah pemesanan milik pelanggan ini
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan || $pemesanan['kdpelanggan'] != $kdpelanggan) {
            return redirect()->to('pelanggan/pemesanan')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Cek apakah DP sudah dikonfirmasi
        if (!$pemesanan['dp_confirmed']) {
            return redirect()->back()->with('error', 'Pembayaran DP belum dikonfirmasi');
        }

        // Upload bukti pembayaran
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
        if ($buktiPembayaran && $buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            $newName = $buktiPembayaran->getRandomName();
            $buktiPembayaran->move(WRITEPATH . 'uploads/pembayaran', $newName);

            // Hitung jumlah H-1 (25% dari total)
            $h1Amount = $pemesanan['grandtotal'] * 0.25;
            $totalPaid = $pemesanan['jumlahbayar'] + $h1Amount;
            $sisa = $pemesanan['grandtotal'] - $totalPaid;

            // Update pembayaran
            $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                'jumlahbayar' => $totalPaid,
                'sisa' => $sisa,
                'buktipembayaran' => $newName,
                'h1_paid' => 1
            ]);

            return redirect()->to('pelanggan/pemesananbarang/pembayaran/' . $id)
                ->with('success', 'Bukti pembayaran H-1 berhasil diunggah, menunggu konfirmasi admin');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran')->withInput();
    }

    /**
     * Proses pelunasan
     */
    public function bayarPelunasan($id)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Cek apakah pemesanan milik pelanggan ini
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan || $pemesanan['kdpelanggan'] != $kdpelanggan) {
            return redirect()->to('pelanggan/pemesanan')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Cek apakah H-1 sudah dikonfirmasi
        if (!$pemesanan['h1_confirmed'] && $pemesanan['sisa'] > 0) {
            return redirect()->back()->with('error', 'Pembayaran H-1 belum dikonfirmasi');
        }

        // Upload bukti pembayaran
        $buktiPembayaran = $this->request->getFile('bukti_pembayaran');
        if ($buktiPembayaran && $buktiPembayaran->isValid() && !$buktiPembayaran->hasMoved()) {
            $newName = $buktiPembayaran->getRandomName();
            $buktiPembayaran->move(WRITEPATH . 'uploads/pembayaran', $newName);

            // Update pembayaran
            $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                'jumlahbayar' => $pemesanan['grandtotal'],
                'sisa' => 0,
                'buktipembayaran' => $newName,
                'tipepembayaran' => 'lunas',
                'full_paid' => 1
            ]);

            return redirect()->to('pelanggan/pemesananbarang/pembayaran/' . $id)
                ->with('success', 'Bukti pelunasan berhasil diunggah, menunggu konfirmasi admin');
        }

        return redirect()->back()->with('error', 'Gagal mengunggah bukti pembayaran')->withInput();
    }

    /**
     * Menampilkan daftar pemesanan barang pelanggan
     */
    public function daftarPemesanan()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        $pemesanan = $this->pemesananModel->getByPelanggan($kdpelanggan);

        $data = [
            'title' => 'Daftar Pemesanan Barang',
            'pemesanan' => $pemesanan
        ];

        return view('pelanggan/pemesananbarang/index', $data);
    }

    /**
     * Menampilkan detail pemesanan barang
     */
    public function detail($id)
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Cek apakah pemesanan milik pelanggan ini
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan || $pemesanan['kdpelanggan'] != $kdpelanggan) {
            return redirect()->to('pelanggan/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($id);

        $data = [
            'title' => 'Detail Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'detail' => $detailPemesanan
        ];

        return view('pelanggan/pemesananbarang/detail', $data);
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
}
