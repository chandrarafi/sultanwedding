<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\PembayaranModel;
use App\Models\PemesananPaketModel;
use App\Models\PelangganModel;
use App\Models\PaketModel;

class Pembayaran extends BaseController
{
    protected $pembayaranModel;
    protected $pemesananPaketModel;
    protected $pelangganModel;
    protected $paketModel;

    public function __construct()
    {
        $this->pembayaranModel = new PembayaranModel();
        $this->pemesananPaketModel = new PemesananPaketModel();
        $this->pelangganModel = new PelangganModel();
        $this->paketModel = new PaketModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Pembayaran',
            'pembayaran_pending' => $this->pembayaranModel->getPendingPayments(),
            'pembayaran_h1_pending' => $this->pembayaranModel->getPendingH1Payments(),
            'pembayaran_full_pending' => $this->pembayaranModel->getPendingFullPayments()
        ];

        return view('admin/pembayaran/index', $data);
    }

    public function detail($kdpemesananpaket)
    {
        // Mendapatkan data pemesanan beserta data pembayaran
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pelanggan.namapelanggan, paket.namapaket, paket.harga, pembayaran.*')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        $data = [
            'title' => 'Detail Pembayaran',
            'pemesanan' => $pemesanan
        ];

        return view('admin/pembayaran/detail', $data);
    }

    /**
     * Konfirmasi pembayaran DP
     */
    public function konfirmasiDP($kdpemesananpaket)
    {
        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Update pembayaran
        $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
            'status' => 'partial',
            'dp_confirmed' => 1,
            'dp_confirmed_at' => date('Y-m-d H:i:s'),
            'dp_confirmed_by' => session()->get('id') // ID admin yang login
        ]);

        // Update status pemesanan
        $this->pemesananPaketModel->update($kdpemesananpaket, [
            'statuspembayaran' => 'partial',
            'status' => 'process'
        ]);

        return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)->with('success', 'Pembayaran DP berhasil dikonfirmasi');
    }

    /**
     * Konfirmasi pembayaran H-1
     */
    public function konfirmasiH1($kdpemesananpaket)
    {
        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Update pembayaran
        $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
            'status' => 'partial',
            'h1_paid' => 1,
            'h1_confirmed' => 1,
            'h1_confirmed_at' => date('Y-m-d H:i:s'),
            'h1_confirmed_by' => session()->get('id') // ID admin yang login
        ]);

        return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)->with('success', 'Pembayaran H-1 berhasil dikonfirmasi');
    }

    /**
     * Konfirmasi pelunasan
     */
    public function konfirmasiPelunasan($kdpemesananpaket)
    {
        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Update pembayaran
        $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
            'status' => 'success',
            'full_confirmed' => 1,
            'full_confirmed_at' => date('Y-m-d H:i:s'),
            'full_confirmed_by' => session()->get('id') // ID admin yang login
        ]);

        // Update status pemesanan
        $this->pemesananPaketModel->update($kdpemesananpaket, [
            'statuspembayaran' => 'success',
            'status' => 'completed'
        ]);

        return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)->with('success', 'Pelunasan berhasil dikonfirmasi');
    }

    /**
     * Tolak pembayaran
     */
    public function tolak($kdpemesananpaket)
    {
        $alasan = $this->request->getPost('alasan');

        if (empty($alasan)) {
            return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)->with('error', 'Alasan penolakan harus diisi');
        }

        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Debug log
        log_message('debug', 'Tolak pembayaran untuk kdpemesananpaket: ' . $kdpemesananpaket);
        log_message('debug', 'Data pemesanan: ' . json_encode($pemesanan));

        // Use direct database approach to avoid field validation issues
        $db = \Config\Database::connect();

        // Get the current database record to check what fields exist
        $query = $db->query("SELECT * FROM pembayaran WHERE kdpembayaran = ?", [$pemesanan['kdpembayaran']]);
        $record = $query->getRowArray();

        if (!$record) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pembayaran tidak ditemukan');
        }

        // Basic rejection data that should work for all cases
        $updateData = [
            'status' => 'partial',  // This field should definitely exist
            'rejected_reason' => $alasan,
            'rejected_at' => date('Y-m-d H:i:s'),
            'rejected_by' => session()->get('id')
        ];

        // Determine payment stage
        $paymentStage = 'dp'; // Default to DP

        if (isset($pemesanan['dp_confirmed']) && $pemesanan['dp_confirmed'] == 1) {
            if (
                isset($pemesanan['h1_paid']) && $pemesanan['h1_paid'] == 1 &&
                (!isset($pemesanan['h1_confirmed']) || $pemesanan['h1_confirmed'] == 0)
            ) {
                $paymentStage = 'h1';
                // Only add h1_paid if it exists in the database
                if (array_key_exists('h1_paid', $record)) {
                    $updateData['h1_paid'] = 0;
                }
            } elseif (
                (isset($pemesanan['tipepembayaran']) && $pemesanan['tipepembayaran'] == 'lunas') ||
                (isset($pemesanan['full_paid']) && !empty($pemesanan['full_paid']))
            ) {
                $paymentStage = 'full';
                // Untuk pelunasan, kita perlu reset full_paid
                // Perhatikan tipe data full_paid adalah char(20), bukan tinyint
                if (array_key_exists('full_paid', $record)) {
                    $updateData['full_paid'] = ''; // Set to empty string instead of null
                }
            }
        }

        log_message('debug', 'Payment stage determined: ' . $paymentStage);
        log_message('debug', 'Final update data: ' . json_encode($updateData));

        // Handle different rejection behaviors based on payment stage
        try {
            // Update the payment record
            $result = $db->table('pembayaran')
                ->where('kdpembayaran', $pemesanan['kdpembayaran'])
                ->update($updateData);

            log_message('debug', 'Payment update result: ' . ($result ? 'success' : 'failed'));

            // Double-check that the update was successful
            $query = $db->query("SELECT rejected_reason, rejected_at FROM pembayaran WHERE kdpembayaran = ?", [$pemesanan['kdpembayaran']]);
            $updatedRecord = $query->getRowArray();
            log_message('debug', 'Updated record check: ' . json_encode($updatedRecord));

            // Update pemesanan status based on payment stage
            switch ($paymentStage) {
                case 'dp':
                    // If DP is rejected, cancel the order
                    $this->pemesananPaketModel->update($kdpemesananpaket, [
                        'statuspembayaran' => 'rejected',
                        'status' => 'cancelled'
                    ]);
                    $message = 'Pembayaran DP ditolak dan pesanan dibatalkan.';
                    break;

                case 'h1':
                    // Keep pemesanan status as partial for h1 rejection
                    $this->pemesananPaketModel->update($kdpemesananpaket, [
                        'statuspembayaran' => 'partial'
                    ]);
                    $message = 'Pembayaran H-1 ditolak. Pelanggan diminta untuk mengunggah bukti pembayaran kembali.';
                    break;

                case 'full':
                    // Keep pemesanan status as partial for full payment rejection
                    $this->pemesananPaketModel->update($kdpemesananpaket, [
                        'statuspembayaran' => 'partial'
                    ]);
                    $message = 'Pelunasan ditolak. Pelanggan diminta untuk mengunggah bukti pembayaran kembali.';
                    break;
            }

            return redirect()->to('/admin/pembayaran')->with('success', $message);
        } catch (\Exception $e) {
            log_message('error', 'Error in payment rejection: ' . $e->getMessage());
            return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)
                ->with('error', 'Gagal menolak pembayaran: ' . $e->getMessage());
        }
    }

    /**
     * Process walk-in payment for H-1
     */
    public function bayarH1($kdpemesananpaket)
    {
        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Verify that DP has been confirmed
        if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] != 1) {
            return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)
                ->with('error', 'Pembayaran DP harus dikonfirmasi terlebih dahulu');
        }

        // Calculate total amount
        $originalTotal = $pemesanan['grandtotal'];

        // Additional 10%
        $additionalPayment = $originalTotal * 0.1;

        // New total paid (DP 10% + H-1 10%)
        $totalPaid = $originalTotal * 0.2;

        // New remaining
        $newSisa = $originalTotal - $totalPaid;

        // Update pembayaran
        $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
            'tgl' => date('Y-m-d'),
            'tipepembayaran' => 'dp2',
            'jumlahbayar' => $additionalPayment,
            'totalpembayaran' => $totalPaid,
            'sisa' => $newSisa,
            'status' => 'partial',
            'h1_paid' => 1,
            'h1_confirmed' => 1,
            'h1_confirmed_at' => date('Y-m-d H:i:s'),
            'h1_confirmed_by' => session()->get('id')
        ]);

        return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)
            ->with('success', 'Pembayaran H-1 berhasil diproses');
    }

    /**
     * Process walk-in payment for full payment (pelunasan)
     */
    public function bayarPelunasan($kdpemesananpaket)
    {
        // Get pemesanan data
        $pemesanan = $this->pemesananPaketModel->select('pemesananpaket.*, pembayaran.*')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpemesananpaket', $kdpemesananpaket)
            ->first();

        if (!$pemesanan) {
            return redirect()->to('/admin/pembayaran')->with('error', 'Data pemesanan tidak ditemukan');
        }

        // Verify that DP has been confirmed
        if (!isset($pemesanan['dp_confirmed']) || $pemesanan['dp_confirmed'] != 1) {
            return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)
                ->with('error', 'Pembayaran DP harus dikonfirmasi terlebih dahulu');
        }

        // Calculate remaining amount
        $sisa = $pemesanan['sisa'];

        // Update pembayaran
        $this->pembayaranModel->update($pemesanan['kdpembayaran'], [
            'tgl' => date('Y-m-d'),
            'tipepembayaran' => 'lunas',
            'jumlahbayar' => $sisa,
            'totalpembayaran' => $pemesanan['grandtotal'],
            'sisa' => 0,
            'status' => 'success',
            'full_paid' => '1',
            'full_confirmed' => 1,
            'full_confirmed_at' => date('Y-m-d H:i:s'),
            'full_confirmed_by' => session()->get('id')
        ]);

        // Update status pemesanan
        $this->pemesananPaketModel->update($kdpemesananpaket, [
            'statuspembayaran' => 'success',
            'status' => 'completed'
        ]);

        return redirect()->to('/admin/pembayaran/detail/' . $kdpemesananpaket)
            ->with('success', 'Pelunasan berhasil diproses');
    }
}
