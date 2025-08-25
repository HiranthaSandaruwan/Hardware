<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $rid=(int)$_POST['request_id'];$items=trim($_POST['items']??'');$amount=(float)$_POST['amount'];
  // ensure a Completed update exists
  $ok=$mysqli->query("SELECT 1 FROM repair_updates WHERE request_id=$rid AND status='Completed' LIMIT 1")->num_rows;
  if(!$ok){ $msg='Complete the repair first'; }
  else {
    $stmt=$mysqli->prepare('INSERT INTO receipts(request_id,technician_id,items,total_amount,created_at) VALUES(?,?,?,?,NOW())');
    $stmt->bind_param('iisd',$rid,$tid,$items,$amount);$stmt->execute();
    $ridNew=$mysqli->insert_id;
    $mysqli->query("INSERT INTO payments(receipt_id,method,status) VALUES($ridNew,'Cash','Pending')");
    $msg='Receipt created';
  }
}
$eligible=$mysqli->query("SELECT DISTINCT a.request_id FROM appointments a JOIN repair_updates ru ON a.request_id=ru.request_id WHERE ru.status='Completed' AND a.technician_id=$tid AND a.request_id NOT IN (SELECT request_id FROM receipts)");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Create Receipt</h1>
<?php if($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <label>Request
    <select name="request_id" required>
      <?php while($e=$eligible->fetch_assoc()): ?>
        <option value="<?= $e['request_id'] ?>">#<?= $e['request_id'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Items / Notes<textarea name="items" required></textarea></label>
  <label>Total Amount<input type="number" step="0.01" name="amount" required></label>
  <button class="btn" type="submit">Create Receipt</button>
</form>
<?php include __DIR__.'/../partials/footer.php'; ?>
