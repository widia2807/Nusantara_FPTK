<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false); // eksplisit & aman

// ==================
// WEB ROUTES
// ==================
$routes->get('/', 'PageController::index');
$routes->get('login', 'PageController::login');
$routes->get('logout', 'Auth::logout');

// Dashboard
$routes->group('dashboard', static function ($routes) {
    $routes->get('/', 'DashboardController::index');
    $routes->get('hr', 'DashboardController::hr');
    $routes->get('management', 'DashboardController::management');
    $routes->get('rekrutmen', 'DashboardController::rekrutmen');
    $routes->get('divisi', 'DashboardController::divisi');
});

// Users - WEB ROUTES (tambahkan ini)
$routes->group('users', static function ($routes) {
    $routes->get('create', 'Users::createForm');  // ✅ Route yang hilang!
    $routes->get('hr_history', 'DashboardController::hr_history'); 
});
$routes->get('history', 'DashboardController::div_history');
// History (khusus web / tampilan)
$routes->group('history', static function ($routes) {
    $routes->get('/', 'DashboardController::history');
    $routes->get('management', 'DashboardController::mng_history');
    $routes->get('divisi', 'DashboardController::div_history');
    $routes->get('rekrutmen', 'DashboardController::rek_history');
    $routes->get('hr', 'DashboardController::hr_history'); // ✅ arahkan ke DashboardController
});

// Pengajuan Form
$routes->get('pengajuan', 'PengajuanForm::index');
$routes->post('pengajuan/store', 'PengajuanForm::store');

// Auth change password
$routes->group('auth', static function ($routes) {
    $routes->get('change-password', 'Auth::changePasswordForm');
    $routes->post('change-password', 'Auth::changePassword');
});

// ==================
// API ROUTES
// ==================
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