<?php
/** Customer payments page: choose method then technician marks paid (logic unchanged). */
require_once __DIR__.'/../config.php';
require_role('user');
require_once __DIR__.'/../db.php';

$uid = current_user()['id'];

// Detect confirmation fields (backward compatible)
$hasConfirmCol = false;
if($colRes = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'customer_confirmed'")){
  $hasConfirmCol = $colRes->num_rows > 0;
}

// Handle method confirmation
if($_SERVER['REQUEST_METHOD']==='POST'){
  $rid    = (int)$_POST['receipt_id'];
  $method = $_POST['method']==='Online' ? 'Online' : 'Cash';
  if($hasConfirmCol){
    $mysqli->query("UPDATE payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id SET p.method='$method', p.customer_confirmed=1, p.confirmed_at=NOW() WHERE p.receipt_id=$rid AND rq.user_id=$uid");
  } else {
    // Legacy: update only method
    $mysqli->query("UPDATE payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id SET p.method='$method' WHERE p.receipt_id=$rid AND rq.user_id=$uid");
  }
  header('Location: payments.php');
  exit;
}

$selectCols = "p.payment_id,r.receipt_id,r.request_id,r.total_amount,p.method,p.status,p.paid_at" . ($hasConfirmCol?",p.customer_confirmed,p.confirmed_at":"");
$pending = $mysqli->query("SELECT $selectCols FROM payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id WHERE rq.user_id=$uid ORDER BY r.receipt_id DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Payments</h1>
<table class="table"><tr><th>Receipt</th><th>Request</th><th>Amount</th><th>Method</th><th>Status</th><th>Action</th></tr>
<?php while($p=$pending->fetch_assoc()): ?>
<tr>
 <td><?= $p['receipt_id'] ?></td>
 <td><?= $p['request_id'] ?></td>
 <td><?= $p['total_amount'] ?></td>
 <td><?= $p['method'] ?></td>
 <?php $customerConfirmed = $hasConfirmCol ? (int)$p['customer_confirmed'] : 1; ?>
 <td><?= $p['status'] ?><?= $p['status']==='Paid' && $p['paid_at']?'<br><small>'.htmlspecialchars($p['paid_at']).'</small>':''; ?><?= !$customerConfirmed && $p['status']!=='Paid'?'<br><small style=\'color:#b55\'>Method not confirmed</small>':''; ?><?= $customerConfirmed && $p['status']!=='Paid'?'<br><small style=\'color:#2a5\'>Waiting technician</small>':''; ?></td>
 <td><?php if($p['status']!=='Paid' && !$customerConfirmed): ?>
   <form method="post" style="display:inline">
     <input type="hidden" name="receipt_id" value="<?= $p['receipt_id'] ?>">
     <select name="method"><option<?= $p['method']==='Cash'?' selected':''; ?>>Cash</option><option<?= $p['method']==='Online'?' selected':''; ?>>Online</option></select>
     <button class="btn" type="submit">Confirm Method</button>
   </form>
 <?php endif; ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
