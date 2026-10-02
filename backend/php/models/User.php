<?php
class User {
    private $conn;
    private $table = 'users';

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($name, $email, $password, $isAdmin = 0) {
        $hash = password_hash($password, PASSWORD_BCRYPT);
        $sql = "INSERT INTO {$this->table} (name,email,password_hash,is_admin) VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $email, $hash, (int)$isAdmin]);
    }

    public function findByEmail($email) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public function findById($id) {
        $stmt = $this->conn->prepare("SELECT id, name, email, is_admin, created_at FROM {$this->table} WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function promote($id) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET is_admin=1 WHERE id=?");
        return $stmt->execute([$id]);
    }
}
