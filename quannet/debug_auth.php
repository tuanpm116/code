<?php
// Debug script to test authentication
session_start();

require_once 'config/database.php';
require_once 'core/Database.php';
require_once 'modules/auth/models/AdminModel.php';

echo "=== AUTHENTICATION DEBUG ===\n\n";

$username = 'admin';
$password = 'password123';

echo "Testing credentials:\n";
echo "Username: $username\n";
echo "Password: $password\n\n";

// Test database connection
echo "1. Testing database connection...\n";
try {
    $db = new Database();
    echo "✓ Database connected successfully\n\n";
} catch (Exception $e) {
    echo "✗ Database connection failed: " . $e->getMessage() . "\n";
    exit;
}

// Test AdminModel
echo "2. Testing AdminModel...\n";
try {
    $model = new AdminModel();
    echo "✓ AdminModel instantiated\n\n";
} catch (Exception $e) {
    echo "✗ AdminModel failed: " . $e->getMessage() . "\n";
    exit;
}

// Test findByUsername
echo "3. Testing findByUsername('$username')...\n";
$admin = $model->findByUsername($username);
if ($admin) {
    echo "✓ Admin found:\n";
    echo "   ID: " . $admin['id'] . "\n";
    echo "   Username: " . $admin['username'] . "\n";
    echo "   Fullname: " . $admin['fullname'] . "\n";
    echo "   Status: " . $admin['status'] . "\n";
    echo "   Password hash: " . substr($admin['password'], 0, 30) . "...\n\n";
} else {
    echo "✗ Admin not found\n";
    exit;
}

// Test password verification
echo "4. Testing password verification...\n";
$verified = password_verify($password, $admin['password']);
if ($verified) {
    echo "✓ Password verified successfully\n\n";
} else {
    echo "✗ Password verification failed\n";
    echo "   Expected password: $password\n";
    echo "   Hash in DB: " . $admin['password'] . "\n";
    
    // Generate correct hash
    $correctHash = password_hash($password, PASSWORD_BCRYPT);
    echo "\n   Correct hash should be: $correctHash\n";
    echo "\n   To fix, run this SQL:\n";
    echo "   UPDATE admins SET password='$correctHash' WHERE username='admin';\n";
    exit;
}

// Test verifyCredentials
echo "5. Testing verifyCredentials()...\n";
$result = $model->verifyCredentials($username, $password);
if ($result) {
    echo "✓ verifyCredentials returned admin data\n";
    echo "   Login should work!\n\n";
} else {
    echo "✗ verifyCredentials returned false\n";
    echo "   This is the problem!\n\n";
}

echo "=== DEBUG COMPLETE ===\n";
