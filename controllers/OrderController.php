<?php
class OrderController {
    // Constantes de estado
    const ESTADO_PENDIENTE = 'pendiente';
    const ESTADO_EN_PROCESO = 'enviado';
    const ESTADO_COMPLETADO = 'entregado';
    const ESTADO_CANCELADO = 'cancelado';

    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=UserController&action=login'); 
            exit;
        }
    }

    public function checkout() {
        $this->requireLogin();
        $carrito = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        if (empty($carrito)) {
            header('Location: index.php?controller=CartController&action=index'); exit;
        }
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'];

        $stmt = $db->prepare("SELECT direccion, pais, ciudad, codigo_postal, contacto FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $datos_envio = $stmt->fetch(PDO::FETCH_ASSOC);
        $mostrar_formulario = false;

        if (
            !isset($_POST['cambiar_envio']) &&
            $datos_envio &&
            $datos_envio['direccion'] && $datos_envio['pais'] && $datos_envio['ciudad'] &&
            $datos_envio['codigo_postal'] && $datos_envio['contacto']
        ) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_envio'])) {
                $direccion = $datos_envio['direccion'];
                $pais = $datos_envio['pais'];
                $ciudad = $datos_envio['ciudad'];
                $codigo_postal = $datos_envio['codigo_postal'];
                $contacto = $datos_envio['contacto'];
                $metodo_pago = trim($_POST['metodo_pago']);
                $db->beginTransaction();
                try {
                    $stmt = $db->prepare("INSERT INTO orders (user_id, direccion, pais, ciudad, codigo_postal, contacto, metodo_pago, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $direccion, $pais, $ciudad, $codigo_postal, $contacto, $metodo_pago, self::ESTADO_PENDIENTE]);
                    $order_id = $db->lastInsertId();
                    foreach ($carrito as $item) {
                        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, cantidad, precio) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$order_id, $item['id'], $item['cantidad'], $item['precio']]);
                        $stmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
                        $stmt->execute([$item['cantidad'], $item['id'], $item['cantidad']]);
                    }
                    $db->commit();
                    unset($_SESSION['cart']);
                    header('Location: index.php?controller=OrderController&action=list&success=1'); exit;
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Error al procesar el pedido.';
                }
            } else {
                require 'views/orders/confirm_envio.php';
                return;
            }
        } else {
            $mostrar_formulario = true;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$mostrar_formulario) {
            // Ya procesado arriba
        } elseif ($_SERVER['REQUEST_METHOD'] === 'POST' || $mostrar_formulario) {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['direccion'])) {
                $direccion = trim($_POST['direccion']);
                $pais = trim($_POST['pais']);
                $ciudad = trim($_POST['ciudad']);
                $codigo_postal = trim($_POST['codigo_postal']);
                $contacto = trim($_POST['contacto']);
                $metodo_pago = trim($_POST['metodo_pago']);
                $stmt = $db->prepare("UPDATE users SET direccion=?, pais=?, ciudad=?, codigo_postal=?, contacto=? WHERE id=?");
                $stmt->execute([$direccion, $pais, $ciudad, $codigo_postal, $contacto, $user_id]);
                $db->beginTransaction();
                try {
                    $stmt = $db->prepare("INSERT INTO orders (user_id, direccion, pais, ciudad, codigo_postal, contacto, metodo_pago, estado) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([$user_id, $direccion, $pais, $ciudad, $codigo_postal, $contacto, $metodo_pago, self::ESTADO_PENDIENTE]);
                    $order_id = $db->lastInsertId();
                    foreach ($carrito as $item) {
                        $stmt = $db->prepare("INSERT INTO order_items (order_id, product_id, cantidad, precio) VALUES (?, ?, ?, ?)");
                        $stmt->execute([$order_id, $item['id'], $item['cantidad'], $item['precio']]);
                        $stmt = $db->prepare("UPDATE products SET stock = stock - ? WHERE id = ? AND stock >= ?");
                        $stmt->execute([$item['cantidad'], $item['id'], $item['cantidad']]);
                    }
                    $db->commit();
                    unset($_SESSION['cart']);
                    header('Location: index.php?controller=OrderController&action=list&success=1'); exit;
                } catch (Exception $e) {
                    $db->rollBack();
                    $error = 'Error al procesar el pedido.';
                }
            }
            require 'views/orders/form.php';
        }
    }

    public function list() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'];
        $stmt = $db->prepare("SELECT * FROM orders WHERE user_id = ? ORDER BY fecha DESC");
        $stmt->execute([$user_id]);
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        require 'views/orders/list.php';
    }

    public function adminList() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rol FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['rol'] !== 'admin') {
            header('Location: index.php?controller=UserController&action=login');
            exit;
        }

        $sql = "SELECT o.*, u.nombre as user_nombre, u.email as user_email 
                FROM orders o 
                JOIN users u ON o.user_id = u.id 
                ORDER BY o.fecha DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute();
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as &$pedido) {
            $sql = "SELECT op.*, p.nombre, p.imagen, p.precio 
                    FROM order_items op 
                    JOIN products p ON op.product_id = p.id 
                    WHERE op.order_id = ?";
            $stmt = $db->prepare($sql);
            $stmt->execute([$pedido['id']]);
            $pedido['productos'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        require_once 'views/orders/admin_list.php';
    }

    public function updateStatus() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rol FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['rol'] !== 'admin') {
            header('Location: index.php?controller=UserController&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id']) && isset($_POST['estado'])) {
            $orderId = $_POST['order_id'];
            $estado = $_POST['estado'];
            $estadosPermitidos = [self::ESTADO_PENDIENTE, self::ESTADO_EN_PROCESO, self::ESTADO_COMPLETADO];

            if (!in_array($estado, $estadosPermitidos)) {
                $_SESSION['error'] = "Estado no válido";
                header('Location: index.php?controller=OrderController&action=adminList');
                exit;
            }

            $sql = "UPDATE orders SET estado = ? WHERE id = ?";
            $stmt = $db->prepare($sql);
            if ($stmt->execute([$estado, $orderId])) {
                $_SESSION['success'] = "Estado del pedido actualizado correctamente";
            } else {
                $_SESSION['error'] = "Error al actualizar el estado del pedido";
            }
        }

        header('Location: index.php?controller=OrderController&action=adminList');
        exit;
    }

    public function cancel() {
        $this->requireLogin();

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = $_POST['order_id'];
            $userId = $_SESSION['user_id'];

            $db = Database::getInstance()->getConnection();

            $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ?");
            $stmt->execute([$orderId, $userId]);
            $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($pedido && $pedido['estado'] === self::ESTADO_PENDIENTE) {
                $stmt = $db->prepare("UPDATE orders SET estado = ? WHERE id = ?");
                $stmt->execute([self::ESTADO_CANCELADO, $orderId]);
                $_SESSION['success'] = "Pedido cancelado exitosamente.";
            } else {
                $_SESSION['error'] = "El pedido no puede ser cancelado.";
            }
        }

        header('Location: index.php?controller=OrderController&action=list');
        exit;
    }
}
