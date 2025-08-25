<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$res=$mysqli->query("SELECT request_id,device_type,status,appointment_time,updated_at FROM requests WHERE user_id=$uid ORDER BY created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>My Requests</h1>
<table class="table"><tr><th>ID</th><th>Device</th><th>Status</th><th>Appointment</th><th>Updated</th></tr>
<?php while($row=$res->fetch_assoc()): ?>
<tr><td><?= $row['request_id'] ?></td><td><?= htmlspecialchars($row['device_type']) ?></td><td><?= $row['status'] ?></td><td><?= $row['appointment_time'] ?></td><td><?= $row['updated_at'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
