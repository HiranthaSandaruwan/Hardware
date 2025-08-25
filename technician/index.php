<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$assigned=$mysqli->query("SELECT r.request_id,r.device_type,r.status,r.appointment_time FROM requests r WHERE r.technician_id=$uid ORDER BY r.updated_at DESC LIMIT 10");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Technician Dashboard</h1>
<h2>Recent Assigned Requests</h2>
<table class="table"><tr><th>ID</th><th>Device</th><th>Status</th><th>Appointment</th></tr>
<?php while($row=$assigned->fetch_assoc()): ?>
<tr><td><?= $row['request_id'] ?></td><td><?= htmlspecialchars($row['device_type']) ?></td><td><?= $row['status'] ?></td><td><?= $row['appointment_time'] ?></td></tr>
<?php endwhile; ?>
</table>
<p><a href="requests.php" class="btn">Manage Assigned Requests</a></p>
<?php include __DIR__.'/../partials/footer.php'; ?>
