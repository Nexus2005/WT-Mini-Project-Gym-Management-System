<?php
require_once '../../includes/session_check.php';
require_once '../../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $age = (int)$_POST['age'];
    $gender = trim($_POST['gender']);
    $mobile = trim($_POST['mobile']);
    $email = trim($_POST['email']);
    $address = trim($_POST['address']);
    $join_date = trim($_POST['join_date']);

    // backend validations
    if (!empty($name) && $age >= 10 && preg_match('/^[6-9]\d{9}$/', $mobile) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // check unique email and mobile
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM members WHERE email=? OR mobile=?");
        $stmt->execute([$email, $mobile]);
        if ($stmt->fetchColumn() > 0) {
            $error = "Email or Mobile already registered.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO members (name, age, gender, mobile, email, address, join_date) VALUES (?, ?, ?, ?, ?, ?, ?)");
            if ($stmt->execute([$name, $age, $gender, $mobile, $email, $address, $join_date])) {
                $new_id = $pdo->lastInsertId();
                $_SESSION['flash_message'] = "Member added successfully. Member ID: $new_id";
                $_SESSION['flash_type'] = "success";
                echo "<script>window.location.href='view.php';</script>";
                exit();
            } else {
                $error = "Failed to add member.";
            }
        }
    } else {
        $error = "Validation failed. Please check your inputs.";
    }
}
?>

<div class="form-container">
    <h2 class="section-title">Add New Member</h2>
    
    <?php if(isset($error)): ?>
        <div class="flash-alert flash-error"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>

    <form method="POST" id="addMemberForm" onsubmit="return validateMemberForm()">
        <div class="form-group">
            <label>Full Name *</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        
        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Age *</label>
                <input type="number" name="age" id="age" class="form-control" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Gender *</label>
                <select name="gender" class="form-control" required>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
        </div>

        <div style="display:flex; gap:20px;">
            <div class="form-group" style="flex:1;">
                <label>Mobile Number *</label>
                <input type="text" name="mobile" id="mobile" class="form-control" required>
            </div>
            <div class="form-group" style="flex:1;">
                <label>Email Address *</label>
                <input type="email" name="email" id="email" class="form-control" required>
            </div>
        </div>
        
        <div class="form-group">
            <label>Address *</label>
            <textarea name="address" id="address" class="form-control" rows="3" required></textarea>
        </div>

        <div class="form-group">
            <label>Join Date *</label>
            <input type="date" name="join_date" id="join_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
        </div>

        <button type="submit" class="btn">Save Member</button>
        <a href="view.php" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<script>
document.getElementById('breadcrumb-text').innerHTML = '<a href="' + BASE_URL + 'dashboard.php">Dashboard</a> > Members > Add Member';

function validateMemberForm() {
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

    // specific prompt requirement for Add Member
    return confirmMemberAdd('name');
}
</script>

<?php require_once '../../includes/footer.php'; ?>
