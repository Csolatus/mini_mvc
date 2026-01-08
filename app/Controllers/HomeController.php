<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;
use Mini\Models\Product;

class HomeController extends Controller
{
    public function index(): void
    {
        $products = Product::getAll();
        $this->render('home/index', ['title' => 'Accueil', 'products' => $products]);
    }

    public function users(): void
    {

        $users = User::getAll();


        header('Content-Type: application/json; charset=utf-8');


        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function showCreateUserForm(): void
    {

        $this->render('home/create-user', params: [
            'title' => 'Créer un utilisateur'
        ]);
    }

    public function createUser(): void
    {

        header('Content-Type: application/json; charset=utf-8');


        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }


        $input = json_decode(file_get_contents('php://input'), true);


        if ($input === null) {
            $input = $_POST;
        }


        if (empty($input['prenom']) || empty($input['nom']) || empty($input['email']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "prenom", "nom", "email" et "password" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }


        if (!filter_var($input['email'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format d\'email invalide.'], JSON_PRETTY_PRINT);
            return;
        }

        if (strlen($input['password']) < 6) {
            http_response_code(400);
            echo json_encode([
                'error' => 'Le mot de passe doit
    contenir au moins 6 caractères.'
            ], JSON_PRETTY_PRINT);
            return;
        }



        $user = new User();
        $user->setNom($input['prenom']);
        $user->setLastname($input['nom']);
        $user->setEmail($input['email']);
        $user->setPassword($input['password']);


        if ($user->save()) {
            http_response_code(201);
            echo json_encode([
                'success' => true,
                'message' => 'Utilisateur créé avec succès.',
                'user' => [
                    'prenom' => $user->getnom(),
                    'nom' => $user->getLastname(),
                    'email' => $user->getEmail()
                ]
            ], JSON_PRETTY_PRINT);
        } else {
            http_response_code(500);
            echo json_encode(['error' => 'Erreur lors de la création de l\'utilisateur.'], JSON_PRETTY_PRINT);
        }
    }
}