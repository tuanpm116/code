<?php
/**
 * Script tạo tài khoản admin mới
 * Tự động tạo password hash đúng
 */

// Load config và core
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

echo "=== TẠO TÀI KHOẢN ADMIN MỚI ===\n\n";

// Cấu hình tài khoản mới
$username = 'admin2';
$password = 'password123';
$fullname = 'Admin Phụ';
$email = 'admin2@quannet.com';
$role = 'admin';  // 'admin' hoặc 'superadmin'
$status = 1;  // 1 = Active, 0 = Inactive

$db = new Database();

// Kiểm tra username đã tồn tại chưa
$checkSql = "SELECT * FROM admins WHERE username = ?";
$existing = $db->query($checkSql, [$username]);

if ($existing) {
    echo "⚠️  Username '{$username}' đã tồn tại!\n";
    echo "💡 Bạn có thể:\n";
    echo "   1. Đổi username khác trong file này (dòng 12)\n";
    echo "   2. Hoặc chạy fix_admin_password.php để sửa password cho tài khoản hiện có\n";
    exit;
}

// Tạo password hash
$passwordHash = password_hash($password, PASSWORD_BCRYPT);

echo "📝 Thông tin tài khoản mới:\n";
echo "  - Username: {$username}\n";
echo "  - Password: {$password}\n";
echo "  - Fullname: {$fullname}\n";
echo "  - Email: {$email}\n";
echo "  - Role: {$role}\n";
echo "  - Status: " . ($status == 1 ? 'Active' : 'Inactive') . "\n\n";

// Verify hash
$verify = password_verify($password, $passwordHash);
echo "🔐 Password hash: {$passwordHash}\n";
echo "✓ Verify hash: " . ($verify ? '✅ OK' : '❌ FAILED') . "\n\n";

if (!$verify) {
    echo "❌ Hash không hợp lệ! Có lỗi xảy ra.\n";
    exit;
}

// Insert vào database
$insertSql = "INSERT INTO admins (username, password, fullname, email, role, status) 
              VALUES (?, ?, ?, ?, ?, ?)";

$result = $db->execute($insertSql, [
    $username,
    $passwordHash,
    $fullname,
    $email,
    $role,
    $status
]);

if ($result) {
    echo "✅ TẠO TÀI KHOẢN THÀNH CÔNG!\n\n";
    echo "📋 Thông tin đăng nhập:\n";
    echo "  - Username: {$username}\n";
    echo "  - Password: {$password}\n\n";
    echo "💡 Bạn có thể đăng nhập ngay bây giờ!\n";
    echo "🔗 URL: http://localhost/quannet/\n";
} else {
    echo "❌ TẠO TÀI KHOẢN THẤT BẠI!\n";
    echo "💡 Kiểm tra:\n";
    echo "   - Kết nối database\n";
    echo "   - Bảng 'admins' đã tồn tại chưa\n";
    echo "   - Quyền INSERT vào database\n";
}

echo "\n" . str_repeat("=", 80) . "\n";
echo "📌 GHI CHÚ:\n";
echo "- Để tạo admin khác, sửa thông tin ở đầu file này (dòng 10-15)\n";
echo "- Để kiểm tra tất cả admin: chạy check_admin.php\n";
echo str_repeat("=", 80) . "\n";
