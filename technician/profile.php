<?php
// Technician Profile - update limited fields (phone, email, availability, password)
require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';

$uid = current_user()['id'];
$err = '';
$ok  = '';

// Load current details
$stmt = $mysqli->prepare('SELECT u.username, tp.full_name, tp.phone, tp.email, tp.specialization, tp.experience_years, tp.availability_notes FROM users u LEFT JOIN technician_profile tp ON tp.technician_id=u.user_id WHERE u.user_id=? LIMIT 1');
$stmt->bind_param('i', $uid);
$stmt->execute();
$res = $stmt->get_result();
$cur = $res->fetch_assoc() ?: [
  'username' => '', 'full_name' => '', 'phone' => '', 'email' => '',
  'specialization' => '', 'experience_years' => 0, 'availability_notes' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $phone       = trim($_POST['phone'] ?? '');
  $email       = trim($_POST['email'] ?? '');
  $availability= trim($_POST['availability'] ?? '');
  $new_pass    = trim($_POST['new_password'] ?? '');
  $confirm     = trim($_POST['confirm_password'] ?? '');

  $phoneOk = preg_match('/^[0-9]{10}$/', $phone);
  $emailOk = ($email === '') ? true : filter_var($email, FILTER_VALIDATE_EMAIL);
  $passOk  = ($new_pass === '') ? true : preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,8}$/', $new_pass);
  $match   = ($new_pass === '' || $new_pass === $confirm);

  if ($phone === '') {
    $err = 'Phone is required';
  } elseif (!$phoneOk) {
    $err = 'Invalid phone (10 digits required)';
  } elseif (!$emailOk) {
    $err = 'Invalid email format';
  } elseif (!$passOk) {
    $err = 'Password must be 6-8 chars incl. letters & numbers';
  } elseif (!$match) {
    $err = 'Passwords do not match';
  } else {
    // Update profile limited fields
    $p = $mysqli->prepare('UPDATE technician_profile SET phone=?, email=?, availability_notes=? WHERE technician_id=?');
    $p->bind_param('sssi', $phone, $email, $availability, $uid);
    $p->execute();

    // Optional password change
    if ($new_pass !== '') {
      $hash = password_hash($new_pass, PASSWORD_DEFAULT);
      $u = $mysqli->prepare('UPDATE users SET password=? WHERE user_id=?');
      $u->bind_param('si', $hash, $uid);
      $u->execute();
    }

    $ok = 'Profile updated';

    // Refresh view state
    $cur['phone'] = $phone;
    $cur['email'] = $email;
    $cur['availability_notes'] = $availability;
  }
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Technician Profile</h1>
<?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>

<form method="post" data-validate>
  <label>Username
    <input value="<?= htmlspecialchars($cur['username']) ?>" readonly>
  </label>
  <label>Full Name
    <input value="<?= htmlspecialchars($cur['full_name']) ?>" readonly>
  </label>
  <label>Specialization / Skills
    <input value="<?= htmlspecialchars($cur['specialization']) ?>" readonly>
  </label>
  <label>Experience (years)
    <input value="<?= htmlspecialchars((string)$cur['experience_years']) ?>" readonly>
  </label>

  <label>Phone
    <input name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? ($cur['phone'] ?? '')) ?>">
  </label>
  <label>Email
    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? ($cur['email'] ?? '')) ?>">
  </label>
  <label>Availability Notes
    <textarea name="availability"><?php echo htmlspecialchars($_POST['availability'] ?? ($cur['availability_notes'] ?? '')); ?></textarea>
  </label>

  <fieldset style="margin:.5rem 0 0; padding:.5rem .75rem; border:1px solid #dfe3e8; border-radius:.4rem;">
    <legend style="padding:0 .35rem; font-size:.85rem; color:#ccc;">Change Password (optional)</legend>
    <label>New Password
      <input type="password" name="new_password" placeholder="Leave blank to keep current password">
    </label>
    <label>Confirm Password
      <input type="password" name="confirm_password">
    </label>
    <small>6-8 characters, must include letters and numbers.</small>
  </fieldset>

  <button type="submit">Save Changes</button>
  <a class="btn outline" href="<?= $BASE_URL ?>/technician/index.php">Cancel</a>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
