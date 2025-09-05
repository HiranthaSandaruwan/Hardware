<?php require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';
$tid = current_user()['id'];
$msg = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $rid = (int)$_POST['request_id'];
  $slot1 = $_POST['slot1'];
  $slot2 = $_POST['slot2'] ?: null;
  $slot3 = $_POST['slot3'] ?: null;
  if ($slot1) {
    // assign request to this technician if still New
    $mysqli->query("UPDATE requests SET assigned_to=$tid, state='Assigned', updated_at=NOW() WHERE request_id=$rid AND state='New'");
    $stmt = $mysqli->prepare('INSERT INTO appointment_proposals(request_id,technician_id,slot1,slot2,slot3,created_at) VALUES(?,?,?,?,?,NOW())');
    $stmt->bind_param('iisss', $rid, $tid, $slot1, $slot2, $slot3);
    $stmt->execute();
    $msg = 'Slots proposed';
  }
}
$approved = $mysqli->query("SELECT request_id,device_type,category,description FROM requests WHERE state='New' ORDER BY created_at DESC LIMIT 30");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Requests (Assign & Propose Slots)</h1>
<?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<table class="table">
  <tr>
    <th>ID</th>
    <th>Device</th>
    <th>Category</th>
    <th>Description</th>
    <th>Propose</th>
  </tr>
  <?php while ($r = $approved->fetch_assoc()): ?>
    <tr>
      <td><?= $r['request_id'] ?></td>
      <td><?= htmlspecialchars($r['device_type']) ?></td>
      <td><?= $r['category'] ?></td>
      <td style="max-width:260px;white-space:normal;">
        <?php 
          $desc = trim($r['description']);
          if (strlen($desc) > 180) { $desc = substr($desc,0,177).'...'; }
          echo nl2br(htmlspecialchars($desc));
        ?>
      </td>
      <td>
        <form method="post" style="display:inline-block;min-width:300px">
          <input type="hidden" name="request_id" value="<?= $r['request_id'] ?>">
          <input type="datetime-local" name="slot1" required>
          <input type="datetime-local" name="slot2">
          <input type="datetime-local" name="slot3">
          <button class="btn" type="submit">Send</button>
        </form>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>