<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

if (isset($_GET['approve'])) {
  $id = (int)$_GET['approve'];
  $mysqli->query("UPDATE requests SET status='Approved' WHERE request_id=$id AND status='Pending'");
  header('Location: requests.php');
  exit;
}
if (isset($_GET['reject'])) {
  $id = (int)$_GET['reject'];
  $mysqli->query("UPDATE requests SET status='Rejected' WHERE request_id=$id AND status IN ('Pending','Approved')");
  header('Location: requests.php');
  exit;
}

$res = $mysqli->query("SELECT r.request_id,u.username,r.device_type,r.category,r.status,r.created_at FROM requests r JOIN users u ON r.user_id=u.user_id ORDER BY r.created_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Manage Requests</h1>
<table class="table">
  <tr><th>ID</th><th>User</th><th>Device</th><th>Category</th><th>Status</th><th>Created</th><th>Action</th></tr>
  <?php while ($row = $res->fetch_assoc()): ?>
    <tr>
      <td><?= $row['request_id'] ?></td>
      <td><?= htmlspecialchars($row['username']) ?></td>
      <td><?= htmlspecialchars($row['device_type']) ?></td>
      <td><?= $row['category'] ?></td>
      <td><span class="status-tag status-<?= str_replace(' ', '-', $row['status']) ?>"><?= $row['status'] ?></span></td>
      <td><?= $row['created_at'] ?></td>
      <td>
        <?php if ($row['status'] === 'Pending'): ?>
          <a class="btn" href="?approve=<?= $row['request_id'] ?>">Approve</a>
          <a class="btn outline" href="?reject=<?= $row['request_id'] ?>">Reject</a>
        <?php elseif ($row['status'] === 'Approved'): ?>
          <a class="btn outline" href="?reject=<?= $row['request_id'] ?>">Reject</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>
