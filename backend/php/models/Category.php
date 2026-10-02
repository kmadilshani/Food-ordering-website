<?php
class Category {
    private $conn;
    private $table = 'categories';

    public function __construct($db) { $this->conn = $db; }

    public function all() {
        $stmt = $this->conn->query("SELECT id, name, slug FROM {$this->table} ORDER BY name");
        return $stmt->fetchAll();
    }

    public function create($name, $slug) {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (name, slug) VALUES (?,?)");
        return $stmt->execute([$name, $slug]);
    }

    public function update($id, $name, $slug) {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET name=?, slug=? WHERE id=?");
        return $stmt->execute([$name, $slug, $id]);
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id=?");
        return $stmt->execute([$id]);
    }
}
