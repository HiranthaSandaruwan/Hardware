<?php require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';
$uid = current_user()['id'];
$recent = $mysqli->query("SELECT request_id,device_type,state,updated_at FROM requests WHERE user_id=$uid ORDER BY updated_at DESC LIMIT 6");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<?php $flash = isset($_GET['created']) ? 'Request submitted.' : ''; ?>
<?php if ($flash): ?><div class="success"><?= htmlspecialchars($flash) ?></div><?php endif; ?>
<h1 style = "margin-bottom:1rem">Customer Dashboard</h1>
<p style = "margin-bottom:1rem">
    <a class="btn" href="request_new.php">New Request</a> 
    <a class="btn outline" href="my_requests.php">All Requests</a>
  </p>
  <h2>Recent Activity</h2>
  <table class="table">
      <tr>
          <th>ID</th>
          <th>Device</th>
          <th>Status</th>
          <th>Updated</th>
      </tr>
      <?php while ($row = $recent->fetch_assoc()): ?>
          <?php $label = ($row['state'] === 'New') ? 'Pending (Not Assigned)' : $row['state']; ?>
          <tr>
              <td><?= $row['request_id'] ?></td>
              <td><?= htmlspecialchars($row['device_type']) ?></td>
              <td><?= htmlspecialchars($label) ?></td>
              <td><?= $row['updated_at'] ?></td>
          </tr>
      <?php endwhile; ?>
  </table>
<?php include __DIR__ . '/../partials/footer.php'; ?>
