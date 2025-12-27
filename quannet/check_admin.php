<?php
/**
 * Script kiểm tra tài khoản admin trong database
 * Chạy file này để xem danh sách admin và test password
 */

// Load config và core
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/core/Database.php';

echo "=== KIỂM TRA TÀI KHOẢN ADMIN ===\n\n";

$db = new Database();

// Lấy tất cả admin
$sql = "SELECT id, username, password, fullname, email, role, status, created_at FROM admins ORDER BY id";
$admins = $db->query($sql);

if (!$admins) {
    echo "❌ Không tìm thấy admin nào trong database!\n";
    exit;
}

echo "📋 DANH SÁCH ADMIN:\n";
echo str_repeat("-", 100) . "\n";
printf("%-5s %-15s %-25s %-30s %-12s %-8s\n", "ID", "Username", "Fullname", "Email", "Role", "Status");
echo str_repeat("-", 100) . "\n";

foreach ($admins as $admin) {
    $statusText = $admin['status'] == 1 ? '✅ Active' : '❌ Inactive';
    printf("%-5s %-15s %-25s %-30s %-12s %-8s\n", 
        $admin['id'], 
        $admin['username'], 
        $admin['fullname'], 
        $admin['email'], 
        $admin['role'], 
        $statusText
    );
}

echo str_repeat("-", 100) . "\n\n";

// Test password cho từng admin
echo "🔐 TEST PASSWORD (password123):\n";
echo str_repeat("-", 100) . "\n";

$testPassword = 'password123';

foreach ($admins as $admin) {
    $verify = password_verify($testPassword, $admin['password']);
    $result = $verify ? '✅ ĐÚNG' : '❌ SAI';
    
    echo "Username: {$admin['username']}\n";
    echo "  - Password hash: " . substr($admin['password'], 0, 50) . "...\n";
    echo "  - Verify 'password123': {$result}\n";
    echo "  - Status: " . ($admin['status'] == 1 ? 'Active' : 'Inactive') . "\n\n";
}

echo str_repeat("=", 100) . "\n";
echo "💡 HƯỚNG DẪN:\n";
echo "1. Nếu password SAI: Chạy script fix_admin_password.php để tạo lại password\n";
echo "2. Nếu Status = Inactive: Chạy câu lệnh UPDATE để kích hoạt tài khoản\n";
echo "3. Nếu không thấy tài khoản: Chạy câu lệnh INSERT để tạo mới\n";
echo str_repeat("=", 100) . "\n";
