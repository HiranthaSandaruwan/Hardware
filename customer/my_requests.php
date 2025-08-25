<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$res=$mysqli->query("SELECT request_id,device_type,admin_status,created_at,updated_at FROM requests WHERE user_id=$uid ORDER BY created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>My Requests</h1>
<table class="table"><tr><th>ID</th><th>Device</th><th>Status</th><th>Created</th><th>Updated</th></tr>
<?php while($r=$res->fetch_assoc()): ?>
<tr><td><?= $r['request_id'] ?></td><td><?= htmlspecialchars($r['device_type']) ?></td><td><?= $r['admin_status'] ?></td><td><?= $r['created_at'] ?></td><td><?= $r['updated_at'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
