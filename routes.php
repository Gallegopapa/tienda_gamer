<?php
require_once 'models/Auth.php';

$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
$segments = explode('/', $url);

// Rutas definidas (alias => [controlador, método, requiere_admin])
$routes = [
    // Rutas públicas
    '' => ['HomeController', 'index', false],
    'inicio' => ['HomeController', 'index', false],
    'productos' => ['ProductController', 'publicList', false],
    'productos/categoria' => ['ProductController', 'byCategory', false],
    'productos/detalle' => ['ProductController', 'detail', false],
    'categorias' => ['CategoryController', 'publicList', false],
    'categorias/ver' => ['CategoryController', 'show', false],
    
    // Rutas de autenticación
    'login' => ['AuthController', 'login', false],
    'logout' => ['AuthController', 'logout', false],
    'registro' => ['AuthController', 'register', false],
    
    // Rutas de usuario (requieren autenticación)
    'cuenta' => ['UserController', 'account', false],
    'cuenta/actualizar' => ['UserController', 'updateAccount', false],
    
    // Rutas de carrito
    'carrito' => ['CartController', 'index', false],
    'carrito/agregar' => ['CartController', 'add', false],
    'carrito/eliminar' => ['CartController', 'remove', false],
    'carrito/actualizar' => ['CartController', 'update', false],
    
    // Rutas de pedidos (requieren autenticación)
    'pedidos' => ['OrderController', 'list', false],
    'pedidos/crear' => ['OrderController', 'checkout', false],
    'pedidos/cancelar' => ['OrderController', 'cancel', false],
    'pedidos/eliminar' => ['OrderController', 'delete', false],
    
    // Rutas de administración (requieren rol de admin)
    'admin/productos' => ['ProductController', 'adminList', true],
    'admin/productos/crear' => ['ProductController', 'create', true],
    'admin/productos/editar' => ['ProductController', 'edit', true],
    'admin/productos/eliminar' => ['ProductController', 'delete', true],
    
    'admin/categorias' => ['CategoryController', 'list', true],
    'admin/categorias/crear' => ['CategoryController', 'create', true],
    'admin/categorias/editar' => ['CategoryController', 'edit', true],
    'admin/categorias/eliminar' => ['CategoryController', 'delete', true],
    
    'admin/pedidos' => ['OrderController', 'adminList', true],
    'admin/pedidos/estado' => ['OrderController', 'updateStatus', true],
    
    'admin/usuarios' => ['UserController', 'adminList', true]
];

// Extrae ruta base (clave)
$routeKey = $url === '' ? '' : (isset($segments[0]) ? $segments[0] : 'inicio');
if (isset($segments[1])) {
    $tempKey = $routeKey . '/' . $segments[1];
    if (isset($routes[$tempKey])) {
        $routeKey = $tempKey;
    }
}

// Verifica si existe la ruta
if (isset($routes[$routeKey])) {
    list($controllerName, $methodName, $requireAdmin) = $routes[$routeKey];
    
    // Verificar permisos de administrador si es necesario
    if ($requireAdmin) {
        Auth::checkAdmin();
    }

    // Cargar controlador
    $controllerFile = 'controllers/' . $controllerName . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();
        
        // Extrae parámetros extra de la URL
        $params = array_slice($segments, count(explode('/', $routeKey)));
        
        if (method_exists($controller, $methodName)) {
            // Verifica si se necesitan parámetros GET
            $reflection = new ReflectionMethod($controller, $methodName);
            $parameters = $reflection->getParameters();
            
            // Si el método espera parámetros pero no hay suficientes en la URL
            if (count($parameters) > count($params)) {
                $missingParams = [];
                for ($i = count($params); $i < count($parameters); $i++) {
                    $param = $parameters[$i];
                    if (!$param->isOptional() && !isset($_GET[$param->getName()])) {
                        $missingParams[] = $param->getName();
                    }
                }
                
                if (!empty($missingParams)) {
                    http_response_code(400);
                    require 'views/error404.php';
                    exit;
                }
            }
            
            call_user_func_array([$controller, $methodName], $params);
        } else {
            http_response_code(404);
            require 'views/error404.php';
        }
    } else {
        http_response_code(404);
        require 'views/error404.php';
    }
} else {
    http_response_code(404);
    require 'views/error404.php';
}
