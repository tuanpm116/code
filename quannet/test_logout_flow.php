<?php
// Test logout flow directly
session_start();

// Simulate logged in user
$_SESSION['admin_id'] = 1;
$_SESSION['admin_username'] = 'admin';
$_SESSION['admin_fullname'] = 'Administrator';
$_SESSION['admin_last_activity'] = time();

echo "=== SIMULATED LOGOUT TEST ===\n\n";

echo "Step 1: Set up logged in session\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";

echo "\nStep 2: Simulate logout action\n";

// Simulate what AuthController::logout() does
if (isset($_COOKIE['remember_admin'])) {
    setcookie('remember_admin', '', time() - 3600, '/');
    echo "Cleared remember cookie\n";
}

// Call Auth::logout()
require_once 'core/Auth.php';
Auth::logout();
echo "Called Auth::logout()\n";

// Set success message (this happens AFTER session_destroy!)
$_SESSION['success'] = 'Đăng xuất thành công!';
echo "Set success message\n";

echo "\nStep 3: Check session after logout\n";
echo "Session ID: " . session_id() . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";

echo "\n=== PROBLEM IDENTIFIED ===\n";
echo "Auth::logout() calls session_destroy() which destroys the session.\n";
echo "Setting \$_SESSION['success'] AFTER session_destroy() doesn't work!\n";
echo "The success message is lost.\n";
