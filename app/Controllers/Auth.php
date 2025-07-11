<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\PelangganModel;

class Auth extends BaseController
{
    protected $userModel;
    protected $pelangganModel;
    protected $email;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->pelangganModel = new PelangganModel();
        // Load helper cookie
        helper('cookie');
        // Load email service
        $this->email = \Config\Services::email();
    }

    public function index()
    {
        return $this->login();
    }

    public function login()
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }

        return view('auth/login');
    }

    public function register()
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }

        return view('auth/register');
    }

    public function verify($userId = null)
    {
        // Jika sudah login, redirect sesuai role
        if (session()->get('logged_in')) {
            return $this->redirectBasedOnRole();
        }

        if (empty($userId)) {
            return redirect()->to('auth/login')->with('error', 'User ID tidak valid');
        }

        return view('auth/verify', ['userId' => $userId]);
    }

    public function loginProcess()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');
        $remember = $this->request->getPost('remember') == 'on';

        $user = $this->userModel->where('username', $username)
            ->orWhere('email', $username)
            ->first();

        if ($user) {
            // Periksa status verifikasi
            if ($user['is_verified'] == 0) {
                // Regenerate OTP and send email
                $otp = $this->userModel->generateOTP($user['id']);
                $this->sendVerificationEmail($user['email'], $user['name'], $otp);

                return $this->response->setJSON([
                    'status' => 'verification_required',
                    'message' => 'Akun Anda belum diverifikasi. Kode OTP baru telah dikirim ke email Anda.',
                    'redirect' => site_url('auth/verify/' . $user['id'])
                ]);
            }

            if ($user['status'] !== 'active') {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.'
                ]);
            }

            if (password_verify($password, $user['password'])) {
                // Update last login
                $this->userModel->update($user['id'], [
                    'last_login' => date('Y-m-d H:i:s')
                ]);

                // Set session data
                $sessionData = [
                    'user_id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email'],
                    'name' => $user['name'],
                    'role' => $user['role'],
                    'logged_in' => true
                ];
                session()->set($sessionData);

                // Handle remember me
                if ($remember) {
                    $this->setRememberMeCookie($user['id']);
                }

                // Redirect to stored URL or default based on role
                $redirectUrl = session()->get('redirect_url') ?? $this->getRedirectUrl($user['role']);
                session()->remove('redirect_url');

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Login berhasil',
                    'redirect' => $redirectUrl
                ]);
            }
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Username/Email atau Password salah'
        ]);
    }

    public function registerProcess()
    {
        // Validate form data
        $rules = [
            'name' => [
                'rules' => 'required|min_length[3]|max_length[100]',
                'errors' => [
                    'required' => 'Nama lengkap harus diisi',
                    'min_length' => 'Nama lengkap minimal 3 karakter',
                    'max_length' => 'Nama lengkap maksimal 100 karakter'
                ]
            ],
            'username' => [
                'rules' => 'required|alpha_numeric_space|min_length[3]|is_unique[users.username]',
                'errors' => [
                    'required' => 'Username harus diisi',
                    'alpha_numeric_space' => 'Username hanya boleh berisi huruf, angka dan spasi',
                    'min_length' => 'Username minimal 3 karakter',
                    'is_unique' => 'Username sudah digunakan'
                ]
            ],
            'email' => [
                'rules' => 'required|valid_email|is_unique[users.email]',
                'errors' => [
                    'required' => 'Email harus diisi',
                    'valid_email' => 'Format email tidak valid',
                    'is_unique' => 'Email sudah digunakan'
                ]
            ],
            'password' => [
                'rules' => 'required|min_length[6]',
                'errors' => [
                    'required' => 'Password harus diisi',
                    'min_length' => 'Password minimal 6 karakter'
                ]
            ],
            'password_confirm' => [
                'rules' => 'required|matches[password]',
                'errors' => [
                    'required' => 'Konfirmasi password harus diisi',
                    'matches' => 'Konfirmasi password tidak cocok'
                ]
            ],
            'nohp' => [
                'rules' => 'required|min_length[10]|max_length[20]',
                'errors' => [
                    'required' => 'Nomor HP harus diisi',
                    'min_length' => 'Nomor HP minimal 10 karakter',
                    'max_length' => 'Nomor HP maksimal 20 karakter'
                ]
            ],
            'alamat' => [
                'rules' => 'permit_empty',
            ],
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Validasi gagal',
                'errors' => $this->validator->getErrors()
            ]);
        }

        // Prepare user data
        $userData = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $this->request->getPost('password'),
            'name' => $this->request->getPost('name'),
            'role' => 'pelanggan', // Set role as pelanggan
            'status' => 'inactive', // Set status as inactive sampai diverifikasi
            'is_verified' => 0,
        ];

        // Prepare pelanggan data
        $pelangganData = [
            'namapelanggan' => $this->request->getPost('name'),
            'alamat' => $this->request->getPost('alamat'),
            'nohp' => $this->request->getPost('nohp'),
        ];

        // Create pelanggan with user
        $result = $this->pelangganModel->createWithUser($userData, $pelangganData);

        if ($result) {
            // Send verification email
            $this->sendVerificationEmail($userData['email'], $userData['name'], $result['otp']);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Registrasi berhasil. Kode OTP telah dikirim ke email Anda untuk verifikasi.',
                'redirect' => site_url('auth/verify/' . $result['user_id'])
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mendaftar, silakan coba lagi'
            ]);
        }
    }

    public function verifyProcess()
    {
        $userId = $this->request->getPost('user_id');
        $otp = $this->request->getPost('otp');

        if (empty($userId) || empty($otp)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User ID atau kode OTP tidak valid'
            ]);
        }

        // Verify OTP
        $verified = $this->userModel->verifyOTP($userId, $otp);

        if ($verified) {
            // Activate user
            $this->userModel->update($userId, ['status' => 'active']);

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Verifikasi berhasil. Silakan login.',
                'redirect' => site_url('auth/login')
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kode OTP tidak valid atau sudah kadaluarsa'
            ]);
        }
    }

    public function resendOTP()
    {
        $userId = $this->request->getPost('user_id');

        if (empty($userId)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User ID tidak valid'
            ]);
        }

        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'User tidak ditemukan'
            ]);
        }

        // Generate new OTP
        $otp = $this->userModel->generateOTP($userId);

        // Send verification email
        $sent = $this->sendVerificationEmail($user['email'], $user['name'], $otp);

        if ($sent) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Kode OTP baru telah dikirim ke email Anda'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengirim kode OTP. Silakan coba lagi nanti.'
            ]);
        }
    }

    protected function sendVerificationEmail($email, $name, $otp)
    {
        $this->email->setFrom('noreply@sultanwedding.com', 'Sultan Wedding');
        $this->email->setTo($email);
        $this->email->setSubject('Verifikasi Akun Sultan Wedding');

        $message = "
        <p>Halo $name,</p>
        <p>Terima kasih telah mendaftar di Sultan Wedding. Untuk menyelesaikan pendaftaran, silakan masukkan kode verifikasi berikut:</p>
        <h2 style='text-align:center;background:#f5f5f5;padding:10px;font-size:24px;letter-spacing:5px'>$otp</h2>
        <p>Kode verifikasi ini berlaku selama 24 jam.</p>
        <p>Jika Anda tidak merasa mendaftar, silakan abaikan email ini.</p>
        <p>Terima kasih,<br>Tim Sultan Wedding</p>
        ";

        $this->email->setMessage($message);
        $this->email->setMailType('html');

        return $this->email->send();
    }

    public function logout()
    {
        // Hapus remember me cookie
        if (get_cookie('remember_token')) {
            delete_cookie('remember_token');
            delete_cookie('user_id');
        }

        // Destroy session
        session()->destroy();

        // Redirect to login page
        return redirect()->to('auth');
    }

    protected function setRememberMeCookie($userId)
    {
        $token = bin2hex(random_bytes(32));

        // Simpan token di database
        $this->userModel->update($userId, [
            'remember_token' => $token
        ]);

        // Set cookies yang akan expired dalam 30 hari
        $expires = 30 * 24 * 60 * 60; // 30 hari
        $secure = isset($_SERVER['HTTPS']); // Set secure hanya jika HTTPS

        // Set cookie untuk remember token
        set_cookie(
            'remember_token',
            $token,
            $expires,
            '',  // domain
            '/', // path
            '', // prefix
            $secure, // secure - hanya set true jika HTTPS
            true  // httponly
        );

        // Set cookie untuk user ID
        set_cookie(
            'user_id',
            $userId,
            $expires,
            '',
            '/',
            '',
            $secure,
            true
        );
    }

    /**
     * Menentukan URL redirect berdasarkan role pengguna
     */
    protected function getRedirectUrl($role = null)
    {
        if ($role === null) {
            $role = session()->get('role');
        }

        switch ($role) {
            case 'admin':
                return site_url('admin');
            case 'pimpinan':
                return site_url('pimpinan');
            case 'pelanggan':
                return site_url(); // Redirect ke base URL
            default:
                return site_url();
        }
    }

    /**
     * Redirect pengguna berdasarkan role setelah login
     */
    protected function redirectBasedOnRole()
    {
        $role = session()->get('role');
        return redirect()->to($this->getRedirectUrl($role));
    }
}
