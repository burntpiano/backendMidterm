<?php
class Database {
    public function getConnection() {
        $host = $_ENV['DB_HOST'] ?? 'db';
        $name = $_ENV['DB_NAME'] ?? 'quotesdb';
        $user = $_ENV['DB_USER'] ?? 'postgres';
        $pass = $_ENV['DB_PASS'] ?? 'root';
        $db = new PDO("pgsql:host={$host};dbname={$name}", $user, $pass);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        return $db;
    }
}
