<?php
class Food {
    private $conn;
    private $table = 'foods';

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        $sql = "SELECT f.id, f.name, f.price, f.description, f.image, c.name AS category_name
                FROM {$this->table} f
                LEFT JOIN categories c ON c.id = f.category_id
                ORDER BY f.created_at DESC";
        return $this->conn->query($sql)->fetchAll();
    }

    public function find($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function byCategory($categoryId) {
        $stmt = $this->conn->prepare("SELECT f.id, f.name, f.price, f.description, f.image, c.name AS category_name FROM {$this->table} f LEFT JOIN categories c ON c.id = f.category_id WHERE f.category_id = ? ORDER BY f.name");
        $stmt->execute([$categoryId]);
        return $stmt->fetchAll();
    }

    public function search($term) {
        $like = "%" . $term . "%";
        $stmt = $this->conn->prepare("SELECT id, name, price, description, image FROM {$this->table} WHERE name LIKE ? OR description LIKE ? ORDER BY name");
        $stmt->execute([$like, $like]);
        return $stmt->fetchAll();
    }

    public function create($categoryId, $name, $price, $description, $image) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (category_id,name,price,description,image) VALUES (?,?,?,?,?)");
        return $stmt->execute([$categoryId ?: null, $name, $price, $description, $image]);
    }

    public function update($id, $categoryId, $name, $price, $description, $image) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET category_id=?, name=?, price=?, description=?, image=? WHERE id=?");
        return $stmt->execute([$categoryId ?: null, $name, $price, $description, $image, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
