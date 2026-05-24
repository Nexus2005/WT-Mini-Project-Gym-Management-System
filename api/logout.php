<?php
session_start();
require_once '../config/db.php';
session_unset();
session_destroy();
header("Location: " . BASE_URL . "index.php");
exit();
?>
