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

        // Verificar información del usuario
        $stmt = $db->prepare("SELECT direccion, pais, ciudad, codigo_postal, contacto FROM users WHERE id = ?");
        $stmt->execute([$user_id]);
        $datos_envio = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificar si falta algún dato de envío
        if (!$datos_envio || 
            empty($datos_envio['direccion']) || 
            empty($datos_envio['pais']) || 
            empty($datos_envio['ciudad']) || 
            empty($datos_envio['codigo_postal']) || 
            empty($datos_envio['contacto'])) {
            
            $_SESSION['error_message'] = "Por favor, complete su información de envío antes de realizar un pedido.";
            $_SESSION['redirect_after_update'] = 'checkout'; // Indicar que debe volver al checkout
            header('Location: index.php?controller=UserController&action=account');
            exit;
        }

        // Si el usuario quiere cambiar sus datos
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cambiar_envio'])) {
            $_SESSION['redirect_after_update'] = 'checkout'; // Indicar que debe volver al checkout
            header('Location: index.php?controller=UserController&action=account');
            exit;
        }

        // Si llegamos aquí, el usuario tiene toda su información y quiere confirmar el pedido
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirmar_envio']) && !isset($_POST['cambiar_envio'])) {
            if (empty($_POST['metodo_pago'])) {
                $_SESSION['error_message'] = "Por favor, seleccione un método de pago.";
                require 'views/orders/confirm_envio.php';
                exit;
            }

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
                header('Location: index.php?controller=OrderController&action=list&success=1');
                exit;
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['error_message'] = 'Error al procesar el pedido.';
                require 'views/orders/confirm_envio.php';
                exit;
            }
        }

        // Mostrar la página de confirmación
        require 'views/orders/confirm_envio.php';
    }

    public function list() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $user_id = $_SESSION['user_id'];
        
        // Obtener los pedidos con sus productos
        $stmt = $db->prepare("
            SELECT 
                o.*,
                oi.cantidad,
                oi.precio as precio_unitario,
                p.nombre as producto_nombre,
                p.imagen as producto_imagen
            FROM orders o
            LEFT JOIN order_items oi ON o.id = oi.order_id
            LEFT JOIN products p ON oi.product_id = p.id
            WHERE o.user_id = ?
            ORDER BY o.fecha DESC
        ");
        $stmt->execute([$user_id]);
        $resultados = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Reorganizar los resultados por pedido
        $pedidos = [];
        foreach ($resultados as $row) {
            $pedido_id = $row['id'];
            if (!isset($pedidos[$pedido_id])) {
                $pedidos[$pedido_id] = [
                    'id' => $row['id'],
                    'fecha' => $row['fecha'],
                    'estado' => $row['estado'],
                    'direccion' => $row['direccion'],
                    'pais' => $row['pais'],
                    'ciudad' => $row['ciudad'],
                    'codigo_postal' => $row['codigo_postal'],
                    'contacto' => $row['contacto'],
                    'metodo_pago' => $row['metodo_pago'],
                    'productos' => []
                ];
            }
            
            if ($row['producto_nombre']) { // Si hay productos
                $pedidos[$pedido_id]['productos'][] = [
                    'nombre' => $row['producto_nombre'],
                    'cantidad' => $row['cantidad'],
                    'precio_unitario' => $row['precio_unitario'],
                    'imagen' => $row['producto_imagen']
                ];
            }
        }

        // Convertir a array indexado
        $pedidos = array_values($pedidos);
        
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
            
            try {
                $db->beginTransaction();

                // Verificar que el pedido existe y pertenece al usuario
                $stmt = $db->prepare("SELECT * FROM orders WHERE id = ? AND user_id = ? AND estado = ?");
                $stmt->execute([$orderId, $userId, self::ESTADO_PENDIENTE]);
                $pedido = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($pedido) {
                    // Obtener los items del pedido
                    $stmt = $db->prepare("SELECT product_id, cantidad FROM order_items WHERE order_id = ?");
                    $stmt->execute([$orderId]);
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    // Restaurar el stock de cada producto
                    foreach ($items as $item) {
                        $stmt = $db->prepare("UPDATE products SET stock = stock + ? WHERE id = ?");
                        $stmt->execute([$item['cantidad'], $item['product_id']]);
                    }

                    // Actualizar el estado del pedido a cancelado
                    $stmt = $db->prepare("UPDATE orders SET estado = ? WHERE id = ?");
                    $stmt->execute([self::ESTADO_CANCELADO, $orderId]);

                    $db->commit();
                    $_SESSION['success'] = "Pedido cancelado exitosamente";
                } else {
                    $db->rollBack();
                    $_SESSION['error'] = "El pedido no puede ser cancelado o no te pertenece.";
                }
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['error'] = "Error al cancelar el pedido: " . $e->getMessage();
            }
        }

        header('Location: index.php?controller=OrderController&action=list');
        exit;
    }

    public function delete() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        
        // Verificar si es admin
        $stmt = $db->prepare("SELECT rol FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['rol'] !== 'admin') {
            $_SESSION['error'] = "No tienes permisos para realizar esta acción.";
            header('Location: index.php?controller=HomeController&action=index');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['order_id'])) {
            $orderId = $_POST['order_id'];
            
            // Verificar si el pedido existe
            $stmt = $db->prepare("SELECT estado FROM orders WHERE id = ?");
            $stmt->execute([$orderId]);
            $order = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$order) {
                $_SESSION['error'] = "El pedido no existe.";
                header('Location: index.php?controller=OrderController&action=adminList');
                exit;
            }

            $db->beginTransaction();
            try {
                // Si el pedido está pendiente, restaurar el stock
                if ($order['estado'] === self::ESTADO_PENDIENTE) {
                    $stmt = $db->prepare("
                        SELECT oi.product_id, oi.cantidad 
                        FROM order_items oi 
                        WHERE oi.order_id = ?
                    ");
                    $stmt->execute([$orderId]);
                    $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

                    foreach ($items as $item) {
                        $stmt = $db->prepare("
                            UPDATE products 
                            SET stock = stock + ? 
                            WHERE id = ?
                        ");
                        $stmt->execute([$item['cantidad'], $item['product_id']]);
                    }
                }

                // Eliminar los items del pedido
                $stmt = $db->prepare("DELETE FROM order_items WHERE order_id = ?");
                $stmt->execute([$orderId]);

                // Eliminar el pedido
                $stmt = $db->prepare("DELETE FROM orders WHERE id = ?");
                $stmt->execute([$orderId]);

                $db->commit();
                $_SESSION['success'] = "Pedido #$orderId eliminado correctamente.";
            } catch (Exception $e) {
                $db->rollBack();
                $_SESSION['error'] = "Error al eliminar el pedido: " . $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "Solicitud inválida.";
        }

        header('Location: index.php?controller=OrderController&action=adminList');
        exit;
    }
}
