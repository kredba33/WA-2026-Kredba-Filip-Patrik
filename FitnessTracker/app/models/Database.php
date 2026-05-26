<?php

class Database {
    private $host = "localhost";
    private $db_name = "fitnesstracker";
    private $username = "root";
    private $password = "";
    public $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Zakomentováno pro produkci: echo rozbíjí přesměrování s hlavičkou header()
            // echo "Připojení k databázi bylo úspěšné!<br>";
            
        } catch (PDOException $exception) {
            echo "Chyba připojení: " . $exception->getMessage();
        }
        return $this->conn;
    }
}