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


    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';


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

            $userModel = new UserModel();

            $userData['is_verified'] = 0;
            $userData['status'] = 'inactive'; // Status inactive sampai diverifikasi

            $userModel->save($userData);
            $userId = $db->insertID();


            $otp = $userModel->generateOTP($userId);


            $pelangganData['iduser'] = $userId;


            $this->save($pelangganData);
            $pelangganId = $db->insertID();


            $db->transCommit();


            return [
                'pelanggan_id' => $pelangganId,
                'user_id' => $userId,
                'otp' => $otp
            ];
        } catch (\Exception $e) {

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


        if ($postData && isset($postData['start']) && isset($postData['length'])) {
            $builder->limit($postData['length'], $postData['start']);
        }

        $result = $builder->get()->getResultArray();


        $totalRecords = $this->db->table('pelanggan')->countAllResults();


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
