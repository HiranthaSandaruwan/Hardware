<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
if($_SERVER['REQUEST_METHOD']==='POST'){
  $rid=(int)$_POST['receipt_id'];
  $method=$_POST['method']==='Online'?'Online':'Cash';
  $mysqli->query("UPDATE payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id SET p.method='$method', p.status='Paid', p.paid_at=NOW() WHERE p.receipt_id=$rid AND rq.user_id=$uid");
  header('Location: payments.php'); exit;
}
$pending=$mysqli->query("SELECT p.payment_id,r.receipt_id,r.request_id,r.total_amount,p.method,p.status FROM payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id WHERE rq.user_id=$uid ORDER BY r.receipt_id DESC");
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
 <td><?= $p['status'] ?></td>
 <td><?php if($p['status']!=='Paid'): ?>
   <form method="post" style="display:inline">
     <input type="hidden" name="receipt_id" value="<?= $p['receipt_id'] ?>">
     <select name="method"><option>Cash</option><option<?= $p['method']==='Online'?' selected':''; ?>>Online</option></select>
     <button class="btn" type="submit">Mark Paid</button>
   </form>
 <?php endif; ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
