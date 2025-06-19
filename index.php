<?php
// Front controller: enruta las peticiones a los controladores correspondientes
session_start();

require_once 'config.php';

// Autocarga de controladores y modelos
spl_autoload_register(function ($class) {
    if (file_exists("controllers/$class.php")) {
        require_once "controllers/$class.php";
    } elseif (file_exists("models/$class.php")) {
        require_once "models/$class.php";
    }
});

// Obtener controlador y acción de la URL
$controller = isset($_GET['controller']) ? $_GET['controller'] : 'HomeController';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

if (class_exists($controller)) {
    $obj = new $controller();
    if (method_exists($obj, $action)) {
        $obj->$action();
    } else {
        echo 'Acción no encontrada';
    }
} else {
    echo 'Controlador no encontrado';
} 