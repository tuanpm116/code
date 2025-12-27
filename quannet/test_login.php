<?php
/**
 * Script test đăng nhập chi tiết
 * Giúp debug vấn đề đăng nhập không thành công
 */

// Load config và core
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/modules/auth/models/AdminModel.php';

echo "=== TEST ĐĂNG NHẬP CHI TIẾT ===\n\n";

// Thông tin đăng nhập cần test
$testUsername = 'admin2';  // Thay đổi username cần test
$testPassword = 'password123';  // Thay đổi password cần test

echo "📝 THÔNG TIN TEST:\n";
echo str_repeat("-", 80) . "\n";
echo "Username: {$testUsername}\n";
echo "Password: {$testPassword}\n";
echo str_repeat("-", 80) . "\n\n";

// Khởi tạo model
$adminModel = new AdminModel();

echo "🔍 BƯỚC 1: Tìm admin theo username...\n";
$admin = $adminModel->findByUsername($testUsername);

if (!$admin) {
    echo "❌ KHÔNG TÌM THẤY ADMIN!\n";
    echo "💡 Nguyên nhân có thể:\n";
    echo "   1. Username '{$testUsername}' không tồn tại trong database\n";
    echo "   2. Tài khoản bị inactive (status = 0)\n";
    echo "   3. Database chưa được đồng bộ giữa các máy\n\n";
    
    // Kiểm tra xem có admin nào với username này không (kể cả inactive)
    $db = new Database();
    $sql = "SELECT * FROM admins WHERE username = ?";
    $result = $db->query($sql, [$testUsername]);
    
    if ($result) {
        echo "⚠️  TÌM THẤY ADMIN NHƯNG BỊ INACTIVE:\n";
        echo "   ID: {$result[0]['id']}\n";
        echo "   Username: {$result[0]['username']}\n";
        echo "   Status: {$result[0]['status']}\n\n";
        echo "💡 GIẢI PHÁP: Chạy câu lệnh SQL sau để kích hoạt:\n";
        echo "   UPDATE admins SET status = 1 WHERE username = '{$testUsername}';\n";
    } else {
        echo "❌ KHÔNG TÌM THẤY ADMIN NÀO!\n";
        echo "💡 GIẢI PHÁP: Chạy script create_new_admin.php để tạo tài khoản\n";
    }
    exit;
}

echo "✅ Tìm thấy admin!\n";
echo "   ID: {$admin['id']}\n";
echo "   Username: {$admin['username']}\n";
echo "   Fullname: {$admin['fullname']}\n";
echo "   Email: {$admin['email']}\n";
echo "   Role: {$admin['role']}\n";
echo "   Status: {$admin['status']}\n\n";

echo "🔍 BƯỚC 2: Kiểm tra password hash...\n";
echo "   Password hash: " . substr($admin['password'], 0, 50) . "...\n";
echo "   Hash length: " . strlen($admin['password']) . " characters\n";
echo "   Hash algorithm: " . (strpos($admin['password'], '$2y$') === 0 ? 'bcrypt' : 'unknown') . "\n\n";

echo "🔍 BƯỚC 3: Verify password...\n";
$verify = password_verify($testPassword, $admin['password']);

if ($verify) {
    echo "✅ PASSWORD ĐÚNG!\n\n";
    
    echo "🔍 BƯỚC 4: Test verifyCredentials method...\n";
    $verifyResult = $adminModel->verifyCredentials($testUsername, $testPassword);
    
    if ($verifyResult) {
        echo "✅ VERIFY CREDENTIALS THÀNH CÔNG!\n\n";
        echo str_repeat("=", 80) . "\n";
        echo "🎉 KẾT LUẬN: TÀI KHOẢN HOẠT ĐỘNG HOÀN HẢO!\n";
        echo str_repeat("=", 80) . "\n\n";
        echo "💡 NẾU VẪN KHÔNG ĐĂNG NHẬP ĐƯỢC TRÊN MÁY KHÁC:\n";
        echo "   1. Kiểm tra file config/database.php trên máy khác\n";
        echo "   2. Đảm bảo máy khác kết nối đến CÙNG database server\n";
        echo "   3. Chạy script này trên máy khác để so sánh kết quả\n";
        echo "   4. Kiểm tra session có hoạt động không (session_start)\n";
        echo "   5. Xóa cache/cookies trình duyệt\n";
    } else {
        echo "❌ VERIFY CREDENTIALS THẤT BẠI!\n";
        echo "💡 Có vấn đề với method verifyCredentials trong AdminModel\n";
    }
} else {
    echo "❌ PASSWORD SAI!\n\n";
    echo "💡 NGUYÊN NHÂN:\n";
    echo "   - Password hash trong database không khớp với password '{$testPassword}'\n\n";
    
    echo "🔧 GIẢI PHÁP:\n";
    echo "   1. Chạy script fix_admin_password.php để tạo lại password\n";
    echo "   2. Hoặc chạy câu lệnh SQL sau:\n\n";
    
    $newHash = password_hash($testPassword, PASSWORD_BCRYPT);
    echo "   UPDATE admins SET password = '{$newHash}' WHERE username = '{$testUsername}';\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "📊 THÔNG TIN HỆ THỐNG:\n";
echo "   - PHP Version: " . phpversion() . "\n";
echo "   - Database: " . DB_HOST . " / " . DB_NAME . "\n";
echo "   - Script: " . __FILE__ . "\n";
echo str_repeat("=", 80) . "\n";
