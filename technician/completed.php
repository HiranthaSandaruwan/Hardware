<?php

/** Technician completed work list; create receipt, mark paid, give feedback (logic unchanged). */
require_once __DIR__ . '/../config.php';
require_role('technician');
require_once __DIR__ . '/../db.php';

$tid = current_user()['id'];
$msgReceipt = '';
$msgFeedback = '';
$msgPay = '';

// Create receipt
if (isset($_POST['action']) && $_POST['action'] === 'create_receipt') {
	$rid   = (int)$_POST['request_id'];
	$items = trim($_POST['items'] ?? '');
	$amount = (float)$_POST['amount'];
	$ok    = $mysqli->query("SELECT 1 FROM repair_updates WHERE request_id=$rid AND status='Completed' LIMIT 1")->num_rows;
	if (!$ok) {
		$msgReceipt = 'Complete the repair first';
	} elseif ($mysqli->query("SELECT 1 FROM receipts WHERE request_id=$rid LIMIT 1")->num_rows) {
		$msgReceipt = 'Receipt already exists';
	} else {
		$stmt = $mysqli->prepare('INSERT INTO receipts(request_id,technician_id,items,total_amount,created_at) VALUES(?,?,?,?,NOW())');
		$stmt->bind_param('iisd', $rid, $tid, $items, $amount);
		$stmt->execute();
		$ridNew = $mysqli->insert_id;
		$mysqli->query("INSERT INTO payments(receipt_id,method,status) VALUES($ridNew,'Cash','Pending')");
		$msgReceipt = 'Receipt created';
	}
}

// Mark payment as paid
if (isset($_POST['action']) && $_POST['action'] === 'tech_mark_paid') {
	$rid = (int)$_POST['request_id'];
	$mysqli->query("UPDATE payments p JOIN receipts rc ON p.receipt_id=rc.receipt_id SET p.status='Paid', p.paid_at=NOW() WHERE rc.request_id=$rid AND rc.technician_id=$tid");
	$msgPay = 'Payment marked as Paid';
}

// Feedback technician -> customer
if (isset($_POST['action']) && $_POST['action'] === 'feedback_tech_to_customer') {
	$rid = (int)$_POST['request_id'];
	$rating = (int)$_POST['rating'];
	$comment = trim($_POST['comment'] ?? '');
	$ok = $mysqli->query("SELECT rq.user_id FROM receipts rc JOIN requests rq ON rc.request_id=rq.request_id WHERE rc.request_id=$rid AND rc.technician_id=$tid LIMIT 1")->fetch_assoc();
	if ($ok) {
		if (!$mysqli->query("SELECT 1 FROM feedback WHERE request_id=$rid AND from_user=$tid AND role_view='technician_to_customer' LIMIT 1")->num_rows) {
			$cust = $ok['user_id'];
			$stmt = $mysqli->prepare('INSERT INTO feedback(request_id,from_user,to_user,role_view,rating,comment,created_at) VALUES(?,?,?,?,?,?,NOW())');
			$role_view = 'technician_to_customer';
			$stmt->bind_param('iiisis', $rid, $tid, $cust, $role_view, $rating, $comment);
			$stmt->execute();
			$msgFeedback = 'Feedback submitted';
		} else {
			$msgFeedback = 'Already submitted feedback';
		}
	}
}

// Detect customer confirmation columns
$hasConfirmCol = false;
if ($colRes = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'customer_confirmed'")) {
	$hasConfirmCol = $colRes->num_rows > 0;
}

$selectCols = "r.request_id,r.state,rc.receipt_id,rc.total_amount,p.method,p.status,p.paid_at" . ($hasConfirmCol ? ",p.customer_confirmed" : "") . ",fb.rating fb_rating,fb.comment fb_comment";
$list = $mysqli->query("SELECT $selectCols FROM requests r LEFT JOIN receipts rc ON rc.request_id=r.request_id AND rc.technician_id=$tid LEFT JOIN payments p ON p.receipt_id=rc.receipt_id LEFT JOIN feedback fb ON fb.request_id=r.request_id AND fb.from_user=$tid AND fb.role_view='technician_to_customer' WHERE r.assigned_to=$tid AND r.state IN('Completed','Cannot Fix','Returned') ORDER BY r.updated_at DESC");

include __DIR__ . '/../partials/header.php'; ?>
<h1>Completed Work</h1>
<?php if (isset($_GET['just'])): ?><div class="success">Status updated & moved here.</div><?php endif; ?>
<?php if ($msgReceipt): ?><div class="success"><?= htmlspecialchars($msgReceipt) ?></div><?php endif; ?>
<?php if ($msgPay): ?><div class="success"><?= htmlspecialchars($msgPay) ?></div><?php endif; ?>
<?php if ($msgFeedback): ?><div class="success"><?= htmlspecialchars($msgFeedback) ?></div><?php endif; ?>
<table class="table">
	<tr>
		<th>Request</th>
		<th>Status</th>
		<th>Receipt</th>
		<th>Amount</th>
		<th>Payment</th>
		<th>Receipt Action</th>
		<th>Feedback</th>
	</tr>
	<?php while ($c = $list->fetch_assoc()): $rid = $c['request_id'];
		$hasReceipt = !empty($c['receipt_id']); ?>
		<tr>
			<td><?= $rid ?></td>
			<td><?= htmlspecialchars($c['state']) ?></td>
			<td><?= $c['receipt_id'] ?: '&mdash;' ?></td>
			<td><?= $c['total_amount'] !== null ? $c['total_amount'] : '&mdash;' ?></td>
			<td>
				<?php if ($c['receipt_id']): ?>
				<div class="cell-box pay-box">
					<?php if ($c['status'] !== 'Paid'): ?>
						<?php $customerConfirmed = $hasConfirmCol ? (int)$c['customer_confirmed'] : 1; ?>
						<?php if (!$customerConfirmed): ?>
							<span class="mini-note" style="color:#b55">Waiting customer method</span>
						<?php else: ?>
							<strong><?= htmlspecialchars($c['method'] ?? '-') ?></strong>
							<form method="post" style="margin-top:.4rem;">
								<input type="hidden" name="action" value="tech_mark_paid">
								<input type="hidden" name="request_id" value="<?= $rid ?>">
								<div class="payment-action-box">
								<button class="btn" style="padding:.25rem .6rem">Mark Paid</button>
								</div>
								<button class="btn mini-btn" style="margin:0;">Mark Paid</button>
							</form>
						<?php endif; ?>
						<span class="mini-note">Status: <?= $c['status'] ?: 'Pending' ?></span>
					<?php else: ?>
						<span class="status-tag status-Approved">Paid</span><br><small><?= $c['method'] ?></small><br><small class="mini-note" style="display:block;"><?= $c['paid_at'] ?></small>
					<?php endif; ?>
				</div>
				<?php else: ?>&mdash;<?php endif; ?>
			</td>
			<td>
				<?php if (!$hasReceipt && $c['state'] === 'Completed'): ?>
					<form method="post" style="min-width:200px">
						<input type="hidden" name="action" value="create_receipt">
						<input type="hidden" name="request_id" value="<?= $rid ?>">
						<textarea name="items" placeholder="Items/Notes" required style="width:100%;height:50px"></textarea>
						<input type="number" step="0.01" name="amount" placeholder="Amount" required>
						<button class="btn" style="margin-top:4px">Save</button>
					</form>
				<?php elseif ($hasReceipt): ?>
					Receipt #<?= $c['receipt_id'] ?><br><strong><?= $c['total_amount'] ?></strong>
					<?php else: ?>&mdash;<?php endif; ?>
			</td>
			<td>
				<?php if ($hasReceipt): ?>
				<div class="cell-box fb-box">
					<?php if ($c['fb_rating']): ?>
						<strong><?= $c['fb_rating'] ?>/5</strong><br><small><?= htmlspecialchars($c['fb_comment']) ?></small>
					<?php else: ?>
						<form method="post" class="fb-form" style="min-width:160px">
							<input type="hidden" name="action" value="feedback_tech_to_customer">
							<input type="hidden" name="request_id" value="<?= $rid ?>">
							<div style="display:flex;gap:.4rem;align-items:flex-start;margin-bottom:.35rem;flex-wrap:wrap;">
								<input type="number" name="rating" min="1" max="5" placeholder="1-5" required class="rating-input">
								<textarea name="comment" placeholder="Comment" class="fb-comment"></textarea>
							</div>
							<button class="btn mini-btn" style="margin:0;">Send</button>
						</form>
					<?php endif; ?>
				</div>
				<?php else: ?>&mdash;<?php endif; ?>
			</td>
		</tr>
	<?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>