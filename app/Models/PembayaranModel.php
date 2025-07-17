<?php

namespace App\Models;

use CodeIgniter\Model;

class PembayaranModel extends Model
{
    protected $table            = 'pembayaran';
    protected $primaryKey       = 'kdpembayaran';
    protected $useAutoIncrement = false; // Custom primary key
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = false; // Allow all fields
    protected $allowedFields    = [];

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
        $db = \Config\Database::connect();

        // Get the last payment code with the same prefix
        $lastPayment = $db->table($this->table)
            ->like('kdpembayaran', $prefix, 'after')
            ->orderBy('kdpembayaran', 'DESC')
            ->limit(1)
            ->get()
            ->getRowArray();

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
     * Create new payment record
     *
     * @param array $data Payment data
     * @return string|false Payment code if successful, false otherwise
     */
    public function createPayment($data)
    {
        // Generate payment code if not provided
        if (!isset($data['kdpembayaran'])) {
            $data['kdpembayaran'] = $this->generatePaymentCode();
        }

        // Set default values
        $data['tgl'] = $data['tgl'] ?? date('Y-m-d');
        $data['status'] = $data['status'] ?? 'pending';
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        if ($db->table($this->table)->insert($data)) {
            return $data['kdpembayaran'];
        }

        return false;
    }

    /**
     * Create new payment for booking (DP 30%)
     *
     * @param float $grandTotal
     * @param string $metodepembayaran
     * @return string|false Payment code if successful, false otherwise
     */
    public function createBookingPayment($grandTotal, $metodepembayaran = 'transfer')
    {
        // Calculate DP amount (30% of grand total)
        $dpAmount = $grandTotal * 0.3;

        // Remaining payment (70%)
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
     * Process payment for H-1 (additional 40%)
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

        // Additional 40%
        $additionalPayment = $originalTotal * 0.4;

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
     * Process final payment (remaining 30%)
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
        $finalPayment = $payment['sisa'];

        $data = [
            'tgl' => date('Y-m-d'),
            'metodepembayaran' => $metodepembayaran,
            'tipepembayaran' => 'lunas',
            'jumlahbayar' => $finalPayment,
            'totalpembayaran' => $payment['totalpembayaran'] + $finalPayment,
            'sisa' => 0,
            'status' => 'partial', // Keep status as partial to maintain payment progress
            'full_paid' => '1' // Mark that full payment has been made - use string since it's a CHAR field
        ];

        // Add bukti pembayaran if provided
        if ($buktipembayaran) {
            $data['buktipembayaran'] = $buktipembayaran;
        }

        // Debug log
        log_message('debug', 'Processing full payment with data: ' . json_encode($data));

        return $this->update($kdpembayaran, $data);
    }

    /**
     * Update existing payment record
     *
     * @param string $kdpembayaran
     * @param array $data
     * @return bool
     */
    public function updatePayment($kdpembayaran, $data)
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->where('kdpembayaran', $kdpembayaran)
            ->update($data);
    }

    /**
     * Get all payments with order ID
     *
     * @return array
     */
    public function getAllPaymentsWithOrder()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('pembayaran.*, pemesananbarang.kdpemesananbarang')
            ->join('pemesananbarang', 'pemesananbarang.kdpembayaran = pembayaran.kdpembayaran', 'left')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get pending payments (DP payments awaiting confirmation)
     *
     * @return array
     */
    public function getPendingPayments()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran', 'left')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left')
            ->where('pembayaran.status', 'pending')
            ->where('pembayaran.buktipembayaran IS NOT NULL')
            ->where('pembayaran.tipepembayaran', 'dp')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get pending H-1 payments awaiting confirmation
     *
     * @return array
     */
    public function getPendingH1Payments()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran', 'left')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left')
            ->where('pembayaran.status', 'partial')
            ->where('pembayaran.buktipembayaran IS NOT NULL')
            ->where('pembayaran.h1_paid', 1)
            ->where('pembayaran.h1_confirmed', 0)
            ->orWhere('pembayaran.h1_confirmed IS NULL')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get pending full payments awaiting confirmation
     *
     * @return array
     */
    public function getPendingFullPayments()
    {
        $db = \Config\Database::connect();
        return $db->table($this->table)
            ->select('pembayaran.*, pemesananpaket.kdpemesananpaket, pelanggan.namapelanggan, paket.namapaket')
            ->join('pemesananpaket', 'pemesananpaket.kdpembayaran = pembayaran.kdpembayaran', 'left')
            ->join('pelanggan', 'pelanggan.kdpelanggan = pemesananpaket.kdpelanggan', 'left')
            ->join('paket', 'paket.kdpaket = pemesananpaket.kdpaket', 'left')
            ->where('pembayaran.status', 'partial')
            ->where('pembayaran.buktipembayaran IS NOT NULL')
            ->where('pembayaran.full_paid', '1')
            ->where('pembayaran.full_confirmed', 0)
            ->orWhere('pembayaran.full_confirmed IS NULL')
            ->orderBy('pembayaran.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get data pembayaran untuk laporan
     * 
     * @param string $tanggal_awal Tanggal awal filter (format: Y-m-d)
     * @param string $tanggal_akhir Tanggal akhir filter (format: Y-m-d)
     * @param string $status Status pembayaran (optional)
     * @return array
     */
    public function getLaporanPembayaran($tanggal_awal, $tanggal_akhir, $status = '')
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' p');

        $builder->select('p.*, pp.kdpemesananpaket, pb.kdpemesananbarang, 
            COALESCE(plg1.namapelanggan, plg2.namapelanggan) as namapelanggan,
            CASE 
                WHEN pp.kdpemesananpaket IS NOT NULL THEN \'Paket\' 
                WHEN pb.kdpemesananbarang IS NOT NULL THEN \'Barang\'
                ELSE \'Lainnya\'
            END as jenis_pemesanan');

        // Join dengan pemesanan paket
        $builder->join('pemesananpaket pp', 'pp.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg1', 'plg1.kdpelanggan = pp.kdpelanggan', 'left');

        // Join dengan pemesanan barang
        $builder->join('pemesananbarang pb', 'pb.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg2', 'plg2.kdpelanggan = pb.kdpelanggan', 'left');

        // Filter berdasarkan tanggal
        $builder->where('DATE(p.tgl) >=', $tanggal_awal);
        $builder->where('DATE(p.tgl) <=', $tanggal_akhir);

        // Filter berdasarkan status jika ada
        if (!empty($status)) {
            if ($status == 'pending') {
                $builder->where('p.status', 'pending');
            } else if ($status == 'partial') {
                $builder->where('p.status', 'partial');
            } else if ($status == 'success') {
                $builder->where('p.status', 'success');
            } else if ($status == 'confirmed') {
                $builder->where('p.status', 'confirmed');
            }
        }

        $builder->orderBy('p.tgl', 'DESC');

        return $builder->get()->getResultArray();
    }

    /**
     * Get data pembayaran untuk laporan bulanan
     * 
     * @param int $bulan Bulan (1-12)
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pembayaran per bulan dan statistik pendapatan
     */
    public function getLaporanBulanan($bulan, $tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' p');

        $builder->select('p.*, pp.kdpemesananpaket, pb.kdpemesananbarang, 
            COALESCE(plg1.namapelanggan, plg2.namapelanggan) as namapelanggan, 
            CASE 
                WHEN pp.kdpemesananpaket IS NOT NULL THEN \'Paket\' 
                WHEN pb.kdpemesananbarang IS NOT NULL THEN \'Barang\'
                ELSE \'Lainnya\'
            END as jenis_pemesanan,
            DAY(p.tgl) as hari');

        // Join dengan pemesanan paket
        $builder->join('pemesananpaket pp', 'pp.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg1', 'plg1.kdpelanggan = pp.kdpelanggan', 'left');

        // Join dengan pemesanan barang
        $builder->join('pemesananbarang pb', 'pb.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg2', 'plg2.kdpelanggan = pb.kdpelanggan', 'left');

        // Filter berdasarkan bulan dan tahun
        $builder->where('MONTH(p.tgl)', $bulan);
        $builder->where('YEAR(p.tgl)', $tahun);

        $builder->orderBy('p.tgl', 'ASC');

        $data = $builder->get()->getResultArray();

        // Hitung statistik
        $totalPendapatan = 0;
        $totalPembayaran = count($data);
        $statusCount = [
            'pending' => 0,
            'partial' => 0,
            'success' => 0,
            'confirmed' => 0
        ];

        $jenisPemesananCount = [
            'Paket' => 0,
            'Barang' => 0,
            'Lainnya' => 0
        ];

        $metodePembayaranCount = [
            'transfer' => 0,
            'cash' => 0,
            'lainnya' => 0
        ];

        foreach ($data as $item) {
            // Hitung total pendapatan dari pembayaran sukses atau terkonfirmasi
            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $totalPendapatan += $item['jumlahbayar'];
            }

            // Hitung jumlah per status
            if ($item['status'] == 'pending') {
                $statusCount['pending']++;
            } else if ($item['status'] == 'partial') {
                $statusCount['partial']++;
            } else if ($item['status'] == 'success') {
                $statusCount['success']++;
            } else if ($item['status'] == 'confirmed') {
                $statusCount['confirmed']++;
            }

            // Hitung jenis pemesanan
            if ($item['jenis_pemesanan'] == 'Paket') {
                $jenisPemesananCount['Paket']++;
            } else if ($item['jenis_pemesanan'] == 'Barang') {
                $jenisPemesananCount['Barang']++;
            } else {
                $jenisPemesananCount['Lainnya']++;
            }

            // Hitung metode pembayaran
            if (isset($item['metodepembayaran'])) {
                if ($item['metodepembayaran'] == 'transfer') {
                    $metodePembayaranCount['transfer']++;
                } else if ($item['metodepembayaran'] == 'cash') {
                    $metodePembayaranCount['cash']++;
                } else {
                    $metodePembayaranCount['lainnya']++;
                }
            }
        }

        // Hitung pembayaran harian untuk grafik
        $harian = array_fill(1, 31, 0); // Inisialisasi array dengan 0 untuk 31 hari
        $pendapatanHarian = array_fill(1, 31, 0);

        foreach ($data as $item) {
            $hari = (int)$item['hari'];
            $harian[$hari]++;

            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $pendapatanHarian[$hari] += $item['jumlahbayar'];
            }
        }

        return [
            'data' => $data,
            'total_pembayaran' => $totalPembayaran,
            'total_pendapatan' => $totalPendapatan,
            'status_count' => $statusCount,
            'jenis_pemesanan_count' => $jenisPemesananCount,
            'metode_pembayaran_count' => $metodePembayaranCount,
            'pembayaran_harian' => $harian,
            'pendapatan_harian' => $pendapatanHarian,
            'bulan' => $bulan,
            'tahun' => $tahun
        ];
    }

    /**
     * Get data pembayaran untuk laporan tahunan
     * 
     * @param int $tahun Tahun (misal: 2024)
     * @return array Array data pembayaran per tahun dan statistik pendapatan
     */
    public function getLaporanTahunan($tahun)
    {
        $db = \Config\Database::connect();
        $builder = $db->table($this->table . ' p');

        $builder->select('p.*, pp.kdpemesananpaket, pb.kdpemesananbarang, 
            COALESCE(plg1.namapelanggan, plg2.namapelanggan) as namapelanggan, 
            CASE 
                WHEN pp.kdpemesananpaket IS NOT NULL THEN \'Paket\' 
                WHEN pb.kdpemesananbarang IS NOT NULL THEN \'Barang\'
                ELSE \'Lainnya\'
            END as jenis_pemesanan,
            MONTH(p.tgl) as bulan');

        // Join dengan pemesanan paket
        $builder->join('pemesananpaket pp', 'pp.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg1', 'plg1.kdpelanggan = pp.kdpelanggan', 'left');

        // Join dengan pemesanan barang
        $builder->join('pemesananbarang pb', 'pb.kdpembayaran = p.kdpembayaran', 'left');
        $builder->join('pelanggan plg2', 'plg2.kdpelanggan = pb.kdpelanggan', 'left');

        // Filter berdasarkan tahun
        $builder->where('YEAR(p.tgl)', $tahun);

        $builder->orderBy('p.tgl', 'ASC');

        $data = $builder->get()->getResultArray();

        // Hitung statistik
        $totalPendapatan = 0;
        $totalPembayaran = count($data);
        $statusCount = [
            'pending' => 0,
            'partial' => 0,
            'success' => 0,
            'confirmed' => 0
        ];

        $jenisPemesananCount = [
            'Paket' => 0,
            'Barang' => 0,
            'Lainnya' => 0
        ];

        $metodePembayaranCount = [
            'transfer' => 0,
            'cash' => 0,
            'lainnya' => 0
        ];

        foreach ($data as $item) {
            // Hitung total pendapatan dari pembayaran sukses atau terkonfirmasi
            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $totalPendapatan += $item['jumlahbayar'];
            }

            // Hitung jumlah per status
            if ($item['status'] == 'pending') {
                $statusCount['pending']++;
            } else if ($item['status'] == 'partial') {
                $statusCount['partial']++;
            } else if ($item['status'] == 'success') {
                $statusCount['success']++;
            } else if ($item['status'] == 'confirmed') {
                $statusCount['confirmed']++;
            }

            // Hitung jenis pemesanan
            if ($item['jenis_pemesanan'] == 'Paket') {
                $jenisPemesananCount['Paket']++;
            } else if ($item['jenis_pemesanan'] == 'Barang') {
                $jenisPemesananCount['Barang']++;
            } else {
                $jenisPemesananCount['Lainnya']++;
            }

            // Hitung metode pembayaran
            if (isset($item['metodepembayaran'])) {
                if ($item['metodepembayaran'] == 'transfer') {
                    $metodePembayaranCount['transfer']++;
                } else if ($item['metodepembayaran'] == 'cash') {
                    $metodePembayaranCount['cash']++;
                } else {
                    $metodePembayaranCount['lainnya']++;
                }
            }
        }

        // Hitung pembayaran bulanan untuk grafik
        $bulanan = array_fill(1, 12, 0); // Inisialisasi array dengan 0 untuk 12 bulan
        $pendapatanBulanan = array_fill(1, 12, 0);

        foreach ($data as $item) {
            $bulan = (int)$item['bulan'];
            $bulanan[$bulan]++;

            if ($item['status'] == 'success' || $item['status'] == 'confirmed') {
                $pendapatanBulanan[$bulan] += $item['jumlahbayar'];
            }
        }

        return [
            'data' => $data,
            'total_pembayaran' => $totalPembayaran,
            'total_pendapatan' => $totalPendapatan,
            'status_count' => $statusCount,
            'jenis_pemesanan_count' => $jenisPemesananCount,
            'metode_pembayaran_count' => $metodePembayaranCount,
            'pembayaran_bulanan' => $bulanan,
            'pendapatan_bulanan' => $pendapatanBulanan,
            'tahun' => $tahun
        ];
    }
}
