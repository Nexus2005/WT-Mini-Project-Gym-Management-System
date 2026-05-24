<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

// Construct query based on search/filters
$query = "
    SELECT p.*, m.name as member_name, pl.plan_name 
    FROM payments p
    JOIN members m ON p.member_id = m.member_id
    JOIN memberships mb ON p.membership_id = mb.membership_id
    JOIN plans pl ON mb.plan_id = pl.plan_id
";

$conditions = [];
$params = [];

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $conditions[] = "m.name LIKE ?";
    $params[] = "%" . trim($_GET['search']) . "%";
}
if (isset($_GET['status']) && $_GET['status'] !== 'All') {
    $conditions[] = "p.status = ?";
    $params[] = trim($_GET['status']);
}

if (count($conditions) > 0) {
    $query .= " WHERE " . implode(' AND ', $conditions);
}

$query .= " ORDER BY p.payment_date DESC";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$payments = $stmt->fetchAll();

$total_revenue = 0;
foreach($payments as $p) {
    if ($p['status'] == 'Paid') {
        $total_revenue += $p['amount'];
    }
}
?>

<div class="dashboard-sections" style="display:block;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 class="section-title" style="margin-bottom:0;">Payment History</h2>
        <a href="record.php" class="btn"><i class="fas fa-plus"></i> Record Payment</a>
    </div>

    <form method="GET" class="filters-row">
        <input type="text" name="search" class="search-bar" style="margin-bottom:0; flex:2;" placeholder="Search member name..." value="<?= htmlspecialchars($_GET['search'] ?? '') ?>">
        <select name="status" class="form-control" style="flex:1;">
            <option value="All">All Statuses</option>
            <option value="Paid" <?= (isset($_GET['status']) && $_GET['status'] == 'Paid') ? 'selected' : '' ?>>Paid</option>
            <option value="Pending" <?= (isset($_GET['status']) && $_GET['status'] == 'Pending') ? 'selected' : '' ?>>Pending</option>
            <option value="Partial" <?= (isset($_GET['status']) && $_GET['status'] == 'Partial') ? 'selected' : '' ?>>Partial</option>
        </select>
        <button type="submit" class="btn">Filter</button>
        <a href="history.php" class="btn btn-secondary">Clear</a>
    </form>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Payment ID</th>
                    <th>Member Name</th>
                    <th>Plan Name</th>
                    <th>Amount</th>
                    <th>Date</th>
                    <th>Status</th>
                    <th>Notes</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($payments) > 0): ?>
                    <?php foreach($payments as $p): ?>
                    <tr style="<?= $p['status'] == 'Pending' ? 'background-color:#fff3cd;' : '' ?>">
                        <td>#<?= $p['payment_id'] ?></td>
                        <td><strong><?= htmlspecialchars($p['member_name']) ?></strong></td>
                        <td><?= htmlspecialchars($p['plan_name']) ?></td>
                        <td>₹<?= number_format($p['amount'], 2) ?></td>
                        <td><?= date('d M Y, h:i A', strtotime($p['payment_date'])) ?></td>
                        <td>
                            <?php 
                                $badge_class = 'active';
                                if($p['status'] == 'Pending') $badge_class = 'pending';
                                if($p['status'] == 'Partial') $badge_class = 'soon';
                            ?>
                            <span class="badge badge-<?= $badge_class ?>"><?= $p['status'] ?></span>
                        </td>
                        <td><?= htmlspecialchars($p['notes']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="7" style="text-align:center;">No payments found.</td></tr>
                <?php endif; ?>
            </tbody>
            <tfoot>
                <tr style="background:#1a1f36; color:white;">
                    <td colspan="3" style="text-align:right; font-weight:bold;">Total Revenue (Paid):</td>
                    <td colspan="4" style="font-weight:bold; font-size:1.1rem;">₹<?= number_format($total_revenue, 2) ?></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Payments > History';
</script>

<?php require_once '../../includes/footer.php'; ?>
