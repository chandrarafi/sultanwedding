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
}
