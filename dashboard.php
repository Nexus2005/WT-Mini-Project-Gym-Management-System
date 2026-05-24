<?php
require_once 'includes/session_check.php';
require_once 'includes/header.php';

// Fetch stats for dashboard
// Total members
$stmt = $pdo->query("SELECT COUNT(*) FROM members");
$total_members = $stmt->fetchColumn();

// Active members
$stmt = $pdo->query("SELECT COUNT(*) FROM members WHERE status = 'Active'");
$active_members = $stmt->fetchColumn();

// Expired members
$stmt = $pdo->query("SELECT COUNT(*) FROM members WHERE status = 'Expired'");
$expired_members = $stmt->fetchColumn();

// Expiring soon
$stmt = $pdo->query("SELECT COUNT(*) FROM members WHERE status = 'Expiring Soon'");
$expiring_soon = $stmt->fetchColumn();

// Total revenue
$stmt = $pdo->query("SELECT SUM(amount) FROM payments WHERE status = 'Paid'");
$total_revenue = $stmt->fetchColumn() ?? 0;

// Pending payments count
$stmt = $pdo->query("SELECT COUNT(*) FROM payments WHERE status = 'Pending'");
$pending_payments = $stmt->fetchColumn();

// Total plans
$stmt = $pdo->query("SELECT COUNT(*) FROM plans");
$total_plans = $stmt->fetchColumn();

// Recent Members (last 5)
$stmt = $pdo->query("SELECT * FROM members ORDER BY member_id DESC LIMIT 5");
$recent_members = $stmt->fetchAll();

// Recent Payments
$stmt = $pdo->query("
    SELECT p.*, m.name as member_name 
    FROM payments p 
    JOIN members m ON p.member_id = m.member_id 
    ORDER BY p.payment_id DESC LIMIT 5
");
$recent_payments = $stmt->fetchAll();
?>

<div class="stat-grid">
    <div class="stat-card">
        <div class="stat-icon bg-primary"><i class="fas fa-users"></i></div>
        <div class="stat-info">
            <h3><?= $total_members ?></h3>
            <p>Total Members</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-success"><i class="fas fa-user-check"></i></div>
        <div class="stat-info">
            <h3><?= $active_members ?></h3>
            <p>Active</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-danger"><i class="fas fa-user-times"></i></div>
        <div class="stat-info">
            <h3><?= $expired_members ?></h3>
            <p>Expired</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-warning"><i class="fas fa-user-clock"></i></div>
        <div class="stat-info">
            <h3><?= $expiring_soon ?></h3>
            <p>Expiring Soon</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-success"><i class="fas fa-rupee-sign"></i></div>
        <div class="stat-info">
            <h3>₹<?= number_format($total_revenue, 2) ?></h3>
            <p>Total Revenue</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-warning"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-info">
            <h3><?= $pending_payments ?></h3>
            <p>Pending</p>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-icon bg-primary"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-info">
            <h3><?= $total_plans ?></h3>
            <p>Plans</p>
        </div>
    </div>
</div>

<div class="dashboard-sections">
    <div class="recent-members">
        <h2 class="section-title">Recent Members</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Mobile</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_members as $m): ?>
                    <tr>
                        <td>#<?= $m['member_id'] ?></td>
                        <td><?= htmlspecialchars($m['name']) ?></td>
                        <td><?= htmlspecialchars($m['mobile']) ?></td>
                        <td>
                            <?php 
                                $badge_class = 'active';
                                if($m['status'] == 'Expired') $badge_class = 'expired';
                                if($m['status'] == 'Expiring Soon') $badge_class = 'soon';
                            ?>
                            <span class="badge badge-<?= $badge_class ?>"><?= $m['status'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    
    <div class="recent-payments">
        <h2 class="section-title">Recent Payments</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Member</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($recent_payments as $p): ?>
                    <tr>
                        <td>#<?= $p['payment_id'] ?></td>
                        <td><?= htmlspecialchars($p['member_name']) ?></td>
                        <td>₹<?= number_format($p['amount'], 2) ?></td>
                        <td>
                            <?php 
                                $pbadge_class = 'active'; // green for Paid
                                if($p['status'] == 'Pending') $pbadge_class = 'pending'; // warning
                                else if($p['status'] == 'Partial') $pbadge_class = 'soon';
                            ?>
                            <span class="badge badge-<?= $pbadge_class ?>"><?= $p['status'] ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="chart-container" style="max-width: 500px; margin: 0 auto;">
    <h2 class="section-title" style="text-align:center; display:block; border-bottom:none">Membership Status</h2>
    <canvas id="statusChart"></canvas>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('statusChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Expired', 'Expiring Soon'],
            datasets: [{
                data: [<?= $active_members ?>, <?= $expired_members ?>, <?= $expiring_soon ?>],
                backgroundColor: ['#27ae60', '#e74c3c', '#f39c12']
            }]
        },
        options: {
            responsive: true
        }
    });
});
</script>

<?php require_once 'includes/footer.php'; ?>
