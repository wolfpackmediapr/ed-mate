<?php
$host = 'localhost';
$dbname = 'edmate_db';
$username = 'root';
$password = 'Buchanan@167'; // Updated MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("SELECT * FROM users WHERE email = 'admin@edmate.com'");
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user) {
        echo "User found:\n";
        echo "Email: " . $user['email'] . "\n";
        echo "Password hash: " . $user['password'] . "\n";
        echo "Role ID: " . $user['role_id'] . "\n";
    } else {
        echo "User not found!\n";
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 