<?php
/**
 * Auth Controller
 * Xử lý đăng nhập, đăng xuất
 */

class AuthController extends Controller {
    private $model;
    
    public function __construct() {
        $this->model = $this->loadModel('AdminModel', 'auth');
    }
    
    /**
     * Hiển thị form đăng nhập
     */
    public function login() {
        // Nếu đã đăng nhập, redirect về trang chủ
        if (Auth::check()) {
            header('Location: ?module=accounts&action=index');
            exit;
        }
        
        // Hiển thị form login
        $this->loadView('login', [], 'auth');
    }
    
    /**
     * Xử lý đăng nhập
     */
    public function doLogin() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ?module=auth&action=login');
            exit;
        }
        
        // Lấy dữ liệu từ form
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);
        
        // Validation
        $errors = [];
        
        if (empty($username)) {
            $errors[] = 'Vui lòng nhập tên đăng nhập!';
        }
        
        if (empty($password)) {
            $errors[] = 'Vui lòng nhập mật khẩu!';
        }
        
        // Nếu có lỗi validation
        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old_username'] = $username;
            header('Location: ?module=auth&action=login');
            exit;
        }
        
        // Xác thực thông tin đăng nhập
        $admin = $this->model->verifyCredentials($username, $password);
        
        if (!$admin) {
            $_SESSION['error'] = 'Tên đăng nhập hoặc mật khẩu không đúng!';
            $_SESSION['old_username'] = $username;
            header('Location: ?module=auth&action=login');
            exit;
        }
        
        // Đăng nhập thành công
        Auth::login($admin);
        
        // Cập nhật last_login
        $this->model->updateLastLogin($admin['id']);
        
        // Set remember me cookie (7 ngày)
        if ($remember) {
            setcookie('remember_admin', $admin['username'], time() + (7 * 24 * 60 * 60), '/');
        }
        
        // Redirect về trang chủ
        $_SESSION['success'] = 'Đăng nhập thành công! Xin chào ' . $admin['fullname'];
        header('Location: ?module=accounts&action=index');
        exit;
    }
    
    /**
     * Đăng xuất
     */
    public function logout() {
        // Xóa remember cookie
        if (isset($_COOKIE['remember_admin'])) {
            setcookie('remember_admin', '', time() - 3600, '/');
        }
        
        // Đăng xuất
        Auth::logout();
        
        // Restart session để có thể set success message
        session_start();
        
        // Redirect về trang login
        $_SESSION['success'] = 'Đăng xuất thành công!';
        header('Location: ?module=auth&action=login');
        exit;
    }
}
