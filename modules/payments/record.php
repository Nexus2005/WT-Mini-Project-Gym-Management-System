<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

// Fetch Members who have a membership assigned
$stmt = $pdo->query("
    SELECT m.member_id, m.name, mb.membership_id, p.plan_name, p.price 
    FROM members m 
    JOIN memberships mb ON m.member_id = mb.member_id
    JOIN plans p ON mb.plan_id = p.plan_id
");
$member_plans = $stmt->fetchAll();
$memberDataJson = json_encode($member_plans);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $member_id = (int)$_POST['member_id'];
    $membership_id = (int)$_POST['membership_id'];
    $amount = (float)$_POST['amount'];
    $payment_date = trim($_POST['payment_date']);
    $status = trim($_POST['status']);
    $notes = trim($_POST['notes']);

    if ($member_id && $membership_id && $amount > 0) {
        $stmt = $pdo->prepare("INSERT INTO payments (member_id, membership_id, amount, payment_date, status, notes) VALUES (?, ?, ?, ?, ?, ?)");
        if ($stmt->execute([$member_id, $membership_id, $amount, $payment_date, $status, $notes])) {
            
            $_SESSION['flash_message'] = "Payment recorded successfully.";
            $_SESSION['flash_type'] = "success";
            echo "<script>window.location.href='history.php';</script>";
            exit();
        } else {
            $error = "Failed to record payment.";
        }
    } else {
        $error = "Please fill all required fields correctly. Amount must be > 0.";
    }
}
?>

<div class="form-container">
    <h2 class="section-title">Record Payment</h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="paymentForm" onsubmit="return validatePaymentForm()">
        <div class="form-group">
            <label>Select Member *</label>
            <select name="member_id" id="member_id" class="form-control" required onchange="autoFillPlan()">
                <option value="">-- Choose a Member --</option>
                <?php foreach($member_plans as $mp): ?>
                    <option value="<?= $mp['member_id'] ?>">
                        <?= htmlspecialchars($mp['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <input type="hidden" name="membership_id" id="membership_id">
        
        <div class="form-group">
            <label>Current Plan</label>
            <input type="text" id="plan_display" class="form-control" disabled value="Select a member first">
        </div>

        <div class="form-group">
            <label>Amount Paid (₹) *</label>
            <input type="number" step="0.01" name="amount" id="amount" class="form-control" required>
        </div>

        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Payment Date *</label>
                <input type="datetime-local" name="payment_date" id="payment_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Status *</label>
                <select name="status" class="form-control" required>
                    <option value="Paid">Paid</option>
                    <option value="Pending">Pending</option>
                    <option value="Partial">Partial</option>
                </select>
            </div>
        </div>
        
        <div class="form-group">
            <label>Notes</label>
            <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="e.g. Paid via UPI, transaction ID..."></textarea>
        </div>

        <button type="submit" class="btn">Save Payment</button>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Payments > Record Payment';

const memberData = <?= $memberDataJson ?>;

function autoFillPlan() {
    const memId = document.getElementById('member_id').value;
    const planDisplay = document.getElementById('plan_display');
    const amountInput = document.getElementById('amount');
    const membershipIdInput = document.getElementById('membership_id');
    
    if(!memId) {
        planDisplay.value = "Select a member first";
        membershipIdInput.value = "";
        amountInput.value = "";
        return;
    }
    
    const m = memberData.find(x => x.member_id == memId);
    if(m) {
        planDisplay.value = m.plan_name + " (₹" + m.price + ")";
        membershipIdInput.value = m.membership_id;
        amountInput.value = m.price;
    }
}

function validatePaymentForm() {
    const amount = document.getElementById('amount').value;
    if(!validatePrice(amount)) {
        alert("Amount must be a number greater than 0"); return false;
    }
    return true;
}
</script>

<?php require_once '../../includes/footer.php'; ?>
