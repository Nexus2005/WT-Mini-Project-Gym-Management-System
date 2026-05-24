<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->username) && isset($data->email) && isset($data->password)) {
    $username = trim($data->username);
    $email = trim($data->email);
    $password = password_hash(trim($data->password), PASSWORD_BCRYPT);

    // check if exists
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM admin_users WHERE email = ? OR username = ?");
    $stmt->execute([$email, $username]);
    if ($stmt->fetchColumn() > 0) {
        echo json_encode(['success' => false, 'message' => 'Email or username already exists']);
        exit();
    }

    $stmt = $pdo->prepare("INSERT INTO admin_users (username, email, password_hash) VALUES (?, ?, ?)");
    if ($stmt->execute([$username, $email, $password])) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Database insert failed']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing required fields']);
}
?>
