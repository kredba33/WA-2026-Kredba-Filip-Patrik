<?php
require_once __DIR__ . '/../../core/Model.php'; 

class Category extends Model {
    
    public function __construct() {
        parent::__construct();
    }

    // Získá všechny kategorie
    public function getAllCategories() {
        $stmt = $this->db->query("SELECT * FROM categories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}