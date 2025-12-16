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
        // Récupère tous les utilisateurs
        $users = User::getAll();

        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');

        // Encode les données en JSON et les affiche
        echo json_encode($users, JSON_PRETTY_PRINT);
    }

    public function showCreateUserForm(): void
    {
        // Affiche le formulaire de création d'utilisateur
        $this->render('home/create-user', params: [
            'title' => 'Créer un utilisateur'
        ]);
    }

    public function createUser(): void
    {
        // Définit le header Content-Type pour indiquer que la réponse est du JSON
        header('Content-Type: application/json; charset=utf-8');

        // Vérifie que la méthode HTTP est POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }

        // Récupère les données JSON du body de la requête
        $input = json_decode(file_get_contents('php://input'), true);

        // Si pas de JSON, essaie de récupérer depuis $_POST
        if ($input === null) {
            $input = $_POST;
        }

        // Valide les données requises
        if (empty($input['prenom']) || empty($input['nom']) || empty($input['email']) || empty($input['password'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Les champs "prenom", "nom", "email" et "password" sont requis.'], JSON_PRETTY_PRINT);
            return;
        }

        // Valide le format de l'email
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


        // Crée une nouvelle instance User
        $user = new User();
        $user->setNom($input['prenom']);
        $user->setLastname($input['nom']);
        $user->setEmail($input['email']);
        $user->setPassword($input['password']);

        // Sauvegarde l'utilisateur
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