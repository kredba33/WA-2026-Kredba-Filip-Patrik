<?php
require_once __DIR__ . '/../../core/Model.php'; 

class Subcategory extends Model {
    
    public function __construct() {
        parent::__construct();
    }

    // Získá všechny subkategorie
    public function getAllSubcategories() {
        $stmt = $this->db->query("SELECT * FROM subcategories ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}