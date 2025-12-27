<?php
/**
 * Auth Helper Class
 * Xử lý authentication và session management
 */

class Auth {
    /**
     * Kiểm tra admin đã đăng nhập chưa
     */
    public static function check() {
        return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
    }
    
    /**
     * Lấy thông tin admin hiện tại
     */
    public static function user() {
        if (!self::check()) {
            return null;
        }
        
        return [
            'id' => $_SESSION['admin_id'] ?? null,
            'username' => $_SESSION['admin_username'] ?? null,
            'fullname' => $_SESSION['admin_fullname'] ?? null,
            'email' => $_SESSION['admin_email'] ?? null,
            'role' => $_SESSION['admin_role'] ?? null,
        ];
    }
    
    /**
     * Lấy ID admin hiện tại
     */
    public static function id() {
        return $_SESSION['admin_id'] ?? null;
    }
    
    /**
     * Đăng nhập
     */
    public static function login($admin) {
        // Regenerate session ID để tránh session fixation
        session_regenerate_id(true);
        
        // Lưu thông tin vào session
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        $_SESSION['admin_username'] = $admin['username'];
        $_SESSION['admin_fullname'] = $admin['fullname'];
        $_SESSION['admin_email'] = $admin['email'];
        $_SESSION['admin_role'] = $admin['role'];
        $_SESSION['admin_login_time'] = time();
        
        return true;
    }
    
    /**
     * Đăng xuất
     */
    public static function logout() {
        // Xóa tất cả session variables
        $_SESSION = [];
        
        // Xóa session cookie
        if (isset($_COOKIE[session_name()])) {
            setcookie(session_name(), '', time() - 3600, '/');
        }
        
        // Destroy session
        session_destroy();
        
        return true;
    }
    
    /**
     * Kiểm tra session timeout (30 phút)
     */
    public static function checkTimeout() {
        if (!self::check()) {
            return false;
        }
        
        $timeout = 1800; // 30 phút
        $loginTime = $_SESSION['admin_login_time'] ?? 0;
        
        if (time() - $loginTime > $timeout) {
            self::logout();
            return false;
        }
        
        // Cập nhật thời gian hoạt động
        $_SESSION['admin_login_time'] = time();
        return true;
    }
    
    /**
     * Redirect nếu chưa đăng nhập
     */
    public static function requireLogin() {
        if (!self::check() || !self::checkTimeout()) {
            header('Location: ?module=auth&action=login');
            exit;
        }
    }
}
