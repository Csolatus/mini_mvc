<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Order;

class OrderController extends Controller
{
    private function ensureLoggedIn()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
    }

    public function index()
    {
        $this->ensureLoggedIn();
        $user = $_SESSION['user'];
        $orders = Order::findByUserId($user['id']);

        $this->render('orders/index', [
            'orders' => $orders
        ]);
    }

    public function show($id)
    {
        $this->ensureLoggedIn();
        $user = $_SESSION['user'];

        $order = Order::findByIdWithItems($id);

        if (!$order || $order['user_id'] != $user['id']) {
            header('Location: /orders');
            exit;
        }

        $this->render('orders/show', [
            'order' => $order
        ]);
    }

    public function create()
    {
        $this->ensureLoggedIn();

        if (empty($_SESSION['cart'])) {
            header('Location: /cart');
            exit;
        }

        $user = $_SESSION['user'];
        $cart = $_SESSION['cart'];
        $total = 0;
        $items = [];

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
            $items[] = [
                'product_id' => $item['product_id'],
                'product_name' => $item['product_name'],
                'price' => $item['price'],
                'quantity' => $item['quantity']
            ];
        }

        $orderId = Order::create($user['id'], $total, $items);

        if ($orderId) {
            // Vider le panier
            unset($_SESSION['cart']);
            // Redirection vers l'accueil comme demandé
            header("Location: /?order_success=1");
            exit;
        } else {
            // Gérer l'erreur (idéalement avec un message flash)
            header('Location: /cart?error=order_failed');
            exit;
        }
    }
}
