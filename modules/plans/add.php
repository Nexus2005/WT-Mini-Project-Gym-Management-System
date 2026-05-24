<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $plan_name = trim($_POST['plan_name']);
    $duration_months = (int)$_POST['duration_months'];
    $price = (float)$_POST['price'];
    $description = trim($_POST['description']);

    if (!empty($plan_name) && $duration_months > 0 && $price > 0) {
        $stmt = $pdo->prepare("INSERT INTO plans (plan_name, duration_months, price, description) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$plan_name, $duration_months, $price, $description])) {
            $_SESSION['flash_message'] = "Plan added successfully!";
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
    <h2 class="section-title">Add New Plan</h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST" onsubmit="return validatePlanForm()">
        <div class="form-group">
            <label>Plan Name *</label>
            <input type="text" name="plan_name" id="plan_name" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Duration (in months) *</label>
            <select name="duration_months" id="duration_months" class="form-control" required>
                <option value="">Select Duration</option>
                <option value="1">1 Month</option>
                <option value="3">3 Months</option>
                <option value="6">6 Months</option>
                <option value="12">12 Months (1 Year)</option>
            </select>
        </div>

        <div class="form-group">
            <label>Price (₹) *</label>
            <input type="number" step="0.01" name="price" id="price" class="form-control" required>
        </div>
        
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="description" class="form-control" rows="4"></textarea>
        </div>

        <button type="submit" class="btn">Save Plan</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Plans > Add Plan';

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
