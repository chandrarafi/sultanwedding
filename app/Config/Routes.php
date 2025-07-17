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

// Debug routes can be added here if needed in the future

// Admin Routes
$routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
    $routes->get('/', 'Admin\Dashboard::index');
    $routes->get('profile', 'Admin\Profile::index');
    $routes->post('profile/update', 'Admin\Profile::update');
    $routes->post('profile/update-password', 'Admin\Profile::updatePassword');

    // Utility
    $routes->get('utility/migrate-files', 'MigrateFileUploads::index');

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

    // Laporan Routes
    $routes->get('laporan/paket', 'Admin\Laporan::paket');
    $routes->get('laporan/cetakPaket', 'Admin\Laporan::cetakPaket');
    $routes->get('laporan/barang', 'Admin\Laporan::barang');
    $routes->get('laporan/cetakBarang', 'Admin\Laporan::cetakBarang');
    $routes->get('laporan/pelanggan', 'Admin\Laporan::pelanggan');
    $routes->get('laporan/cetakPelanggan', 'Admin\Laporan::cetakPelanggan');
    // Tambahkan rute baru untuk laporan pemesanan
    $routes->get('laporan/pemesananPaket', 'Admin\Laporan::pemesananPaket');
    $routes->get('laporan/cetakLaporanPaket', 'Admin\Laporan::cetakLaporanPaket');
    $routes->get('laporan/pemesananBarang', 'Admin\Laporan::pemesananBarang');
    $routes->get('laporan/cetakLaporanBarang', 'Admin\Laporan::cetakLaporanBarang');
    $routes->get('laporan/pembayaran', 'Admin\Laporan::pembayaran');
    $routes->get('laporan/cetakLaporanPembayaran', 'Admin\Laporan::cetakLaporanPembayaran');

    // Routes untuk laporan bulanan
    $routes->get('laporan/pemesananPaketBulanan', 'Admin\Laporan::pemesananPaketBulanan');
    $routes->get('laporan/cetakLaporanPaketBulanan', 'Admin\Laporan::cetakLaporanPaketBulanan');
    $routes->get('laporan/pemesananBarangBulanan', 'Admin\Laporan::pemesananBarangBulanan');
    $routes->get('laporan/cetakLaporanBarangBulanan', 'Admin\Laporan::cetakLaporanBarangBulanan');
    $routes->get('laporan/pembayaranBulanan', 'Admin\Laporan::pembayaranBulanan');
    $routes->get('laporan/cetakLaporanPembayaranBulanan', 'Admin\Laporan::cetakLaporanPembayaranBulanan');

    // Routes untuk laporan tahunan
    $routes->get('laporan/pemesananPaketTahunan', 'Admin\Laporan::pemesananPaketTahunan');
    $routes->get('laporan/cetakLaporanPaketTahunan', 'Admin\Laporan::cetakLaporanPaketTahunan');
    $routes->get('laporan/pemesananBarangTahunan', 'Admin\Laporan::pemesananBarangTahunan');
    $routes->get('laporan/cetakLaporanBarangTahunan', 'Admin\Laporan::cetakLaporanBarangTahunan');
    $routes->get('laporan/pembayaranTahunan', 'Admin\Laporan::pembayaranTahunan');
    $routes->get('laporan/cetakLaporanPembayaranTahunan', 'Admin\Laporan::cetakLaporanPembayaranTahunan');

    // Routes untuk laporan data master
    $routes->get('laporan/pelanggan', 'Admin\Laporan::pelanggan');
    $routes->get('laporan/cetakLaporanPelanggan', 'Admin\Laporan::cetakLaporanPelanggan');
    $routes->get('laporan/dataBarang', 'Admin\Laporan::dataBarang');
    $routes->get('laporan/cetakLaporanDataBarang', 'Admin\Laporan::cetakLaporanDataBarang');

    // PemesananPaket routes
    $routes->get('pemesananpaket', 'Admin\PemesananPaket::index');
    $routes->get('pemesananpaket/create', 'Admin\PemesananPaket::create');
    $routes->get('pemesananpaket/detail/(:any)', 'Admin\PemesananPaket::detail/$1');
    $routes->post('pemesananpaket/update-status/(:any)', 'Admin\PemesananPaket::updateStatus/$1');
    $routes->post('pemesananpaket/cancel/(:any)', 'Admin\PemesananPaket::cancel/$1');
    $routes->post('pemesananpaket/store', 'Admin\PemesananPaket::store');
    $routes->get('pemesananpaket/edit/(:any)', 'Admin\PemesananPaket::edit/$1');
    $routes->post('pemesananpaket/update/(:any)', 'Admin\PemesananPaket::update/$1');
    $routes->get('pemesananpaket/delete/(:any)', 'Admin\PemesananPaket::delete/$1');

    // PemesananBarang routes
    $routes->get('pemesananbarang', 'Admin\PemesananBarang::index');
    $routes->get('pemesananbarang/create', 'Admin\PemesananBarang::create');
    $routes->get('pemesananbarang/detail/(:num)', 'Admin\PemesananBarang::detail/$1');
    $routes->get('pemesananbarang/edit/(:num)', 'Admin\PemesananBarang::edit/$1');
    $routes->post('pemesananbarang/store', 'Admin\PemesananBarang::store');
    $routes->post('pemesananbarang/update/(:num)', 'Admin\PemesananBarang::update/$1');
    $routes->delete('pemesananbarang/delete/(:num)', 'Admin\PemesananBarang::delete/$1');
    $routes->get('pemesananbarang/getAll', 'Admin\PemesananBarang::getAll');
    $routes->get('pemesananbarang/getBarang', 'Admin\PemesananBarang::getBarang');
    $routes->get('pemesananbarang/getPelanggan', 'Admin\PemesananBarang::getPelanggan');
    $routes->post('pemesananbarang/bayar-h1/(:num)', 'Admin\PemesananBarang::bayarH1/$1');
    $routes->post('pemesananbarang/bayar-pelunasan/(:num)', 'Admin\PemesananBarang::bayarPelunasan/$1');

    // Pemesanan routes (for walk-in customers)
    $routes->get('pemesanan', 'Admin\PemesananPaket::index');
    $routes->get('pemesanan/create', 'Admin\PemesananPaket::create');
    $routes->post('pemesanan/store', 'Admin\PemesananPaket::store');
    $routes->get('pemesanan/detail/(:any)', 'Admin\PemesananPaket::detail/$1');
    $routes->get('pemesanan/edit/(:any)', 'Admin\PemesananPaket::edit/$1');
    $routes->post('pemesanan/update/(:any)', 'Admin\PemesananPaket::update/$1');
    $routes->get('pemesanan/delete/(:any)', 'Admin\PemesananPaket::delete/$1');
    $routes->get('pemesanan/cancel/(:any)', 'Admin\PemesananPaket::cancel/$1');

    // Pembayaran routes
    $routes->get('pembayaran', 'Admin\Pembayaran::index');
    $routes->get('pembayaran/detail/(:any)', 'Admin\Pembayaran::detail/$1');
    $routes->post('pembayaran/konfirmasi-dp/(:any)', 'Admin\Pembayaran::konfirmasiDP/$1');
    $routes->post('pembayaran/konfirmasi-h1/(:any)', 'Admin\Pembayaran::konfirmasiH1/$1');
    $routes->post('pembayaran/konfirmasi-pelunasan/(:any)', 'Admin\Pembayaran::konfirmasiPelunasan/$1');
    $routes->post('pembayaran/tolak/(:any)', 'Admin\Pembayaran::tolak/$1');
    $routes->get('pembayaran/bayar-h1/(:any)', 'Admin\Pembayaran::bayarH1/$1');
    $routes->get('pembayaran/bayar-pelunasan/(:any)', 'Admin\Pembayaran::bayarPelunasan/$1');

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
    $routes->get('barang/history/(:num)', 'Admin\Barang::history/$1');

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

// Self-service routes
$routes->group('sewa', static function ($routes) {
    $routes->get('/', 'Home::barang');
    $routes->get('barang', 'Home::barang');
    $routes->get('barang/(:num)', 'Home::barangDetail/$1');
});

// Tambahkan route untuk pemesanan paket
$routes->group('pelanggan', ['filter' => 'role:pelanggan'], function ($routes) {
    $routes->get('/', 'Pelanggan\Dashboard::index');
    $routes->get('profile', 'Pelanggan\Profile::index');
    $routes->post('profile/update', 'Pelanggan\Profile::update');
    $routes->post('profile/update-password', 'Pelanggan\Profile::updatePassword');

    // Pemesanan paket routes
    $routes->get('pemesanan/paket/(:num)', 'Pelanggan\PemesananController::pemesanPaket/$1');
    $routes->post('pemesanan/process', 'Pelanggan\PemesananController::processPemesanan');
    $routes->get('pemesanan/pembayaran/(:any)', 'Pelanggan\PemesananController::pembayaran/$1');
    $routes->get('pemesanan/bayar/(:any)', 'Pelanggan\PemesananController::pembayaran/$1'); // Route alternatif
    $routes->post('pemesanan/pembayaran/upload', 'Pelanggan\PemesananController::uploadBuktiPembayaran');
    $routes->post('pemesanan/upload-bukti', 'Pelanggan\PemesananController::uploadBuktiPembayaran');
    $routes->post('pemesanan/pembayaran/h1', 'Pelanggan\PemesananController::processH1Payment');
    $routes->post('pemesanan/pembayaran/full', 'Pelanggan\PemesananController::processFullPayment');
    $routes->get('pemesanan', 'Pelanggan\PemesananController::daftarPemesanan');
    $routes->get('pemesanan/check-status/(:any)', 'Pelanggan\PemesananController::checkPaymentStatus/$1');

    // Notifikasi Routes
    $routes->get('pemesanan/check-rejected-payments', 'Pelanggan\Notifikasi::checkRejectedPayments');
    $routes->get('notifikasi', 'Pelanggan\Notifikasi::index');
});

// Pemesanan Barang
$routes->group('admin/pemesananbarang', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Admin\PemesananBarang::index');
    $routes->get('getAll', 'Admin\PemesananBarang::getAll');
    $routes->get('create', 'Admin\PemesananBarang::create');
    $routes->post('store', 'Admin\PemesananBarang::store');
    $routes->get('edit/(:segment)', 'Admin\PemesananBarang::edit/$1');
    $routes->post('update/(:segment)', 'Admin\PemesananBarang::update/$1');
    $routes->post('delete/(:segment)', 'Admin\PemesananBarang::delete/$1');
    $routes->get('detail/(:segment)', 'Admin\PemesananBarang::detail/$1');
    $routes->get('getBarang', 'Admin\PemesananBarang::getBarang');
    $routes->get('getPelanggan', 'Admin\PemesananBarang::getPelanggan');
    $routes->get('bayarPelunasan/(:segment)', 'Admin\PemesananBarang::bayarPelunasan/$1');
    $routes->get('cetakFaktur/(:segment)', 'Admin\PemesananBarang::cetakFaktur/$1');
    $routes->get('lihatFaktur/(:segment)', 'Admin\PemesananBarang::lihatFaktur/$1');
    $routes->get('pengembalian', 'Admin\PemesananBarang::pengembalian');
    $routes->get('prosesPengembalian/(:segment)', 'Admin\PemesananBarang::prosesPengembalian/$1');
    $routes->post('simpanPengembalian/(:segment)', 'Admin\PemesananBarang::simpanPengembalian/$1');
    $routes->get('completeStatus/(:segment)', 'Admin\PemesananBarang::completeStatus/$1');
});
