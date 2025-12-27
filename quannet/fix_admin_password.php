<?php
/**
 * Script sửa password cho tài khoản admin
 * Tạo lại password mới với hash đúng
 */

// Load config và core
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

echo "=== SỬA PASSWORD ADMIN ===\n\n";

// Cấu hình
$username = 'admin2';  // Thay đổi username cần sửa
$newPassword = 'password123';  // Mật khẩu mới

$db = new Database();

// Kiểm tra admin có tồn tại không
$sql = "SELECT * FROM admins WHERE username = ?";
$admin = $db->query($sql, [$username]);

if (!$admin) {
    echo "❌ Không tìm thấy admin với username: {$username}\n";
    echo "💡 Bạn có thể tạo mới bằng cách chạy: create_new_admin.php\n";
    exit;
}

echo "✅ Tìm thấy admin: {$username}\n";
echo "📝 Thông tin hiện tại:\n";
echo "  - ID: {$admin[0]['id']}\n";
echo "  - Fullname: {$admin[0]['fullname']}\n";
echo "  - Email: {$admin[0]['email']}\n";
echo "  - Status: " . ($admin[0]['status'] == 1 ? 'Active' : 'Inactive') . "\n\n";

// Tạo hash mới
$newHash = password_hash($newPassword, PASSWORD_BCRYPT);

echo "🔐 Tạo password hash mới...\n";
echo "  - Password: {$newPassword}\n";
echo "  - Hash: {$newHash}\n\n";

// Verify hash
$verify = password_verify($newPassword, $newHash);
echo "✓ Verify hash: " . ($verify ? '✅ OK' : '❌ FAILED') . "\n\n";

if (!$verify) {
    echo "❌ Hash không hợp lệ! Có lỗi xảy ra.\n";
    exit;
}

// Cập nhật password
$updateSql = "UPDATE admins SET password = ? WHERE username = ?";
$result = $db->execute($updateSql, [$newHash, $username]);

if ($result) {
    echo "✅ CẬP NHẬT THÀNH CÔNG!\n\n";
    echo "📋 Thông tin đăng nhập:\n";
    echo "  - Username: {$username}\n";
    echo "  - Password: {$newPassword}\n\n";
    echo "💡 Bạn có thể đăng nhập ngay bây giờ!\n";
} else {
    echo "❌ CẬP NHẬT THẤT BẠI!\n";
    echo "💡 Kiểm tra kết nối database và quyền truy cập.\n";
}
