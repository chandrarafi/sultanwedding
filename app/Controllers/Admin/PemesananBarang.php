<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BarangModel;
use App\Models\PelangganModel;
use App\Models\PemesananBarangModel;
use App\Models\DetailPemesananBarangModel;
use App\Models\PembayaranModel;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use Dompdf\Options;

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
            // Log data untuk debugging
            log_message('debug', 'POST DATA: ' . json_encode($this->request->getPost()));

            // Generate kode pemesanan manual untuk menghindari konflik tipe data
            $prefix = 'BR-' . date('Ymd') . '-';
            $lastBooking = $db->table('pemesananbarang')
                ->like('kdpemesananbarang', $prefix, 'after')
                ->orderBy('kdpemesananbarang', 'DESC')
                ->get()
                ->getRowArray();

            if ($lastBooking) {
                // Extract the sequential number and increment
                $lastSeq = substr($lastBooking['kdpemesananbarang'], -4);
                $nextSeq = str_pad((int)$lastSeq + 1, 4, '0', STR_PAD_LEFT);
            } else {
                // First booking of the day
                $nextSeq = '0001';
            }

            $kdPemesananBarang = $prefix . $nextSeq;
            log_message('debug', 'Generated Booking Code: ' . $kdPemesananBarang);

            // Data pemesanan
            $lamaPemesanan = $this->request->getPost('lamapemesanan');

            // Pastikan kdpelanggan tidak kosong - Wajib memilih pelanggan yang terdaftar
            $kdpelanggan = $this->request->getPost('kdpelanggan');
            if (empty($kdpelanggan)) {
                throw new \Exception('Pelanggan wajib dipilih. Tidak diperbolehkan pemesanan tanpa data pelanggan.');
            }

            // Simpan data pemesanan secara manual menggunakan query builder
            $pemesananData = [
                'kdpemesananbarang' => $kdPemesananBarang,
                'tgl' => $this->request->getPost('tgl'),
                'kdpelanggan' => $kdpelanggan,
                'alamatpesanan' => $this->request->getPost('alamatpesanan'),
                'lamapemesanan' => $lamaPemesanan,
                'status' => 'process', // Langsung process karena walk-in
                'grandtotal' => $this->request->getPost('grandtotal') ?: 0,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            log_message('debug', 'Pemesanan Data: ' . json_encode($pemesananData));

            // Insert menggunakan Query Builder
            $insertResult = $db->table('pemesananbarang')->insert($pemesananData);

            if (!$insertResult) {
                log_message('error', 'Insert pemesanan gagal: ' . json_encode($db->error()));
                throw new \Exception('Gagal menyimpan pemesanan barang: ' . $db->error()['message']);
            }

            // Data barang yang dipesan
            $kdbarang = $this->request->getPost('kdbarang');
            $jumlah = $this->request->getPost('jumlah');
            $harga = $this->request->getPost('harga');
            $subtotal = $this->request->getPost('subtotal');

            $grandTotal = 0;

            // Pastikan ada barang yang dipesan
            if (!is_array($kdbarang) || count($kdbarang) === 0) {
                throw new \Exception('Tidak ada barang yang dipesan');
            }

            // Simpan detail pemesanan
            for ($i = 0; $i < count($kdbarang); $i++) {
                $detailData = [
                    'kdpemesananbarang' => $kdPemesananBarang,
                    'kdbarang' => $kdbarang[$i],
                    'jumlah' => $jumlah[$i],
                    'harga' => $harga[$i],
                    'subtotal' => $subtotal[$i],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                log_message('debug', 'Detail Pemesanan Data #' . ($i + 1) . ': ' . json_encode($detailData));

                // Insert menggunakan Query Builder
                $insertDetailResult = $db->table('detailpemesananbarang')->insert($detailData);

                if (!$insertDetailResult) {
                    log_message('error', 'Insert detail pemesanan gagal: ' . json_encode($db->error()));
                    throw new \Exception('Gagal menyimpan detail pemesanan barang: ' . $db->error()['message']);
                }

                // Kurangi stok barang
                $stokResult = $this->barangModel->reduceStock($kdbarang[$i], $jumlah[$i]);

                if (!$stokResult) {
                    log_message('error', 'Update stok barang gagal untuk kdbarang: ' . $kdbarang[$i]);
                    throw new \Exception('Stok barang tidak mencukupi atau terjadi kesalahan saat memperbarui stok');
                }

                $grandTotal += (float)$subtotal[$i];
            }

            // Update grand total pada pemesanan untuk memastikan konsistensi data
            $updateResult = $db->table('pemesananbarang')
                ->where('kdpemesananbarang', $kdPemesananBarang)
                ->update(['grandtotal' => $grandTotal]);

            if (!$updateResult) {
                log_message('error', 'Update grandtotal gagal: ' . json_encode($db->error()));
                throw new \Exception('Gagal memperbarui total pemesanan');
            }

            // Jika pembayaran langsung lunas
            if ($this->request->getPost('is_paid') == '1') {
                // Buat kode pembayaran
                $kdpembayaran = 'PAY-' . date('Ymd') . '-' . $nextSeq;

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
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                log_message('debug', 'Pembayaran Data: ' . json_encode($pembayaranData));

                // Insert pembayaran menggunakan Query Builder
                $insertPembayaranResult = $db->table('pembayaran')->insert($pembayaranData);

                if (!$insertPembayaranResult) {
                    log_message('error', 'Insert pembayaran gagal: ' . json_encode($db->error()));
                    throw new \Exception('Gagal menyimpan data pembayaran');
                }

                // Update kdpembayaran di pemesanan menggunakan Query Builder
                $updatePembayaranResult = $db->table('pemesananbarang')
                    ->where('kdpemesananbarang', $kdPemesananBarang)
                    ->update(['kdpembayaran' => $kdpembayaran]);

                if (!$updatePembayaranResult) {
                    log_message('error', 'Update kdpembayaran gagal: ' . json_encode($db->error()));
                    throw new \Exception('Gagal memperbarui referensi pembayaran');
                }
            }
            // Jika pembayaran DP (50%)
            else if ($this->request->getPost('is_paid') == '2') {
                // Buat kode pembayaran
                $kdpembayaran = 'PAY-' . date('Ymd') . '-' . $nextSeq;

                // Hitung jumlah DP (50% dari total)
                $dpAmount = $grandTotal * 0.5;
                $sisaPembayaran = $grandTotal - $dpAmount;

                $pembayaranData = [
                    'kdpembayaran' => $kdpembayaran,
                    'tgl' => date('Y-m-d'),
                    'metodepembayaran' => $this->request->getPost('metodepembayaran'),
                    'tipepembayaran' => 'dp',
                    'jumlahbayar' => $dpAmount,
                    'sisa' => $sisaPembayaran,
                    'totalpembayaran' => $grandTotal,
                    'status' => 'partial',
                    'dp_confirmed' => 1,
                    'dp_confirmed_at' => date('Y-m-d H:i:s'),
                    'dp_confirmed_by' => session()->get('user_id'),
                    'full_paid' => 0,
                    'full_confirmed' => 0,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                log_message('debug', 'Pembayaran DP Data: ' . json_encode($pembayaranData));

                // Insert pembayaran menggunakan Query Builder
                $insertPembayaranResult = $db->table('pembayaran')->insert($pembayaranData);

                if (!$insertPembayaranResult) {
                    log_message('error', 'Insert pembayaran DP gagal: ' . json_encode($db->error()));
                    throw new \Exception('Gagal menyimpan data pembayaran DP');
                }

                // Update kdpembayaran di pemesanan menggunakan Query Builder
                $updatePembayaranResult = $db->table('pemesananbarang')
                    ->where('kdpemesananbarang', $kdPemesananBarang)
                    ->update(['kdpembayaran' => $kdpembayaran]);

                if (!$updatePembayaranResult) {
                    log_message('error', 'Update kdpembayaran gagal: ' . json_encode($db->error()));
                    throw new \Exception('Gagal memperbarui referensi pembayaran');
                }
            }

            $db->transCommit();

            log_message('info', 'Pemesanan barang berhasil disimpan dengan ID: ' . $kdPemesananBarang);

            // Cek apakah request adalah AJAX
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Pemesanan barang berhasil ditambahkan',
                    'kdpemesanan' => $kdPemesananBarang,
                    'redirect_url' => site_url('admin/pemesananbarang/lihatFaktur/' . $kdPemesananBarang)
                ]);
            }

            // Redirect ke halaman lihat faktur setelah penyimpanan berhasil
            return redirect()->to('admin/pemesananbarang/lihatFaktur/' . $kdPemesananBarang);
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Error saat menyimpan pemesanan: ' . $e->getMessage());
            log_message('error', 'Stack trace: ' . $e->getTraceAsString());

            // Cek apakah request adalah AJAX
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan: ' . $e->getMessage()
                ]);
            }

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

        $detail = $this->detailPemesananModel->getDetailWithBarang($id);

        $data = [
            'title' => 'Detail Pemesanan Barang',
            'pemesanan' => $pemesanan,
            'detail' => $detail
        ];

        return view('admin/pemesananbarang/detail', $data);
    }

    /**
     * Menampilkan form edit pemesanan barang
     */
    public function edit($id)
    {
        // $pemesanan = $this->pemesananModel->find($id);
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($id);
        if (!$pemesanan) {
            return redirect()->to('admin/pemesananbarang')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Debug
        log_message('debug', 'DATA PEMESANAN: ' . json_encode($pemesanan));

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

    /**
     * Cetak faktur pemesanan barang dalam format PDF
     *
     * @param string $kdpemesananbarang
     * @return void
     */
    public function cetakFaktur($kdpemesananbarang)
    {
        // Ambil data pemesanan
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananbarang);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananbarang')->with('error', 'Pemesanan tidak ditemukan');
        }

        // Ambil detail pemesanan
        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($kdpemesananbarang);

        $data = [
            'pemesanan' => $pemesanan,
            'detailPemesanan' => $detailPemesanan,
            'title' => 'Faktur Pemesanan Barang',
        ];

        // Load library DOMPDF
        $dompdf = new \Dompdf\Dompdf();

        // Load view faktur
        $html = view('admin/pemesananbarang/faktur', $data);

        // Set options
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        // Load HTML content
        $dompdf->loadHtml($html);

        // Set paper size (A4)
        $dompdf->setPaper('A4', 'portrait');

        // Render PDF (generate)
        $dompdf->render();

        // Stream PDF (force download)
        $dompdf->stream("Faktur_Pemesanan_Barang_" . $kdpemesananbarang . ".pdf", [
            "Attachment" => true
        ]);
    }

    /**
     * Tampilkan faktur pemesanan barang dalam browser
     *
     * @param string $kdpemesananbarang
     * @return void
     */
    public function lihatFaktur($kdpemesananbarang)
    {
        // Ambil data pemesanan
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananbarang);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananbarang')->with('error', 'Pemesanan tidak ditemukan');
        }

        // Ambil detail pemesanan
        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($kdpemesananbarang);

        $data = [
            'pemesanan' => $pemesanan,
            'detailPemesanan' => $detailPemesanan,
            'title' => 'Faktur Pemesanan Barang',
        ];

        return view('admin/pemesananbarang/faktur', $data);
    }

    /**
     * Menampilkan halaman pengembalian barang
     */
    public function pengembalian()
    {
        $data = [
            'title' => 'Pengembalian Barang',
            'pemesanan' => $this->pemesananModel->getPemesananForReturn()
        ];

        return view('admin/pemesananbarang/pengembalian', $data);
    }

    /**
     * Menampilkan form proses pengembalian barang
     */
    public function prosesPengembalian($kdpemesananbarang)
    {
        $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananbarang);

        if (!$pemesanan) {
            return redirect()->to('/admin/pemesananbarang/pengembalian')->with('error', 'Pemesanan tidak ditemukan');
        }

        $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($kdpemesananbarang);

        $data = [
            'title' => 'Proses Pengembalian Barang',
            'pemesanan' => $pemesanan,
            'detailPemesanan' => $detailPemesanan
        ];

        return view('admin/pemesananbarang/proses_pengembalian', $data);
    }

    /**
     * Simpan data pengembalian barang
     */
    public function simpanPengembalian($kdpemesananbarang)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            $tgl_kembali = $this->request->getPost('tgl_kembali');

            // Konversi format tanggal dari d/m/Y ke Y-m-d
            if (strpos($tgl_kembali, '/') !== false) {
                $date = \DateTime::createFromFormat('d/m/Y', $tgl_kembali);
                if ($date) {
                    $tgl_kembali = $date->format('Y-m-d');
                }
            }

            $catatan_pengembalian = $this->request->getPost('catatan_pengembalian');
            $pelunasan = $this->request->getPost('pelunasan');
            $metodepembayaran = $this->request->getPost('metodepembayaran');

            // Simpan data pengembalian
            $data = [
                'tgl_kembali' => $tgl_kembali,
                'status_pengembalian' => 'baik', // Default status baik
                'catatan_pengembalian' => $catatan_pengembalian
            ];

            $this->pemesananModel->updatePengembalian($kdpemesananbarang, $data);

            // Ambil detail pemesanan untuk menambahkan stok barang kembali
            $detailPemesanan = $this->detailPemesananModel->getDetailWithBarang($kdpemesananbarang);

            // Update stok barang (tambahkan kembali)
            foreach ($detailPemesanan as $detail) {
                $stokResult = $this->barangModel->addStock($detail['kdbarang'], $detail['jumlah']);

                if (!$stokResult) {
                    throw new \Exception('Gagal menambahkan stok barang dengan ID: ' . $detail['kdbarang']);
                }

                log_message('info', 'Stok barang ' . $detail['namabarang'] . ' ditambahkan sebanyak ' .
                    $detail['jumlah'] . ' setelah pengembalian');
            }

            // Cek jika perlu proses pelunasan
            $pemesanan = $this->pemesananModel->getPemesananWithPayment($kdpemesananbarang);

            if ($pelunasan == '1' && $pemesanan['statuspembayaran'] == 'partial') {
                // Update pembayaran yang sudah ada
                $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
                    'jumlahbayar' => $pemesanan['grandtotal'],
                    'sisa' => 0,
                    'tipepembayaran' => 'lunas',
                    'status' => 'success',
                    'full_paid' => 1,
                    'full_confirmed' => 1,
                    'full_confirmed_at' => date('Y-m-d H:i:s'),
                    'full_confirmed_by' => session()->get('user_id'),
                    'metodepembayaran' => $metodepembayaran
                ]);
            }

            $db->transCommit();
            return redirect()->to('/admin/pemesananbarang/pengembalian')->with('success', 'Data pengembalian berhasil disimpan' . ($pelunasan == '1' ? ' dan pelunasan telah diproses' : ''));
        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->with('error', 'Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Ubah status pemesanan menjadi 'completed'
     * Untuk memudahkan proses pengembalian
     */
    public function completeStatus($kdpemesananbarang)
    {
        $result = $this->pemesananModel->update($kdpemesananbarang, ['status' => 'completed']);

        if ($result) {
            return redirect()->to('/admin/pemesananbarang/pengembalian')->with('success', 'Status pemesanan berhasil diubah menjadi selesai. Silakan proses pengembalian barang.');
        } else {
            return redirect()->to('/admin/pemesananbarang')->with('error', 'Gagal mengubah status pemesanan');
        }
    }
}
