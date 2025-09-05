<?php
require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';

$uid = current_user()['id'];
$assigned = $mysqli->query(
	"SELECT r.request_id, r.device_type, r.state, 
	  (SELECT a.chosen_slot FROM appointments a 
	     WHERE a.request_id = r.request_id 
	     ORDER BY a.created_at DESC LIMIT 1) AS chosen_slot
     FROM requests r
     WHERE r.assigned_to = $uid
     ORDER BY r.updated_at DESC
     LIMIT 10"
);
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php if (isset($_GET['status_updated'])): ?>
	<div class="success">Status updated.</div>
<?php endif; ?>
<h1 style="margin-bottom:1rem">Technician Dashboard</h1>
<h2>Assigned Requests</h2>
<table class="table">
	<tr>
		<th>ID</th>
		<th>Device</th>
		<th>State</th>
		<th>Appointment</th>
	</tr>
	<?php while ($row = $assigned->fetch_assoc()): ?>
		<tr>
			<td><?= $row['request_id'] ?></td>
			<td><?= htmlspecialchars($row['device_type']) ?></td>
			<td><?= htmlspecialchars($row['state']) ?></td>
			<td><?= $row['chosen_slot'] ?: '-' ?></td>
		</tr>
	<?php endwhile; ?>
</table>
<p><a href="accepted_appointments.php" class="btn">Manage Appointments / Updates</a> <a href="completed.php" class="btn outline">Completed Work</a></p>
<?php include __DIR__ . '/../partials/footer.php'; ?>