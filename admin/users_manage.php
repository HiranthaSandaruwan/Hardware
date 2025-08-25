<?php require_once __DIR__.'/../config.php'; require_role('admin'); require_once __DIR__.'/../db.php';
if(isset($_GET['toggle'])){ $id=(int)$_GET['toggle']; $mysqli->query("UPDATE users SET is_disabled = IF(is_disabled=1,0,1) WHERE user_id=$id AND role!='admin'"); header('Location: users_manage.php'); exit; }
$users=$mysqli->query("SELECT user_id,username,role,status,is_disabled,created_at FROM users ORDER BY created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Manage Users</h1>
<table class="table"><tr><th>ID</th><th>Username</th><th>Role</th><th>Status</th><th>Disabled</th><th>Action</th></tr>
<?php while($u=$users->fetch_assoc()): ?>
<tr>
  <td><?= $u['user_id'] ?></td>
  <td><?= htmlspecialchars($u['username']) ?></td>
  <td><?= $u['role'] ?></td>
  <td><?= $u['status'] ?></td>
  <td><?= $u['is_disabled'] ? 'Yes':'No' ?></td>
  <td><?php if($u['role']!='admin'): ?><a class="btn" href="?toggle=<?= $u['user_id'] ?>"><?= $u['is_disabled']?'Enable':'Disable' ?></a><?php endif; ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
