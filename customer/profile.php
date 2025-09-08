<?php
require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';

$uid = current_user()['id'];
$err = '';
$ok  = '';

// Current details
$stmt = $mysqli->prepare('SELECT u.username, u.password, cp.full_name, cp.phone, cp.email, cp.address 
                            FROM users u LEFT JOIN customer_profile cp ON cp.customer_id = u.user_id 
                            WHERE u.user_id = ? LIMIT 1');

$stmt->bind_param('i', $uid);
$stmt->execute();
$result = $stmt->get_result();
$current = $result->fetch_assoc() ?: [
  'username'   => '',
  'full_name'  => '',
  'phone'      => '',
  'email'      => '',
  'address'    => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $full_name = trim($_POST['full_name'] ?? '');
  $phone     = trim($_POST['phone'] ?? '');
  $email     = trim($_POST['email'] ?? '');
  $address   = trim($_POST['address'] ?? '');
  $username  = trim($_POST['username'] ?? '');
  $new_pass  = trim($_POST['new_password'] ?? '');
  $confirm   = trim($_POST['confirm_password'] ?? '');

  // Validations
  $usernameOk = preg_match('/^[A-Za-z0-9_]{3,16}$/', $username);
  $phoneOk    = preg_match('/^[0-9]{10}$/', $phone);
  $emailOk    = ($email === '') ? true : filter_var($email, FILTER_VALIDATE_EMAIL);
  $passOk     = ($new_pass === '') ? true : preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,8}$/', $new_pass);
  $passMatch  = ($new_pass === '' || $new_pass === $confirm);

  if ($full_name === '' || $phone === '' || $username === '') {
    $err = 'Required fields missing';
  } elseif (!$usernameOk) {
    $err = 'Invalid username (3-16 letters, numbers, underscore)';
  } elseif (!$phoneOk) {
    $err = 'Invalid phone (10 digits required)';
  } elseif (!$emailOk) {
    $err = 'Invalid email format';
  } elseif (!$passOk) {
    $err = 'Password must be 6-8 chars incl. letters & numbers';
  } elseif (!$passMatch) {
    $err = 'Passwords do not match';
  } else {

    // Start update
    // Username change and check for existing users to avoid duplication
    if ($username !== $current['username']) {
      $chk = $mysqli->prepare('SELECT user_id FROM users WHERE username = ? AND user_id <> ? LIMIT 1');
      $chk->bind_param('si', $username, $uid);
      $chk->execute();
      $chk->store_result();
      if ($chk->num_rows > 0) {
        $err = 'Username taken';
      }
    }

    if (!$err) {
      // Update users
      if ($new_pass !== '') {
        $hash = password_hash($new_pass, PASSWORD_DEFAULT);
        $u = $mysqli->prepare('UPDATE users SET username=?, password=? WHERE user_id=?');
        $u->bind_param('ssi', $username, $hash, $uid);
        $u->execute();
      } else {
        $u = $mysqli->prepare('UPDATE users SET username=? WHERE user_id=?');
        $u->bind_param('si', $username, $uid);
        $u->execute();
      }

      // Upsert customer_profile
      $exists = $mysqli->prepare('SELECT customer_id FROM customer_profile WHERE customer_id=?');
      $exists->bind_param('i', $uid);
      $exists->execute();
      $exists->store_result();
      if ($exists->num_rows > 0) {
        $p = $mysqli->prepare('UPDATE customer_profile SET full_name=?, phone=?, email=?, address=? WHERE customer_id=?');
        $p->bind_param('ssssi', $full_name, $phone, $email, $address, $uid);
        $p->execute();
      } else {
        $p = $mysqli->prepare('INSERT INTO customer_profile(customer_id, full_name, phone, email, address) VALUES(?,?,?,?,?)');
        $p->bind_param('issss', $uid, $full_name, $phone, $email, $address);
        $p->execute();
      }

      // Update session username if changed
      if ($username !== $current['username']) {
        $_SESSION['user']['username'] = $username;
      }

      $ok = 'Profile updated';

      // Refresh current values for display
      $current = array_merge($current, [
        'username'  => $username,
        'full_name' => $full_name,
        'phone'     => $phone,
        'email'     => $email,
        'address'   => $address,
      ]);
    }
  }
}

?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>My Profile</h1>
<?php if ($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if ($ok): ?><div class="success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
<form method="post" data-validate>
  <label>Full Name
    <input name="full_name" required value="<?= htmlspecialchars($_POST['full_name'] ?? ($current['full_name'] ?? '')) ?>">
  </label>
  <label>Phone
    <input name="phone" required value="<?= htmlspecialchars($_POST['phone'] ?? ($current['phone'] ?? '')) ?>">
  </label>
  <label>Email
    <input type="email" name="email" value="<?= htmlspecialchars($_POST['email'] ?? ($current['email'] ?? '')) ?>">
  </label>
  <label>Address
    <textarea name="address"><?php echo htmlspecialchars($_POST['address'] ?? ($current['address'] ?? '')); ?></textarea>
  </label>
  <label>Username
    <input name="username" required value="<?= htmlspecialchars($_POST['username'] ?? ($current['username'] ?? '')) ?>">
  </label>
  <fieldset style="margin:.5rem 0 0; padding:.5rem .75rem; border:1px solid #dfe3e8; border-radius:.4rem;">
    <legend style="padding:0 .35rem; font-size:.85rem; color:#333;">Change Password (optional)</legend>
    <label>New Password
      <input type="password" name="new_password" placeholder="Leave blank to keep current password">
    </label>
    <label>Confirm Password
      <input type="password" name="confirm_password">
    </label>
    <small>6-8 characters, must include letters and numbers.</small>
  </fieldset>
  <button type="submit">Save Changes</button>
  <a class="btn outline" href="<?= $BASE_URL ?>/customer/index.php">Cancel</a>
  </form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
