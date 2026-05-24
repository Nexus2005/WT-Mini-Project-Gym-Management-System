<?php
session_start();
require_once __DIR__ . '/../config/db.php';

if (!isset($_SESSION['admin_id'])) {
    echo "<script>window.location.href='" . BASE_URL . "index.php';</script>";
    exit();
}
?>
