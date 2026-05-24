<?php
session_start();
require_once '../config/db.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['error' => 'Unauthorized']);
    exit();
}

$query = "
    SELECT m.*, mb.expiry_date, p.plan_name 
    FROM members m
    LEFT JOIN memberships mb ON m.member_id = mb.member_id
    LEFT JOIN plans p ON mb.plan_id = p.plan_id
";

$conditions = [];
$params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = "%" . $_GET['search'] . "%";
    $conditions[] = "(m.name LIKE ? OR m.mobile = ?)";
    $params[] = $search;
    $params[] = $_GET['search']; // mobile exact match
}

if (isset($_GET['filter']) && !empty($_GET['filter']) && $_GET['filter'] !== 'All') {
    $conditions[] = "m.status = ?";
    $params[] = $_GET['filter'];
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY m.member_id DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$members = $stmt->fetchAll();

echo json_encode($members);
?>
