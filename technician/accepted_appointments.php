<?php require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';
$tid = current_user()['id'];
$msg = '';
// Mark device received / no-show quick actions
if (isset($_GET['received'])) {
  $aid = (int)$_GET['received'];
  $row = $mysqli->query("SELECT request_id FROM appointments WHERE appointment_id=$aid AND technician_id=$tid")->fetch_assoc();
  if ($row) {
    $mysqli->query("UPDATE appointments SET device_received=1 WHERE appointment_id=$aid");
    $mysqli->query("UPDATE requests SET state='Device Received', updated_at=NOW() WHERE request_id=" . $row['request_id']);
  }
  header('Location: accepted_appointments.php');
  exit;
}
if (isset($_GET['noshow'])) {
  $aid = (int)$_GET['noshow'];
  $row = $mysqli->query("SELECT request_id FROM appointments WHERE appointment_id=$aid AND technician_id=$tid")->fetch_assoc();
  if ($row) {
    $mysqli->query("UPDATE appointments SET no_show=1 WHERE appointment_id=$aid");
    $mysqli->query("UPDATE requests SET state='No-Show', updated_at=NOW() WHERE request_id=" . $row['request_id']);
  }
  header('Location: accepted_appointments.php');
  exit;
}

// Inline status update per request
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status_update'])) {
  $rid = (int)$_POST['request_id'];
  $status = $_POST['status'];
  $note = trim($_POST['note'] ?? '');
  $row = $mysqli->query("SELECT a.device_received FROM appointments a WHERE a.request_id=$rid AND a.technician_id=$tid LIMIT 1")->fetch_assoc();
  if ($status !== 'Pending' && (!$row || !$row['device_received'])) {
    $msg = 'Device must be received first';
  } else {
    $stmt = $mysqli->prepare('INSERT INTO repair_updates(request_id,technician_id,status,note,created_at) VALUES(?,?,?,?,NOW())');
    $stmt->bind_param('iiss', $rid, $tid, $status, $note);
    if ($stmt->execute()) {
      $newState = $status === 'Pending' ? 'Device Received' : $status;
      if ($status === 'Pending' && !$row['device_received']) { /* fallback already handled above */
      }
      $mysqli->query("UPDATE requests SET state='" . $mysqli->real_escape_string($newState) . "', updated_at=NOW() WHERE request_id=$rid");
      if (in_array($newState, ['Completed', 'Cannot Fix', 'Returned'])) {
        header('Location: completed.php?just=1');
        exit;
      }
      $msg = 'Status updated';
    } else {
      $msg = 'Insert failed';
    }
  }
}

$apps = $mysqli->query("SELECT a.appointment_id,a.request_id,a.chosen_slot,a.device_received,a.no_show,r.state FROM appointments a JOIN requests r ON r.request_id=a.request_id WHERE a.technician_id=$tid ORDER BY a.created_at DESC");
include __DIR__ . '/../partials/header.php'; ?>
<h1>Accepted Appointments</h1>
<?php if ($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<table class="table">
  <tr>
    <th>ID</th>
    <th>Request</th>
    <th>Slot</th>
    <th>Device / Attendance</th>
    <th>State</th>
    <th>Status Update</th>
  </tr>
  <?php while ($a = $apps->fetch_assoc()): ?>
    <tr>
      <td><?= $a['appointment_id'] ?></td>
      <td><?= $a['request_id'] ?></td>
      <td><?= $a['chosen_slot'] ?></td>
      <td>
        <?php if ($a['device_received']): ?>Received<?php elseif ($a['no_show']): ?>No-Show<?php else: ?>
        <a class="btn" href="?received=<?= $a['appointment_id'] ?>" style="margin-right:4px">Device Received</a>
        <a class="btn outline" href="?noshow=<?= $a['appointment_id'] ?>">No-Show</a>
      <?php endif; ?>
      </td>
      <td><?= htmlspecialchars($a['state']) ?></td>
      <td>
        <?php if ($a['device_received'] && !$a['no_show'] && !in_array($a['state'], ['Completed', 'Cannot Fix', 'Returned'])): ?>
          <form method="post" style="min-width:220px">
            <input type="hidden" name="status_update" value="1">
            <input type="hidden" name="request_id" value="<?= $a['request_id'] ?>">
            <select name="status" style="width:100%;margin-bottom:4px">
              <?php foreach (['In Progress', 'Completed', 'Cannot Fix', 'On Hold'] as $s): ?>
                <option value="<?= $s ?>" <?= $s === $a['state'] ? 'selected' : '' ?>><?= $s ?></option>
              <?php endforeach; ?>
            </select>
            <textarea name="note" placeholder="Note" style="width:100%;height:40px"></textarea>
            <button class="btn" type="submit">Save</button>
          </form>
          <?php else: ?>—<?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>