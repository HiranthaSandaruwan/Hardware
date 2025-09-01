<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

$err = '';
$ok  = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');
  $role     = $_POST['role'] ?? 'user';

  if ($username === '' || $password === '') {
    $err = 'All fields required';
  } elseif (!in_array($role, ['user', 'technician'])) {
    $err = 'Invalid role';
  } else {
    $stmt = $mysqli->prepare('SELECT user_id FROM users WHERE username=? LIMIT 1');
    $stmt->bind_param('s', $username);
    $stmt->execute();
    $stmt->store_result();
    if ($stmt->num_rows > 0) {
      $err = 'Username taken';
    } else {
  // Auto-approve normal users; technicians require admin approval
  $status = ($role === 'technician') ? 'pending' : 'approved';
      $now    = date('Y-m-d H:i:s');
      $stmt2  = $mysqli->prepare('INSERT INTO users(username,password,role,status,created_at) VALUES(?,?,?,?,?)');
      $stmt2->bind_param('sssss', $username, $password, $role, $status, $now);
      if ($stmt2->execute()) {
        if($role==='technician'){
          $ok = 'Technician registration submitted. Await admin approval.';
        } else {
          $ok = 'Customer registered. You can now log in.';
        }
      } else {
        $err = 'Insert failed';
      }
    }
  }
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Register</h1>
<?php if ($err): ?>
  <div class="error"><?= htmlspecialchars($err) ?></div>
<?php endif; ?>
<?php if ($ok): ?>
  <div class="success"><?= htmlspecialchars($ok) ?></div>
<?php endif; ?>
<form method="post" data-validate>
  <label>Username
    <input name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <label>Role
    <select name="role">
      <option value="user" <?= (($_POST['role'] ?? '') === 'user') ? 'selected' : ''; ?>>User</option>
      <option value="technician" <?= (($_POST['role'] ?? '') === 'technician') ? 'selected' : ''; ?>>Technician</option>
    </select>
  </label>
  <button type="submit">Register</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
