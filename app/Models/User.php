<?php


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




    public static function getAll()
    {
        $pdo = Database::getPDO();
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


    public static function authenticate(string $email, string $password): ?array
    {
        $user = self::findAuthByEmail($email);
        if (!$user) {
            return null;
        }

        if (!password_verify($password, $user['password'])) {
            return null;
        }


        unset($user['password']);
        return $user;
    }


    public function save()
    {
        $pdo = Database::getPDO();

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


    public function delete()
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$this->id]);
    }
}
