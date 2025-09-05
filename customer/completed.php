<?php require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';
$uid = current_user()['id'];
$msgPay = '';
$msgFb = '';
// Detect confirmation columns for payments
$hasConfirmCol = false;
if ($colRes = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'customer_confirmed'")) {
	$hasConfirmCol = $colRes->num_rows > 0;
}
// Handle payment mark paid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'pay') {
	$rid = (int)$_POST['receipt_id'];
	$method = $_POST['method'] === 'Online' ? 'Online' : 'Cash';
	if ($hasConfirmCol) {
		$mysqli->query("UPDATE payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id SET p.method='$method', p.customer_confirmed=1, p.confirmed_at=NOW() WHERE p.receipt_id=$rid AND rq.user_id=$uid");
	} else {
		$mysqli->query("UPDATE payments p JOIN receipts r ON p.receipt_id=r.receipt_id JOIN requests rq ON r.request_id=rq.request_id SET p.method='$method' WHERE p.receipt_id=$rid AND rq.user_id=$uid");
	}
	$msgPay = 'Payment method saved (awaiting technician confirmation).';
}
// Handle feedback submit
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'feedback') {
	$req = (int)$_POST['request_id'];
	$rating = (int)$_POST['rating'];
	$comment = trim($_POST['comment'] ?? '');
	$ok = $mysqli->query("SELECT r.request_id,a.technician_id FROM receipts rc JOIN requests r ON rc.request_id=r.request_id JOIN appointments a ON a.request_id=r.request_id WHERE r.request_id=$req AND r.user_id=$uid LIMIT 1")->fetch_assoc();
	if ($ok && !$mysqli->query("SELECT 1 FROM feedback WHERE request_id=$req AND from_user=$uid AND role_view='customer_to_technician' LIMIT 1")->num_rows) {
		$tech = $ok['technician_id'];
		$stmt = $mysqli->prepare('INSERT INTO feedback(request_id,from_user,to_user,role_view,rating,comment,created_at) VALUES(?,?,?,?,?,?,NOW())');
		$rv = 'customer_to_technician';
		$stmt->bind_param('iiisis', $req, $uid, $tech, $rv, $rating, $comment);
		$stmt->execute();
		$msgFb = 'Feedback submitted';
	}
}
// Combined data: completed requests with receipt/payment + existing feedback
$selectCols = "r.request_id,r.state,a.chosen_slot,rc.receipt_id,rc.total_amount,p.method,p.status pay_status,p.paid_at" . ($hasConfirmCol ? ",p.customer_confirmed,p.confirmed_at" : "") . ",fb.rating fb_rating,fb.comment fb_comment";
$completed = $mysqli->query("SELECT $selectCols FROM requests r LEFT JOIN appointments a ON a.request_id=r.request_id LEFT JOIN receipts rc ON rc.request_id=r.request_id LEFT JOIN payments p ON p.receipt_id=rc.receipt_id LEFT JOIN feedback fb ON fb.request_id=r.request_id AND fb.role_view='customer_to_technician' AND fb.from_user=$uid WHERE r.user_id=$uid AND r.state IN('Completed','Cannot Fix','Returned') ORDER BY r.updated_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Completed / Payments & Feedback</h1>
<?php if ($msgPay): ?><div class="success"><?= htmlspecialchars($msgPay) ?></div><?php endif; ?>
<?php if ($msgFb): ?><div class="success"><?= htmlspecialchars($msgFb) ?></div><?php endif; ?>
<table class="table">
	<tr>
		<th>Req</th>
		<th>State</th>
		<th>Appointment</th>
		<th>Receipt</th>
		<th>Amount</th>
		<th>Payment</th>
		<th>Your Feedback</th>
	</tr>
	<?php while ($c = $completed->fetch_assoc()): $rid = $c['request_id']; ?>
		<tr>
			<td><?= $rid ?></td>
			<td><?= $c['state'] ?></td>
			<td><?= $c['chosen_slot'] ?: '-' ?></td>
			<td><?= $c['receipt_id'] ?? '-' ?></td>
			<td><?= $c['total_amount'] ?? '-' ?></td>
			<td>
				<?php if ($c['receipt_id']): ?>
				<div class="cell-box pay-box">
					<?php $customerConfirmed = $hasConfirmCol ? (int)($c['customer_confirmed'] ?? 0) : 1; ?>
					<?php if ($c['pay_status'] !== 'Paid'): ?>
						<?php if (!$customerConfirmed): ?>
							<form method="post">
								<input type="hidden" name="action" value="pay">
								<input type="hidden" name="receipt_id" value="<?= $c['receipt_id'] ?>">
								<select name="method" class="mini-select">
									<option<?= $c['method'] === 'Cash' ? ' selected' : ''; ?>>Cash</option>
									<option<?= $c['method'] === 'Online' ? ' selected' : ''; ?>>Online</option>
								</select>
								<button class="btn mini-btn">Save Method</button>
							</form>
						<?php else: ?>
							<strong><?= $c['method'] ?></strong><span class="mini-note">Waiting technician confirmation</span>
						<?php endif; ?>
						<span class="mini-note">Status: <?= $c['pay_status'] ?: 'Pending' ?></span>
					<?php else: ?>
						<span class="status-tag status-Approved">Paid</span><br><small><?= $c['method'] ?></small><br><small class="mini-note" style="display:block;"><?= $c['paid_at'] ?></small>
					<?php endif; ?>
				</div>
				<?php else: ?>-
				<?php endif; ?>
			</td>
			<td>
				<?php if ($c['receipt_id']): ?>
				<div class="cell-box fb-box">
					<?php if ($c['fb_rating']): ?>
						<strong><?= $c['fb_rating'] ?>/5</strong><br><small><?= htmlspecialchars($c['fb_comment']) ?></small>
					<?php else: ?>
						<form method="post" class="fb-form">
							<input type="hidden" name="action" value="feedback">
							<input type="hidden" name="request_id" value="<?= $rid ?>">
							<div style="display:flex;gap:.4rem;align-items:flex-start;margin-bottom:.35rem;">
								<input type="number" name="rating" min="1" max="5" placeholder="1-5" required class="rating-input">
								<textarea name="comment" placeholder="Comment" class="fb-comment"></textarea>
							</div>
							<button class="btn mini-btn" style="margin:0;">Send</button>
						</form>
					<?php endif; ?>
				</div>
				<?php else: ?>-
				<?php endif; ?>
			</td>
		</tr>
	<?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>