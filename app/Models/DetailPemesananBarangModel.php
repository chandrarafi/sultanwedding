<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPemesananBarangModel extends Model
{
    protected $table            = 'detailpemesananbarang';
    protected $primaryKey       = 'kddetailpemesananbarang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'kdpemesananbarang',
        'kdbarang',
        'jumlah',
        'harga',
        'subtotal',
        'created_at',
        'updated_at'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules = [
        'kdpemesananbarang' => 'required|integer|is_not_unique[pemesananbarang.kdpemesananbarang]',
        'kdbarang'          => 'required|integer|is_not_unique[barang.kdbarang]',
        'jumlah'            => 'required|integer|greater_than[0]',
        'harga'             => 'required|numeric|greater_than_equal_to[0]',
        'subtotal'          => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'kdpemesananbarang' => [
            'required' => 'ID pemesanan barang harus diisi',
            'integer' => 'ID pemesanan barang harus berupa angka',
            'is_not_unique' => 'ID pemesanan barang tidak valid',
        ],
        'kdbarang' => [
            'required' => 'ID barang harus diisi',
            'integer' => 'ID barang harus berupa angka',
            'is_not_unique' => 'ID barang tidak valid',
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi',
            'integer' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0',
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than_equal_to' => 'Harga tidak boleh negatif',
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'numeric' => 'Subtotal harus berupa angka',
            'greater_than_equal_to' => 'Subtotal tidak boleh negatif',
        ],
    ];

    /**
     * Get detail pemesanan barang with barang information
     *
     * @param int $kdpemesananbarang
     * @return array
     */
    public function getDetailWithBarang($kdpemesananbarang)
    {
        return $this->select('detailpemesananbarang.*, barang.namabarang, barang.satuan, barang.foto')
            ->join('barang', 'barang.kdbarang = detailpemesananbarang.kdbarang')
            ->where('detailpemesananbarang.kdpemesananbarang', $kdpemesananbarang)
            ->findAll();
    }

    /**
     * Calculate total for a pemesanan
     *
     * @param int $kdpemesananbarang
     * @return float
     */
    public function calculateTotal($kdpemesananbarang)
    {
        $result = $this->selectSum('subtotal')
            ->where('kdpemesananbarang', $kdpemesananbarang)
            ->first();

        return $result['subtotal'] ?? 0;
    }
}
