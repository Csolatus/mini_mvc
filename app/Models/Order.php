<?php

namespace Mini\Models;

use Mini\Core\Database;
use PDO;

class Order
{
    private $id;
    private $userId;
    private $totalAmount;
    private $status;
    private $createdAt;

    // =====================
    // Getters / Setters
    // =====================

    public function getId()
    {
        return $this->id;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getTotalAmount()
    {
        return $this->totalAmount;
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    // =====================
    // Méthodes CRUD
    // =====================

    /**
     * Crée une commande et ses lignes
     * @param int $userId
     * @param float $totalAmount
     * @param array $items Tableau d'items du panier ['product_id', 'product_name', 'price', 'quantity']
     * @return int|false ID de la commande créée ou false
     */
    public static function create(int $userId, float $totalAmount, array $items)
    {
        $pdo = Database::getPDO();
        
        try {
            $pdo->beginTransaction();

            // 1. Insérer la commande
            $stmt = $pdo->prepare("
                INSERT INTO orders (user_id, total_amount, status, created_at)
                VALUES (?, ?, 'pending', NOW())
            ");
            $inputAmount = $totalAmount; // Hack pour passer par référence si besoin, mais ici valeur directe ok
            $stmt->execute([$userId, $inputAmount]);
            
            $orderId = $pdo->lastInsertId();

            // 2. Insérer les lignes de commande
            $stmtItem = $pdo->prepare("
                INSERT INTO order_items (order_id, product_id, product_name, price, quantity)
                VALUES (?, ?, ?, ?, ?)
            ");

            foreach ($items as $item) {
                $stmtItem->execute([
                    $orderId,
                    $item['product_id'],
                    $item['product_name'],
                    $item['price'],
                    $item['quantity']
                ]);
            }

            $pdo->commit();
            return $orderId;
            
        } catch (\Exception $e) {
            $pdo->rollBack();
            // On pourrait logger l'erreur ici
            return false;
        }
    }

    /**
     * Récupère toutes les commandes d'un utilisateur
     * @param int $userId
     * @return array
     */
    public static function findByUserId(int $userId): array
    {
        $pdo = Database::getPDO();
        $stmt = $pdo->prepare("
            SELECT * FROM orders 
            WHERE user_id = ?
            ORDER BY created_at DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une commande par son ID avec ses items
     * @param int $orderId
     * @return array|null
     */
    public static function findByIdWithItems(int $orderId)
    {
        $pdo = Database::getPDO();
        
        // Récup infos commande
        $stmt = $pdo->prepare("SELECT * FROM orders WHERE id = ?");
        $stmt->execute([$orderId]);
        $order = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$order) {
            return null;
        }

        // Récup items
        $stmtItems = $pdo->prepare("SELECT * FROM order_items WHERE order_id = ?");
        $stmtItems->execute([$orderId]);
        $order['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

        return $order;
    }
}
