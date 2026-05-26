<?php

class Database {
    private $host     = "localhost";
    private $db_name  = "wa_2026_gaming_forum";
    private $username = "root";
    private $password = "";
    public  $conn;

    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO(
                "mysql:host=" . $this->host . ";dbname=" . $this->db_name . ";charset=utf8mb4",  //podpora unicode
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die("Chyba připojení k databázi: " . $e->getMessage());
        }
        return $this->conn;
    }
}