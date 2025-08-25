<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];
$list=$mysqli->query("SELECT r.request_id,rc.receipt_id,rc.total_amount FROM receipts rc JOIN requests r ON rc.request_id=r.request_id WHERE rc.technician_id=$tid ORDER BY rc.receipt_id DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Completed Work</h1>
<table class="table"><tr><th>Request</th><th>Receipt</th><th>Amount</th></tr>
<?php while($c=$list->fetch_assoc()): ?>
<tr><td><?= $c['request_id'] ?></td><td><?= $c['receipt_id'] ?></td><td><?= $c['total_amount'] ?></td></tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
