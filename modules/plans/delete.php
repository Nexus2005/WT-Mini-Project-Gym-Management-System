<?php
session_start();
require_once '../../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    header("Location: " . BASE_URL . "index.php");
    exit();
}

if (isset($_GET['id'])) {
    $plan_id = (int)$_GET['id'];
    
    // Check if plan is in use
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM memberships WHERE plan_id = ?");
    $stmt->execute([$plan_id]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['flash_message'] = "Cannot delete plan currently assigned to members.";
        $_SESSION['flash_type'] = "error";
    } else {
        $stmt = $pdo->prepare("DELETE FROM plans WHERE plan_id = ?");
        if ($stmt->execute([$plan_id])) {
            $_SESSION['flash_message'] = "Plan deleted successfully.";
            $_SESSION['flash_type'] = "success";
        } else {
            $_SESSION['flash_message'] = "Failed to delete plan.";
            $_SESSION['flash_type'] = "error";
        }
    }
}
header("Location: view.php");
exit();
?>
