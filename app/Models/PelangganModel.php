<?php

namespace App\Models;

use CodeIgniter\Model;

class PelangganModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'pelanggan';
    protected $primaryKey       = 'kdpelanggan';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['namapelanggan', 'alamat', 'nohp', 'iduser', 'created_at', 'updated_at'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'namapelanggan' => 'required|min_length[3]|max_length[100]',
        'nohp' => 'required|min_length[10]|max_length[20]',
    ];
    protected $validationMessages   = [
        'namapelanggan' => [
            'required' => 'Nama pelanggan harus diisi',
            'min_length' => 'Nama pelanggan minimal 3 karakter',
            'max_length' => 'Nama pelanggan maksimal 100 karakter'
        ],
        'nohp' => [
            'required' => 'Nomor HP harus diisi',
            'min_length' => 'Nomor HP minimal 10 karakter',
            'max_length' => 'Nomor HP maksimal 20 karakter'
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get pelanggan by user ID
     * 
     * @param int $userId
     * @return array|null
     */
    public function getByUserId($userId)
    {
        return $this->where('iduser', $userId)->first();
    }

    /**
     * Create new pelanggan with user account
     * 
     * @param array $userData
     * @param array $pelangganData
     * @return bool|int
     */
    public function createWithUser($userData, $pelangganData)
    {
        $db = \Config\Database::connect();
        $db->transBegin();

        try {
            // Create user account first
            $userModel = new UserModel();
            // Set user as unverified by default
            $userData['is_verified'] = 0;
            $userData['status'] = 'inactive'; // Status inactive sampai diverifikasi

            $userModel->save($userData);
            $userId = $db->insertID();

            // Generate OTP for verification
            $otp = $userModel->generateOTP($userId);

            // Set user ID for pelanggan
            $pelangganData['iduser'] = $userId;

            // Create pelanggan record
            $this->save($pelangganData);
            $pelangganId = $db->insertID();

            // Commit transaction
            $db->transCommit();

            // Return data with OTP and user ID
            return [
                'pelanggan_id' => $pelangganId,
                'user_id' => $userId,
                'otp' => $otp
            ];
        } catch (\Exception $e) {
            // Rollback transaction on error
            $db->transRollback();
            log_message('error', 'Error creating pelanggan with user: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get data for DataTables
     * 
     * @param array $postData
     * @return array
     */
    public function getDataTables($postData = null)
    {
        $builder = $this->db->table('pelanggan p')
            ->select('p.*, u.username, u.email')
            ->join('users u', 'u.id = p.iduser', 'left');

        // Jika ada postData untuk pencarian
        if ($postData && isset($postData['search']['value']) && !empty($postData['search']['value'])) {
            $searchValue = $postData['search']['value'];
            $builder->groupStart()
                ->like('p.namapelanggan', $searchValue)
                ->orLike('p.alamat', $searchValue)
                ->orLike('p.nohp', $searchValue)
                ->orLike('u.username', $searchValue)
                ->orLike('u.email', $searchValue)
                ->groupEnd();
        }

        // Pengurutan
        if ($postData && isset($postData['order'])) {
            $columns = ['', 'p.namapelanggan', 'p.alamat', 'p.nohp', 'u.username', ''];
            $columnIndex = $postData['order'][0]['column'];
            $columnName = $columns[$columnIndex];
            $columnSortOrder = $postData['order'][0]['dir'];

            if (!empty($columnName)) {
                $builder->orderBy($columnName, $columnSortOrder);
            }
        } else {
            $builder->orderBy('p.namapelanggan', 'ASC');
        }

        // Limit & offset
        if ($postData && isset($postData['start']) && isset($postData['length'])) {
            $builder->limit($postData['length'], $postData['start']);
        }

        $result = $builder->get()->getResultArray();

        // Hitung total records
        $totalRecords = $this->db->table('pelanggan')->countAllResults();

        // Hitung total records dengan filter
        $totalRecordsWithFilter = $totalRecords;
        if ($postData && isset($postData['search']['value']) && !empty($postData['search']['value'])) {
            $searchBuilder = $this->db->table('pelanggan p')
                ->select('p.kdpelanggan')
                ->join('users u', 'u.id = p.iduser', 'left');

            $searchValue = $postData['search']['value'];
            $searchBuilder->groupStart()
                ->like('p.namapelanggan', $searchValue)
                ->orLike('p.alamat', $searchValue)
                ->orLike('p.nohp', $searchValue)
                ->orLike('u.username', $searchValue)
                ->orLike('u.email', $searchValue)
                ->groupEnd();

            $totalRecordsWithFilter = $searchBuilder->countAllResults();
        }

        // Format response untuk DataTables
        $response = [
            "draw" => intval($postData['draw'] ?? 0),
            "recordsTotal" => $totalRecords,
            "recordsFiltered" => $totalRecordsWithFilter,
            "data" => $result
        ];

        return $response;
    }

    /**
     * Get pelanggan with user data
     * 
     * @param int $kdpelanggan
     * @return array|null
     */
    public function getPelangganWithUser($kdpelanggan)
    {
        return $this->db->table('pelanggan p')
            ->select('p.*, u.id as iduser, u.username, u.email')
            ->join('users u', 'u.id = p.iduser', 'left')
            ->where('p.kdpelanggan', $kdpelanggan)
            ->get()
            ->getRowArray();
    }
}
