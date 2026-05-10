<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */

// Redirection racine vers login
$routes->get('/', 'Auth::login');

// Auth
$routes->match(['get', 'post'], 'login',           'Auth::login');
$routes->match(['get', 'post'], 'register',        'Auth::register');
$routes->match(['get', 'post'], 'register/health', 'Auth::registerHealth');
$routes->get('logout', 'Auth::logout');

// Front office (protégé)
$routes->get('dashboard', 'Dashboard::index');

// Back office (protégé — à compléter plus tard)
// $routes->get('admin/dashboard', 'Admin\Dashboard::index');

$routes->get('profile', 'Profile::index');
$routes->post('profile/save-goal', 'Profile::saveGoal');

$routes->get('suggestions',  'Suggestions::index');
$routes->post('suggestions/buy', 'Suggestions::buy');

//pdf
$routes->get('export/preview/(:num)', 'ExportPdf::preview/$1');
$routes->get('export/pdf/(:num)',     'ExportPdf::generate/$1');