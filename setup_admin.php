<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
require 'config/db.php';

echo "<h2>Fixing Admin Account...</h2>";

try {
    // 1. Delete existing admin
    $pdo->query("DELETE FROM admin_users WHERE email = 'admin@gym.com'");
    
    // 2. Generate a fresh hash for 'Admin@123'
    $password = 'Admin@123';
    $hash = password_hash($password, PASSWORD_BCRYPT);
    
    // 3. Insert back
    $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash) VALUES ('admin', 'admin@gym.com', ?)");
    $stmt->execute([$hash]);
    
    echo "<p style='color:green;'>Success! Admin account has been forcefully reset.</p>";
    echo "<p>Go back to <a href='index.php'>Login</a> and try again with:</p>";
    echo "<ul><li>Email: <b>admin@gym.com</b></li><li>Password: <b>Admin@123</b></li></ul>";
    
} catch (PDOException $e) {
    echo "<p style='color:red;'>Database Error: " . $e->getMessage() . "</p>";
    echo "<p>Did you run the <b>schema.sql</b> file in phpMyAdmin to create the tables?</p>";
}
?>
