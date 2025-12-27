<?php
// Test password hash
$password = 'password123';
$hash = password_hash($password, PASSWORD_BCRYPT);
echo "Password: $password\n";
echo "Hash: $hash\n";
echo "\nVerify test:\n";
$testHash = '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi';
echo "Old hash verifies: " . (password_verify($password, $testHash) ? 'YES' : 'NO') . "\n";
echo "New hash verifies: " . (password_verify($password, $hash) ? 'YES' : 'NO') . "\n";
