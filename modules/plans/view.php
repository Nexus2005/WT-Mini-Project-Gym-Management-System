<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

$stmt = $pdo->query("SELECT * FROM plans ORDER BY plan_id DESC");
$plans = $stmt->fetchAll();
?>

<div class="dashboard-sections" style="display:block;">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
        <h2 class="section-title" style="margin-bottom:0;">View All Plans</h2>
        <a href="add.php" class="btn"><i class="fas fa-plus"></i> Add New Plan</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Plan Name</th>
                    <th>Duration (Months)</th>
                    <th>Price (₹)</th>
                    <th>Description</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if(count($plans) > 0): ?>
                    <?php foreach($plans as $p): ?>
                    <tr>
                        <td>#<?= $p['plan_id'] ?></td>
                        <td><strong><?= htmlspecialchars($p['plan_name']) ?></strong></td>
                        <td><?= $p['duration_months'] ?> Months</td>
                        <td>₹<?= number_format($p['price'], 2) ?></td>
                        <td><?= htmlspecialchars($p['description']) ?></td>
                        <td>
                            <a href="edit.php?id=<?= $p['plan_id'] ?>" class="btn" style="padding:5px 10px; font-size:0.8rem;"><i class="fas fa-edit"></i> Edit</a>
                            <a href="delete.php?id=<?= $p['plan_id'] ?>" class="btn btn-danger" style="padding:5px 10px; font-size:0.8rem;" onclick="return confirmDelete('Are you sure you want to delete this plan?');"><i class="fas fa-trash"></i> Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="6" style="text-align:center;">No plans found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Plans > View Plans';
</script>

<?php require_once '../../includes/footer.php'; ?>
