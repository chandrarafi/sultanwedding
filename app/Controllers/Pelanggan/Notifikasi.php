<?php

namespace App\Controllers\Pelanggan;

use App\Controllers\BaseController;
use App\Models\PemesananPaketModel;
use App\Models\PembayaranModel;

class Notifikasi extends BaseController
{
    protected $pemesananModel;
    protected $pembayaranModel;

    public function __construct()
    {
        $this->pemesananModel = new PemesananPaketModel();
        $this->pembayaranModel = new PembayaranModel();
    }

    /**
     * Menampilkan semua notifikasi pelanggan
     */
    public function index()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return redirect()->to(site_url('auth/login'))->with('error', 'Silahkan login terlebih dahulu');
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Get pemesanan with rejected payments
        $rejectedPayments = $this->pemesananModel->select('pemesananpaket.*, paket.namapaket, pembayaran.status as statuspembayaran, pembayaran.rejected_reason, pembayaran.rejected_at, pembayaran.dp_confirmed, pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.full_paid, pembayaran.tipepembayaran')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpelanggan', $kdpelanggan)
            ->where('pembayaran.rejected_reason IS NOT NULL')
            ->where('pembayaran.rejected_at IS NOT NULL')
            ->orderBy('pembayaran.rejected_at', 'DESC')
            ->findAll();

        $notifications = [];

        foreach ($rejectedPayments as $payment) {
            $paymentStage = 'dp'; // Default to DP
            $actionRequired = '';
            $actionUrl = site_url('pelanggan/pemesanan/pembayaran/' . $payment['kdpemesananpaket']);

            // Determine payment stage and action required
            if (isset($payment['dp_confirmed']) && $payment['dp_confirmed'] == 1) {
                if (
                    isset($payment['h1_paid']) && $payment['h1_paid'] == 1 &&
                    (!isset($payment['h1_confirmed']) || $payment['h1_confirmed'] == 0)
                ) {
                    $paymentStage = 'h1';
                    $actionRequired = 'Silahkan upload ulang bukti pembayaran H-1.';
                } elseif (
                    isset($payment['h1_confirmed']) && $payment['h1_confirmed'] == 1 &&
                    (isset($payment['full_paid']) && $payment['full_paid'] === '' ||
                        (isset($payment['tipepembayaran']) && $payment['tipepembayaran'] == 'lunas' && isset($payment['full_confirmed']) && $payment['full_confirmed'] == 0))
                ) {
                    $paymentStage = 'full';
                    $actionRequired = 'Silahkan upload ulang bukti pelunasan.';
                }
            } else {
                // DP rejected means order cancelled
                $paymentStage = 'dp';
                $actionRequired = 'Pemesanan dibatalkan. Silahkan lakukan pemesanan baru.';
                $actionUrl = site_url('pelanggan/paket');
            }

            $notifications[] = [
                'id' => $payment['kdpemesananpaket'],
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran ' . ($paymentStage == 'dp' ? 'DP' : ($paymentStage == 'h1' ? 'H-1' : 'Pelunasan')) .
                    ' untuk paket ' . $payment['namapaket'] . ' ditolak. Alasan: ' . $payment['rejected_reason'],
                'action_required' => $actionRequired,
                'action_url' => $actionUrl,
                'time' => date('d M Y H:i', strtotime($payment['rejected_at'])),
                'payment_stage' => $paymentStage
            ];
        }

        $data = [
            'title' => 'Notifikasi',
            'notifications' => $notifications
        ];

        return view('pelanggan/notifikasi/index', $data);
    }

    /**
     * Check for rejected payments and return notification data
     */
    public function checkRejectedPayments()
    {
        // Cek apakah user sudah login sebagai pelanggan
        if (!session()->has('kdpelanggan')) {
            return $this->response->setContentType('application/json')
                ->setJSON([
                    'status' => false,
                    'message' => 'Sesi login tidak valid'
                ]);
        }

        $kdpelanggan = session()->get('kdpelanggan');

        // Get pemesanan with rejected payments
        $rejectedPayments = $this->pemesananModel->select('pemesananpaket.*, paket.namapaket, pembayaran.status as statuspembayaran, pembayaran.rejected_reason, pembayaran.rejected_at, pembayaran.dp_confirmed, pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.full_paid, pembayaran.tipepembayaran')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran')
            ->where('pemesananpaket.kdpelanggan', $kdpelanggan)
            ->where('pembayaran.rejected_reason IS NOT NULL')
            ->where('pembayaran.rejected_at IS NOT NULL')
            ->orderBy('pembayaran.rejected_at', 'DESC')
            ->findAll();

        $notifications = [];

        foreach ($rejectedPayments as $payment) {
            $paymentStage = 'dp'; // Default to DP
            $actionRequired = '';
            $actionUrl = site_url('pelanggan/pemesanan/pembayaran/' . $payment['kdpemesananpaket']);

            // Determine payment stage and action required
            if (isset($payment['dp_confirmed']) && $payment['dp_confirmed'] == 1) {
                if (
                    isset($payment['h1_paid']) && $payment['h1_paid'] == 1 &&
                    (!isset($payment['h1_confirmed']) || $payment['h1_confirmed'] == 0)
                ) {
                    $paymentStage = 'h1';
                    $actionRequired = 'Silahkan upload ulang bukti pembayaran H-1.';
                } elseif (
                    isset($payment['h1_confirmed']) && $payment['h1_confirmed'] == 1 &&
                    (isset($payment['full_paid']) && $payment['full_paid'] === '' ||
                        (isset($payment['tipepembayaran']) && $payment['tipepembayaran'] == 'lunas' && isset($payment['full_confirmed']) && $payment['full_confirmed'] == 0))
                ) {
                    $paymentStage = 'full';
                    $actionRequired = 'Silahkan upload ulang bukti pelunasan.';
                }
            } else {
                // DP rejected means order cancelled
                $paymentStage = 'dp';
                $actionRequired = 'Pemesanan dibatalkan. Silahkan lakukan pemesanan baru.';
                $actionUrl = site_url('pelanggan/paket');
            }

            $notifications[] = [
                'id' => $payment['kdpemesananpaket'],
                'title' => 'Pembayaran Ditolak',
                'message' => 'Pembayaran ' . ($paymentStage == 'dp' ? 'DP' : ($paymentStage == 'h1' ? 'H-1' : 'Pelunasan')) .
                    ' untuk paket ' . $payment['namapaket'] . ' ditolak. Alasan: ' . $payment['rejected_reason'],
                'action_required' => $actionRequired,
                'action_url' => $actionUrl,
                'time' => date('d M Y H:i', strtotime($payment['rejected_at'])),
                'payment_stage' => $paymentStage,
                'is_read' => false // Default to unread
            ];
        }

        return $this->response->setContentType('application/json')
            ->setJSON([
                'status' => true,
                'count' => count($notifications),
                'notifications' => $notifications
            ]);
    }
}
