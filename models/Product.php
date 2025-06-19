<?php
class Product {
    private $db;
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    public function getRecientes($limite = 6) {
        $sql = "SELECT * FROM products ORDER BY id DESC LIMIT ?";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(1, (int)$limite, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function create($nombre, $precio, $descripcion, $imagen, $category_id, $stock) {
        $sql = "INSERT INTO products (nombre, precio, descripcion, imagen, category_id, stock) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $precio, $descripcion, $imagen, $category_id, $stock]);
    }
    public function update($id, $nombre, $precio, $descripcion, $imagen, $category_id, $stock) {
        $sql = "UPDATE products SET nombre=?, precio=?, descripcion=?, imagen=?, category_id=?, stock=? WHERE id=?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$nombre, $precio, $descripcion, $imagen, $category_id, $stock, $id]);
    }
    public function getAll() {
        $sql = "SELECT p.*, c.nombre as categoria FROM products p LEFT JOIN categories c ON p.category_id = c.id ORDER BY p.id DESC";
        $stmt = $this->db->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getByCategory($category_id) {
        $sql = "SELECT * FROM products WHERE category_id = ? ORDER BY id DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$category_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getById($id) {
        $sql = "SELECT * FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
} 