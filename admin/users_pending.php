<?php require_once __DIR__.'/../config.php'; require_role('admin'); require_once __DIR__.'/../db.php';
if(isset($_GET['approve'])){ $id=(int)$_GET['approve']; $mysqli->query("UPDATE users SET status='approved' WHERE user_id=$id AND status='pending'"); header('Location: users_pending.php'); exit; }
if(isset($_GET['reject'])){ $id=(int)$_GET['reject']; $mysqli->query("UPDATE users SET status='rejected' WHERE user_id=$id AND status='pending'"); header('Location: users_pending.php'); exit; }
$pending=$mysqli->query("SELECT user_id,username,role,created_at FROM users WHERE status='pending' ORDER BY created_at ASC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Pending User Accounts</h1>
<table class="table"><tr><th>ID</th><th>Username</th><th>Role</th><th>Created</th><th>Action</th></tr>
<?php while($r=$pending->fetch_assoc()): ?>
<tr>
 <td><?= $r['user_id'] ?></td>
 <td><?= htmlspecialchars($r['username']) ?></td>
 <td><?= $r['role'] ?></td>
 <td><?= $r['created_at'] ?></td>
 <td>
   <a class="btn" href="?approve=<?= $r['user_id'] ?>">Approve</a>
   <a class="btn outline" href="?reject=<?= $r['user_id'] ?>">Reject</a>
 </td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
