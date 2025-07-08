<?php
class ProductController {
    private function isAdmin() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }

    public function adminList() {
        if (!$this->isAdmin()) {
            header('Location: index.php'); exit;
        }
        $product = new Product();
        $productos = $product->getAll();
        require 'views/products/list.php';
    }

    public function create() {
        if (!$this->isAdmin()) {
            header('Location: index.php'); exit;
        }
        $category = new Category();
        $categorias = $category->getAll();
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $precio = floatval($_POST['precio']);
            $descripcion = trim($_POST['descripcion']);
            $category_id = intval($_POST['category_id']);
            $stock = min(10, max(1, intval($_POST['stock'])));

            // Validación de precio negativo
            if ($precio < 0) {
                $_SESSION['error'] = 'El precio no puede ser negativo.';
                require 'views/products/form.php';
                return;
            }

            // Validación de stock
            if ($stock < 1) $stock = 1;

            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreImg = uniqid() . '_' . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], 'assets/images/' . $nombreImg);
                $imagen = $nombreImg;
            }
            $product = new Product();
            if ($product->create($nombre, $precio, $descripcion, $imagen, $category_id, $stock)) {
                $_SESSION['success'] = 'Producto creado exitosamente.';
                header('Location: index.php?controller=ProductController&action=adminList'); 
                exit;
            } else {
                $_SESSION['error'] = 'Error al crear el producto.';
            }
        }
        require 'views/products/form.php';
    }

    public function edit() {
        if (!$this->isAdmin()) {
            header('Location: index.php'); exit;
        }
        $product = new Product();
        $category = new Category();
        $categorias = $category->getAll();
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=ProductController&action=adminList'); exit;
        }
        $id = intval($_GET['id']);
        $prod = $product->getById($id);
        if (!$prod) {
            header('Location: index.php?controller=ProductController&action=adminList'); exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $precio = floatval($_POST['precio']);
            $descripcion = trim($_POST['descripcion']);
            $category_id = intval($_POST['category_id']);
            $stock = min(10, max(1, intval($_POST['stock'])));

            // Validación de precio negativo
            if ($precio < 0) {
                $_SESSION['error'] = 'El precio no puede ser negativo.';
                require 'views/products/form.php';
                return;
            }

            // Validación de stock
            if ($stock < 1) $stock = 1;

            $imagen = $prod['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreImg = uniqid() . '_' . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], 'assets/images/' . $nombreImg);
                $imagen = $nombreImg;
            }
            if ($product->update($id, $nombre, $precio, $descripcion, $imagen, $category_id, $stock)) {
                $_SESSION['success'] = 'Producto actualizado exitosamente.';
                header('Location: index.php?controller=ProductController&action=adminList'); 
                exit;
            } else {
                $_SESSION['error'] = 'Error al actualizar el producto.';
            }
        }
        require 'views/products/form.php';
    }

    public function delete() {
        if (!$this->isAdmin()) {
            header('Location: index.php'); 
            exit;
        }

        if (isset($_GET['id'])) {
            try {
                $product = new Product();
                $id = intval($_GET['id']);

                if ($product->delete($id)) {
                    $_SESSION['success'] = "Producto eliminado exitosamente.";
                } else {
                    $_SESSION['error'] = "Error al eliminar el producto.";
                }
            } catch (Exception $e) {
                $_SESSION['error'] = $e->getMessage();
            }
        } else {
            $_SESSION['error'] = "ID de producto no proporcionado.";
        }

        header('Location: index.php?controller=ProductController&action=adminList'); 
        exit;
    }

    public function byCategory() {
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=ProductController&action=adminList'); exit;
        }
        $category_id = intval($_GET['id']);
        $product = new Product();
        $productos = $product->getByCategory($category_id);
        $categoryModel = new Category();
        $cat = $categoryModel->getById($category_id);
        require 'views/products/by_category.php';
    }

    public function detail() {
        if (!isset($_GET['id'])) {
            header('Location: index.php'); exit;
        }
        $id = intval($_GET['id']);
        $product = new Product();
        $prod = $product->getById($id);
        if (!$prod) {
            header('Location: index.php'); exit;
        }
        // Obtener productos sugeridos
        $sugerencias = $product->getSugerencias($id);
        require 'views/products/detail.php';
    }

    public function publicList() {
        require 'views/products/public_list.php';
    }
}
