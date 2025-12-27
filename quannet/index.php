<?php

/**
 * =============================================
 * HỆ THỐNG QUẢN LÝ TÀI KHOẢN QUÁN NET
 * =============================================
 * Entry point của ứng dụng
 * Xử lý routing và load controller tương ứng
 * =============================================
 */

// Start session
session_start();

// Define constants
define('BASE_URL', 'http://localhost/quannet/');
define('ROOT_PATH', __DIR__ . '/');

// Load config
require_once ROOT_PATH . 'config/database.php';

// Load core classes
require_once ROOT_PATH . 'core/Database.php';
require_once ROOT_PATH . 'core/Controller.php';
require_once ROOT_PATH . 'core/Auth.php';

// Get URL parameters
$module = $_GET['module'] ?? 'accounts';
$action = $_GET['action'] ?? 'index';

// Public routes (không cần đăng nhập)
$publicRoutes = ['auth'];

// Kiểm tra authentication
if (!in_array($module, $publicRoutes)) {
    Auth::requireLogin();
}

// Map module name to controller name
if ($module === 'accounts') {
    $controllerName = 'AccountController';
} elseif ($module === 'auth') {
    $controllerName = 'AuthController';
} else {
    $controllerName = ucfirst($module) . 'Controller';
}

// Load controller
$controllerPath = ROOT_PATH . 'modules/' . $module . '/controllers/' . $controllerName . '.php';

if (file_exists($controllerPath)) {
    require_once $controllerPath;
    
    $controller = new $controllerName();
    
    // Check if action exists
    if (method_exists($controller, $action)) {
        $controller->$action();
    } else {
        die("Action không tồn tại: {$action}");
    }
} else {
    die("Controller không tồn tại: {$controllerPath}");
}
