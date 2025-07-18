<?php

namespace App\Models;

use CodeIgniter\Model;

class PaketModel extends Model
{
    protected $table            = 'paket';
    protected $primaryKey       = 'kdpaket';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'namapaket',
        'detailpaket',
        'harga',
        'foto',
        'kdkategori',
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
        'namapaket'    => 'required|min_length[3]|max_length[100]',
        'detailpaket'  => 'permit_empty',
        'harga'        => 'required|numeric|greater_than_equal_to[0]',
        'kdkategori'   => 'required|integer|is_not_unique[kategori.kdkategori]',
    ];

    protected $validationMessages = [
        'namapaket' => [
            'required'    => 'Nama paket harus diisi',
            'min_length'  => 'Nama paket minimal 3 karakter',
            'max_length'  => 'Nama paket maksimal 100 karakter',
        ],
        'harga' => [
            'required'              => 'Harga harus diisi',
            'numeric'               => 'Harga harus berupa angka',
            'greater_than_equal_to' => 'Harga tidak boleh negatif',
        ],
        'kdkategori' => [
            'required'      => 'Kategori harus dipilih',
            'integer'       => 'Kategori tidak valid',
            'is_not_unique' => 'Kategori tidak ditemukan',
        ],
    ];

    protected $skipValidation     = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get paket with kategori information
     */
    public function getPaketWithKategori($kdpaket = null)
    {
        $builder = $this->db->table('paket p')
            ->select('p.*, k.namakategori')
            ->join('kategori k', 'k.kdkategori = p.kdkategori');

        if ($kdpaket !== null) {
            return $builder->where('p.kdpaket', $kdpaket)->get()->getRowArray();
        }

        return $builder->orderBy('p.created_at', 'DESC')->get()->getResultArray();
    }

    /**
     * Get paket with kategori information filtered by kategori
     */
    public function getPaketByKategori($kdkategori)
    {
        return $this->db->table('paket p')
            ->select('p.*, k.namakategori')
            ->join('kategori k', 'k.kdkategori = p.kdkategori')
            ->where('p.kdkategori', $kdkategori)
            ->orderBy('p.created_at', 'DESC')
            ->get()
            ->getResultArray();
    }

    /**
     * Get paket with kategori information
     */
    public function getPaketDetail($kdpaket)
    {
        $builder = $this->db->table('paket p')
            ->select('p.*, k.namakategori')
            ->join('kategori k', 'k.kdkategori = p.kdkategori')
            ->where('p.kdpaket', $kdpaket);

        return $builder->get()->getRowArray();
    }

    /**
     * Get paket with kategori, barang, and detail paket items
     */
    public function getPaketWithItems($kdpaket)
    {
        // Get paket data
        $paket = $this->getPaketDetail($kdpaket);

        if (!$paket) {
            return null;
        }

        // Get detail paket items
        $detailPaketModel = new DetailPaketModel();
        $paket['items'] = $detailPaketModel->getDetailPaket($kdpaket);

        return $paket;
    }
}
