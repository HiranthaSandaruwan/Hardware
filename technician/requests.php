<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
// Accept assignment (simplified: auto-assign first time action) or update status
if(isset($_GET['start'])){ $id=(int)$_GET['start']; $mysqli->query("UPDATE requests SET status='In Progress', technician_id=$uid, updated_at=NOW() WHERE request_id=$id AND (technician_id IS NULL OR technician_id=$uid) AND status IN ('Approved','In Progress')"); header('Location: requests.php'); exit; }
if(isset($_GET['complete'])){ $id=(int)$_GET['complete']; $mysqli->query("UPDATE requests SET status='Completed', updated_at=NOW() WHERE request_id=$id AND technician_id=$uid AND status='In Progress'"); header('Location: requests.php'); exit; }
// Appointment propose
if($_SERVER['REQUEST_METHOD']==='POST'){
  $id=(int)$_POST['id']; $time=trim($_POST['appointment_time']??'');
  if($time){ $stmt=$mysqli->prepare("UPDATE requests SET appointment_time=?, technician_id=?, updated_at=NOW() WHERE request_id=? AND (technician_id IS NULL OR technician_id=?)"); $stmt->bind_param('siii',$time,$uid,$id,$uid); $stmt->execute(); }
  header('Location: requests.php'); exit;
}
$res=$mysqli->query("SELECT r.request_id,r.device_type,r.status,r.appointment_time,(r.technician_id=$uid) mine FROM requests r WHERE r.status IN ('Approved','In Progress') ORDER BY r.created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Assigned / Available Requests</h1>
<table class="table"><tr><th>ID</th><th>Device</th><th>Status</th><th>Appointment</th><th>Action</th></tr>
<?php while($row=$res->fetch_assoc()): ?>
<tr><td><?= $row['request_id'] ?></td><td><?= htmlspecialchars($row['device_type']) ?></td><td><?= $row['status'] ?></td><td><?= $row['appointment_time'] ?></td><td>
  <?php if($row['status']==='Approved'): ?>
    <form method="post" style="display:inline">
      <input type="hidden" name="id" value="<?= $row['request_id'] ?>">
      <input type="datetime-local" name="appointment_time" value="<?= $row['appointment_time'] ?>">
      <button class="btn" type="submit">Set Time</button>
    </form>
    <a class="btn outline" href="?start=<?= $row['request_id'] ?>">Start</a>
  <?php elseif($row['status']==='In Progress' && $row['mine']): ?>
    <a class="btn" href="?complete=<?= $row['request_id'] ?>">Complete</a>
  <?php endif; ?>
</td></tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
