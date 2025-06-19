<?php

$url = isset($_GET['url']) ? trim($_GET['url'], '/') : '';
$segments = explode('/', $url);

// Rutas definidas (alias => [controlador, método])
$routes = [
    'inicio' => ['HomeController', 'index'],
    'login' => ['UserController', 'login'],
    'logout' => ['UserController', 'logout'],
    'registro' => ['UserController', 'register'],
    'pedidos' => ['OrderController', 'list'],
    'pedidos/ver' => ['OrderController', 'view'],       // ej: /pedidos/ver/123
    'pedidos/crear' => ['OrderController', 'checkout'],
    'pedidos/cancelar' => ['OrderController', 'cancel'],
    'admin/pedidos' => ['OrderController', 'adminList'],
    'admin/pedidos/estado' => ['OrderController', 'updateStatus'],
    // Agrega más rutas aquí...
];

// Extrae ruta base (clave)
$routeKey = isset($segments[0]) ? $segments[0] : 'inicio';
$routeKey .= isset($segments[1]) ? '/' . $segments[1] : '';

// Verifica si existe la ruta
if (isset($routes[$routeKey])) {
    list($controllerName, $methodName) = $routes[$routeKey];

    // Cargar controlador
    $controllerFile = 'controllers/' . $controllerName . '.php';
    if (file_exists($controllerFile)) {
        require_once $controllerFile;
        $controller = new $controllerName();

        // Extrae parámetros extra (ej: /pedidos/ver/123 → [123])
        $params = array_slice($segments, 2);

        if (method_exists($controller, $methodName)) {
            call_user_func_array([$controller, $methodName], $params);
        } else {
            http_response_code(404);
            echo "Método '$methodName' no encontrado.";
        }
    } else {
        http_response_code(404);
        echo "Controlador '$controllerName' no encontrado.";
    }
} else {
    http_response_code(404);
    echo "Ruta no definida.";
}
