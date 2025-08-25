<?php require_once __DIR__.'/../config.php'; require_role('admin'); require_once __DIR__.'/../db.php';
if(isset($_GET['approve'])){ $id=(int)$_GET['approve']; $mysqli->query("UPDATE requests SET admin_status='Approved', updated_at=NOW() WHERE request_id=$id AND admin_status='Pending'"); header('Location: requests_pending.php'); exit; }
if(isset($_GET['reject'])){ $id=(int)$_GET['reject']; $mysqli->query("UPDATE requests SET admin_status='Rejected', updated_at=NOW() WHERE request_id=$id AND admin_status='Pending'"); header('Location: requests_pending.php'); exit; }
$pending=$mysqli->query("SELECT r.request_id,u.username,r.device_type,r.category,r.created_at FROM requests r JOIN users u ON r.user_id=u.user_id WHERE r.admin_status='Pending' ORDER BY r.created_at ASC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Pending Requests</h1>
<table class="table"><tr><th>ID</th><th>Customer</th><th>Device</th><th>Category</th><th>Created</th><th>Action</th></tr>
<?php while($r=$pending->fetch_assoc()): ?>
<tr>
 <td><?= $r['request_id'] ?></td>
 <td><?= htmlspecialchars($r['username']) ?></td>
 <td><?= htmlspecialchars($r['device_type']) ?></td>
 <td><?= $r['category'] ?></td>
 <td><?= $r['created_at'] ?></td>
 <td><a class="btn" href="?approve=<?= $r['request_id'] ?>">Approve</a> <a class="btn outline" href="?reject=<?= $r['request_id'] ?>">Reject</a></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
