<?php require_once __DIR__ . '/../config.php';
require_role('user');
require_once __DIR__ . '/../db.php';
$uid = current_user()['id'];
$res = $mysqli->query("SELECT request_id,device_type,state,created_at,updated_at 
                       FROM requests 
                       WHERE user_id=$uid AND state NOT IN('Completed','Cannot Fix','Returned') 
                       ORDER BY created_at DESC");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>My Requests</h1>
<table class="table">
    <tr>
        <th>ID</th>
        <th>Device</th>
        <th>Status</th>
        <th>Created</th>
        <th>Updated</th>
    </tr>
    <?php while ($r = $res->fetch_assoc()): ?>
        <?php $label = ($r['state'] === 'New') ? 'Pending (Not Assigned)' : $r['state']; ?>
        <tr>
            <td><?= $r['request_id'] ?></td>
            <td><?= htmlspecialchars($r['device_type']) ?></td>
            <td><?= htmlspecialchars($label) ?></td>
            <td><?= $r['created_at'] ?></td>
            <td><?= $r['updated_at'] ?></td>
        </tr>
    <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>