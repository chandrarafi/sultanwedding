<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananPaketModel extends Model
{
    protected $table            = 'pemesananpaket';
    protected $primaryKey       = 'kdpemesananpaket';
    protected $useAutoIncrement = false;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdpemesananpaket',
        'tgl',
        'kdpelanggan',
        'kdpaket',
        'hargapaket',
        'alamatpesanan',
        'jumlahhari',
        'luaslokasi',
        'grandtotal',
        'status',
        'kdpembayaran',
        'metodepembayaran',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Generate unique booking code
     * Format: BK-YYYYMMDD-XXXX (XXXX is sequential number)
     * 
     * @return string
     */
    public function generateBookingCode()
    {
        $prefix = 'BK-' . date('Ymd') . '-';

        // Get the last booking code with the same prefix
        $lastBooking = $this->like('kdpemesananpaket', $prefix, 'after')
            ->orderBy('kdpemesananpaket', 'DESC')
            ->first();

        if ($lastBooking) {
            // Extract the sequential number and increment
            $lastNumber = substr($lastBooking['kdpemesananpaket'], -4);
            $nextNumber = str_pad((int)$lastNumber + 1, 4, '0', STR_PAD_LEFT);
        } else {
            // First booking of the day
            $nextNumber = '0001';
        }

        return $prefix . $nextNumber;
    }

    /**
     * Get pemesanan paket with related data
     *
     * @param string|null $id
     * @return array
     */
    public function getPemesananPaket($id = null)
    {
        $this->select('pemesananpaket.*, paket.namapaket, paket.foto as fotopaket, pelanggan.namapelanggan, 
            pembayaran.status as statuspembayaran, pembayaran.totalpembayaran, pembayaran.sisa, 
            pembayaran.metodepembayaran, pembayaran.tipepembayaran, pembayaran.buktipembayaran, 
            pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.dp_confirmed, 
            pembayaran.full_paid, pembayaran.full_confirmed, pembayaran.rejected_reason, pembayaran.rejected_at');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left');

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
        $this->select('pemesananpaket.*, paket.namapaket, paket.foto as fotopaket');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket');
        $this->where('pemesananpaket.kdpelanggan', $kdpelanggan);
        $this->orderBy('pemesananpaket.created_at', 'DESC');

        return $this->findAll();
    }

    /**
     * Get pemesanan with payment information
     *
     * @param string $kdpemesanan
     * @return array|null
     */
    public function getPemesananWithPayment($kdpemesanan)
    {
        $this->select('pemesananpaket.*, paket.namapaket, paket.foto as fotopaket, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.metodepembayaran, pembayaran.tipepembayaran, pembayaran.buktipembayaran, pembayaran.h1_paid, pembayaran.h1_confirmed, pembayaran.dp_confirmed, pembayaran.full_paid, pembayaran.full_confirmed, pembayaran.dp_confirmed_at, pembayaran.h1_confirmed_at, pembayaran.full_confirmed_at, pembayaran.rejected_reason, pembayaran.rejected_at');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left');
        $this->where('pemesananpaket.kdpemesananpaket', $kdpemesanan);

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
