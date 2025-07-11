<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Load helper cookie
        helper('cookie');

        // Jika belum login
        if (!session()->get('logged_in')) {
            // Cek remember me cookie
            $userId = get_cookie('user_id');
            $rememberToken = get_cookie('remember_token');

            if ($userId && $rememberToken) {
                $userModel = new \App\Models\UserModel();
                $user = $userModel->find($userId);

                if ($user && $user['remember_token'] === $rememberToken) {
                    // Set session
                    $sessionData = [
                        'user_id' => $user['id'],
                        'username' => $user['username'],
                        'email' => $user['email'],
                        'name' => $user['name'],
                        'role' => $user['role'],
                        'logged_in' => true
                    ];
                    session()->set($sessionData);

                    // Jika ada argumen role, periksa apakah user memiliki role yang sesuai
                    if (!empty($arguments) && !in_array($user['role'], $arguments)) {
                        // Redirect sesuai role jika tidak memiliki akses
                        return $this->redirectBasedOnRole($user['role']);
                    }
                } else {
                    // Simpan URL yang dicoba diakses
                    session()->set('redirect_url', current_url());
                    return redirect()->to('auth');
                }
            } else {
                // Simpan URL yang dicoba diakses
                session()->set('redirect_url', current_url());
                return redirect()->to('auth');
            }
        } else {
            // Jika sudah login, periksa apakah memiliki role yang sesuai
            if (!empty($arguments)) {
                $userRole = session()->get('role');
                if (!in_array($userRole, $arguments)) {
                    // Redirect sesuai role jika tidak memiliki akses
                    return $this->redirectBasedOnRole($userRole);
                }
            }
        }

        // Redirect based on role if accessing root URL
        if ($request->getUri()->getPath() === '/') {
            // Jika sudah login, redirect ke halaman sesuai role (hanya untuk admin)
            if (session()->get('logged_in')) {
                $role = session()->get('role');
                if ($role === 'admin') {
                    return redirect()->to(site_url('admin'));
                }
                // Untuk role pelanggan, tetap di landing page
            }
            // Jika tidak login, tampilkan landing page (tidak perlu redirect)
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing
    }

    /**
     * Redirect pengguna berdasarkan role
     */
    protected function redirectBasedOnRole($role)
    {
        switch ($role) {
            case 'admin':
                return redirect()->to(site_url('admin'));
            case 'pimpinan':
                return redirect()->to(site_url('pimpinan'));
            case 'pelanggan':
                return redirect()->to(site_url()); // Redirect ke base URL
            default:
                return redirect()->to(site_url());
        }
    }
}
