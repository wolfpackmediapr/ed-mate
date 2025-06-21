<?php
$password = 'Buchanan@167'; // Default password
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);
echo "Hashed password: " . $hashedPassword . "\n";

// Read the SQL file
$sql = file_get_contents('create_super_admin.sql');

// Replace the placeholder with the actual hashed password
$sql = str_replace('$2y$10$YourHashedPasswordHere', $hashedPassword, $sql);

// Write back to the SQL file
file_put_contents('create_super_admin.sql', $sql);

echo "SQL file updated with hashed password.\n"; 