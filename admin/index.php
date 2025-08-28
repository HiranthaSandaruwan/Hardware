<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Basic counts
$counts = [];
foreach (['users'] as $tbl) {
  $r = $mysqli->query("SELECT COUNT(*) c FROM $tbl");
  $counts[$tbl] = $r->fetch_assoc()['c'];
}
?
    <p><a href="reports.php">Open Reports</a></p>
  </div>
  <div class="card">
    <h3>Feedback</h3>
    <p>Service quality insights.</p>
    <p><a href="feedback_customers.php">Customer → Tech</a><br><a href="feedback_technicians.php">Tech → Customer</a></p>
  </div>
  <div class="card">
    <h3>Maintenance</h3>
    <p>Purge transactional data (keep users).</p>
    <p><a href="clear_data.php" style="color:#b50000;">Clear Data</a></p>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>
