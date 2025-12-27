<?php

/**
 * Class Controller
 * Base controller cho tất cả các controller khác
 */
class Controller {
    
    /**
     * Load model
     */
    protected function model($model) {
        // Map module name to model file name
        $modelName = 'AccountModel'; // For accounts module
        $modelPath = __DIR__ . '/../modules/' . $model . '/models/' . $modelName . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $modelName();
        } else {
            die("Model không tồn tại: " . $modelPath);
        }
    }
    
    /**
     * Load model (new flexible version)
     */
    protected function loadModel($modelName, $module = null) {
        if ($module === null) {
            $module = 'accounts'; // default
        }
        
        $modelPath = __DIR__ . '/../modules/' . $module . '/models/' . $modelName . '.php';
        
        if (file_exists($modelPath)) {
            require_once $modelPath;
            return new $modelName();
        } else {
            die("Model không tồn tại: " . $modelPath);
        }
    }
    
    /**
     * Load view (new flexible version)
     */
    protected function loadView($viewName, $data = [], $module = null) {
        if ($module === null) {
            $module = 'accounts'; // default
        }
        
        extract($data);
        
        $viewPath = __DIR__ . '/../modules/' . $module . '/views/' . $viewName . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View không tồn tại: " . $viewPath);
        }
    }
    
    /**
     * Load view
     */
    protected function view($view, $data = []) {
        extract($data);
        
        $viewPath = __DIR__ . '/../modules/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            require_once $viewPath;
        } else {
            die("View không tồn tại: " . $viewPath);
        }
    }
    
    /**
     * Redirect
     */
    protected function redirect($url) {
        header('Location: ' . BASE_URL . $url);
        exit();
    }
    
    /**
     * Set flash message
     */
    protected function setFlash($type, $message) {
        $_SESSION['flash'] = [
            'type' => $type,
            'message' => $message
        ];
    }
    
    /**
     * Get flash message
     */
    protected function getFlash() {
        if (isset($_SESSION['flash'])) {
            $flash = $_SESSION['flash'];
            unset($_SESSION['flash']);
            return $flash;
        }
        return null;
    }
}
