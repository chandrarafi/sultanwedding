<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PemesananPaketModel;
use App\Models\PemesananBarangModel;
use App\Models\DetailPemesananBarangModel;
use App\Models\PaketModel;
use App\Models\BarangModel;
use App\Models\PembayaranModel;
use App\Models\KategoriModel;

class Laporan extends BaseController
{
    protected $pemesananPaketModel;
    protected $pemesananBarangModel;
    protected $detailPemesananBarangModel;
    protected $paketModel;
    protected $barangModel;
    protected $pembayaranModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->pemesananPaketModel = new PemesananPaketModel();
        $this->pemesananBarangModel = new PemesananBarangModel();
        $this->detailPemesananBarangModel = new DetailPemesananBarangModel();
        $this->paketModel = new PaketModel();
        $this->barangModel = new BarangModel();
        $this->pembayaranModel = new PembayaranModel();
        $this->kategoriModel = new KategoriModel();
    }

    /**
     * Halaman laporan paket
     */
    public function paket()
    {
        $kdkategori = $this->request->getGet('kategori') ?? '';

        // Ambil data kategori untuk dropdown filter
        $kategori = $this->kategoriModel->findAll();

        // Ambil data paket dengan filter kategori jika ada
        if ($kdkategori != '') {
            $pakets = $this->paketModel->getPaketByKategori($kdkategori);
        } else {
            $pakets = $this->paketModel->getPaketWithKategori();
        }

        $data = [
            'title' => 'Laporan Paket',
            'pakets' => $pakets,
            'kategori' => $kategori,
            'selected_kategori' => $kdkategori
        ];

        return view('admin/laporan/paket', $data);
    }

    /**
     * Cetak laporan paket dalam format HTML (tab baru)
     */
    public function cetakPaket()
    {
        $kdkategori = $this->request->getGet('kategori') ?? '';
        $tanggal = date('d/m/Y');

        // Ambil data kategori untuk dropdown filter
        $kategori = $this->kategoriModel->findAll();

        // Ambil data paket dengan filter kategori jika ada
        if ($kdkategori != '') {
            $pakets = $this->paketModel->getPaketByKategori($kdkategori);
            // Ambil nama kategori jika ada filter
            $kategoriName = '';
            foreach ($kategori as $kat) {
                if ($kat['kdkategori'] == $kdkategori) {
                    $kategoriName = $kat['namakategori'];
                    break;
                }
            }
            $title = 'Laporan Paket - Kategori: ' . $kategoriName;
        } else {
            $pakets = $this->paketModel->getPaketWithKategori();
            $title = 'Laporan Paket';
        }

        $data = [
            'title' => $title,
            'pakets' => $pakets,
            'tanggal' => $tanggal
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_paket', $data);
    }

    /**
     * Halaman laporan pemesanan paket
     */
    public function pemesananPaket()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pemesanan paket
        $pemesanan = $this->pemesananPaketModel->getLaporanPemesananPaket($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pemesanan as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                // Hanya hitung yang sudah dibayar
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }
        }

        $data = [
            'title' => 'Laporan Pemesanan Paket',
            'pemesanan' => $pemesanan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        return view('admin/laporan/pemesanan_paket', $data);
    }

    /**
     * Cetak laporan pemesanan paket dalam format HTML (tab baru)
     */
    public function cetakLaporanPaket()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pemesanan paket
        $pemesanan = $this->pemesananPaketModel->getLaporanPemesananPaket($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pemesanan as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                // Hanya hitung yang sudah dibayar
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }
        }

        $data = [
            'title' => 'Laporan Pemesanan Paket',
            'pemesanan' => $pemesanan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_paket', $data);
    }

    /**
     * Halaman laporan pemesanan barang
     */
    public function pemesananBarang()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pemesanan barang
        $pemesanan = $this->pemesananBarangModel->getLaporanPemesananBarang($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pemesanan as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                // Hanya hitung yang sudah dibayar
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }
        }

        $data = [
            'title' => 'Laporan Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        return view('admin/laporan/pemesanan_barang', $data);
    }

    /**
     * Cetak laporan pemesanan barang dalam format HTML (tab baru)
     */
    public function cetakLaporanBarang()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pemesanan barang
        $pemesanan = $this->pemesananBarangModel->getLaporanPemesananBarang($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pemesanan as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                // Hanya hitung yang sudah dibayar
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }
        }

        $data = [
            'title' => 'Laporan Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_barang', $data);
    }

    /**
     * Halaman laporan pembayaran
     */
    public function pembayaran()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pembayaran
        $pembayaran = $this->pembayaranModel->getLaporanPembayaran($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pembayaran as $item) {
            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $totalPendapatan += $item['jumlahbayar'];
            }
        }

        $data = [
            'title' => 'Laporan Pembayaran',
            'pembayaran' => $pembayaran,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        return view('admin/laporan/pembayaran', $data);
    }

    /**
     * Cetak laporan pembayaran dalam format HTML (tab baru)
     */
    public function cetakLaporanPembayaran()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-01');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        $status = $this->request->getGet('status') ?? '';

        // Ambil data pembayaran
        $pembayaran = $this->pembayaranModel->getLaporanPembayaran($tanggal_awal, $tanggal_akhir, $status);

        // Hitung total pendapatan
        $totalPendapatan = 0;
        foreach ($pembayaran as $item) {
            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $totalPendapatan += $item['jumlahbayar'];
            }
        }

        $data = [
            'title' => 'Laporan Pembayaran',
            'pembayaran' => $pembayaran,
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir,
            'status' => $status,
            'totalPendapatan' => $totalPendapatan
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pembayaran', $data);
    }

    /**
     * Halaman laporan pemesanan paket bulanan
     */
    public function pemesananPaketBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pemesananPaketModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pemesanan Paket Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        return view('admin/laporan/pemesanan_paket_bulanan', $data);
    }

    /**
     * Cetak laporan pemesanan paket bulanan dalam format HTML (tab baru)
     */
    public function cetakLaporanPaketBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pemesananPaketModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pemesanan Paket Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_paket_bulanan', $data);
    }

    /**
     * Halaman laporan pemesanan paket tahunan
     */
    public function pemesananPaketTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pemesananPaketModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pemesanan Paket Tahunan',
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        return view('admin/laporan/pemesanan_paket_tahunan', $data);
    }

    /**
     * Cetak laporan pemesanan paket tahunan dalam format HTML (tab baru)
     */
    public function cetakLaporanPaketTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pemesananPaketModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pemesanan Paket Tahunan ' . $tahun,
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_paket_tahunan', $data);
    }

    /**
     * Halaman laporan pemesanan barang bulanan
     */
    public function pemesananBarangBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pemesananBarangModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pemesanan Barang Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        return view('admin/laporan/pemesanan_barang_bulanan', $data);
    }

    /**
     * Cetak laporan pemesanan barang bulanan dalam format HTML (tab baru)
     */
    public function cetakLaporanBarangBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pemesananBarangModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pemesanan Barang Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_barang_bulanan', $data);
    }

    /**
     * Halaman laporan pemesanan barang tahunan
     */
    public function pemesananBarangTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pemesananBarangModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pemesanan Barang Tahunan',
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        return view('admin/laporan/pemesanan_barang_tahunan', $data);
    }

    /**
     * Cetak laporan pemesanan barang tahunan dalam format HTML (tab baru)
     */
    public function cetakLaporanBarangTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pemesananBarangModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pemesanan Barang Tahunan ' . $tahun,
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pemesanan_barang_tahunan', $data);
    }

    /**
     * Halaman laporan pembayaran bulanan
     */
    public function pembayaranBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pembayaranModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pembayaran Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        return view('admin/laporan/pembayaran_bulanan', $data);
    }

    /**
     * Cetak laporan pembayaran bulanan dalam format PDF
     */
    public function cetakLaporanPembayaranBulanan()
    {
        $bulan = $this->request->getGet('bulan') ?? date('m');
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan bulanan
        $laporan = $this->pembayaranModel->getLaporanBulanan($bulan, $tahun);

        $data = [
            'title' => 'Laporan Pembayaran Bulanan',
            'laporan' => $laporan,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nama_bulan' => $this->getNamaBulan($bulan)
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pembayaran_bulanan', $data);
    }

    /**
     * Halaman laporan pembayaran tahunan
     */
    public function pembayaranTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pembayaranModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pembayaran Tahunan',
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        return view('admin/laporan/pembayaran_tahunan', $data);
    }

    /**
     * Cetak laporan pembayaran tahunan dalam format HTML (tab baru)
     */
    public function cetakLaporanPembayaranTahunan()
    {
        $tahun = $this->request->getGet('tahun') ?? date('Y');

        // Ambil data laporan tahunan
        $laporan = $this->pembayaranModel->getLaporanTahunan($tahun);

        $data = [
            'title' => 'Laporan Pembayaran Tahunan ' . $tahun,
            'laporan' => $laporan,
            'tahun' => $tahun
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pembayaran_tahunan', $data);
    }

    /**
     * Helper untuk mendapatkan nama bulan dari angka bulan
     */
    private function getNamaBulan($bulan)
    {
        $nama_bulan = [
            '01' => 'Januari',
            '02' => 'Februari',
            '03' => 'Maret',
            '04' => 'April',
            '05' => 'Mei',
            '06' => 'Juni',
            '07' => 'Juli',
            '08' => 'Agustus',
            '09' => 'September',
            '10' => 'Oktober',
            '11' => 'November',
            '12' => 'Desember'
        ];

        return $nama_bulan[$bulan] ?? '';
    }

    /**
     * Halaman laporan pelanggan
     */
    public function pelanggan()
    {
        // Ambil data pelanggan
        $db = \Config\Database::connect();
        $pelanggan = $db->table('pelanggan p')
            ->select('p.*, u.username, u.email')
            ->join('users u', 'u.id = p.iduser', 'left')
            ->orderBy('p.namapelanggan', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Laporan Pelanggan',
            'pelanggan' => $pelanggan
        ];

        return view('admin/laporan/pelanggan', $data);
    }

    /**
     * Cetak laporan pelanggan dalam format HTML (tab baru)
     */
    public function cetakLaporanPelanggan()
    {
        // Ambil data pelanggan
        $db = \Config\Database::connect();
        $pelanggan = $db->table('pelanggan p')
            ->select('p.*, u.username, u.email')
            ->join('users u', 'u.id = p.iduser', 'left')
            ->orderBy('p.namapelanggan', 'ASC')
            ->get()
            ->getResultArray();

        $data = [
            'title' => 'Laporan Data Pelanggan',
            'pelanggan' => $pelanggan,
            'tanggal' => date('d/m/Y')
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_pelanggan', $data);
    }

    /**
     * Halaman laporan barang
     */
    public function dataBarang()
    {
        // Ambil data barang
        $barang = $this->barangModel->orderBy('namabarang', 'ASC')->findAll();

        $data = [
            'title' => 'Laporan Barang',
            'barang' => $barang
        ];

        return view('admin/laporan/barang', $data);
    }

    /**
     * Cetak laporan barang dalam format HTML (tab baru)
     */
    public function cetakLaporanDataBarang()
    {
        // Ambil data barang
        $barang = $this->barangModel->orderBy('namabarang', 'ASC')->findAll();

        $data = [
            'title' => 'Laporan Data Barang',
            'barang' => $barang,
            'tanggal' => date('d/m/Y')
        ];

        // Tampilkan langsung sebagai HTML di tab baru
        return view('admin/laporan/cetak_data_barang', $data);
    }
}
