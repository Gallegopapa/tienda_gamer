<?php
class CartController {
    public function add() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=AuthController&action=login'); exit;
        }
        if (!isset($_GET['id']) || !isset($_POST['cantidad'])) {
            header('Location: index.php?controller=CartController&action=index'); exit;
        }
        $id = intval($_GET['id']);
        $cantidad = max(1, intval($_POST['cantidad']));
        $product = new Product();
        $prod = $product->getById($id);
        if (!$prod || $prod['stock'] < $cantidad) {
            header('Location: index.php?controller=CartController&action=index'); exit;
        }
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        if (isset($_SESSION['cart'][$id])) {
            $nuevaCantidad = $_SESSION['cart'][$id]['cantidad'] + $cantidad;
            $_SESSION['cart'][$id]['cantidad'] = min($nuevaCantidad, $prod['stock']);
        } else {
            $_SESSION['cart'][$id] = [
                'id' => $prod['id'],
                'nombre' => $prod['nombre'],
                'precio' => $prod['precio'],
                'imagen' => $prod['imagen'],
                'stock' => $prod['stock'],
                'cantidad' => min($cantidad, $prod['stock'])
            ];
        }
        header('Location: index.php?controller=CartController&action=index');
        exit;
    }
    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?controller=AuthController&action=login'); exit;
        }
        $carrito = isset($_SESSION['cart']) ? $_SESSION['cart'] : [];
        require 'views/cart/index.php';
    }
    public function update() {
        if (isset($_POST['id'], $_POST['cantidad'])) {
            $id = intval($_POST['id']);
            $cantidad = max(1, intval($_POST['cantidad']));
            $product = new Product();
            $prod = $product->getById($id);
            if ($prod && isset($_SESSION['cart'][$id])) {
                $_SESSION['cart'][$id]['cantidad'] = min($cantidad, $prod['stock']);
            }
        }
        header('Location: index.php?controller=CartController&action=index');
        exit;
    }
    public function remove() {
        if (isset($_GET['id']) && isset($_SESSION['cart'][$_GET['id']])) {
            unset($_SESSION['cart'][$_GET['id']]);
        }
        header('Location: index.php?controller=CartController&action=index');
        exit;
    }
    public function clear() {
        unset($_SESSION['cart']);
        header('Location: index.php?controller=CartController&action=index');
        exit;
    }
} 