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
    public function delete($id) {
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