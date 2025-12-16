<?php

declare(strict_types=1);

namespace Mini\Controllers;

use Mini\Core\Controller;
use Mini\Models\User;

final class AuthController extends Controller
{
    public function showLoginForm(): void
    {
        $this->render('home/login', ['title' => 'Connexion']);
    }

    public function login(): void
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

        $email = trim($input['email'] ?? '');
        $password = $input['password'] ?? '';

        if ($email === '' || $password === '') {
            http_response_code(400);
            echo json_encode(['error' => 'Email et mot de passe sont requis.'], JSON_PRETTY_PRINT);
            return;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode(['error' => 'Format d\'email invalide.'], JSON_PRETTY_PRINT);
            return;
        }

        $user = User::authenticate($email, $password);

        if (!$user) {
            http_response_code(401);
            echo json_encode(['error' => 'Identifiants incorrects.'], JSON_PRETTY_PRINT);
            return;
        }

        session_regenerate_id(true);
        $_SESSION['user'] = $user;

        echo json_encode([
            'success' => true,
            'message' => 'Connexion réussie.',
            'user' => $user
        ], JSON_PRETTY_PRINT);
    }

    public function logout(): void
    {
        header('Content-Type: application/json; charset=utf-8');

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            echo json_encode(['error' => 'Méthode non autorisée. Utilisez POST.'], JSON_PRETTY_PRINT);
            return;
        }

        unset($_SESSION['user']);

        echo json_encode([
            'success' => true,
            'message' => 'Déconnexion effectuée.'
        ], JSON_PRETTY_PRINT);
    }
}

