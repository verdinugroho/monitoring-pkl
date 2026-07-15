<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Public Routes (Auth)
$routes->get('/login', 'Auth::login');
$routes->get('/register', 'Auth::register');
$routes->post('/auth/processLogin', 'Auth::processLogin', ['filter' => 'csrf']);
$routes->post('/auth/processRegister', 'Auth::processRegister', ['filter' => 'csrf']);
$routes->get('/logout', 'Auth::logout');

// Authenticated Routes (Protected by AuthFilter)
$routes->group('', ['filter' => 'auth'], function ($routes) {
    $routes->get('/', 'Dashboard::index');
    $routes->get('/dashboard', 'Dashboard::index');
    $routes->get('/setup-pkl', 'Dashboard::setupPkl');
    $routes->post('/setup-pkl/store', 'Dashboard::storePkl', ['filter' => 'csrf']);

    // 1. Mahasiswa Routes (Only Mahasiswa Role)
    $routes->group('', ['filter' => 'role:mahasiswa'], function ($routes) {
        $routes->get('/logbook', 'LogbookController::index');
        $routes->get('/logbook/create', 'LogbookController::create');
        $routes->post('/logbook/store', 'LogbookController::store', ['filter' => 'csrf']);
        $routes->get('/logbook/edit/(:num)', 'LogbookController::edit/$1');
        $routes->post('/logbook/update/(:num)', 'LogbookController::update/$1', ['filter' => 'csrf']);
        $routes->get('/logbook/delete/(:num)', 'LogbookController::delete/$1');

        $routes->get('/documents', 'DocumentController::index');
        $routes->post('/documents/upload', 'DocumentController::upload', ['filter' => 'csrf']);
        $routes->get('/documents/delete/(:num)', 'DocumentController::delete/$1');
    });

    // 2. Dosen Routes (Only Dosen Role)
    $routes->group('', ['filter' => 'role:dosen'], function ($routes) {
        $routes->get('/bimbingan', 'Dosen::bimbingan');
        $routes->get('/bimbingan/detail/(:num)', 'Dosen::detail/$1');
        $routes->get('/bimbingan/logbook/(:num)', 'Dosen::logbookDetails/$1');
        $routes->post('/bimbingan/logbook/comment', 'Dosen::addComment', ['filter' => 'csrf']);
        $routes->post('/bimbingan/logbook/status', 'Dosen::updateLogbookStatus', ['filter' => 'csrf']);
        $routes->post('/bimbingan/assess', 'Dosen::submitAssessment', ['filter' => 'csrf']);
        $routes->get('/bimbingan/export-pdf/(:num)', 'Dosen::exportPdf/$1');
        $routes->get('/bimbingan/penilaian', 'Dosen::penilaian');
    });

    // 3. Admin Routes (Only Admin Role)
    $routes->group('admin', ['filter' => 'role:admin'], function ($routes) {
        $routes->get('dashboard', 'Admin::dashboard');
        $routes->get('penilaian', 'Admin::penilaian');
        // CRUD Mahasiswa
        $routes->get('mahasiswa', 'Admin::mahasiswa');
        $routes->get('mahasiswa/create', 'Admin::createMahasiswa');
        $routes->post('mahasiswa/store', 'Admin::storeMahasiswa', ['filter' => 'csrf']);
        $routes->get('mahasiswa/edit/(:num)', 'Admin::editMahasiswa/$1');
        $routes->post('mahasiswa/update/(:num)', 'Admin::updateMahasiswa/$1', ['filter' => 'csrf']);
        $routes->get('mahasiswa/delete/(:num)', 'Admin::deleteMahasiswa/$1');
        $routes->get('mahasiswa/detail/(:num)', 'Admin::detailMahasiswa/$1');
        $routes->get('mahasiswa/toggle/(:num)', 'Admin::toggleStatusMahasiswa/$1');
        $routes->get('mahasiswa/reset-password/(:num)', 'Admin::resetPasswordMahasiswa/$1');
        // CRUD Dosen
        $routes->get('dosen', 'Admin::dosen');
        $routes->get('dosen/create', 'Admin::createDosen');
        $routes->post('dosen/store', 'Admin::storeDosen', ['filter' => 'csrf']);
        $routes->get('dosen/edit/(:num)', 'Admin::editDosen/$1');
        $routes->post('dosen/update/(:num)', 'Admin::updateDosen/$1', ['filter' => 'csrf']);
        $routes->get('dosen/delete/(:num)', 'Admin::deleteDosen/$1');
        $routes->get('dosen/detail/(:num)', 'Admin::detailDosen/$1');
        $routes->get('dosen/toggle/(:num)', 'Admin::toggleStatusDosen/$1');
        $routes->get('dosen/reset-password/(:num)', 'Admin::resetPasswordDosen/$1');
    });
});

// REST API Routes (Protected or Public as per requirements)
$routes->group('api', function ($routes) {
    // Internships
    $routes->get('internships', 'Api\InternshipApiController::index');
    $routes->get('internships/(:num)', 'Api\InternshipApiController::show/$1');

    // Logbooks
    $routes->get('logbooks', 'Api\LogbookApiController::index');
    $routes->get('logbooks/(:num)', 'Api\LogbookApiController::show/$1');
    $routes->post('logbooks', 'Api\LogbookApiController::create');
    $routes->put('logbooks/(:num)', 'Api\LogbookApiController::update/$1');
    $routes->delete('logbooks/(:num)', 'Api\LogbookApiController::delete/$1');

    // Assessments
    $routes->get('assessments', 'Api\AssessmentApiController::index');
    $routes->post('assessments', 'Api\AssessmentApiController::create');

    // Documents
    $routes->get('documents', 'Api\DocumentApiController::index');
    $routes->post('documents', 'Api\DocumentApiController::create');
    $routes->delete('documents/(:num)', 'Api\DocumentApiController::delete/$1');
});