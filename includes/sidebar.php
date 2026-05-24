<aside class="sidebar">
    <div class="sidebar-header">
        <h2><i class="fas fa-dumbbell"></i> FitZone</h2>
    </div>
    
    <ul class="nav-links">
        <li><a href="<?= BASE_URL ?>dashboard.php" class="nav-item"><i class="fas fa-home"></i> Dashboard</a></li>
        
        <li class="has-submenu">
            <a href="#" class="nav-item"><i class="fas fa-users"></i> Members <i class="fas fa-chevron-down toggle-icon"></i></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>modules/members/add.php">Add Member</a></li>
                <li><a href="<?= BASE_URL ?>modules/members/view.php">All Members</a></li>
            </ul>
        </li>
        
        <li class="has-submenu">
            <a href="#" class="nav-item"><i class="fas fa-clipboard-list"></i> Plans <i class="fas fa-chevron-down toggle-icon"></i></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>modules/plans/add.php">Add Plan</a></li>
                <li><a href="<?= BASE_URL ?>modules/plans/view.php">All Plans</a></li>
            </ul>
        </li>

        <li><a href="<?= BASE_URL ?>modules/memberships/assign.php" class="nav-item"><i class="fas fa-id-card"></i> Assign Plan</a></li>
        
        <li class="has-submenu">
            <a href="#" class="nav-item"><i class="fas fa-rupee-sign"></i> Payments <i class="fas fa-chevron-down toggle-icon"></i></a>
            <ul class="submenu">
                <li><a href="<?= BASE_URL ?>modules/payments/record.php">Record Payment</a></li>
                <li><a href="<?= BASE_URL ?>modules/payments/history.php">Payment History</a></li>
            </ul>
        </li>
        

        <li><a href="<?= BASE_URL ?>api/logout.php" class="logout-btn"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
    </ul>
</aside>
