<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class RoleFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        // Pastikan user sudah login
        if (!session()->get('logged_in')) {
            return redirect()->to('auth');
        }

        // Cek role
        $userRole = session()->get('role');

        // Jika tidak ada role yang diperlukan, izinkan akses
        if (empty($arguments)) {
            return;
        }

        // Jika role user tidak sesuai dengan yang diizinkan
        if (!in_array($userRole, $arguments)) {
            // Redirect sesuai role
            return $this->redirectBasedOnRole($userRole);
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
                return redirect()->to(site_url('admin'))->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
            case 'pimpinan':
                return redirect()->to(site_url('pimpinan'))->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
            case 'pelanggan':
                return redirect()->to(site_url())->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
            default:
                return redirect()->to(site_url())->with('error', 'Anda tidak memiliki akses ke halaman tersebut');
        }
    }
}
