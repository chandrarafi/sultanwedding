<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPaketModel extends Model
{
    protected $table            = 'detail_paket';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdpaket',
        'kdbarang',
        'jumlah',
        'harga',
        'keterangan',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules = [
        'kdpaket'    => 'required|integer|is_not_unique[paket.kdpaket]',
        'kdbarang'   => 'required|integer|is_not_unique[barang.kdbarang]',
        'jumlah'     => 'required|integer|greater_than[0]',
        'harga'      => 'required|numeric|greater_than_equal_to[0]',
        'keterangan' => 'permit_empty',
    ];

    protected $validationMessages = [
        'kdpaket' => [
            'required'      => 'ID Paket harus diisi',
            'integer'       => 'ID Paket tidak valid',
            'is_not_unique' => 'Paket tidak ditemukan',
        ],
        'kdbarang' => [
            'required'      => 'ID Barang harus diisi',
            'integer'       => 'ID Barang tidak valid',
            'is_not_unique' => 'Barang tidak ditemukan',
        ],
        'jumlah' => [
            'required'     => 'Jumlah harus diisi',
            'integer'      => 'Jumlah harus berupa angka bulat',
            'greater_than' => 'Jumlah harus lebih dari 0',
        ],
        'harga' => [
            'required'              => 'Harga harus diisi',
            'numeric'               => 'Harga harus berupa angka',
            'greater_than_equal_to' => 'Harga tidak boleh negatif',
        ],
    ];

    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    /**
     * Get detail paket dengan informasi barang
     *
     * @param int $kdpaket
     * @return array
     */
    public function getDetailPaket($kdpaket)
    {
        return $this->db->table('detail_paket dp')
            ->select('dp.*, b.namabarang, b.satuan')
            ->join('barang b', 'b.kdbarang = dp.kdbarang')
            ->where('dp.kdpaket', $kdpaket)
            ->get()
            ->getResultArray();
    }

    /**
     * Hapus semua detail paket berdasarkan kdpaket
     *
     * @param int $kdpaket
     * @return bool
     */
    public function deleteByPaket($kdpaket)
    {
        return $this->where('kdpaket', $kdpaket)->delete();
    }
}
