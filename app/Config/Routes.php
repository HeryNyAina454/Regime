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

// Front office
$routes->get('dashboard', 'Dashboard::index');

// Back office
$routes->get('admin/dashboard', 'Admin\Dashboard::index');

// Back office (protégé — à compléter plus tard)
// $routes->get('admin/dashboard', 'Admin\Dashboard::index');

$routes->get('profile', 'Profile::index');
$routes->post('profile/save-goal', 'Profile::saveGoal');

$routes->get('suggestions',  'Suggestions::index');
$routes->post('suggestions/buy', 'Suggestions::buy');

//pdf
$routes->get('export/preview/(:num)', 'ExportPdf::preview/$1');
$routes->get('export/pdf/(:num)',     'ExportPdf::generate/$1');

//code promo
$routes->get('wallet','Wallet::index');
$routes->post('wallet/recharge','Wallet::recharge');

//gold
$routes->get('gold','Gold::index');
$routes->post('gold/activate','Gold::activate');

//CRUD régimes (admin)
$routes->get('admin/regimes','Admin\Regimes::index');
$routes->post('admin/regimes/store','Admin\Regimes::store');
$routes->post('admin/regimes/update/(:num)', 'Admin\Regimes::update/$1');
$routes->get('admin/regimes/delete/(:num)', 'Admin\Regimes::delete/$1');

// Activités
$routes->get('admin/activities','Admin\Activities::index');
$routes->post('admin/activities/store','Admin\Activities::store');
$routes->post('admin/activities/update/(:num)','Admin\Activities::update/$1');
$routes->get('admin/activities/delete/(:num)','Admin\Activities::delete/$1');

// Codes
$routes->get('admin/codes','Admin\Codes::index');
$routes->post('admin/codes/store','Admin\Codes::store');
$routes->get('admin/codes/validate/(:num)','Admin\Codes::validate_code/$1');
$routes->get('admin/codes/delete/(:num)','Admin\Codes::delete/$1');

// Paramètres
$routes->get('admin/settings','Admin\Settings::index');
$routes->post('admin/settings/update','Admin\Settings::update');
$routes->post('admin/settings/store','Admin\Settings::store');
$routes->get('admin/settings/delete/(:num)','Admin\Settings::delete/$1');