<?php

namespace App\Models;

use CodeIgniter\Model;

class PemesananBarangModel extends Model
{
    protected $table            = 'pemesananbarang';
    protected $primaryKey       = 'kdpemesananbarang';
    protected $useAutoIncrement = false; // Set to false karena kita menggunakan custom ID
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdpemesananbarang',
        'tgl',
        'kdpelanggan',
        'alamatpesanan',
        'lamapemesanan',
        'grandtotal',
        'kdpembayaran',
        'status',
        'tgl_kembali',
        'status_pengembalian',
        'catatan_pengembalian',
        'created_at',
        'updated_at',
        'nopesanan', // Tambahkan field nopesanan
        'tanggalpemesanan',
        'total',
        'tanggalmulai',
        'tanggalselesai',
        'keterangan',
        'metodepembayaran',
        'jenispembayaran',
        'statuspembayaran'
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
            pembayaran.full_paid, pembayaran.full_confirmed');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
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
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran, pembayaran.buktipembayaran, pembayaran.dp_confirmed, 
            pembayaran.dp_confirmed_at, pembayaran.full_paid, pembayaran.full_confirmed, 
            pembayaran.full_confirmed_at, pembayaran.rejected_reason, pembayaran.rejected_at');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
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

    /**
     * Get pemesanan yang siap untuk dikembalikan
     * (status = completed dan belum ada status pengembalian)
     * 
     * @return array
     */
    public function getPemesananForReturn()
    {
        $this->select('pemesananbarang.*, pelanggan.namapelanggan');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
        $this->where('pemesananbarang.status', 'completed');
        $this->where('pemesananbarang.status_pengembalian IS NULL OR pemesananbarang.status_pengembalian = ""');
        $this->orderBy('pemesananbarang.tgl', 'ASC');

        return $this->findAll();
    }

    /**
     * Update status pengembalian barang
     * 
     * @param string $kdpemesananbarang
     * @param array $data
     * @return bool
     */
    public function updatePengembalian($kdpemesananbarang, $data)
    {
        return $this->update($kdpemesananbarang, [
            'tgl_kembali' => $data['tgl_kembali'],
            'status_pengembalian' => $data['status_pengembalian'],
            'catatan_pengembalian' => $data['catatan_pengembalian'] ?? null,
        ]);
    }

    /**
     * Get data pemesanan barang untuk laporan
     * 
     * @param string $tanggal_awal Tanggal awal filter (format: Y-m-d)
     * @param string $tanggal_akhir Tanggal akhir filter (format: Y-m-d)
     * @param string $status Status pemesanan (optional)
     * @return array
     */
    public function getLaporanPemesananBarang($tanggal_awal, $tanggal_akhir, $status = '')
    {
        $this->select('pemesananbarang.*, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananbarang.kdpembayaran', 'left');

        // Filter berdasarkan tanggal
        $this->where('DATE(pemesananbarang.tgl) >=', $tanggal_awal);
        $this->where('DATE(pemesananbarang.tgl) <=', $tanggal_akhir);

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            if ($status == 'pending') {
                $this->where('pembayaran.status', 'pending');
            } else if ($status == 'partial') {
                $this->where('pembayaran.status', 'partial');
            } else if ($status == 'success') {
                $this->where('pembayaran.status', 'success');
            } else if ($status == 'completed') {
                $this->where('pemesananbarang.status', 'completed');
            } else if ($status == 'returned') {
                $this->where('pemesananbarang.status_pengembalian IS NOT NULL');
            }
        }

        $this->orderBy('pemesananbarang.tgl', 'DESC');

        return $this->findAll();
    }

    /**
     * Get data pemesanan barang untuk laporan bulanan
     * 
     * @param int $bulan Bulan (1-12)
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pemesanan barang per bulan dan statistik pendapatan
     */
    public function getLaporanBulanan($bulan, $tahun)
    {
        $db = \Config\Database::connect();

        // Ambil data pemesanan barang bulan ini
        $this->select('pemesananbarang.*, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran, DATE(pemesananbarang.tgl) as tanggal_pemesanan, 
            DAY(pemesananbarang.tgl) as hari');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananbarang.kdpembayaran', 'left');

        // Filter berdasarkan bulan dan tahun
        $this->where('MONTH(pemesananbarang.tgl)', $bulan);
        $this->where('YEAR(pemesananbarang.tgl)', $tahun);

        $this->orderBy('pemesananbarang.tgl', 'ASC');

        $data = $this->findAll();

        // Hitung statistik
        $totalPendapatan = 0;
        $totalPemesanan = count($data);
        $statusCount = [
            'pending' => 0,
            'partial' => 0,
            'success' => 0,
            'completed' => 0,
            'returned' => 0
        ];

        foreach ($data as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }

            // Hitung jumlah per status
            if (isset($item['statuspembayaran'])) {
                if ($item['statuspembayaran'] == 'pending') {
                    $statusCount['pending']++;
                } else if ($item['statuspembayaran'] == 'partial') {
                    $statusCount['partial']++;
                } else if ($item['statuspembayaran'] == 'success') {
                    $statusCount['success']++;
                }
            }

            if (isset($item['status']) && $item['status'] == 'completed') {
                $statusCount['completed']++;
            }

            if (isset($item['status_pengembalian']) && !empty($item['status_pengembalian'])) {
                $statusCount['returned']++;
            }
        }

        // Hitung pemesanan harian untuk grafik
        $harian = array_fill(1, 31, 0); // Inisialisasi array dengan 0 untuk 31 hari
        $pendapatanHarian = array_fill(1, 31, 0);

        foreach ($data as $item) {
            $hari = (int)$item['hari'];
            $harian[$hari]++;

            if ($item['statuspembayaran'] == 'success') {
                $pendapatanHarian[$hari] += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                $pendapatanHarian[$hari] += $item['totalpembayaran'] ?? 0;
            }
        }

        return [
            'data' => $data,
            'total_pemesanan' => $totalPemesanan,
            'total_pendapatan' => $totalPendapatan,
            'status_count' => $statusCount,
            'pemesanan_harian' => $harian,
            'pendapatan_harian' => $pendapatanHarian,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    /**
     * Get data pemesanan barang untuk laporan tahunan
     * 
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pemesanan barang per tahun dan statistik pendapatan
     */
    public function getLaporanTahunan($tahun)
    {
        $db = \Config\Database::connect();

        // Ambil data pemesanan barang tahun ini
        $this->select('pemesananbarang.*, pelanggan.namapelanggan, pembayaran.status as statuspembayaran, 
            pembayaran.totalpembayaran, pembayaran.sisa, pembayaran.jumlahbayar, pembayaran.metodepembayaran, 
            pembayaran.tipepembayaran, MONTH(pemesananbarang.tgl) as bulan');
        $this->join('pelanggan', 'pelanggan.kdpelanggan = pemesananbarang.kdpelanggan', 'left');
        $this->join('pembayaran', 'pembayaran.kdpembayaran = pemesananbarang.kdpembayaran', 'left');

        // Filter berdasarkan tahun
        $this->where('YEAR(pemesananbarang.tgl)', $tahun);

        $this->orderBy('pemesananbarang.tgl', 'ASC');

        $data = $this->findAll();

        // Hitung statistik
        $totalPendapatan = 0;
        $totalPemesanan = count($data);
        $statusCount = [
            'pending' => 0,
            'partial' => 0,
            'success' => 0,
            'completed' => 0,
            'returned' => 0
        ];

        foreach ($data as $item) {
            if ($item['statuspembayaran'] == 'success') {
                $totalPendapatan += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                $totalPendapatan += $item['totalpembayaran'] ?? 0;
            }

            // Hitung jumlah per status
            if (isset($item['statuspembayaran'])) {
                if ($item['statuspembayaran'] == 'pending') {
                    $statusCount['pending']++;
                } else if ($item['statuspembayaran'] == 'partial') {
                    $statusCount['partial']++;
                } else if ($item['statuspembayaran'] == 'success') {
                    $statusCount['success']++;
                }
            }

            if (isset($item['status']) && $item['status'] == 'completed') {
                $statusCount['completed']++;
            }

            if (isset($item['status_pengembalian']) && !empty($item['status_pengembalian'])) {
                $statusCount['returned']++;
            }
        }

        // Hitung pemesanan bulanan untuk grafik
        $bulanan = array_fill(1, 12, 0); // Inisialisasi array dengan 0 untuk 12 bulan
        $pendapatanBulanan = array_fill(1, 12, 0);

        foreach ($data as $item) {
            $bulan = (int)$item['bulan'];
            $bulanan[$bulan]++;

            if ($item['statuspembayaran'] == 'success') {
                $pendapatanBulanan[$bulan] += $item['grandtotal'];
            } else if ($item['statuspembayaran'] == 'partial') {
                $pendapatanBulanan[$bulan] += $item['totalpembayaran'] ?? 0;
            }
        }

        return [
            'data' => $data,
            'total_pemesanan' => $totalPemesanan,
            'total_pendapatan' => $totalPendapatan,
            'status_count' => $statusCount,
            'pemesanan_bulanan' => $bulanan,
            'pendapatan_bulanan' => $pendapatanBulanan,
            'tahun' => $tahun
        ];
    }
}
