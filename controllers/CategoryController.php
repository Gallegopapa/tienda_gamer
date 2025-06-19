<?php
class CategoryController {
    private function isAdmin() {
        return isset($_SESSION['user_rol']) && $_SESSION['user_rol'] === 'admin';
    }
    public function list() {
        if (!$this->isAdmin()) {
            header('Location: index.php');
            exit;
        }
        $category = new Category();
        $categorias = $category->getAll();
        require 'views/categories/list.php';
    }
    public function create() {
        if (!$this->isAdmin()) {
            header('Location: index.php');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $imagen = '';
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreImg = uniqid() . '_' . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], 'assets/images/' . $nombreImg);
                $imagen = $nombreImg;
            }
            $category = new Category();
            if ($category->create($nombre)) {
                $cat_id = $category->getLastInsertId();
                if ($imagen) $category->updateImage($cat_id, $imagen);
                header('Location: index.php?controller=CategoryController&action=list');
                exit;
            } else {
                $error = 'Error al crear la categoría.';
            }
        }
        require 'views/categories/form.php';
    }
    public function edit() {
        if (!$this->isAdmin()) {
            header('Location: index.php');
            exit;
        }
        $category = new Category();
        if (!isset($_GET['id'])) {
            header('Location: index.php?controller=CategoryController&action=list');
            exit;
        }
        $id = intval($_GET['id']);
        $cat = $category->getById($id);
        if (!$cat) {
            header('Location: index.php?controller=CategoryController&action=list');
            exit;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nombre = trim($_POST['nombre']);
            $imagen = $cat['imagen'];
            if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] === UPLOAD_ERR_OK) {
                $nombreImg = uniqid() . '_' . basename($_FILES['imagen']['name']);
                move_uploaded_file($_FILES['imagen']['tmp_name'], 'assets/images/' . $nombreImg);
                $imagen = $nombreImg;
                $category->updateImage($id, $imagen);
            }
            if ($category->update($id, $nombre)) {
                header('Location: index.php?controller=CategoryController&action=list');
                exit;
            } else {
                $error = 'Error al actualizar la categoría.';
            }
        }
        require 'views/categories/form.php';
    }
    public function delete() {
        if (!$this->isAdmin()) {
            header('Location: index.php');
            exit;
        }
        if (isset($_GET['id'])) {
            $category = new Category();
            $category->delete(intval($_GET['id']));
        }
        header('Location: index.php?controller=CategoryController&action=list');
        exit;
    }
    public function show() {
        if (!isset($_GET['id'])) {
            header('Location: index.php'); exit;
        }
        $category = new Category();
        $cat = $category->getById(intval($_GET['id']));
        if (!$cat) {
            header('Location: index.php'); exit;
        }
        $product = new Product();
        $productos = $product->getByCategory($cat['id']);
        require 'views/categories/show.php';
    }
} 