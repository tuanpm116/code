<?php
/**
 * Script debug kết nối database và kiểm tra tài khoản admin
 * Chạy script này trên cả 2 máy để so sánh
 */

echo "=== DEBUG DATABASE CONNECTION ===\n\n";

// Load config
require_once __DIR__ . '/config/database.php';

echo "📋 THÔNG TIN KẾT NỐI:\n";
echo str_repeat("-", 80) . "\n";
echo "Database Host: " . DB_HOST . "\n";
echo "Database Name: " . DB_NAME . "\n";
echo "Database User: " . DB_USER . "\n";
echo "Database Pass: " . (DB_PASS ? '***' : '(empty)') . "\n";
echo "Database Charset: " . DB_CHARSET . "\n";
echo str_repeat("-", 80) . "\n\n";

// Thử kết nối
try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ KẾT NỐI DATABASE THÀNH CÔNG!\n\n";
    
    // Kiểm tra bảng admins
    $stmt = $pdo->query("SHOW TABLES LIKE 'admins'");
    $tableExists = $stmt->fetch();
    
    if (!$tableExists) {
        echo "❌ BẢNG 'admins' KHÔNG TỒN TẠI!\n";
        echo "💡 Bạn cần chạy file database.sql để tạo bảng.\n";
        exit;
    }
    
    echo "✅ Bảng 'admins' tồn tại\n\n";
    
    // Lấy danh sách admin
    $stmt = $pdo->query("SELECT id, username, fullname, email, role, status, 
                         LEFT(password, 20) as password_preview,
                         created_at 
                         FROM admins ORDER BY id");
    $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($admins)) {
        echo "❌ KHÔNG CÓ TÀI KHOẢN ADMIN NÀO!\n";
        echo "💡 Chạy script create_new_admin.php để tạo tài khoản.\n";
        exit;
    }
    
    echo "📊 DANH SÁCH ADMIN TRONG DATABASE:\n";
    echo str_repeat("-", 80) . "\n";
    printf("%-5s %-15s %-25s %-20s %-10s %-10s\n", 
           "ID", "Username", "Fullname", "Password Preview", "Role", "Status");
    echo str_repeat("-", 80) . "\n";
    
    foreach ($admins as $admin) {
        $statusText = $admin['status'] == 1 ? '✅ Active' : '❌ Inactive';
        printf("%-5s %-15s %-25s %-20s %-10s %-10s\n",
               $admin['id'],
               $admin['username'],
               $admin['fullname'],
               $admin['password_preview'] . '...',
               $admin['role'],
               $statusText
        );
    }
    
    echo str_repeat("-", 80) . "\n\n";
    
    // Test password cho từng admin
    echo "🔐 TEST PASSWORD (password123):\n";
    echo str_repeat("-", 80) . "\n";
    
    $testPassword = 'password123';
    
    foreach ($admins as $admin) {
        // Lấy password đầy đủ
        $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = ?");
        $stmt->execute([$admin['id']]);
        $fullAdmin = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $verify = password_verify($testPassword, $fullAdmin['password']);
        $result = $verify ? '✅ ĐÚNG' : '❌ SAI';
        
        echo "ID {$admin['id']} - Username: {$admin['username']}\n";
        echo "  Status: " . ($admin['status'] == 1 ? 'Active' : 'Inactive') . "\n";
        echo "  Password verify: {$result}\n";
        
        if (!$verify) {
            echo "  ⚠️  PASSWORD HASH: {$fullAdmin['password']}\n";
        }
        echo "\n";
    }
    
    echo str_repeat("=", 80) . "\n";
    echo "💾 THÔNG TIN HỆ THỐNG:\n";
    echo "  - PHP Version: " . phpversion() . "\n";
    echo "  - Server: " . php_uname() . "\n";
    echo "  - Script Path: " . __FILE__ . "\n";
    echo str_repeat("=", 80) . "\n";
    
} catch (PDOException $e) {
    echo "❌ LỖI KẾT NỐI DATABASE!\n";
    echo "Error: " . $e->getMessage() . "\n\n";
    echo "💡 KIỂM TRA:\n";
    echo "1. MySQL server có đang chạy không?\n";
    echo "2. Thông tin kết nối trong config/database.php có đúng không?\n";
    echo "3. Database '" . DB_NAME . "' đã được tạo chưa?\n";
}
