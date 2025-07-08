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

    public function delete($id) {
        // Verificar si el producto existe
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            throw new Exception("El producto no existe.");
        }

        // Verificar si el producto está en algún pedido
        $stmt = $this->db->prepare("SELECT COUNT(*) as count FROM order_items WHERE product_id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result['count'] > 0) {
            throw new Exception("No se puede eliminar el producto porque está asociado a pedidos existentes.");
        }

        // Si no hay pedidos asociados, eliminar el producto
        $stmt = $this->db->prepare("DELETE FROM products WHERE id = ?");
        return $stmt->execute([$id]);
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

    public function getSugerencias($product_id, $limite = 3) {
        // Primero obtenemos la categoría del producto actual
        $sql = "SELECT category_id FROM products WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$product_id]);
        $producto = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$producto) {
            return [];
        }

        // Obtenemos productos de la misma categoría, excluyendo el producto actual
        $sql = "SELECT * FROM products 
                WHERE category_id = ? 
                AND id != ? 
                AND stock > 0
                ORDER BY RAND() 
                LIMIT " . (int)$limite;
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$producto['category_id'], $product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
} 