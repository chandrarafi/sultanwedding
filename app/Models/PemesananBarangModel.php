<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananBarangModel extends Model
{
    protected $table            = 'pemesananbarang';
    protected $primaryKey       = 'kdpemesananbarang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'tgl',
        'kdpelanggan',
        'alamatpesanan',
        'lamapemesanan',
        'grandtotal',
        'kdpembayaran',
        'status',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique booking code for barang
     * Format: BR-YYYYMMDD-XXXX (XXXX is sequential number)
     * 
     * @return string
     */
    public function generateBookingCode()
    {
        $prefix = 'BR-' . date('Ymd') . '-';

        // Get the last booking code with the same prefix
        $lastBooking = $this->like('kdpemesananbarang', $prefix, 'after')
            ->orderBy('kdpemesananbarang', 'DESC')
            ->first();

        if ($lastBooking) {
            // Extract the sequential number and increment
            $lastNumber = substr($lastBooking['kdpemesananbarang'], -4);
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First booking of the day
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Get pemesanan barang with related data
     *
     * @param int|null $id
     * @return array
     */
    public function getPemesananBarang($id = null)
    {
        $this->select('pemesananbarang.*, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran, pembayaran.buktipembayaran, pembayaran.dp_confirmed, 
            pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.full_paid, pembayaran.full_confirmed');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananbarang.kdpembayaran', 'left');

        if ($id !== null) {
            return $this->find($id);
        }

        return $this->findAll();
    }

    /**
     * Get pemesanan by pelanggan
     *
     * @param int $kdpelanggan
     * @return array
     */
    public function getByPelanggan($kdpelanggan)
    {
        $this->select('pemesananbarang.*');
        $this->where('pemesananbarang.kdpelanggan', $kdpelanggan);
        $this->orderBy('pemesananbarang.created_at', 'DESC');

        return $this->findAll();
    }

    /**
     * Get pemesanan with payment information
     *
     * @param int $kdpemesanan
     * @return array|null
     */
    public function getPemesananWithPayment($kdpemesanan)
    {
        $this->select('pemesananbarang.*, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran, pembayaran.buktipembayaran, pembayaran.dp_confirmed, 
            pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.full_paid, pembayaran.full_confirmed, 
            pembayaran.dp_confirmed_at, pembayaran.h1_confirmed_at, pembayaran.full_confirmed_at, 
            pembayaran.rejected_reason, pembayaran.rejected_at');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananbarang.kdpembayaran', 'left');
        $this->where('pemesananbarang.kdpemesananbarang', $kdpemesanan);

        $result = $this->first();

        // Add default values for missing fields
        if ($result) {
            // Add confirmation fields if they don't exist
            if (!isset($result['dp_confirmed'])) $result['dp_confirmed'] = 0;
            if (!isset($result['h1_paid'])) $result['h1_paid'] = 0;
            if (!isset($result['h1_confirmed'])) $result['h1_confirmed'] = 0;
            if (!isset($result['full_paid'])) $result['full_paid'] = 0;
            if (!isset($result['full_confirmed'])) $result['full_confirmed'] = 0;
        }

        return $result;
    }
}
