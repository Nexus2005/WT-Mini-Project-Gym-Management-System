<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

// Fetch Members
$stmt = $pdo->query("SELECT member_id, name FROM members ORDER BY name ASC");
$members = $stmt->fetchAll();

// Fetch Plans
$stmt = $pdo->query("SELECT plan_id, plan_name, duration_months, price FROM plans ORDER BY plan_name ASC");
$plans = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = (int)$_POST['member_id'];
    $plan_id = (int)$_POST['plan_id'];
    $start_date = trim($_POST['start_date']);
    $expiry_date = trim($_POST['calculated_expiry']); // hidden field calculated by JS

    if ($member_id && $plan_id && !empty($start_date) && !empty($expiry_date)) {
        
        $pdo->prepare("DELETE FROM memberships WHERE member_id=?")->execute([$member_id]);
        
        $stmt = $pdo->prepare("INSERT INTO memberships (member_id, plan_id, start_date, expiry_date) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$member_id, $plan_id, $start_date, $expiry_date])) {
            $pdo->prepare("UPDATE members SET status='Active' WHERE member_id=?")->execute([$member_id]);
            
            $pmt_stmt = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE member_id=?");
            $pmt_stmt->execute([$member_id]);
            if ($pmt_stmt->fetchColumn() == 0) {
                $_SESSION['flash_message'] = "Plan assigned successfully. Warning: Payment pending — status will remain Pending until payment is recorded.";
                $_SESSION['flash_type'] = "warning";
            } else {
                $_SESSION['flash_message'] = "Plan assigned successfully.";
                $_SESSION['flash_type'] = "success";
            }
            
            echo "<script>window.location.href='assign.php';</script>";
            exit();
        } else {
            $error = "Failed to assign plan.";
        }
    } else {
        $error = "Please fill all required fields.";
    }
}
?>

<div class="form-container">
    <h2 class="section-title">Assign Plan to Member</h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="assignForm" onsubmit="return confirm('Are you sure you want to assign this plan?');">
        <div class="form-group">
            <label>Select Member *</label>
            <select name="member_id" id="member_id" class="form-control" required>
                <option value="">-- Choose a Member --</option>
                <?php foreach($members as $m): ?>
                    <option value="<?= $m['member_id'] ?>"><?= htmlspecialchars($m['name']) ?> (ID: <?= $m['member_id'] ?>)</option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Select Plan *</label>
            <select name="plan_id" id="plan_id" class="form-control" required onchange="calculateExpiry()">
                <option value="" data-duration="0">-- Choose a Plan --</option>
                <?php foreach($plans as $p): ?>
                    <option value="<?= $p['plan_id'] ?>" data-duration="<?= $p['duration_months'] ?>">
                        <?= htmlspecialchars($p['plan_name']) ?> - ₹<?= $p['price'] ?> (<?= $p['duration_months'] ?> Months)
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="form-group">
            <label>Start Date *</label>
            <input type="date" name="start_date" id="start_date" class="form-control" value="<?= date('Y-m-d') ?>" required onchange="calculateExpiry()">
        </div>

        <div class="form-group">
            <label>Calculated Expiry Date</label>
            <div id="expiry_display" style="padding:10px; background:#e9ecef; border-radius:4px; font-weight:600;">
                Select a plan to calculate expiry.
            </div>
            <input type="hidden" name="calculated_expiry" id="calculated_expiry">
        </div>

        <button type="submit" class="btn">Assign Plan</button>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Memberships > Assign Plan';

function calculateExpiry() {
    const planSelect = document.getElementById('plan_id');
    const selectedOption = planSelect.options[planSelect.selectedIndex];
    const durationMonths = parseInt(selectedOption.getAttribute('data-duration')) || 0;
    
    const startDateStr = document.getElementById('start_date').value;
    if (!startDateStr || durationMonths === 0) {
        document.getElementById('expiry_display').innerText = "Select a plan to calculate expiry.";
        document.getElementById('calculated_expiry').value = "";
        return;
    }

    const startDate = new Date(startDateStr);
    const expiryDate = new Date(startDate);
    expiryDate.setMonth(expiryDate.getMonth() + durationMonths);
    
    document.getElementById('expiry_display').innerText = expiryDate.toDateString();
    
    // format YYYY-MM-DD for backend
    const yyyy = expiryDate.getFullYear();
    const mm = String(expiryDate.getMonth() + 1).padStart(2, '0');
    const dd = String(expiryDate.getDate()).padStart(2, '0');
    document.getElementById('calculated_expiry').value = `${yyyy}-${mm}-${dd}`;
}
</script>

<?php require_once '../../includes/footer.php'; ?>
