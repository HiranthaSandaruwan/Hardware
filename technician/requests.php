<?php
require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';

// This page is deprecated in favor of accepted_appointments.php & completed.php (state-driven).
// Keep a minimal read-only list to avoid broken links; encourage navigation to new panels.
$uid = current_user()['id'];
$res = $mysqli->query("SELECT r.request_id,r.device_type,r.state, (SELECT a.chosen_slot FROM appointments a WHERE a.request_id=r.request_id ORDER BY a.created_at DESC LIMIT 1) chosen_slot FROM requests r WHERE r.assigned_to=$uid ORDER BY r.updated_at DESC LIMIT 50");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Requests (Legacy View)</h1>
<p>This page is read-only now. Use <a href="accepted_appointments.php">Accepted Appointments</a> for live updates and <a href="completed.php">Completed Work</a> for final actions.</p>
<table class="table">
  <tr>
    <th>ID</th>
    <th>Device</th>
    <th>State</th>
    <th>Appointment</th>
  </tr>
  <?php while ($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= $row['request_id'] ?></td>
      <td><?= htmlspecialchars($row['device_type']) ?></td>
      <td><?= htmlspecialchars($row['state']) ?></td>
      <td><?= $row['chosen_slot'] ?: '-' ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>