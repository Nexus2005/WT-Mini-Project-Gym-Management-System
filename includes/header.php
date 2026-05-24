<?php
// Ensure session is started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Automatically calculate member statuses on every page load to guarantee freshness (per module 3 requirements)
if (isset($pdo)) {
    $status_update_sql = "
        UPDATE members m
        JOIN memberships mb ON m.member_id = mb.member_id
        SET m.status = CASE
          WHEN mb.expiry_date < CURDATE() THEN 'Expired'
          WHEN DATEDIFF(mb.expiry_date, CURDATE()) <= 5 THEN 'Expiring Soon'
          ELSE 'Active'
        END";
    $stmt = $pdo->prepare($status_update_sql);
    $stmt->execute();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FitZone Gym Management</title>
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/style.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>assets/css/dashboard.css">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
      // Make BASE_URL available to JS
      const BASE_URL = "<?= BASE_URL ?>";
    </script>
</head>
<body>
    <div class="app-container">
        <!-- Sidebar included here -->
        <?php include_once 'sidebar.php'; ?>
        
        <div class="main-content">
            <!-- Topbar -->
            <header class="topbar">
                <div class="breadcrumb">
                    <span id="breadcrumb-text">Dashboard</span>
                </div>
                <div class="user-info">
                    <span>Welcome, <strong><?= htmlspecialchars($_SESSION['admin_name'] ?? 'Admin') ?></strong></span>
                </div>
            </header>
            
            <div class="content-wrapper">
                <!-- Flash messages -->
                <?php if (isset($_SESSION['flash_message'])): ?>
                    <div class="flash-alert flash-<?= $_SESSION['flash_type'] ?? 'info' ?>">
                        <?= htmlspecialchars($_SESSION['flash_message']) ?>
                    </div>
                    <?php 
                        unset($_SESSION['flash_message']); 
                        unset($_SESSION['flash_type']);
                    ?>
                <?php endif; ?>
