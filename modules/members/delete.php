<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

if (isset($_GET['id'])) {
    $member_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("DELETE FROM members WHERE member_id = ?");
    if ($stmt->execute([$member_id])) {
        $_SESSION['flash_message'] = "Member deleted successfully.";
        $_SESSION['flash_type'] = "success";
    } else {
        $_SESSION['flash_message'] = "Failed to delete member.";
        $_SESSION['flash_type'] = "error";
    }
}

header("Location: view.php");
exit();
?>
