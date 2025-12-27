<?php
/**
 * Admin Model
 * Xử lý authentication và quản lý admin
 */

class AdminModel {
    private $db;
    private $table = 'admins';
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Tìm admin theo username
     */
    public function findByUsername($username) {
        $sql = "SELECT * FROM {$this->table} WHERE username = ? AND status = 1";
        $result = $this->db->query($sql, [$username]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Tìm admin theo ID
     */
    public function findById($id) {
        $sql = "SELECT * FROM {$this->table} WHERE id = ?";
        $result = $this->db->query($sql, [$id]);
        return $result ? $result[0] : null;
    }
    
    /**
     * Xác thực thông tin đăng nhập
     */
    public function verifyCredentials($username, $password) {
        $admin = $this->findByUsername($username);
        
        if (!$admin) {
            return false;
        }
        
        // Kiểm tra password
        if (password_verify($password, $admin['password'])) {
            return $admin;
        }
        
        return false;
    }
    
    /**
     * Cập nhật thời gian đăng nhập cuối
     */
    public function updateLastLogin($id) {
        $sql = "UPDATE {$this->table} SET last_login = NOW() WHERE id = ?";
        return $this->db->execute($sql, [$id]);
    }
    
    /**
     * Lấy tất cả admin
     */
    public function getAll() {
        $sql = "SELECT id, username, fullname, email, role, status, last_login, created_at 
                FROM {$this->table} 
                ORDER BY created_at DESC";
        return $this->db->query($sql);
    }
    
    /**
     * Đếm số lượng admin
     */
    public function count() {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        $result = $this->db->query($sql);
        return $result ? $result[0]['total'] : 0;
    }
}
