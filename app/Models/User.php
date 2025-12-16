<?php

// Ici je définit le namespace ou il y aura ma class
namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class User
{
    private $id;
    private $nom;
    private $email;
    private $password;
    private $lastname;

    // =====================
    // Getters / Setters
    // =====================

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getnom()
    {
        return $this->nom;
    }

    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function setPassword($password)
    {
        $this->password = $password;
    }

    public function getLastname()
    {
        return $this->lastname;
    }

    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Récupère tous les utilisateurs
     * @return array
     */
    public static function getAll()
    {
        $pdo = Database::getPDO();
        // Mappe la table `users` vers les clés attendues par les vues (nom/email)
        $stmt = $pdo->query("
            SELECT 
                id,
                firstname AS nom,
                email
            FROM users
            ORDER BY id DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son ID
     * @param int $id
     * @return array|null
     */
    public static function findById($id)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                id,
                firstname AS nom,
                email
            FROM users
            WHERE id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère un utilisateur par son email
     * @param string $email
     * @return array|null
     */
    public static function findByEmail($email)
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                id,
                firstname AS nom,
                email
            FROM users
            WHERE email = ?
        ");
        $stmt->execute([$email]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère les infos utiles à l'authentification
     * @param string $email
     * @return array|null
     */
    public static function findAuthByEmail(string $email): ?array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT 
                id,
                email,
                password,
                firstname AS nom,
                lastname,
                role
            FROM users
            WHERE email = ?
            LIMIT 1
        ");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Vérifie les identifiants fournis.
     * Retourne les infos utilisateur sans le hash si OK, sinon null.
     * @param string $email
     * @param string $password
     * @return array|null
     */
    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findAuthByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }

        // On ne renvoie pas le hash
        unset($user['password']);
        return $user;
    }

    /**
     * Crée un nouvel utilisateur
     * @return bool
     */
    public function save()
    {
        $pdo = Database::getPDO();
        // Mot de passe par défaut si non fourni (pour rester compatible avec le schéma)
        $password = $this->password ?? 'password';
        $stmt = $pdo->prepare("
            INSERT INTO users (firstname, lastname, email, password)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([
            $this->nom,
            $this->lastname,
            $this->email,
            password_hash($password, PASSWORD_DEFAULT)
        ]);
    }

    /**
     * Met à jour les informations d’un utilisateur existant
     * @return bool
     */
    public function update()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            UPDATE users 
            SET firstname = ?, email = ?
            WHERE id = ?
        ");
        return $stmt->execute([$this->nom, $this->email, $this->id]);
    }

    /**
     * Supprime un utilisateur
     * @return bool
     */
    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
