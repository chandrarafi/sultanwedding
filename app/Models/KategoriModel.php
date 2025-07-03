<?php

namespace App\Models;

use CodeIgniter\Model;

class KategoriModel extends Model
{
    protected $table            = 'kategori';
    protected $primaryKey       = 'kdkategori';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['namakategori', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'namakategori' => 'required|min_length[3]|max_length[100]|is_unique[kategori.namakategori,kdkategori,{kdkategori}]',
    ];
    protected $validationMessages   = [
        'namakategori' => [
            'required' => 'Nama kategori harus diisi',
            'min_length' => 'Nama kategori minimal 3 karakter',
            'max_length' => 'Nama kategori maksimal 100 karakter',
            'is_unique' => 'Nama kategori sudah ada'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get data for DataTables
     *
     * @param array $postData
     * @return array
     */
    public function getDataTables($postData)
    {
        $dt = $this->builder();

        // Search
        if (!empty($postData['search']['value'])) {
            $dt->like('namakategori', $postData['search']['value']);
        }

        // Order
        if (!empty($postData['order'])) {
            $dt->orderBy(
                $this->allowedFields[$postData['order']['0']['column']],
                $postData['order']['0']['dir']
            );
        } else {
            $dt->orderBy('kdkategori', 'DESC');
        }

        // Limit & offset
        if ($postData['length'] != -1) {
            $dt->limit($postData['length'], $postData['start']);
        }

        $results = $dt->get()->getResultArray();

        // Count filtered results
        $this->select('COUNT(*) as total');
        if (!empty($postData['search']['value'])) {
            $this->like('namakategori', $postData['search']['value']);
        }
        $recordsFiltered = $this->first()['total'];

        // Count total results
        $recordsTotal = $this->countAllResults();

        return [
            'draw' => $postData['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $results,
        ];
    }
}
