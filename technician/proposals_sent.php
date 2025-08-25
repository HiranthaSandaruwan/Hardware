<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];
$sent=$mysqli->query("SELECT ap.proposal_id,ap.request_id,ap.status,ap.slot1,ap.slot2,ap.slot3 FROM appointment_proposals ap WHERE ap.technician_id=$tid ORDER BY ap.created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Proposals Sent</h1>
<table class="table"><tr><th>ID</th><th>Request</th><th>Status</th><th>Slots</th></tr>
<?php while($p=$sent->fetch_assoc()): ?>
<tr>
 <td><?= $p['proposal_id'] ?></td>
 <td><?= $p['request_id'] ?></td>
 <td><?= $p['status'] ?></td>
 <td><?php for($i=1;$i<=3;$i++){ $s=$p['slot'.$i]; if($s) echo '<div>'.$s.'</div>'; } ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
