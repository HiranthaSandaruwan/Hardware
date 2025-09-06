<?php require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';
$uid = current_user()['id'];
if (isset($_GET['accept'])) {
  $pid = (int)$_GET['accept'];
  $slotIndex = (int)($_GET['slot'] ?? 1);
  if ($slotIndex < 1 || $slotIndex > 3) $slotIndex = 1;

  
  // Select required fields, avoid name conflicts
  $stmt = $mysqli->prepare("SELECT ap.request_id, ap.technician_id AS proposal_technician_id, ap.slot1, ap.slot2, ap.slot3 FROM appointment_proposals ap JOIN requests r ON ap.request_id=r.request_id WHERE ap.proposal_id=? AND r.user_id=? AND ap.status='Waiting' LIMIT 1");
  $stmt->bind_param('ii', $pid, $uid);
  $stmt->execute();
  $res = $stmt->get_result();
  if ($p = $res->fetch_assoc()) {
    $chosen = $p['slot' . $slotIndex];
    if ($chosen) {
      // insert appointment
      $ins = $mysqli->prepare('INSERT INTO appointments(request_id,technician_id,chosen_slot) VALUES(?,?,?)');
      $ins->bind_param('iis', $p['request_id'], $p['proposal_technician_id'], $chosen);
      if ($ins->execute()) {
        // mark accepted proposal
        $upd = $mysqli->prepare("UPDATE appointment_proposals SET status='Accepted' WHERE proposal_id=?");
        $upd->bind_param('i', $pid);
        $upd->execute();
        // reject other waiting proposals for same request
        $rej = $mysqli->prepare("UPDATE appointment_proposals SET status='Rejected' WHERE request_id=? AND proposal_id<>? AND status='Waiting'");
        $rej->bind_param('ii', $p['request_id'], $pid);
        $rej->execute();

  // assigned_to now used for quick reference only
  $tid = $p['proposal_technician_id'];
  $mysqli->query("UPDATE requests SET assigned_to=$tid, updated_at=NOW() WHERE request_id=" . (int)$p['request_id']);
      }
    }
  }
  header('Location: proposals.php');
  exit;
}
$waiting = $mysqli->query("SELECT ap.proposal_id,ap.request_id,ap.slot1,ap.slot2,ap.slot3,u.username tech FROM appointment_proposals ap JOIN requests r ON ap.request_id=r.request_id JOIN users u ON ap.technician_id=u.user_id WHERE r.user_id=$uid AND ap.status='Waiting' ORDER BY ap.created_at ASC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Proposed Time Slots</h1>
<table class="table">
  <tr>
    <th>Proposal</th>
    <th>Technician</th>
    <th>Slots</th>
    <th>Action</th>
  </tr>
  <?php while ($p = $waiting->fetch_assoc()): ?>
    <tr>
      <td>#<?= $p['proposal_id'] ?> (Req <?= $p['request_id'] ?>)</td>
      <td><?= htmlspecialchars($p['tech']) ?></td>
      <td>
        <?php for ($i = 1; $i <= 3; $i++): $s = $p['slot' . $i];
          if ($s): ?>
            <div><?= $s ?> <a class="btn outline" href="?accept=<?= $p['proposal_id'] ?>&slot=<?= $i ?>">Accept</a></div>
        <?php endif;
        endfor; ?>
      </td>
      <td>-</td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>