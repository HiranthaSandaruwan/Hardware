<?php require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';
$uid = current_user()['id'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $req = (int)$_POST['request_id'];
  $rating = (int)$_POST['rating'];
  $comment = trim($_POST['comment'] ?? '');
  // ensure receipt exists
  $ok = $mysqli->query("SELECT r.request_id,a.technician_id FROM receipts rc JOIN requests r ON rc.request_id=r.request_id JOIN appointments a ON a.request_id=r.request_id WHERE r.request_id=$req AND r.user_id=$uid LIMIT 1")->fetch_assoc();
  if ($ok) {
    $tech = $ok['technician_id'];
    $stmt = $mysqli->prepare('INSERT INTO feedback(request_id,from_user,to_user,role_view,rating,comment,created_at) VALUES(?,?,?,?,?,?,NOW())');
    $role_view = 'customer_to_technician';
    $stmt->bind_param('iiisis', $req, $uid, $tech, $role_view, $rating, $comment);
    $stmt->execute();
    $msg = 'Feedback submitted';
  }
}
$eligible = $mysqli->query("SELECT r.request_id FROM receipts rc JOIN requests r ON rc.request_id=r.request_id WHERE r.user_id=$uid AND r.request_id NOT IN (SELECT request_id FROM feedback WHERE from_user=$uid AND role_view='customer_to_technician')");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Feedback for Technicians</h1>
<?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <label>Request
    <select name="request_id" required>
      <?php while ($e = $eligible->fetch_assoc()): ?>
        <option value="<?= $e['request_id'] ?>">Request #<?= $e['request_id'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Rating (1-5)<input type="number" name="rating" min="1" max="5" required></label>
  <label>Comment<textarea name="comment"></textarea></label>
  <button class="btn" type="submit">Submit Feedback</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>