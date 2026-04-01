<?php
class Author {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function getAll() {
        $stmt = $this->conn->query("SELECT id, author FROM authors ORDER BY id");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT id, author FROM authors WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->fetchAll();
    }

    public function exists($id) {
        $stmt = $this->conn->prepare("SELECT id FROM authors WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount() > 0;
    }

    public function create($author) {
        $stmt = $this->conn->prepare("INSERT INTO authors (author) VALUES (:author)");
        $stmt->execute([':author' => $author]);
        return ['id' => (int)$this->conn->lastInsertId(), 'author' => $author];
    }

    public function update($id, $author) {
        $stmt = $this->conn->prepare("UPDATE authors SET author = :author WHERE id = :id");
        $stmt->execute([':author' => $author, ':id' => $id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM authors WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}
