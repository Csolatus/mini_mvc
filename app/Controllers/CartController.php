<?php

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;

class CartController extends Controller
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
    }

    public function index()
    {
        $cartItems = $_SESSION['cart'];
        $cleanCart = [];
        $total = 0;

        // Nettoyage et calcul
        foreach ($cartItems as $id => $item) {
            // Vérification de l'intégrité des données
            if (!isset($item['product_name']) || !isset($item['price']) || $item['product_name'] === null || $item['price'] === null) {
                continue;
            }
            $cleanCart[$id] = $item;
            $total += $item['price'] * $item['quantity'];
        }

        // Mise à jour de la session si nettoyage effectué
        if (count($cleanCart) !== count($cartItems)) {
            $_SESSION['cart'] = $cleanCart;
        }

        $this->render('cart/index', [
            'cartItems' => $cleanCart,
            'total' => $total
        ]);
    }

    public function add()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int) $_POST['product_id'];
            $quantity = (int) ($_POST['quantity'] ?? 1);

            $product = Product::findById($productId);

            if ($product) {
                if (isset($_SESSION['cart'][$productId])) {
                    $_SESSION['cart'][$productId]['quantity'] += $quantity;
                } else {
                    $_SESSION['cart'][$productId] = [
                        'product_id' => $product['id'],
                        'product_name' => $product['nom'],
                        'price' => $product['prix'],
                        'quantity' => $quantity
                    ];
                }
            }
        }

        header('Location: /cart');
        exit;
    }

    public function remove()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $productId = (int) $_POST['product_id'];
            if (isset($_SESSION['cart'][$productId])) {
                unset($_SESSION['cart'][$productId]);
            }
        }

        header('Location: /cart');
        exit;
    }
}
