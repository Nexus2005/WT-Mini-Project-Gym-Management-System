<?php
// config/db.php
define('BASE_URL', '/gym/'); // Updated to match the deployment folder structure

$host = 'localhost';
$user = 'root';
$password = ''; // ENTER YOUR DATABASE PASSWORD HERE
$dbname = 'gym_management';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $user, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // Set default fetch mode to associative array
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    die("Database Connection failed: " . $e->getMessage());
}
?>
