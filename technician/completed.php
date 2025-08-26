<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];
// Show any request assigned to this technician that is in a final state, with optional receipt details
$list=$mysqli->query("SELECT r.request_id,r.state,rc.receipt_id,rc.total_amount FROM requests r LEFT JOIN receipts rc ON rc.request_id=r.request_id AND rc.technician_id=$tid WHERE r.assigned_to=$tid AND r.state IN('Completed','Cannot Fix','Returned') ORDER BY r.updated_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Completed Work</h1>
<?php if(isset($_GET['just'])): ?><div class="success">Status updated & moved here.</div><?php endif; ?>
<table class="table"><tr><th>Request</th><th>Status</th><th>Receipt</th><th>Amount</th></tr>
<?php while($c=$list->fetch_assoc()): ?>
<tr>
	<td><?= $c['request_id'] ?></td>
	<td><?= htmlspecialchars($c['state']) ?></td>
	<td><?= $c['receipt_id'] ?: '&mdash;' ?></td>
	<td><?= $c['total_amount'] !== null ? $c['total_amount'] : '&mdash;' ?></td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
