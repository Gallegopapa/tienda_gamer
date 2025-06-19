<?php
class AuthController {
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $user = new User();
            if ($user->findByEmail($email)) {
                $error = 'El email ya está registrado.';
                require 'views/auth/register.php';
                return;
            }
            if ($user->register($nombre, $email, $password)) {
                header('Location: index.php?controller=AuthController&action=login&registro=ok');
                exit;
            } else {
                $error = 'Error al registrar usuario.';
            }
        }
        require 'views/auth/register.php';
    }
    public function login() {
        $mensaje = '';
        if (isset($_GET['registro']) && $_GET['registro'] === 'ok') {
            $mensaje = 'Registro exitoso. Ahora puedes iniciar sesión.';
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email']);
            $password = $_POST['password'];
            $user = new User();
            $usuario = $user->findByEmail($email);
            if ($usuario && password_verify($password, $usuario['password'])) {
                $_SESSION['user_id'] = $usuario['id'];
                $_SESSION['user_nombre'] = $usuario['nombre'];
                $_SESSION['user_rol'] = $usuario['rol'];
                header('Location: index.php');
                exit;
            } else {
                $error = 'Credenciales incorrectas.';
            }
        }
        require 'views/auth/login.php';
    }
    public function logout() {
        session_unset();
        session_destroy();
        header('Location: index.php');
        exit;
    }
} 