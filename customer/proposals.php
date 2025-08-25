<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
if(isset($_GET['accept'])){
  $pid=(int)$_GET['accept'];
  // fetch proposal details
  $p=$mysqli->query("SELECT * FROM appointment_proposals ap JOIN requests r ON ap.request_id=r.request_id WHERE ap.proposal_id=$pid AND r.user_id=$uid AND ap.status='Waiting'")->fetch_assoc();
  if($p){
    $slotIndex=(int)($_GET['slot']??1); $chosen=$p['slot'.$slotIndex];
    if($chosen){
      $mysqli->query("INSERT INTO appointments(request_id,technician_id,chosen_slot) VALUES(".$p['request_id'].",".$p['technician_id'].",'".$mysqli->real_escape_string($chosen)."')");
      $mysqli->query("UPDATE appointment_proposals SET status='Accepted' WHERE proposal_id=$pid");
      $mysqli->query("UPDATE appointment_proposals SET status='Rejected' WHERE request_id=".$p['request_id']." AND proposal_id<>$pid AND status='Waiting'");
    }
  }
  header('Location: proposals.php'); exit;
}
$waiting=$mysqli->query("SELECT ap.proposal_id,ap.request_id,ap.slot1,ap.slot2,ap.slot3,u.username tech FROM appointment_proposals ap JOIN requests r ON ap.request_id=r.request_id JOIN users u ON ap.technician_id=u.user_id WHERE r.user_id=$uid AND ap.status='Waiting' ORDER BY ap.created_at ASC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Proposed Time Slots</h1>
<table class="table"><tr><th>Proposal</th><th>Technician</th><th>Slots</th><th>Action</th></tr>
<?php while($p=$waiting->fetch_assoc()): ?>
<tr>
 <td>#<?= $p['proposal_id'] ?> (Req <?= $p['request_id'] ?>)</td>
 <td><?= htmlspecialchars($p['tech']) ?></td>
 <td>
   <?php for($i=1;$i<=3;$i++): $s=$p['slot'.$i]; if($s): ?>
     <div><?= $s ?> <a class="btn outline" href="?accept=<?= $p['proposal_id'] ?>&slot=<?= $i ?>">Accept</a></div>
   <?php endif; endfor; ?>
 </td>
 <td>-</td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
