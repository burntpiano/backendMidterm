<?php
class Category {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function getAll() {
        $stmt = $this->conn->query("SELECT id, category FROM categories ORDER BY id");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, category FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }

    public function exists($id) {
        $stmt = $this->conn->prepare("SELECT id FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function create($category) {
        $stmt = $this->conn->prepare("INSERT INTO categories (category) VALUES (:category)");
        $stmt->execute([':category' => $category]);
        return ['id' => (int)$this->conn->lastInsertId(), 'category' => $category];
    }

    public function update($id, $category) {
        $stmt = $this->conn->prepare("UPDATE categories SET category = :category WHERE id = :id");
        $stmt->execute([':category' => $category, ':id' => $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM categories WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}
