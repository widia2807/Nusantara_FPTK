<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // eksplisit & aman

$routes->get('/', 'PageController::index');
$routes->get('login', 'PageController::login');  // tanpa slash
$routes->get('dashboard', 'DashboardController::index');

$routes->get('dashboard/hr', 'DashboardController::hr');
$routes->get('dashboard/management', 'DashboardController::management');
$routes->get('dashboard/rekrutmen', 'DashboardController::rekrutmen');
$routes->get('dashboard/divisi', 'DashboardController::divisi');

$routes->get('users/create', 'Users::createForm'); // GET /users/create
$routes->get('logout', 'Auth::logout');

$routes->get('pengajuan', 'PengajuanForm::index');
$routes->post('pengajuan/store', 'PengajuanForm::store');

$routes->get('history', 'DashboardController::history');
$routes->get('users/hr_history', 'Users::hr_history');

$routes->get('auth/change-password', 'Auth::changePasswordForm');
$routes->post('auth/change-password', 'Auth::changePassword');




$routes->group('api', static function($routes) {
    // Preflight untuk semua /api/*
    $routes->options('(:any)', 'Auth::options');     // OPTIONS /api/*

    // Health check
    $routes->get('ping', 'Auth::ping');              // GET /api/ping

    // Auth
     $routes->post('login', 'Auth::login');

    // Dev util (hapus di produksi)
    $routes->get('dev/set-password', 'Auth::devSetPassword');

    $routes->post('users', 'Users::create'); // POST /api/users
     $routes->put('users/(:num)/change-password', 'Users::changePassword/$1');


     // DIVISI
    $routes->get('divisi', 'Divisi::index');
    $routes->post('divisi', 'Divisi::create');

    // POSISI
    $routes->get('posisi', 'Posisi::index');               // ambil semua posisi / by divisi
    $routes->post('posisi', 'Posisi::create');             // tambah posisi

    // PENGAJUAN
     $routes->get('pengajuan', 'Pengajuan::index'); 
    $routes->post('pengajuan', 'Pengajuan::create');       // buat pengajuan baru
    $routes->get('pengajuan/(:num)', 'Pengajuan::show/$1');// detail pengajuan
    $routes->put('pengajuan/(:num)/hr-review', 'Pengajuan::hrReview/$1');
    $routes->put('pengajuan/(:num)/management-review', 'Pengajuan::managementReview/$1');
    $routes->put('pengajuan/(:num)/rekrutmen-review', 'Pengajuan::rekrutmenReview/$1');
   // HISTORY
$routes->get('history', 'History::index');
    
});
