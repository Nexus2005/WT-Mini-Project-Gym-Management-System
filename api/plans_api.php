<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$stmt = $pdo->query("SELECT plan_id, plan_name, duration_months, price FROM plans ORDER BY plan_name ASC");
$plans = $stmt->fetchAll();

echo json_encode($plans);
?>
