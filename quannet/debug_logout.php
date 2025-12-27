<?php
// Debug logout
session_start();

require_once 'core/Auth.php';

echo "=== LOGOUT DEBUG ===\n\n";

echo "Before logout:\n";
echo "Session ID: " . session_id() . "\n";
echo "Logged in: " . (Auth::check() ? 'YES' : 'NO') . "\n";
if (Auth::check()) {
    echo "User: " . Auth::user()['username'] . "\n";
}
echo "\n";

echo "Calling Auth::logout()...\n";
Auth::logout();

echo "\nAfter logout:\n";
echo "Session ID: " . session_id() . "\n";
echo "Logged in: " . (Auth::check() ? 'YES' : 'NO') . "\n";
echo "Session data: " . print_r($_SESSION, true) . "\n";
