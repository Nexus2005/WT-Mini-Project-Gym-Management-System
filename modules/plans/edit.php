<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='view.php';</script>";
    exit();
}

$plan_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM plans WHERE plan_id = ?");
$stmt->execute([$plan_id]);
$plan = $stmt->fetch();

if (!$plan) {
    echo "<script>window.location.href='view.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_name = trim($_POST['plan_name']);
    $duration_months = (int)$_POST['duration_months'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);

    if (!empty($plan_name) && $duration_months > 0 && $price > 0) {
        $stmt = $pdo->prepare("UPDATE plans SET plan_name=?, duration_months=?, price=?, description=? WHERE plan_id=?");
        if ($stmt->execute([$plan_name, $duration_months, $price, $description, $plan_id])) {
            $_SESSION['flash_message'] = "Plan updated successfully!";
            $_SESSION['flash_type'] = "success";
            echo "<script>window.location.href='view.php';</script>";
            exit();
        }
    } else {
        $error = "Please fill all required fields correctly.";
    }
}
?>

<div class="form-container">
    <h2 class="section-title">Edit Plan #<?= $plan['plan_id'] ?></h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validatePlanForm()">
        <div class="form-group">
            <label>Plan Name *</label>
            <input type="text" name="plan_name" id="plan_name" class="form-control" value="<?= htmlspecialchars($plan['plan_name']) ?>" required>
        </div>
        
        <div class="form-group">
            <label>Duration (in months) *</label>
            <select name="duration_months" id="duration_months" class="form-control" required>
                <option value="1" <?= $plan['duration_months']==1?'selected':'' ?>>1 Month</option>
                <option value="3" <?= $plan['duration_months']==3?'selected':'' ?>>3 Months</option>
                <option value="6" <?= $plan['duration_months']==6?'selected':'' ?>>6 Months</option>
                <option value="12" <?= $plan['duration_months']==12?'selected':'' ?>>12 Months</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price (₹) *</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" value="<?= $plan['price'] ?>" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"><?= htmlspecialchars($plan['description']) ?></textarea>
        </div>

        <button type="submit" class="btn">Update Plan</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Plans > Edit Plan';

function validatePlanForm() {
    const price = document.getElementById('price').value;
    if(!document.getElementById('plan_name').value.trim()) {
        alert("Plan name is required"); return false;
    }
    if(!validatePrice(price)) {
        alert("Enter a valid price greater than 0"); return false;
    }
    return true;
}
</script>

<?php require_once '../../includes/footer.php'; ?>
