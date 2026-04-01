<?php
class Quote {
    private $conn;

    public function __construct($db) { $this->conn = $db; }

    public function get($id = null, $author_id = null, $category_id = null) {
        $base = "SELECT q.id, q.quote, a.author, c.category
                FROM quotes q
                JOIN authors a ON q.author_id = a.id
                JOIN categories c ON q.category_id = c.id";
        $where = []; $p = [];
        if ($id !== null)          { $where[] = 'q.id = :id';                   $p[':id'] = $id; }
        if ($author_id !== null)   { $where[] = 'q.author_id = :author_id';     $p[':author_id'] = $author_id; }
        if ($category_id !== null) { $where[] = 'q.category_id = :category_id'; $p[':category_id'] = $category_id; }

        $sql = $base
            . ($where ? ' WHERE ' . implode(' AND ', $where) : '')
            . ' ORDER BY q.id';

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($p);
        return $stmt->fetchAll();
    }

    public function create($quote, $author_id, $category_id) {
        $stmt = $this->conn->prepare("INSERT INTO quotes (quote, author_id, category_id) VALUES (:quote, :author_id, :category_id)");
        $stmt->execute([':quote' => $quote, ':author_id' => $author_id, ':category_id' => $category_id]);
        return ['id' => (int)$this->conn->lastInsertId(), 'quote' => $quote, 'author_id' => (int)$author_id, 'category_id' => (int)$category_id];
    }

    public function update($id, $quote, $author_id, $category_id) {
        $stmt = $this->conn->prepare("UPDATE quotes SET quote = :quote, author_id = :author_id, category_id = :category_id WHERE id = :id");
        $stmt->execute([':id' => $id, ':quote' => $quote, ':author_id' => $author_id, ':category_id' => $category_id]);
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM quotes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }
}
