<?php
declare(strict_types=1);
session_start();
require dirname(path: __DIR__) . '/vendor/autoload.php';

use Mini\Core\Router;

// Table des routes minimaliste
$routes = [
    ['GET', '/', [Mini\Controllers\HomeController::class, 'index']],
    ['GET', '/users', [Mini\Controllers\HomeController::class, 'users']],
    ['POST', '/users', [Mini\Controllers\HomeController::class, 'createUser']],
    ['GET', '/users/create', [Mini\Controllers\HomeController::class, 'showCreateUserForm']],
    ['GET', '/login', [Mini\Controllers\AuthController::class, 'showLoginForm']],
    ['POST', '/login', [Mini\Controllers\AuthController::class, 'login']],
    ['POST', '/logout', [Mini\Controllers\AuthController::class, 'logout']],
    ['GET', '/products', [Mini\Controllers\ProductController::class, 'listProducts']],
    ['GET', '/products/create', [Mini\Controllers\ProductController::class, 'showCreateProductForm']],
    ['GET', '/products/{id}', [Mini\Controllers\ProductController::class, 'showProduct']],
    ['POST', '/products', [Mini\Controllers\ProductController::class, 'createProduct']],

    // Panier
    ['GET', '/cart', [Mini\Controllers\CartController::class, 'index']],
    ['POST', '/cart/add', [Mini\Controllers\CartController::class, 'add']],
    ['POST', '/cart/remove', [Mini\Controllers\CartController::class, 'remove']],
    ['POST', '/checkout', [Mini\Controllers\OrderController::class, 'create']],

    // Commandes
    ['GET', '/orders', [Mini\Controllers\OrderController::class, 'index']],
    ['GET', '/orders/{id}', [Mini\Controllers\OrderController::class, 'show']],
];
// Bootstrap du router
$router = new Router($routes);
$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);