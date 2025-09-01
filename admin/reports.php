<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Users summary
$userSummary = $mysqli->query("SELECT role, status, COUNT(*) c FROM users GROUP BY role,status");
// Requests summary
$byStatus   = $mysqli->query("SELECT state status,COUNT(*) c FROM requests GROUP BY state");
$byCategory = $mysqli->query("SELECT category,COUNT(*) c FROM requests GROUP BY category");
// Payments summary
$paySummary = $mysqli->query("SELECT method,status,COUNT(*) c,SUM(rc.total_amount) total FROM payments p JOIN receipts rc ON p.receipt_id=rc.receipt_id GROUP BY method,status");
// Feedback averages
$fbCust = $mysqli->query("SELECT AVG(rating) a, COUNT(*) c FROM feedback WHERE role_view='customer_to_technician'")->fetch_assoc();
$fbTech = $mysqli->query("SELECT AVG(rating) a, COUNT(*) c FROM feedback WHERE role_view='technician_to_customer'")->fetch_assoc();
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Reports</h1>
<fieldset style="margin-bottom:1rem;border:1px solid #dfe3e8;padding:.6rem .9rem;background:#fff;border-radius:.45rem;">
	<legend style="padding:0 .4rem;font-weight:600;font-size:.85rem;">Users Summary</legend>
	<table class="table mini">
		<tr>
			<th>Role</th>
			<th>Status</th>
			<th>Count</th>
		</tr>
		<?php while ($row = $userSummary->fetch_assoc()): ?>
			<tr>
				<td><?= $row['role'] ?></td>
				<td><?= $row['status'] ?></td>
				<td><?= $row['c'] ?></td>
			</tr>
		<?php endwhile; ?>
	</table>
</fieldset>
<fieldset style="margin-bottom:1rem;border:1px solid #dfe3e8;padding:.6rem .9rem;background:#fff;border-radius:.45rem;">
	<legend style="padding:0 .4rem;font-weight:600;font-size:.85rem;">Requests Summary</legend>
	<div class="flex" style="gap:1rem;">
		<div style="flex:1;min-width:15rem;">
			<h3 style="font-size:.8rem;margin:.2rem 0 .4rem;">By Status</h3>
			<table class="table mini">
				<tr>
					<th>Status</th>
					<th>Count</th>
				</tr>
				<?php while ($row = $byStatus->fetch_assoc()): ?>
					<tr>
						<td><?= $row['status'] ?></td>
						<td><?= $row['c'] ?></td>
					</tr>
				<?php endwhile; ?>
			</table>
		</div>
		<div style="flex:1;min-width:15rem;">
			<h3 style="font-size:.8rem;margin:.2rem 0 .4rem;">By Category</h3>
			<table class="table mini">
				<tr>
					<th>Category</th>
					<th>Count</th>
				</tr>
				<?php while ($row = $byCategory->fetch_assoc()): ?>
					<tr>
						<td><?= $row['category'] ?></td>
						<td><?= $row['c'] ?></td>
					</tr>
				<?php endwhile; ?>
			</table>
		</div>
	</div>
</fieldset>
<fieldset style="margin-bottom:1rem;border:1px solid #dfe3e8;padding:.6rem .9rem;background:#fff;border-radius:.45rem;">
	<legend style="padding:0 .4rem;font-weight:600;font-size:.85rem;">Payments Summary</legend>
	<table class="table mini">
		<tr>
			<th>Method</th>
			<th>Status</th>
			<th>Count</th>
			<th>Total</th>
		</tr>
		<?php while ($row = $paySummary->fetch_assoc()): ?>
			<tr>
				<td><?= $row['method'] ?></td>
				<td><?= $row['status'] ?></td>
				<td><?= $row['c'] ?></td>
				<td><?= number_format($row['total'], 2) ?></td>
			</tr>
		<?php endwhile; ?>
	</table>
</fieldset>
<fieldset style="margin-bottom:1rem;border:1px solid #dfe3e8;padding:.6rem .9rem;background:#fff;border-radius:.45rem;">
	<legend style="padding:0 .4rem;font-weight:600;font-size:.85rem;">Feedback Summary</legend>
	<p style="font-size:.8rem;">Customer → Technician: <strong><?= number_format($fbCust['a'] ?? 0, 2) ?></strong> (<?= $fbCust['c'] ?>)</p>
	<p style="font-size:.8rem;">Technician → Customer: <strong><?= number_format($fbTech['a'] ?? 0, 2) ?></strong> (<?= $fbTech['c'] ?>)</p>
</fieldset>
<p style="font-size:.7rem;color:#555;">(Export CSV coming soon)</p>
<?php include __DIR__ . '/../partials/footer.php'; ?>