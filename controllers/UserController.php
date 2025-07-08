<?php
class UserController {
    private function requireLogin() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=AuthController&action=login');
            exit;
        }
    }

    public function account() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT nombre, email, direccion, pais, ciudad, codigo_postal, contacto FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        require 'views/users/account.php';
    }

    public function updateAccount() {
        $this->requireLogin();
        $db = Database::getInstance()->getConnection();
        $nombre = trim($_POST['nombre']);
        $email = trim($_POST['email']);
        $direccion = isset($_POST['direccion']) ? trim($_POST['direccion']) : '';
        $pais = isset($_POST['pais']) ? trim($_POST['pais']) : '';
        $ciudad = isset($_POST['ciudad']) ? trim($_POST['ciudad']) : '';
        $codigo_postal = isset($_POST['codigo_postal']) ? trim($_POST['codigo_postal']) : '';
        $contacto = isset($_POST['contacto']) ? trim($_POST['contacto']) : '';
        
        // Validación básica
        if ($nombre === '' || $email === '') {
            $_SESSION['mensaje_error'] = 'El nombre y el correo son obligatorios.';
            header('Location: index.php?controller=UserController&action=account');
            exit;
        }

        $stmt = $db->prepare("UPDATE users SET nombre=?, email=?, direccion=?, pais=?, ciudad=?, codigo_postal=?, contacto=? WHERE id=?");
        if ($stmt->execute([$nombre, $email, $direccion, $pais, $ciudad, $codigo_postal, $contacto, $_SESSION['user_id']])) {
            $_SESSION['user_nombre'] = $nombre;
            
            // Verificar si todos los campos de envío están completos
            if (!empty($direccion) && !empty($pais) && !empty($ciudad) && 
                !empty($codigo_postal) && !empty($contacto)) {
                
                // Si venimos del checkout y todos los campos están completos, volvemos al checkout
                if (isset($_SESSION['redirect_after_update']) && $_SESSION['redirect_after_update'] === 'checkout') {
                    unset($_SESSION['redirect_after_update']);
                    header('Location: index.php?controller=OrderController&action=checkout');
                    exit;
                }
            }
            
            $_SESSION['mensaje_exito'] = 'Datos actualizados correctamente.';
        } else {
            $_SESSION['mensaje_error'] = 'Error al actualizar los datos.';
        }
        
        header('Location: index.php?controller=UserController&action=account');
        exit;
    }
} 