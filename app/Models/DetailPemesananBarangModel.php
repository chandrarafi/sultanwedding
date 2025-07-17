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
    protected $validationRules = [];
    protected $validationMessages = [];

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
