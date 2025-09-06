<?php
/** Login */
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

$err  = '';
$info = '';

// Registration success info banner
if (isset($_GET['registered'])) {
  if (($_GET['type'] ?? '') === 'technician') {
    $info = 'Technician registration submitted. Wait for admin approval before logging in.';
  } else {
    $info = 'Registration successful. You can log in now.';
  }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = trim($_POST['password'] ?? '');

  $stmt = $mysqli->prepare('SELECT user_id,username,password,role,status,is_disabled FROM users WHERE username=? LIMIT 1');
  $stmt->bind_param('s', $username);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($row = $res->fetch_assoc()) {
    // Support both hashed (new) and legacy plain text passwords
    $valid = password_verify($password, $row['password']) || $row['password'] === $password;
    if ($valid) {
      if ($row['is_disabled']) {
        $err = 'Account disabled by admin';
      } elseif ($row['role'] === 'technician' && $row['status'] !== 'approved') {
        $err = 'Technician account pending approval';
      } else {
        // Store minimal session user
        $_SESSION['user'] = [
          'id'       => $row['user_id'],
          'username' => $row['username'],
          'role'     => $row['role']
        ];
        // Role-based landing
        if ($row['role'] === 'admin') {
          header('Location: ../admin/index.php');
        } elseif ($row['role'] === 'technician') {
          header('Location: ../technician/index.php');
        } else {
          header('Location: ../customer/dashboard.php');
        }
        exit;
      }
    } else {
      $err = 'Invalid credentials';
    }
  } else {
    $err = 'Invalid credentials';
  }
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Login</h1>
<?php if ($info): ?>
  <div class="success"><?= htmlspecialchars($info) ?></div>
<?php endif; ?>
<?php if ($err): ?>
  <div class="error"><?= htmlspecialchars($err) ?></div>
<?php endif; ?>
<form method="post" data-validate>
  <label>Username
    <input name="username" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
  </label>
  <label>Password
    <input type="password" name="password" required>
  </label>
  <div class="button-container">
    <button type="submit">Login</button>
    <a href="choose_role.php" class="btn outline" style="margin:0;">Register</a>
  </div>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
