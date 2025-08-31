<?php
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../db.php';

$err = '';
$info='';
if(isset($_GET['registered'])){
  if(($_GET['type'] ?? '')==='technician'){
    $info='Technician registration submitted. Wait for admin approval before logging in.';
  } else {
    $info='Registration successful. You can log in now.';
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
    if ($row['password'] === $password) { // plain text per spec
      if ($row['is_disabled']) {
        $err = 'Account disabled by admin';
      } elseif ($row['role']==='technician' && $row['status'] !== 'approved') {
        $err = 'Technician account pending approval';
      } else {
        $_SESSION['user'] = [
          'id'       => $row['user_id'],
          'username' => $row['username'],
          'role'     => $row['role']
        ];
        if ($row['role'] === 'admin') {
          header('Location: ../admin/index.php');
        } elseif ($row['role'] === 'technician') {
          // dashboard file is index.php in technician directory
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
  <button type="submit">Login</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>
