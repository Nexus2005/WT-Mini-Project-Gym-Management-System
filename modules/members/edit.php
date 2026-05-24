<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

if (!isset($_GET['id'])) {
    echo "<script>window.location.href='view.php';</script>";
    exit();
}

$member_id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM members WHERE member_id = ?");
$stmt->execute([$member_id]);
$member = $stmt->fetch();

if (!$member) {
    echo "<script>window.location.href='view.php';</script>";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];
    $gender = trim($_POST['gender']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);

    // backend validations
    if (!empty($name) && $age >= 10 && preg_match('/^[6-9]\d{9}$/', $mobile) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // check unique email and mobile excluding current member
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE (email=? OR mobile=?) AND member_id != ?");
        $stmt->execute([$email, $mobile, $member_id]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Email or Mobile already registered by another member.";
        } else {
            $stmt = $pdo->prepare("UPDATE members SET name=?, age=?, gender=?, mobile=?, email=?, address=? WHERE member_id=?");
            if ($stmt->execute([$name, $age, $gender, $mobile, $email, $address, $member_id])) {
                $_SESSION['flash_message'] = "Member updated successfully.";
                $_SESSION['flash_type'] = "success";
                echo "<script>window.location.href='view.php';</script>";
                exit();
            } else {
                $error = "Failed to update member.";
            }
        }
    } else {
        $error = "Validation failed. Please check your inputs.";
    }
}
?>

<div class="form-container">
    <h2 class="section-title">Edit Member #<?= $member['member_id'] ?></h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="editMemberForm" onsubmit="return validateEditForm()">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= htmlspecialchars($member['name']) ?>" required>
        </div>
        
        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Age *</label>
                <input type="number" name="age" id="age" class="form-control" value="<?= $member['age'] ?>" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Gender *</label>
                <select name="gender" class="form-control" required>
                    <option value="Male" <?= $member['gender']=='Male'?'selected':'' ?>>Male</option>
                    <option value="Female" <?= $member['gender']=='Female'?'selected':'' ?>>Female</option>
                    <option value="Other" <?= $member['gender']=='Other'?'selected':'' ?>>Other</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Mobile Number *</label>
                <input type="text" name="mobile" id="mobile" class="form-control" value="<?= htmlspecialchars($member['mobile']) ?>" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Email Address *</label>
                <input type="email" name="email" id="email" class="form-control" value="<?= htmlspecialchars($member['email']) ?>" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Address *</label>
            <textarea name="address" id="address" class="form-control" rows="3" required><?= htmlspecialchars($member['address']) ?></textarea>
        </div>

        <button type="submit" class="btn">Update Member</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Members > Edit Member';

function validateEditForm() {
    const name = document.getElementById('name').value;
    const mobile = document.getElementById('mobile').value;
    const age = document.getElementById('age').value;
    const email = document.getElementById('email').value;

    if (!validateName(name)) {
        alert("Name must contain only letters and spaces (min 2 chars)."); return false;
    }
    if (!validateMobile(mobile)) {
        alert("Mobile must be 10 digits starting with 6-9."); return false;
    }
    if (!validateAge(age)) {
        alert("Age must be between 10 and 100."); return false;
    }
    if (!validateEmail(email)) {
        alert("Invalid email format."); return false;
    }

    return true;
}
</script>

<?php require_once '../../includes/footer.php'; ?>
