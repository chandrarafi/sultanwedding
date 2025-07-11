<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Landing Page Routes (Public)
$routes->get('/', 'Home::index');
$routes->get('about', 'Home::about');
$routes->get('paket', 'Home::paket');
$routes->get('paket/(:num)', 'Home::paketDetail/$1');
$routes->get('barang', 'Home::barang');
$routes->get('barang/(:num)', 'Home::barangDetail/$1');
$routes->get('galeri', 'Home::galeri');
$routes->get('kontak', 'Home::kontak');

// Auth Routes
$routes->group('auth', function ($routes) {
    $routes->get('/', 'Auth::index');
    $routes->get('login', 'Auth::login');
    $routes->post('loginProcess', 'Auth::loginProcess');
    $routes->get('logout', 'Auth::logout');
    $routes->get('register', 'Auth::register');
    $routes->post('registerProcess', 'Auth::registerProcess');
    $routes->get('verify/(:num)', 'Auth::verify/$1');
    $routes->post('verifyProcess', 'Auth::verifyProcess');
    $routes->post('resendOTP', 'Auth::resendOTP');
});

// Admin Routes
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Admin::index');

    // User Management (hanya admin)
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('users', 'Admin::users');
        $routes->get('getUsers', 'Admin::getUsers');
        $routes->post('users/getAll', 'Admin::getUsers'); // Route untuk DataTables
        $routes->post('getAllUsers', 'Admin::getAllUsers'); // Alias untuk DataTables
        $routes->get('getUser/(:num)', 'Admin::getUser/$1');
        $routes->get('users/getById/(:num)', 'Admin::getUser/$1'); // Route untuk detail user
        $routes->post('createUser', 'Admin::createUser');
        $routes->post('users/create', 'Admin::createUser'); // Route untuk create user
        $routes->post('addUser', 'Admin::addUser');
        $routes->post('updateUser/(:num)', 'Admin::updateUser/$1');
        $routes->post('users/update/(:num)', 'Admin::updateUser/$1'); // Route untuk update user
        $routes->post('deleteUser/(:num)', 'Admin::deleteUser/$1');
        $routes->delete('users/delete/(:num)', 'Admin::deleteUser/$1'); // Route untuk delete user
        $routes->get('getRoles', 'Admin::getRoles');
    });

    // Kategori routes
    $routes->get('kategori', 'Admin\Kategori::index');
    $routes->get('kategori/getAll', 'Admin\Kategori::getAll');
    $routes->post('kategori/getAll', 'Admin\Kategori::getAll');
    $routes->get('kategori/getById/(:num)', 'Admin\Kategori::getById/$1');
    $routes->post('kategori/create', 'Admin\Kategori::create');
    $routes->post('kategori/update/(:num)', 'Admin\Kategori::update/$1');
    $routes->post('kategori/save', 'Admin\Kategori::save');
    $routes->post('kategori/edit', 'Admin\Kategori::edit');
    $routes->post('kategori/delete', 'Admin\Kategori::delete');
    $routes->delete('kategori/delete/(:num)', 'Admin\Kategori::delete/$1');

    // Barang routes
    $routes->get('barang', 'Admin\Barang::index');
    $routes->get('barang/getAll', 'Admin\Barang::getAll');
    $routes->post('barang/getAll', 'Admin\Barang::getAll');
    $routes->get('barang/getById/(:num)', 'Admin\Barang::getById/$1');
    $routes->post('barang/create', 'Admin\Barang::create');
    $routes->post('barang/update/(:num)', 'Admin\Barang::update/$1');
    $routes->delete('barang/delete/(:num)', 'Admin\Barang::delete/$1');

    // Paket routes
    $routes->get('paket', 'Admin\Paket::index');
    $routes->get('paket/getAll', 'Admin\Paket::getAll');
    $routes->post('paket/getAll', 'Admin\Paket::getAll');
    $routes->get('paket/create', 'Admin\Paket::create');
    $routes->post('paket/store', 'Admin\Paket::store');
    $routes->get('paket/edit/(:num)', 'Admin\Paket::edit/$1');
    $routes->post('paket/update/(:num)', 'Admin\Paket::update/$1');
    $routes->post('paket/delete/(:num)', 'Admin\Paket::delete/$1');
    $routes->get('paket/detail/(:num)', 'Admin\Paket::detail/$1');
    $routes->get('paket/getDetailPaket/(:num)', 'Admin\Paket::getDetailPaket/$1');
    $routes->get('paket/getBarang', 'Admin\Paket::getBarang');
    $routes->get('paket/getBarang/(:num)', 'Admin\Paket::getBarang/$1');
    $routes->get('paket/getAllBarang', 'Admin\Paket::getAllBarang');

    // Pelanggan routes
    $routes->get('pelanggan', 'Admin\Pelanggan::index');
    $routes->get('pelanggan/getAll', 'Admin\Pelanggan::getAll');
    $routes->post('pelanggan/getAll', 'Admin\Pelanggan::getAll');
    $routes->get('pelanggan/getById/(:num)', 'Admin\Pelanggan::getById/$1');
    $routes->post('pelanggan/save', 'Admin\Pelanggan::save');
    $routes->post('pelanggan/delete', 'Admin\Pelanggan::delete');
    $routes->delete('pelanggan/delete/(:num)', 'Admin\Pelanggan::delete/$1');
});

// Pimpinan Routes
$routes->group('pimpinan', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Pimpinan::index');

    // Laporan routes
    $routes->get('laporan/penjualan', 'Pimpinan::laporanPenjualan');
    $routes->get('laporan/barang', 'Pimpinan::laporanBarang');
    $routes->get('laporan/pelanggan', 'Pimpinan::laporanPelanggan');
});

// Fitur Pelanggan - Memerlukan login
$routes->group('', ['filter' => 'auth'], function ($routes) {
    // Profil
    $routes->get('profile', 'Pelanggan::profile');
    $routes->post('updateProfile', 'Pelanggan::updateProfile');

    // Pemesanan
    $routes->get('pemesanan', 'Pelanggan::pemesanan');
    $routes->get('pemesanan/create', 'Pelanggan::createPemesanan');
    $routes->post('pemesanan/store', 'Pelanggan::storePemesanan');
    $routes->get('pemesanan/detail/(:num)', 'Pelanggan::detailPemesanan/$1');

    // Lainnya
    $routes->get('dashboard', 'Pelanggan::index');
});
