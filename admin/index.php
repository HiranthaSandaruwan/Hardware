<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Basic counts
$counts = [];
foreach (['users', 'requests'] as $tbl) {
  $r = $mysqli->query("SELECT COUNT(*) c FROM $tbl");
  $counts[$tbl] = $r->fetch_assoc()['c'];
}
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Admin Dashboard</h1>
<div class="flex">
  <div class="card">
    <h3>Total Users</h3>
    <p><strong><?= $counts['users'] ?? 0 ?></strong></p>
  </div>
  <div class="card">
    <h3>Total Requests</h3>
    <p><strong><?= $counts['requests'] ?? 0 ?></strong></p>
  </div>
</div>
<p style="margin-top:20px">
  Quick links:
  <a href="users.php">Approve Users</a> |
  <a href="requests.php">Manage Requests</a> |
  <a href="reports.php">Reports</a>
</p>
<?php include __DIR__ . '/../partials/footer.php'; ?>
