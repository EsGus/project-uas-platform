<?php
namespace Config;

use CodeIgniter\Config\Services;
use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes = Services::routes();

$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Product');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(false);

$routes->get('/', 'Product::index');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::doLogin');
$routes->get('logout', 'Auth::logout');
$routes->get('product/(:num)', 'Product::detail/$1');
$routes->get('order/create/(:num)', 'Order::create/$1', ['filter' => 'auth']);
$routes->post('order/store', 'Order::store', ['filter' => 'auth']);
$routes->post('order/upload_proof/(:any)', 'Order::uploadProof/$1', ['filter' => 'auth']);
$routes->get('register', 'Auth::register');
$routes->post('register', 'Auth::doRegister');

$routes->get('admin', 'Admin::index', ['filter' => 'auth']);
$routes->get('admin/approve/(:num)', 'Admin::approve/$1', ['filter' => 'auth']);
$routes->get('admin/ship/(:num)', 'Admin::ship/$1', ['filter' => 'auth']);
$routes->get('test', 'Test::index');

$routes->get('cart', 'Cart::index', ['filter' => 'auth']);
$routes->post('cart/add', 'Cart::add', ['filter' => 'auth']);
$routes->get('cart/remove/(:num)', 'Cart::remove/$1', ['filter' => 'auth']);

$routes->get('cart/checkout', 'Cart::checkout', ['filter' => 'auth']);
$routes->get('cart/payment', 'Cart::payment', ['filter' => 'auth']);

$routes->get('transactions', 'Transaction::index', ['filter' => 'auth']);

$routes->get('order/upload_proof/(:num)', 'Order::uploadProof/$1', ['filter' => 'auth']);
$routes->post('order/proof/(:num)', 'Order::postUploadProof/$1', ['filter' => 'auth']);

$routes->get('order/cancel_payment/(:num)', 'Order::cancelPayment/$1', ['filter' => 'auth']);
$routes->get('order/delete_transaction/(:num)', 'Order::deleteTransaction/$1', ['filter' => 'auth']);
$routes->get('admin/cancel_order/(:num)', 'Admin::cancelOrder/$1', ['filter' => 'auth']);

$routes->get('admin/delete_order/(:num)', 'Admin::deleteOrder/$1', ['filter' => 'auth']);