<?php

class Order {
    private $db;

    public function __construct() {
        require_once 'config.php';
        $this->db = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getAllOrdersWithDetails() {
        $sql = "SELECT o.*, u.nombre as user_nombre, u.email as user_email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.fecha DESC";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Obtener los productos de cada pedido
        foreach ($pedidos as &$pedido) {
            $sql = "SELECT op.*, p.nombre, p.imagen, p.precio 
                    FROM order_products op 
                    JOIN products p ON op.product_id = p.id 
                    WHERE op.order_id = ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$pedido['id']]);
            $pedido['productos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return $pedidos;
    }

    public function updateOrderStatus($orderId, $estado) {
        try {
            $sql = "UPDATE orders SET estado = ? WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$estado, $orderId]);
        } catch (PDOException $e) {
            error_log("Error al actualizar el estado del pedido: " . $e->getMessage());
            return false;
        }
    }
} 