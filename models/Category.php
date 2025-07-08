<?php
class Category {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAll() {
        $sql = "SELECT * FROM categories ORDER BY id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function create($nombre) {
        $sql = "INSERT INTO categories (nombre) VALUES (?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre]);
    }
    
    public function getById($id) {
        $sql = "SELECT * FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function update($id, $nombre) {
        $sql = "UPDATE categories SET nombre = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $id]);
    }

    public function countProducts($id) {
        $sql = "SELECT COUNT(*) as count FROM products WHERE category_id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)$result['count'];
    }
    
    public function delete($id) {
        // Verificar si hay productos asociados
        $productCount = $this->countProducts($id);
        if ($productCount > 0) {
            throw new Exception("No se puede eliminar la categoría porque tiene {$productCount} productos asociados. Por favor, elimina o reasigna los productos primero.");
        }

        $sql = "DELETE FROM categories WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$id]);
    }
    
    public function updateImage($id, $imagen) {
        $sql = "UPDATE categories SET imagen = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$imagen, $id]);
    }
    
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
} 