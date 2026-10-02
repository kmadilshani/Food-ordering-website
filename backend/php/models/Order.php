<?php
class Order {
    private $conn;
    private $table = 'orders';

    public function __construct($db) { $this->conn = $db; }

    public function create($userId, $items) {
        $this->conn->beginTransaction();
        try {
            $total = 0;
            foreach ($items as $it) {
                $total += $it['price'] * $it['qty'];
            }
            $stmt = $this->conn->prepare("INSERT INTO {$this->table} (user_id,total_amount,status) VALUES (?,?,?)");
            $stmt->execute([$userId, $total, 'pending']);
            $orderId = $this->conn->lastInsertId();

            $detailStmt = $this->conn->prepare("INSERT INTO order_items (order_id, food_id, quantity, unit_price) VALUES (?,?,?,?)");
            foreach ($items as $it) {
                $detailStmt->execute([$orderId, $it['food_id'], $it['qty'], $it['price']]);
            }
            $this->conn->commit();
            return $orderId;
        } catch (Exception $e) {
            $this->conn->rollBack();
            throw $e;
        }
    }
}
