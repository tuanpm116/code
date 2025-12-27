<?php
// Fix admin password
require_once 'config/database.php';
require_once 'core/Database.php';

echo "Fixing admin password...\n\n";

$password = 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);

echo "Generated hash: $hash\n\n";

$db = new Database();
$result = $db->execute("UPDATE admins SET password = ? WHERE username = ?", [$hash, 'admin']);

if ($result) {
    echo "✓ Password updated successfully!\n\n";
    
    // Verify
    $admin = $db->query("SELECT * FROM admins WHERE username = ?", ['admin']);
    if ($admin && count($admin) > 0) {
        echo "Verification:\n";
        echo "Username: " . $admin[0]['username'] . "\n";
        echo "Password hash: " . $admin[0]['password'] . "\n";
        echo "Hash starts with \$2y\$10: " . (strpos($admin[0]['password'], '$2y$10') === 0 ? 'YES' : 'NO') . "\n";
        echo "Password verifies: " . (password_verify($password, $admin[0]['password']) ? 'YES' : 'NO') . "\n";
    }
} else {
    echo "✗ Failed to update password\n";
}
