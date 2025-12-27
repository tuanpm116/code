<?php

/**
 * Class Database
 * Xử lý kết nối và thao tác với database sử dụng PDO
 */
class Database {
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $dbname = DB_NAME;
    private $charset = DB_CHARSET;
    
    private $conn;
    private $stmt;
    private $error;
    
    /**
     * Kết nối database
     */
    public function __construct() {
        $dsn = "mysql:host={$this->host};dbname={$this->dbname};charset={$this->charset}";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        try {
            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            $this->error = $e->getMessage();
            die("Lỗi kết nối database: " . $this->error);
        }
    }
    
    /**
     * Chuẩn bị câu query (fluent interface)
     */
    public function prepare($sql) {
        $this->stmt = $this->conn->prepare($sql);
        return $this;
    }
    
    /**
     * Query với tham số (helper method)
     * Sử dụng: $db->query($sql, [$param1, $param2])
     */
    public function query($sql, $params = []) {
        $this->stmt = $this->conn->prepare($sql);
        
        // Bind parameters
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->stmt->bindValue($key + 1, $value);
            }
        }
        
        $this->stmt->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Execute query (INSERT, UPDATE, DELETE)
     * Sử dụng: $db->execute($sql, [$param1, $param2])
     */
    public function execute($sql, $params = []) {
        $this->stmt = $this->conn->prepare($sql);
        
        // Bind parameters
        if (!empty($params)) {
            foreach ($params as $key => $value) {
                $this->stmt->bindValue($key + 1, $value);
            }
        }
        
        return $this->stmt->execute();
    }
    
    /**
     * Bind giá trị vào query
     */
    public function bind($param, $value, $type = null) {
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
        return $this;
    }
    
    /**
     * Thực thi query (fluent interface)
     */
    public function run() {
        return $this->stmt->execute();
    }
    
    /**
     * Lấy nhiều bản ghi
     */
    public function fetchAll() {
        $this->stmt->execute();
        return $this->stmt->fetchAll();
    }
    
    /**
     * Lấy một bản ghi
     */
    public function fetch() {
        $this->stmt->execute();
        return $this->stmt->fetch();
    }
    
    /**
     * Đếm số bản ghi
     */
    public function rowCount() {
        return $this->stmt->rowCount();
    }
    
    /**
     * Lấy ID của bản ghi vừa insert
     */
    public function lastInsertId() {
        return $this->conn->lastInsertId();
    }
    
    /**
     * Bắt đầu transaction
     */
    public function beginTransaction() {
        return $this->conn->beginTransaction();
    }
    
    /**
     * Commit transaction
     */
    public function commit() {
        return $this->conn->commit();
    }
    
    /**
     * Rollback transaction
     */
    public function rollBack() {
        return $this->conn->rollBack();
    }
}
