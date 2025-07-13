<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'kdpembayaran';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdpembayaran',
        'tgl',
        'metodepembayaran',
        'tipepembayaran',
        'jumlahbayar',
        'sisa',
        'totalpembayaran',
        'buktipembayaran',
        'status',
        'dp_confirmed',
        'dp_confirmed_at',
        'dp_confirmed_by',
        'h1_paid',
        'h1_confirmed',
        'h1_confirmed_at',
        'h1_confirmed_by',
        'full_paid',
        'full_confirmed',
        'full_confirmed_at',
        'full_confirmed_by',
        'rejected_reason',
        'rejected_at',
        'rejected_by',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique payment code
     * Format: INV-YYYYMMDD-XXXX (XXXX is sequential number)
     * 
     * @return string
     */
    public function generatePaymentCode()
    {
        $prefix = 'INV-' . date('Ymd') . '-';

        // Get the last payment code with the same prefix
        $lastPayment = $this->like('kdpembayaran', $prefix, 'after')
            ->orderBy('kdpembayaran', 'DESC')
            ->first();

        if ($lastPayment) {
            // Extract the sequential number and increment
            $lastNumber = substr($lastPayment['kdpembayaran'], -4);
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First payment of the day
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Create new payment for booking (DP 10%)
     *
     * @param float $grandTotal
     * @param string $metodepembayaran
     * @return string|false Payment code if successful, false otherwise
     */
    public function createBookingPayment($grandTotal, $metodepembayaran = 'transfer')
    {
        // Calculate DP amount (10% of grand total)
        $dpAmount = $grandTotal * 0.1;

        // Remaining payment (90%)
        $sisa = $grandTotal - $dpAmount;

        // Generate payment code
        $kdpembayaran = $this->generatePaymentCode();

        $data = [
            'kdpembayaran' => $kdpembayaran,
            'tgl' => date('Y-m-d'),
            'metodepembayaran' => $metodepembayaran,
            'tipepembayaran' => 'dp',
            'jumlahbayar' => $dpAmount,
            'totalpembayaran' => $dpAmount,
            'sisa' => $sisa,
            'status' => 'pending'
        ];

        if ($this->insert($data)) {
            return $kdpembayaran;
        }

        return false;
    }

    /**
     * Process payment for H-1 (additional 10%)
     *
     * @param string $kdpembayaran
     * @param string $metodepembayaran
     * @param string|null $buktipembayaran
     * @return bool
     */
    public function processH1Payment($kdpembayaran, $metodepembayaran = 'transfer', $buktipembayaran = null)
    {
        $payment = $this->find($kdpembayaran);

        if (!$payment) {
            return false;
        }

        // Calculate total paid so far
        $originalTotal = $payment['totalpembayaran'] + $payment['sisa'];

        // Additional 10%
        $additionalPayment = $originalTotal * 0.1;

        // New total paid
        $totalPaid = $payment['totalpembayaran'] + $additionalPayment;

        // New remaining
        $newSisa = $originalTotal - $totalPaid;

        $data = [
            'tgl' => date('Y-m-d'),
            'metodepembayaran' => $metodepembayaran,
            'tipepembayaran' => 'dp2',
            'jumlahbayar' => $additionalPayment,
            'totalpembayaran' => $totalPaid,
            'sisa' => $newSisa,
            'status' => 'partial', // Keep status as partial to maintain payment progress
            'h1_paid' => 1 // Tandai bahwa H1 telah dibayar
        ];

        // Add bukti pembayaran if provided
        if ($buktipembayaran) {
            $data['buktipembayaran'] = $buktipembayaran;
        }

        return $this->update($kdpembayaran, $data);
    }

    /**
     * Process final payment
     *
     * @param string $kdpembayaran
     * @param string $metodepembayaran
     * @param string|null $buktipembayaran
     * @return bool
     */
    public function processFullPayment($kdpembayaran, $metodepembayaran = 'transfer', $buktipembayaran = null)
    {
        $payment = $this->find($kdpembayaran);

        if (!$payment) {
            return false;
        }

        // Total amount
        $totalAmount = $payment['totalpembayaran'] + $payment['sisa'];
        $finalPayment = $payment['sisa'];

        $data = [
            'tgl' => date('Y-m-d'),
            'metodepembayaran' => $metodepembayaran,
            'tipepembayaran' => 'lunas',
            'jumlahbayar' => $finalPayment,
            'totalpembayaran' => $totalAmount,
            'sisa' => 0,
            'status' => 'partial', // Keep status as partial to maintain payment progress
            'full_paid' => '1' // Mark that full payment has been made - use string since it's a CHAR field
        ];

        // Add bukti pembayaran if provided
        if ($buktipembayaran) {
            $data['buktipembayaran'] = $buktipembayaran;
        }

        return $this->update($kdpembayaran, $data);
    }

    /**
     * Mendapatkan semua pembayaran dengan status pending
     */
    public function getPendingPayments()
    {
        return $this->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pemesananpaket.tgl as tglacara, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->where('pembayaran.status', 'pending')
            ->where('pembayaran.buktipembayaran IS NOT NULL')
            ->where('pembayaran.dp_confirmed', 0)
            ->orderBy('pembayaran.created_at', 'DESC')
            ->findAll();
    }

    /**
     * Mendapatkan semua pembayaran H-1 yang menunggu konfirmasi
     */
    public function getPendingH1Payments()
    {
        return $this->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pemesananpaket.tgl as tglacara, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->where('pembayaran.status', 'partial')
            ->where('pembayaran.h1_paid', 1)
            ->where('pembayaran.h1_confirmed', 0)
            ->orderBy('pembayaran.updated_at', 'DESC')
            ->findAll();
    }

    /**
     * Mendapatkan semua pembayaran pelunasan yang menunggu konfirmasi
     */
    public function getPendingFullPayments()
    {
        return $this->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pemesananpaket.tgl as tglacara, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket')
            ->where('pembayaran.tipepembayaran', 'lunas')
            ->where('pembayaran.full_confirmed', 0)
            ->orderBy('pembayaran.updated_at', 'DESC')
            ->findAll();
    }
}
