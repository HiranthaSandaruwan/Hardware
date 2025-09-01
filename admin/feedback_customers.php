<?php require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';
$cust = $mysqli->query("SELECT f.feedback_id,f.request_id,u.username AS from_user,u2.username AS to_user,f.rating,f.comment,f.created_at FROM feedback f JOIN users u ON f.from_user=u.user_id JOIN users u2 ON f.to_user=u2.user_id WHERE f.role_view='customer_to_technician' ORDER BY f.created_at DESC");
$avgRow = $mysqli->query("SELECT AVG(rating) a, COUNT(*) c FROM feedback WHERE role_view='customer_to_technician'")->fetch_assoc();

?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Customer → Technician Feedback</h1>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Req</th>
        <th>From</th>
        <th>Technician</th>
        <th>Comment</th>
        <th>Date</th>
    </tr>
    <?php while ($f = $cust->fetch_assoc()): ?>
        <tr>
            <td><?= $f['feedback_id'] ?></td>
            <td><?= $f['request_id'] ?></td>
            <td><?= htmlspecialchars($f['from_user']) ?></td>
            <td><?= htmlspecialchars($f['to_user']) ?></td>
            <td><?= htmlspecialchars($f['comment']) ?></td>
            <td><?= $f['created_at'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>