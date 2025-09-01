<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

if (isset($_GET['approve'])) {
	$id = (int)$_GET['approve'];
	$mysqli->query("UPDATE users SET status='approved' WHERE user_id=$id");
	header('Location: users.php');
	exit;
}
if (isset($_GET['delete'])) {
	$id = (int)$_GET['delete'];
	if ($id !== 1) {
		$mysqli->query("DELETE FROM users WHERE user_id=$id");
	}
	header('Location: users.php');
	exit;
}

$pending = $mysqli->query("SELECT u.user_id,u.username,u.role,u.created_at,tp.full_name,tp.phone,tp.email,tp.specialization,tp.experience_years FROM users u LEFT JOIN technician_profile tp ON tp.technician_id=u.user_id WHERE u.status='pending' AND u.role='technician' ORDER BY u.created_at ASC");
$all     = $mysqli->query("SELECT user_id,username,role,status,created_at FROM users ORDER BY created_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>User Management</h1>
<h2>Pending Approvals</h2>
<table class="table">
	<tr>
		<th>ID</th>
		<th>Username</th>
		<th>Name</th>
		<th>Phone</th>
		<th>Email</th>
		<th>Specialization</th>
		<th>Exp(Y)</th>
		<th>Created</th>
		<th>Action</th>
	</tr>
	<?php while ($row = $pending->fetch_assoc()): ?>
		<tr>
			<td><?= $row['user_id'] ?></td>
			<td><?= htmlspecialchars($row['username']) ?></td>
			<td><?= htmlspecialchars($row['full_name'] ?? '') ?></td>
			<td><?= htmlspecialchars($row['phone'] ?? '') ?></td>
			<td><?= htmlspecialchars($row['email'] ?? '') ?></td>
			<td><?= htmlspecialchars($row['specialization'] ?? '') ?></td>
			<td><?= (int)($row['experience_years'] ?? 0) ?></td>
			<td><?= $row['created_at'] ?></td>
			<td><a class="btn" href="?approve=<?= $row['user_id'] ?>">Approve</a></td>
		</tr>
	<?php endwhile; ?>
</table>

<h2>All Users</h2>
<table class="table">
	<tr>
		<th>ID</th>
		<th>Username</th>
		<th>Role</th>
		<th>Status</th>
		<th>Created</th>
		<th>Delete</th>
	</tr>
	<?php while ($row = $all->fetch_assoc()): ?>
		<tr>
			<td><?= $row['user_id'] ?></td>
			<td><?= htmlspecialchars($row['username']) ?></td>
			<td><?= $row['role'] ?></td>
			<td><?= $row['status'] ?></td>
			<td><?= $row['created_at'] ?></td>
			<td>
				<?php if ($row['user_id'] != 1): ?>
					<a data-confirm="Delete user?" class="btn outline" href="?delete=<?= $row['user_id'] ?>">Delete</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>