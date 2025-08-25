<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$recent=$mysqli->query("SELECT request_id,device_type,status,updated_at FROM requests WHERE user_id=$uid ORDER BY updated_at DESC LIMIT 8");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>User Dashboard</h1>
<p><a class="btn" href="request_new.php">Submit New Request</a> <a class="btn outline" href="my_requests.php">View All</a></p>
<h2>Recent Activity</h2>
<table class="table"><tr><th>ID</th><th>Device</th><th>Status</th><th>Updated</th></tr>
<?php while($row=$recent->fetch_assoc()): ?>
<tr><td><?= $row['request_id'] ?></td><td><?= htmlspecialchars($row['device_type']) ?></td><td><?= $row['status'] ?></td><td><?= $row['updated_at'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
