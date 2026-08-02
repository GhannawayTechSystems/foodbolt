<?php

declare(strict_types=1);

/**
 * Public entry point. Autoloads the app classes, starts the session, seeds
 * demo data on first run, and dispatches the route.
 */

error_reporting(E_ALL);
ini_set('display_errors', '1');

session_start();

spl_autoload_register(function (string $class): void {
    $file = __DIR__ . '/../app/' . $class . '.php';
    if (is_file($file)) require $file;
});

require __DIR__ . '/../app/helpers.php';
require __DIR__ . '/../app/View.php';

$config = require __DIR__ . '/../app/config.php';

csrf_ensure();

$storage = new Storage($config['storage']);
(new Seeder($storage))->seedIfEmpty();

$kitchens = new Kitchen($storage);
$menu     = new MenuItem($storage);
$orders   = new Order($storage);
$cart     = new Cart();

$pub  = new PublicController($kitchens, $menu, $cart, $orders, $config);
$adm  = new AdminController($kitchens, $menu, $orders, $config);

$router = new Router();
$router->add('home/index',          fn() => $pub->home());
$router->add('kitchen/show',        fn() => $pub->kitchen());
$router->add('cart/add',            fn() => $pub->cartAdd());
$router->add('cart/index',          fn() => $pub->cart());
$router->add('cart/update',         fn() => $pub->cartUpdate());
$router->add('cart/remove',         fn() => $pub->cartRemove());
$router->add('cart/checkout',       fn() => $pub->checkout());
$router->add('order/place',         fn() => $pub->placeOrder());
$router->add('order/show',          fn() => $pub->order());

$router->add('admin/login',          fn() => $adm->login());
$router->add('admin/logout',         fn() => $adm->logout());
$router->add('admin/index',         fn() => $adm->dashboard());
$router->add('admin/kitchens',      fn() => $adm->kitchens());
$router->add('admin/kitchen/save',  fn() => $adm->kitchenSave());
$router->add('admin/kitchen/delete',fn() => $adm->kitchenDelete());
$router->add('admin/menu',          fn() => $adm->menu());
$router->add('admin/menu/save',     fn() => $adm->menuSave());
$router->add('admin/menu/delete',   fn() => $adm->menuDelete());
$router->add('admin/orders',        fn() => $adm->orders());
$router->add('admin/order/status',  fn() => $adm->orderStatus());
$router->add('admin/order/delete',  fn() => $adm->orderDelete());
$router->add('admin/kitchen/orders',fn() => $adm->kitchenOrders());

$router->dispatch((string) ($_GET['r'] ?? ''));
