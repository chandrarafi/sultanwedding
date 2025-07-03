<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('auth', 'Auth::index');
$routes->post('auth/login', 'Auth::login');
$routes->get('auth/logout', 'Auth::logout');

// Admin Routes
$routes->group('admin', ['filter' => 'auth:admin'], function ($routes) {
    $routes->get('/', 'Admin::index');

    // User Management (hanya admin)
    $routes->group('', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('users', 'Admin::users');
        $routes->get('getUsers', 'Admin::getUsers');
        $routes->get('getUser/(:num)', 'Admin::getUser/$1');
        $routes->post('createUser', 'Admin::createUser');
        $routes->post('addUser', 'Admin::addUser');
        $routes->post('updateUser/(:num)', 'Admin::updateUser/$1');
        $routes->post('deleteUser/(:num)', 'Admin::deleteUser/$1');
        $routes->get('getRoles', 'Admin::getRoles');
    });

    // Kategori routes
    $routes->get('kategori', 'Admin\Kategori::index');
    $routes->get('kategori/getAll', 'Admin\Kategori::getAll');
    $routes->get('kategori/getById/(:num)', 'Admin\Kategori::getById/$1');
    $routes->post('kategori/create', 'Admin\Kategori::create');
    $routes->post('kategori/update/(:num)', 'Admin\Kategori::update/$1');
    $routes->delete('kategori/delete/(:num)', 'Admin\Kategori::delete/$1');

    // Barang routes
    $routes->get('barang', 'Admin\Barang::index');
    $routes->get('barang/getAll', 'Admin\Barang::getAll');
    $routes->get('barang/getById/(:num)', 'Admin\Barang::getById/$1');
    $routes->post('barang/create', 'Admin\Barang::create');
    $routes->post('barang/update/(:num)', 'Admin\Barang::update/$1');
    $routes->delete('barang/delete/(:num)', 'Admin\Barang::delete/$1');

    // Paket routes
    $routes->get('paket', 'Admin\Paket::index');
    $routes->get('paket/getAll', 'Admin\Paket::getAll');
    $routes->get('paket/create', 'Admin\Paket::create');
    $routes->post('paket/store', 'Admin\Paket::store');
    $routes->get('paket/edit/(:num)', 'Admin\Paket::edit/$1');
    $routes->post('paket/update/(:num)', 'Admin\Paket::update/$1');
    $routes->delete('paket/delete/(:num)', 'Admin\Paket::delete/$1');
    $routes->get('paket/detail/(:num)', 'Admin\Paket::detail/$1');
});
