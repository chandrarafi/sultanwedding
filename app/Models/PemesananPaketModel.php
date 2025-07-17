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
            if (!isset($result['full_paid'])) $result['full_paid'] = ''; // Use empty string for CHAR field
            if (!isset($result['full_confirmed'])) $result['full_confirmed'] = 0;
        }

        return $result;
    }

    /**
     * Get data pemesanan paket untuk laporan
     * 
     * @param string $tanggal_awal Tanggal awal filter (format: Y-m-d)
     * @param string $tanggal_akhir Tanggal akhir filter (format: Y-m-d)
     * @param string $status Status pemesanan (optional)
     * @return array
     */
    public function getLaporanPemesananPaket($tanggal_awal, $tanggal_akhir, $status = '')
    {
        $this->select('pemesananpaket.*, pelanggan.namapelanggan, paket.namapaket, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left');

        // Filter berdasarkan tanggal pembuatan (created_at)
        $this->where('DATE(pemesananpaket.created_at) >=', $tanggal_awal);
        $this->where('DATE(pemesananpaket.created_at) <=', $tanggal_akhir);

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            if ($status == 'pending') {
                $this->where('pembayaran.status', 'pending');
            } else if ($status == 'partial') {
                $this->where('pembayaran.status', 'partial');
            } else if ($status == 'success') {
                $this->where('pembayaran.status', 'success');
            } else if ($status == 'completed') {
                $this->where('pemesananpaket.status', 'completed');
            }
        }

        $this->orderBy('pemesananpaket.created_at', 'DESC');

        return $this->findAll();
    }

    /**
     * Get data pemesanan paket untuk laporan bulanan
     * 
     * @param int $bulan Bulan (1-12)
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pemesanan paket per bulan dan statistik pendapatan
     */
    public function getLaporanBulanan($bulan, $tahun)
    {
        $db = \Config\Database::connect();

        // Query untuk mendapatkan total dan statistik langsung dari database
        $queryStats = $db->query("
            SELECT 
                COUNT(*) as total_pemesanan,
                SUM(CASE WHEN pembayaran.status = 'success' THEN pemesananpaket.grandtotal
                     WHEN pembayaran.status = 'partial' THEN IFNULL(pembayaran.totalpembayaran, 0)
                     ELSE 0 END) as total_pendapatan,
                SUM(CASE WHEN pembayaran.status = 'pending' THEN 1 ELSE 0 END) as status_pending,
                SUM(CASE WHEN pembayaran.status = 'partial' THEN 1 ELSE 0 END) as status_partial,
                SUM(CASE WHEN pembayaran.status = 'success' THEN 1 ELSE 0 END) as status_success,
                SUM(CASE WHEN pemesananpaket.status = 'completed' THEN 1 ELSE 0 END) as status_completed
            FROM pemesananpaket
            LEFT JOIN pelanggan ON pelanggan.kdpelanggan = pemesananpaket.kdpelanggan
            LEFT JOIN paket ON paket.kdpaket = pemesananpaket.kdpaket
            LEFT JOIN pembayaran ON pembayaran.kdpembayaran = pemesananpaket.kdpembayaran
            WHERE MONTH(pemesananpaket.created_at) = ? 
            AND YEAR(pemesananpaket.created_at) = ?
        ", [$bulan, $tahun]);

        $stats = $queryStats->getRowArray();

        // Query untuk mendapatkan statistik harian langsung dari database
        $queryHarian = $db->query("
            SELECT 
                DAY(pemesananpaket.created_at) as hari,
                COUNT(*) as jumlah_pemesanan,
                SUM(CASE WHEN pembayaran.status = 'success' THEN pemesananpaket.grandtotal
                     WHEN pembayaran.status = 'partial' THEN IFNULL(pembayaran.totalpembayaran, 0)
                     ELSE 0 END) as pendapatan
            FROM pemesananpaket
            LEFT JOIN pembayaran ON pembayaran.kdpembayaran = pemesananpaket.kdpembayaran
            WHERE MONTH(pemesananpaket.created_at) = ? 
            AND YEAR(pemesananpaket.created_at) = ?
            GROUP BY DAY(pemesananpaket.created_at)
        ", [$bulan, $tahun]);

        $harianData = $queryHarian->getResultArray();

        // Inisialisasi array dengan 0 untuk 31 hari
        $harian = array_fill(1, 31, 0);
        $pendapatanHarian = array_fill(1, 31, 0);

        // Isi array dengan data dari query
        foreach ($harianData as $item) {
            $hari = (int)$item['hari'];
            $harian[$hari] = (int)$item['jumlah_pemesanan'];
            $pendapatanHarian[$hari] = (float)$item['pendapatan'];
        }

        // Ambil data pemesanan paket bulan ini dengan LIMIT untuk mengurangi beban memori
        $this->select('pemesananpaket.*, pelanggan.namapelanggan, paket.namapaket, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left');
        $this->where('MONTH(pemesananpaket.created_at)', $bulan);
        $this->where('YEAR(pemesananpaket.created_at)', $tahun);
        $this->orderBy('pemesananpaket.created_at', 'ASC');

        $data = $this->findAll();

        return [
            'data' => $data,
            'total_pemesanan' => (int)$stats['total_pemesanan'],
            'total_pendapatan' => (float)$stats['total_pendapatan'],
            'status_count' => [
                'pending' => (int)$stats['status_pending'],
                'partial' => (int)$stats['status_partial'],
                'success' => (int)$stats['status_success'],
                'completed' => (int)$stats['status_completed'],
            ],
            'pemesanan_harian' => $harian,
            'pendapatan_harian' => $pendapatanHarian,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    /**
     * Get data pemesanan paket untuk laporan tahunan
     * 
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pemesanan paket per tahun dan statistik pendapatan
     */
    public function getLaporanTahunan($tahun)
    {
        $db = \Config\Database::connect();

        // Query untuk mendapatkan total dan statistik langsung dari database
        $queryStats = $db->query("
            SELECT 
                COUNT(*) as total_pemesanan,
                SUM(CASE WHEN pembayaran.status = 'success' THEN pemesananpaket.grandtotal
                     WHEN pembayaran.status = 'partial' THEN IFNULL(pembayaran.totalpembayaran, 0)
                     ELSE 0 END) as total_pendapatan,
                SUM(CASE WHEN pembayaran.status = 'pending' THEN 1 ELSE 0 END) as status_pending,
                SUM(CASE WHEN pembayaran.status = 'partial' THEN 1 ELSE 0 END) as status_partial,
                SUM(CASE WHEN pembayaran.status = 'success' THEN 1 ELSE 0 END) as status_success,
                SUM(CASE WHEN pemesananpaket.status = 'completed' THEN 1 ELSE 0 END) as status_completed
            FROM pemesananpaket
            LEFT JOIN pelanggan ON pelanggan.kdpelanggan = pemesananpaket.kdpelanggan
            LEFT JOIN paket ON paket.kdpaket = pemesananpaket.kdpaket
            LEFT JOIN pembayaran ON pembayaran.kdpembayaran = pemesananpaket.kdpembayaran
            WHERE YEAR(pemesananpaket.created_at) = ?
        ", [$tahun]);

        $stats = $queryStats->getRowArray();

        // Query untuk mendapatkan statistik bulanan langsung dari database
        $queryBulanan = $db->query("
            SELECT 
                MONTH(pemesananpaket.created_at) as bulan,
                COUNT(*) as jumlah_pemesanan,
                SUM(CASE WHEN pembayaran.status = 'success' THEN pemesananpaket.grandtotal
                     WHEN pembayaran.status = 'partial' THEN IFNULL(pembayaran.totalpembayaran, 0)
                     ELSE 0 END) as pendapatan
            FROM pemesananpaket
            LEFT JOIN pembayaran ON pembayaran.kdpembayaran = pemesananpaket.kdpembayaran
            WHERE YEAR(pemesananpaket.created_at) = ?
            GROUP BY MONTH(pemesananpaket.created_at)
        ", [$tahun]);

        $bulananData = $queryBulanan->getResultArray();

        // Inisialisasi array dengan 0 untuk 12 bulan
        $bulanan = array_fill(1, 12, 0);
        $pendapatanBulanan = array_fill(1, 12, 0);

        // Isi array dengan data dari query
        foreach ($bulananData as $item) {
            $bulan = (int)$item['bulan'];
            $bulanan[$bulan] = (int)$item['jumlah_pemesanan'];
            $pendapatanBulanan[$bulan] = (float)$item['pendapatan'];
        }

        // Ambil data pemesanan paket tahun ini dengan LIMIT untuk mengurangi beban memori
        $this->select('pemesananpaket.*, pelanggan.namapelanggan, paket.namapaket, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left');
        $this->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananpaket.kdpembayaran', 'left');
        $this->where('YEAR(pemesananpaket.created_at)', $tahun);
        $this->orderBy('pemesananpaket.created_at', 'ASC');

        $data = $this->findAll();

        return [
            'data' => $data,
            'total_pemesanan' => (int)$stats['total_pemesanan'],
            'total_pendapatan' => (float)$stats['total_pendapatan'],
            'status_count' => [
                'pending' => (int)$stats['status_pending'],
                'partial' => (int)$stats['status_partial'],
                'success' => (int)$stats['status_success'],
                'completed' => (int)$stats['status_completed'],
            ],
            'pemesanan_bulanan' => $bulanan,
            'pendapatan_bulanan' => $pendapatanBulanan,
            'tahun' => $tahun
        ];
    }
}
