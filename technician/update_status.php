<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $rid=(int)$_POST['request_id'];$status=$_POST['status'];$note=trim($_POST['note']??'');
  // ensure device received first (except Pending)
  $row=$mysqli->query("SELECT a.device_received FROM appointments a WHERE a.request_id=$rid LIMIT 1")->fetch_assoc();
  if($status!=='Pending' && (!$row || !$row['device_received'])){
    $msg='Device must be received first';
  } else {
    $stmt=$mysqli->prepare('INSERT INTO repair_updates(request_id,technician_id,status,note,created_at) VALUES(?,?,?,?,NOW())');
    $stmt->bind_param('iiss',$rid,$tid,$status,$note);
    $stmt->execute();
    $msg='Status updated';
  }
}
$my=$mysqli->query("SELECT request_id FROM appointments WHERE technician_id=$tid AND device_received=1 ORDER BY created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Update Repair Status</h1>
<?php if($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <label>Request
    <select name="request_id" required>
      <?php while($r=$my->fetch_assoc()): ?>
        <option value="<?= $r['request_id'] ?>">#<?= $r['request_id'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Status
    <select name="status">
      <?php foreach(['Pending','In Progress','Completed','Cannot Fix','On Hold'] as $s): ?>
        <option value="<?= $s ?>"><?= $s ?></option>
      <?php endforeach; ?>
    </select>
  </label>
  <label>Note<textarea name="note"></textarea></label>
  <button class="btn" type="submit">Add Update</button>
</form>
<?php include __DIR__.'/../partials/footer.php'; ?>
