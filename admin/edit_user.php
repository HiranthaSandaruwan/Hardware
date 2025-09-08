<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

$user_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($user_id < 1) {
    echo "<p>Invalid user ID.</p>";
    exit;
}

// Fetch user and profile data
$user = $mysqli->query("SELECT * FROM users WHERE user_id=$user_id")->fetch_assoc();
if (!$user) {
    echo "<p>User not found.</p>";
    exit;
}

$role = $user['role'];
if ($role === 'user') {
    $profile = $mysqli->query("SELECT * FROM customer_profile WHERE customer_id=$user_id")->fetch_assoc();
} elseif ($role === 'technician') {
    $profile = $mysqli->query("SELECT * FROM technician_profile WHERE technician_id=$user_id")->fetch_assoc();
} else {
    $profile = [];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = $mysqli->real_escape_string($_POST['full_name'] ?? '');
    $email = $mysqli->real_escape_string($_POST['email'] ?? '');
    $phone = $mysqli->real_escape_string($_POST['phone'] ?? '');
    $address = $mysqli->real_escape_string($_POST['address'] ?? '');
    if ($role === 'user') {
        $mysqli->query("UPDATE customer_profile SET full_name='$full_name', email='$email', phone='$phone', address='$address' WHERE customer_id=$user_id");
    } elseif ($role === 'technician') {
        $specialization = $mysqli->real_escape_string($_POST['specialization'] ?? '');
        $experience_years = (int)($_POST['experience_years'] ?? 0);
        $availability_notes = $mysqli->real_escape_string($_POST['availability_notes'] ?? '');
        $mysqli->query("UPDATE technician_profile SET full_name='$full_name', email='$email', phone='$phone', specialization='$specialization', experience_years=$experience_years, availability_notes='$availability_notes' WHERE technician_id=$user_id");
    }
    header('Location: users_manage.php');
    exit;
}

include __DIR__ . '/../partials/header.php';
?>
<h1>Edit User</h1>
<form method="post" class="form" style="max-width:500px;">
    <label>Name:<br>
        <input type="text" name="full_name" value="<?= htmlspecialchars($profile['full_name'] ?? '') ?>" required>
    </label><br>
    <label>Email:<br>
        <input type="email" name="email" value="<?= htmlspecialchars($profile['email'] ?? '') ?>" required>
    </label><br>
    <label>Phone:<br>
        <input type="text" name="phone" value="<?= htmlspecialchars($profile['phone'] ?? '') ?>" required>
    </label><br>
    <?php if ($role === 'user'): ?>
        <label>Address:<br>
            <input type="text" name="address" value="<?= htmlspecialchars($profile['address'] ?? '') ?>">
        </label><br>
    <?php elseif ($role === 'technician'): ?>
        <label>Specialization:<br>
            <input type="text" name="specialization" value="<?= htmlspecialchars($profile['specialization'] ?? '') ?>">
        </label><br>
        <label>Experience (years):<br>
            <input type="number" name="experience_years" value="<?= htmlspecialchars($profile['experience_years'] ?? '') ?>">
        </label><br>
        <label>Availability Notes:<br>
            <input type="text" name="availability_notes" value="<?= htmlspecialchars($profile['availability_notes'] ?? '') ?>">
        </label><br>
    <?php endif; ?>
    <button type="submit" class="btn">Save Changes</button>
    <a href="users_manage.php" class="btn outline">Cancel</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
