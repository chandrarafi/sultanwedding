<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table            = 'barang';
    protected $primaryKey       = 'kdbarang';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'namabarang',
        'satuan',
        'jumlah',
        'hargasewa',
        'foto',
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
        'namabarang' => 'required|min_length[3]|max_length[100]',
        'satuan'     => 'required|max_length[20]',
        'jumlah'     => 'required|integer|greater_than_equal_to[0]',
        'hargasewa'  => 'required|numeric|greater_than_equal_to[0]',
    ];

    protected $validationMessages = [
        'namabarang' => [
            'required' => 'Nama barang harus diisi',
            'min_length' => 'Nama barang minimal 3 karakter',
            'max_length' => 'Nama barang maksimal 100 karakter',
        ],
        'satuan' => [
            'required' => 'Satuan harus diisi',
            'max_length' => 'Satuan maksimal 20 karakter',
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi',
            'integer' => 'Jumlah harus berupa angka',
            'greater_than_equal_to' => 'Jumlah tidak boleh negatif',
        ],
        'hargasewa' => [
            'required' => 'Harga sewa harus diisi',
            'numeric' => 'Harga sewa harus berupa angka',
            'greater_than_equal_to' => 'Harga sewa tidak boleh negatif',
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
     * Get barang information
     */
    public function getBarangWithKategori($kdbarang = null)
    {
        $builder = $this->db->table('barang b');

        if ($kdbarang !== null) {
            return $builder->where('b.kdbarang', $kdbarang)->get()->getRowArray();
        }

        return $builder->get()->getResultArray();
    }
}
