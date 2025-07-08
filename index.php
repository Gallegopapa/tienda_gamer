<?php
session_start();

// Configuración de errores
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('log_errors', 1);
ini_set('error_log', 'logs/error.log');

// Cargar configuración
require_once 'config.php';

// Cargar modelos y controladores necesarios
require_once 'models/Database.php';
require_once 'models/User.php';
require_once 'models/Product.php';
require_once 'models/Category.php';
require_once 'models/Order.php';

require_once 'controllers/HomeController.php';
require_once 'controllers/AuthController.php';
require_once 'controllers/UserController.php';
require_once 'controllers/ProductController.php';
require_once 'controllers/CategoryController.php';
require_once 'controllers/CartController.php';
require_once 'controllers/OrderController.php';

// Definir constantes
define('BASE_URL', '/tienda_gamer');

// Procesar la solicitud
if (isset($_GET['controller']) && isset($_GET['action'])) {
    // Formato antiguo de URL
    $controllerName = $_GET['controller'];
    $actionName = $_GET['action'];
    
    // Verificar que el controlador existe
    if (file_exists("controllers/$controllerName.php")) {
        $controller = new $controllerName();
        if (method_exists($controller, $actionName)) {
            // Llamar al método con los parámetros GET restantes
            $params = $_GET;
            unset($params['controller'], $params['action']);
            
            // Convertir params a array indexado para call_user_func_array
            $methodParams = [];
            if ($actionName === 'detail' || $actionName === 'byCategory' || $actionName === 'show') {
                if (isset($params['id'])) {
                    $methodParams[] = $params['id'];
                    unset($params['id']);
                }
            }
            
            // Agregar parámetros restantes
            foreach ($params as $value) {
                $methodParams[] = $value;
            }
            
            call_user_func_array([$controller, $actionName], $methodParams);
            exit;
        }
    }
}

// Si no es una URL antigua o no se encontró el controlador/acción, procesar como URL nueva
require_once 'routes.php'; 