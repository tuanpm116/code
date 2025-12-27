<?php

/**
 * Class AccountModel
 * Xử lý các thao tác CRUD cho tài khoản
 */
class AccountModel {
    private $db;
    
    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Lấy tất cả tài khoản
     */
    public function getAll() {
        $this->db->prepare("SELECT * FROM accounts ORDER BY created_at DESC");
        return $this->db->fetchAll();
    }
    
    /**
     * Lấy tài khoản theo ID
     */
    public function getById($id) {
        $this->db->prepare("SELECT * FROM accounts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->fetch();
    }
    
    /**
     * Kiểm tra username đã tồn tại chưa
     */
    public function checkUsernameExists($username, $excludeId = null) {
        if ($excludeId) {
            $this->db->prepare("SELECT id FROM accounts WHERE username = :username AND id != :id");
            $this->db->bind(':username', $username);
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->prepare("SELECT id FROM accounts WHERE username = :username");
            $this->db->bind(':username', $username);
        }
        
        return $this->db->fetch() ? true : false;
    }
    
    /**
     * Kiểm tra số điện thoại đã tồn tại chưa
     */
    public function checkPhoneExists($phone, $excludeId = null) {
        // Nếu phone rỗng, không cần kiểm tra
        if (empty($phone)) {
            return false;
        }
        
        if ($excludeId) {
            $this->db->prepare("SELECT id FROM accounts WHERE phone = :phone AND id != :id");
            $this->db->bind(':phone', $phone);
            $this->db->bind(':id', $excludeId);
        } else {
            $this->db->prepare("SELECT id FROM accounts WHERE phone = :phone");
            $this->db->bind(':phone', $phone);
        }
        
        return $this->db->fetch() ? true : false;
    }
    
    /**
     * Thêm tài khoản mới
     */
    public function create($data) {
        $this->db->prepare("INSERT INTO accounts (username, password, phone, balance, status) 
                         VALUES (:username, :password, :phone, :balance, :status)");
        
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':balance', $data['balance']);
        $this->db->bind(':status', $data['status']);
        
        if ($this->db->run()) {
            return $this->db->lastInsertId();
        }
        return false;
    }
    
    /**
     * Cập nhật tài khoản
     */
    public function update($id, $data) {
        // Nếu có password mới thì update, không thì giữ nguyên
        if (!empty($data['password'])) {
            $sql = "UPDATE accounts SET 
                    username = :username,
                    password = :password,
                    phone = :phone,
                    balance = :balance,
                    status = :status
                    WHERE id = :id";
            
            $this->db->prepare($sql);
            $this->db->bind(':password', password_hash($data['password'], PASSWORD_DEFAULT));
        } else {
            $sql = "UPDATE accounts SET 
                    username = :username,
                    phone = :phone,
                    balance = :balance,
                    status = :status
                    WHERE id = :id";
            
            $this->db->prepare($sql);
        }
        
        $this->db->bind(':id', $id);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':balance', $data['balance']);
        $this->db->bind(':status', $data['status']);
        
        return $this->db->run();
    }
    
    /**
     * Xóa tài khoản
     */
    public function delete($id) {
        $this->db->prepare("DELETE FROM accounts WHERE id = :id");
        $this->db->bind(':id', $id);
        return $this->db->run();
    }
    
    /**
     * Tìm kiếm tài khoản
     */
    public function search($keyword) {
        $this->db->prepare("SELECT * FROM accounts 
                         WHERE username LIKE :keyword 
                         OR fullname LIKE :keyword 
                         OR email LIKE :keyword 
                         OR phone LIKE :keyword
                         ORDER BY created_at DESC");
        
        $this->db->bind(':keyword', '%' . $keyword . '%');
        return $this->db->fetchAll();
    }
    
    /**
     * Đếm tổng số tài khoản
     */
    public function countAll() {
        $this->db->prepare("SELECT COUNT(*) as total FROM accounts");
        $result = $this->db->fetch();
        return $result['total'];
    }
    
    /**
     * Đếm tài khoản active
     */
    public function countActive() {
        $this->db->prepare("SELECT COUNT(*) as total FROM accounts WHERE status = 1");
        $result = $this->db->fetch();
        return $result['total'];
    }
}
