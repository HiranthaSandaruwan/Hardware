<?php require_once __DIR__.'/../config.php'; require_role('admin'); require_once __DIR__.'/../db.php';
$byStatus=$mysqli->query("SELECT status,COUNT(*) c FROM requests GROUP BY status");
$byCategory=$mysqli->query("SELECT category,COUNT(*) c FROM requests GROUP BY category");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Reports</h1>
<h2>Requests by Status</h2>
<table class="table"><tr><th>Status</th><th>Count</th></tr>
<?php while($row=$byStatus->fetch_assoc()): ?><tr><td><?= $row['status'] ?></td><td><?= $row['c'] ?></td></tr><?php endwhile; ?>
</table>
<h2>Requests by Category</h2>
<table class="table"><tr><th>Category</th><th>Count</th></tr>
<?php while($row=$byCategory->fetch_assoc()): ?><tr><td><?= $row['category'] ?></td><td><?= $row['c'] ?></td></tr><?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
