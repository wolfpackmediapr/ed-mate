<?php
$host = 'localhost';
$dbname = 'edmate_db';
$username = 'root';
$password = 'Buchanan@167'; // MySQL password

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $newPassword = 'admin123';
    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
    
    $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE email = 'admin@edmate.com'");
    $stmt->execute(['password' => $hashedPassword]);
    
    echo "Password updated successfully!\n";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?> 