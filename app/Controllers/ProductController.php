<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\Product;

class ProductController extends Controller
{
    public function listProducts(): void
    {

        $products = Product::getAll();


        $this->render('product/list-products', params: [
            'title' => 'Liste des produits',
            'products' => $products
        ]);
    }

    public function showCreateProductForm(): void
    {

        $this->render('product/create-product', params: [
            'title' => 'Créer un produit'
        ]);
    }

    public function createProduct(): void
    {

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: /products/create');
            return;
        }


        $input = $_POST;


        if (empty($input['nom']) || empty($input['prix']) || empty($input['stock'])) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Les champs "nom", "prix" et "stock" sont requis.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }


        if (!is_numeric($input['prix']) || floatval($input['prix']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le prix doit être un nombre positif.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }


        if (!is_numeric($input['stock']) || intval($input['stock']) < 0) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Le stock doit être un entier positif.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }


        $image_url = $input['image_url'] ?? '';
        if (!empty($image_url) && !filter_var($image_url, FILTER_VALIDATE_URL)) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'L\'URL de l\'image n\'est pas valide.',
                'success' => false,
                'old_values' => $input
            ]);
            return;
        }


        $product = new Product();
        $product->setNom($input['nom']);
        $product->setDescription($input['description'] ?? '');
        $product->setPrix(floatval($input['prix']));
        $product->setStock(intval($input['stock']));
        $product->setImageUrl($image_url);


        if ($product->save()) {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Produit créé avec succès.',
                'success' => true
            ]);
        } else {
            $this->render('product/create-product', params: [
                'title' => 'Créer un produit',
                'message' => 'Erreur lors de la création du produit.',
                'success' => false,
                'old_values' => $input
            ]);
        }
    }

    public function showProduct(int $id): void
    {
        $product = Product::findById($id);

        if (!$product) {
            http_response_code(404);
            $this->render('errors/404', ['title' => 'Produit introuvable']);
            return;
        }

        $this->render('product/detail', [
            'title' => $product['nom'],
            'product' => $product,
        ]);
    }
}