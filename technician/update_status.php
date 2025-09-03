<?php require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';
$tid = current_user()['id'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rid = (int)$_POST['request_id'];
  $status = $_POST['status'];
  $note = trim($_POST['note'] ?? '');
  // ensure device received first (except Pending)
  $row = $mysqli->query("SELECT a.device_received FROM appointments a WHERE a.request_id=$rid LIMIT 1")->fetch_assoc();
  if ($status !== 'Pending' && (!$row || !$row['device_received'])) {
    $msg = 'Device must be received first';
  } else {
    $stmt = $mysqli->prepare('INSERT INTO repair_updates(request_id,technician_id,status,note,created_at) VALUES(?,?,?,?,NOW())');
    $stmt->bind_param('iiss', $rid, $tid, $status, $note);
    if ($stmt->execute()) {
      // Map repair update status to request.state transitions
      $newState = $status;
      if ($status === 'Pending') $newState = 'Device Received'; // after device received
      if ($status === 'In Progress') $newState = 'In Progress';
      if ($status === 'Completed') $newState = 'Completed';
      if ($status === 'Cannot Fix') $newState = 'Cannot Fix';
      if ($status === 'On Hold') $newState = 'On Hold';
      $mysqli->query("UPDATE requests SET state='" . $mysqli->real_escape_string($newState) . "', updated_at=NOW() WHERE request_id=$rid");
      // If moved to a final/completed style state, go straight to Completed Work list so technician sees it there
      if (in_array($newState, ['Completed', 'Cannot Fix', 'Returned'])) {
        header('Location: completed.php?just=1');
      } else {
        header('Location: index.php?status_updated=1');
      }
      exit;
    } else {
      $msg = 'Insert failed';
    }
  }
}
$my = $mysqli->query("SELECT a.request_id FROM appointments a JOIN requests r ON r.request_id=a.request_id WHERE a.technician_id=$tid AND r.state NOT IN('Completed','Cannot Fix','Returned') ORDER BY a.created_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Update Repair Status</h1>
<?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <label>Request
    <select name="request_id" required>
      <?php while ($r = $my->fetch_assoc()): ?>
        <option value="<?= $r['request_id'] ?>">#<?= $r['request_id'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Status
    <select name="status">
      <?php foreach (['Pending', 'In Progress', 'Completed', 'Cannot Fix', 'On Hold'] as $s): ?>
        <option value="<?= $s ?>"><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Note<textarea name="note"></textarea></label>
  <button class="btn" type="submit">Add Update</button>
</form>
<?php include __DIR__ . '/../partials/footer.php'; ?>