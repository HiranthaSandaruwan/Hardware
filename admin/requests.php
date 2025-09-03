<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Approvals workflow deprecated; provide read-only state view.
$res = $mysqli->query("SELECT r.request_id,u.username,r.device_type,r.category,r.state,r.created_at FROM requests r JOIN users u ON r.user_id=u.user_id ORDER BY r.created_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>All Requests (Read‑Only)</h1>
<p>State-driven flow active. Approval/rejection controls removed.</p>
<table class="table">
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Device</th>
    <th>Category</th>
    <th>State</th>
    <th>Created</th>
  </tr>
  <?php while ($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= $row['request_id'] ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['device_type']) ?></td>
      <td><?= $row['category'] ?></td>
      <td><span class="status-tag status-<?= str_replace(' ', '-', $row['state']) ?>"><?= $row['state'] ?></span></td>
      <td><?= $row['created_at'] ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>