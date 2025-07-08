<?php
class Auth {
    public static function check() {
        if (!isset($_SESSION['user_id'])) {
            $_SESSION['error_message'] = "Debes iniciar sesión para acceder a esta página.";
            header('Location: index.php?controller=AuthController&action=login');
            exit;
        }
        return true;
    }

    public static function checkAdmin() {
        self::check();
        
        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rol FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user || $user['rol'] !== 'admin') {
            $_SESSION['error_message'] = "No tienes permisos para acceder a esta sección.";
            header('Location: index.php');
            exit;
        }
        return true;
    }

    public static function isAdmin() {
        if (!isset($_SESSION['user_id'])) {
            return false;
        }

        $db = Database::getInstance()->getConnection();
        $stmt = $db->prepare("SELECT rol FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($user && $user['rol'] === 'admin');
    }
} 