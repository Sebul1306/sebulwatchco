<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Rute utama (home) - dashboard jika login, preview jika belum
$routes->get("/", "Home::index");

// Rute autentikasi
$routes->get("login", "AuthController::login");
$routes->post("login", "AuthController::login");
$routes->get("register", "AuthController::register");
$routes->post("register", "AuthController::register");
$routes->get("logout", "AuthController::logout");
$routes->match(['get', 'post'], "auth/google", "AuthController::oauthGoogle");
$routes->get("auth/google/callback", "AuthController::oauthGoogleCallback");
$routes->match(['get', 'post'], "auth/facebook", "AuthController::oauthFacebook/login");
$routes->match(['get', 'post'], "auth/facebook/register", "AuthController::oauthFacebook/register");

$routes->group("produk", ["filter" => "auth"], function ($routes) {
    $routes->get("", "ProdukController::index");
    $routes->post("", "ProdukController::create");
    $routes->post("edit/(:num)", 'ProdukController::edit/$1');
    $routes->post("restock/(:num)", 'ProdukController::restock/$1');
    $routes->get("delete/(:num)", 'ProdukController::delete/$1');
    $routes->get("export-pdf", "ProdukController::exportPdf");
    $routes->get("export-excel", "ProdukController::exportExcel");
});

// Laporan Keuangan
$routes->group('laporan', ['filter' => 'auth'], function ($routes) {
    $routes->get('pendapatan', 'LaporanController::pendapatan');
    $routes->get('terlaris', 'LaporanController::terlaris');
    $routes->get('piutang', 'LaporanController::piutang');
    $routes->get('hutang', 'LaporanController::hutang');
    $routes->get('bayarHutang/(:num)', 'LaporanController::bayarHutang/$1');
    $routes->get('arus-kas', 'LaporanController::arusKas');
    $routes->get('laba-rugi', 'LaporanController::labaRugi');
    $routes->get('exportPdf', 'LaporanController::exportPdf');
    $routes->get('exportExcel', 'LaporanController::exportExcel');
    $routes->get('exportHutangPdf', 'LaporanController::exportHutangPdf');
    $routes->get('exportHutangExcel', 'LaporanController::exportHutangExcel');
});

// Chat
$routes->group('chat', ['filter' => 'auth'], function ($routes) {
    $routes->get('', 'ChatController::index');
    $routes->get('getMessages/(:num)', 'ChatController::getMessages/$1');
    $routes->post('sendMessage', 'ChatController::sendMessage');
    $routes->get('getUnread', 'ChatController::getUnread');
    $routes->get('getCustomers', 'ChatController::getCustomers');
    $routes->get('getProducts', 'ChatController::getProducts');
});

$routes->post("review", "ProdukController::review", ["filter" => "auth"]);
$routes->get("produk/reset_casio", "ProdukController::reset_casio");

// Rute produk - dilindungi filter auth
$routes->get("produk", "ProdukController::index", ["filter" => "auth"]);
// Rute keranjang - dilindungi filter auth
$routes->group("keranjang", ["filter" => "auth"], function ($routes) {
    $routes->get("", "TransaksiController::index");
    $routes->post("add/(:num)", "TransaksiController::add/$1");
    $routes->get("remove/(:any)", "TransaksiController::remove/$1");
    $routes->post("update", "TransaksiController::update");
    $routes->get("clear", "TransaksiController::clear");
    $routes->get("download", "TransaksiController::download");
});

$routes->get("checkout", "TransaksiController::checkout", ["filter" => "auth"]);
$routes->post("buy", "TransaksiController::buy", ["filter" => "auth"]);
$routes->get("get-location", "TransaksiController::getLocation", ["filter" => "auth"]);
$routes->get("get-cost", "TransaksiController::getCost", ["filter" => "auth"]);
$routes->post('upload-bukti', 'TransaksiController::uploadBukti', ["filter" => "auth"]);
$routes->post('api/payment/callback', 'TransaksiController::paymentCallback');

$routes->group("ongkir", ["filter" => "auth"], function ($routes) {
    $routes->get("", "OngkirController::index");
    $routes->get("lokasi", "OngkirController::lokasi");
    $routes->post("biaya", "OngkirController::biaya");
});

$routes->get("settings", "SettingsController::index", ["filter" => "auth"]);
$routes->post("settings/upload-qris", "SettingsController::uploadQris", ["filter" => "auth"]);
$routes->post("settings/upload-logo", "SettingsController::uploadLogo", ["filter" => "auth"]);
$routes->post("settings/update-address", "SettingsController::updateAddress", ["filter" => "auth"]);

$routes->get("faq", "Home::faq", ["filter" => "auth"]);
$routes->get("profile", "Home::profile", ["filter" => "auth"]);
$routes->get("invoice/(:num)", "Home::invoice/$1", ["filter" => "auth"]);
$routes->get('transaction/complete/(:num)', 'Home::completeTransaction/$1', ['filter' => 'auth']);
$routes->get('transaction/pay/(:num)', 'Home::payTransaction/$1', ['filter' => 'auth']);
$routes->get("contact", "Home::contact", ["filter" => "auth"]);
$routes->get('penjualan', 'Home::penjualan', ['filter' => 'auth']);
$routes->get('penjualan/export-pdf', 'TransaksiController::exportPdf', ['filter' => 'auth']);
$routes->get('penjualan/export-excel', 'TransaksiController::exportExcel', ['filter' => 'auth']);
$routes->post('penjualan/updateStatus/(:any)', 'TransaksiController::updateStatus/$1', ['filter' => 'auth']);

$routes->get('laporan/pendapatan', 'LaporanController::pendapatan', ['filter' => 'auth']);
$routes->get('laporan/exportPdf', 'LaporanController::exportPdf', ['filter' => 'auth']);
$routes->get('laporan/exportExcel', 'LaporanController::exportExcel', ['filter' => 'auth']);

// Routes untuk Supplier
$routes->group("supplier", ["filter" => "auth"], function ($routes) {
    $routes->get("", "SupplierController::index");
    $routes->post("add", "SupplierController::add");
    $routes->post("edit/(:num)", "SupplierController::edit/$1");
    $routes->get("delete/(:num)", "SupplierController::delete/$1");
});

$routes->get('dashboard', 'Dashboard::index', ['filter' => 'auth']);
$routes->get('beban', 'BebanController::index', ['filter' => 'auth']);
$routes->post('beban/add', 'BebanController::add', ['filter' => 'auth']);
$routes->get('beban/delete/(:num)', 'BebanController::delete/$1', ['filter' => 'auth']);

$routes->get('laporan/produk-terlaris', 'LaporanController::produkTerlaris', ['filter' => 'auth']);
$routes->get('laporan/terlaris/export-pdf', 'LaporanController::exportTerlarisPdf', ['filter' => 'auth']);
$routes->get('laporan/terlaris/export-excel', 'LaporanController::exportTerlarisExcel', ['filter' => 'auth']);

$routes->get('laporan/piutang', 'LaporanController::piutang', ['filter' => 'auth']);
$routes->get('laporan/piutang/export-pdf', 'LaporanController::exportPiutangPdf', ['filter' => 'auth']);
$routes->get('laporan/piutang/export-excel', 'LaporanController::exportPiutangExcel', ['filter' => 'auth']);

$routes->get('laporan/arus-kas', 'LaporanController::arusKas', ['filter' => 'auth']);
$routes->get('laporan/arus-kas/export-pdf', 'LaporanController::exportArusKasPdf', ['filter' => 'auth']);
$routes->get('laporan/arus-kas/export-excel', 'LaporanController::exportArusKasExcel', ['filter' => 'auth']);

$routes->get('laporan/laba-rugi', 'LaporanController::labaRugi', ['filter' => 'auth']);
$routes->get('laporan/laba-rugi/export-pdf', 'LaporanController::exportLabaRugiPdf', ['filter' => 'auth']);
$routes->get('laporan/laba-rugi/export-excel', 'LaporanController::exportLabaRugiExcel', ['filter' => 'auth']);

$routes->resource('api', ['controller' => 'ApiController']);
