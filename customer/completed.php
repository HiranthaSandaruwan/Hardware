<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$completed=$mysqli->query("SELECT rq.request_id,rc.receipt_id,rc.total_amount,a.chosen_slot FROM receipts rc JOIN requests rq ON rc.request_id=rq.request_id JOIN appointments a ON a.request_id=rq.request_id WHERE rq.user_id=$uid ORDER BY rc.receipt_id DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Completed & Receipts</h1>
<table class="table"><tr><th>Request</th><th>Receipt</th><th>Amount</th><th>Appointment</th></tr>
<?php while($c=$completed->fetch_assoc()): ?>
<tr>
 <td><?= $c['request_id'] ?></td>
 <td><?= $c['receipt_id'] ?></td>
 <td><?= $c['total_amount'] ?></td>
 <td><?= $c['chosen_slot'] ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
