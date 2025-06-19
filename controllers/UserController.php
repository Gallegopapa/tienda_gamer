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
        $direccion = trim($_POST['direccion']);
        $pais = trim($_POST['pais']);
        $ciudad = trim($_POST['ciudad']);
        $codigo_postal = trim($_POST['codigo_postal']);
        $contacto = trim($_POST['contacto']);
        $mensaje_exito = null;
        $mensaje_error = null;
        // Validación básica
        if ($nombre === '' || $email === '') {
            $mensaje_error = 'El nombre y el correo son obligatorios.';
        } else {
            $stmt = $db->prepare("UPDATE users SET nombre=?, email=?, direccion=?, pais=?, ciudad=?, codigo_postal=?, contacto=? WHERE id=?");
            if ($stmt->execute([$nombre, $email, $direccion, $pais, $ciudad, $codigo_postal, $contacto, $_SESSION['user_id']])) {
                $mensaje_exito = 'Datos actualizados correctamente.';
                $_SESSION['user_nombre'] = $nombre;
            } else {
                $mensaje_error = 'Error al actualizar los datos.';
            }
        }
        // Volver a cargar los datos actualizados
        $stmt = $db->prepare("SELECT nombre, email, direccion, pais, ciudad, codigo_postal, contacto FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        require 'views/users/account.php';
    }
} 