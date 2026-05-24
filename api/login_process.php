<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

$data = json_decode(file_get_contents("php://input"));

if (isset($data->email) && isset($data->password)) {
    $email = trim($data->email);
    $password = trim($data->password);

    $stmt = $pdo->prepare("SELECT admin_id, username, password_hash FROM admin_users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password_hash'])) {
        $_SESSION['admin_id'] = $user['admin_id'];
        $_SESSION['admin_name'] = $user['username'];

        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid email or password']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Missing fields']);
}
?>
