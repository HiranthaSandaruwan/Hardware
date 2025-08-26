<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];
$completed=$mysqli->query("SELECT r.request_id,r.state,a.chosen_slot,rc.receipt_id,rc.total_amount FROM requests r LEFT JOIN appointments a ON a.request_id=r.request_id LEFT JOIN receipts rc ON rc.request_id=r.request_id WHERE r.user_id=$uid AND r.state IN('Completed','Cannot Fix','Returned') ORDER BY r.updated_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Completed & Receipts</h1>
<table class="table"><tr><th>Request</th><th>Status</th><th>Appointment</th><th>Receipt</th><th>Amount</th></tr>
<?php while($c=$completed->fetch_assoc()): ?>
<tr>
 <td><?= $c['request_id'] ?></td>
 <td><?= $c['state'] ?></td>
 <td><?= $c['chosen_slot'] ?></td>
 <td><?= $c['receipt_id'] ?? '-' ?></td>
 <td><?= $c['total_amount'] ?? '-' ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
