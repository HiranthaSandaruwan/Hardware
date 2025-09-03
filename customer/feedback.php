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
<div class="feedback-section">
    <h1>Your Feedback</h1>
    <?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
    
    <form method="post" class="feedback-form">
        <div class="form-group">
            <label>Select Request</label>
            <select name="request_id" required>
                <?php while ($e = $eligible->fetch_assoc()): ?>
                    <option value="<?= $e['request_id'] ?>">Request #<?= $e['request_id'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        
        <div class="form-group">
            <label>Rating (1-5)</label>
            <input type="number" name="rating" min="1" max="5" required>
        </div>
        
        <div class="form-group">
            <label>Your Comment</label>
            <textarea name="comment" placeholder="Share your experience with the technician..."></textarea>
        </div>
        
        <button type="submit" class="send-btn">Send Feedback</button>
    </form>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>