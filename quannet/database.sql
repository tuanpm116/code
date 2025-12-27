-- =============================================
-- HỆ THỐNG QUẢN LÝ TÀI KHOẢN QUÁN NET
-- Database: quannet_db
-- =============================================

-- Tạo database
CREATE DATABASE IF NOT EXISTS quannet_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE quannet_db;

-- Bảng tài khoản khách hàng
CREATE TABLE IF NOT EXISTS accounts (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    balance DECIMAL(10,2) DEFAULT 0.00,
    status TINYINT(1) DEFAULT 1 COMMENT '1: Active, 0: Inactive',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm dữ liệu mẫu
INSERT INTO accounts (username, password, phone, balance, status) VALUES
('user001', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0901234567', 50000.00, 1),
('user002', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0912345678', 75000.00, 1),
('user003', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0923456789', 30000.00, 1),
('user004', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0934567890', 0.00, 0),
('user005', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '0945678901', 120000.00, 1);

-- Password mặc định cho tất cả tài khoản mẫu: password123

-- =============================================
-- Bảng quản trị viên (Admin)
-- =============================================
CREATE TABLE IF NOT EXISTS admins (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    fullname VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    role VARCHAR(20) DEFAULT 'admin' COMMENT 'admin, superadmin',
    status TINYINT(1) DEFAULT 1 COMMENT '1: Active, 0: Inactive',
    last_login TIMESTAMP NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_username (username),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Thêm tài khoản admin mặc định
INSERT INTO admins (username, password, fullname, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'admin@quannet.com', 'admin');

-- Password mặc định cho admin: password123
