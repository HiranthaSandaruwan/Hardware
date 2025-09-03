<?php
require_once __DIR__.'/../config.php';
require_role('admin');
require_once __DIR__.'/../db.php';

// Fetch both directions in simple separate queries (limit to reasonable size)
$cust = $mysqli->query("SELECT f.feedback_id,f.request_id,u.username AS from_user,u2.username AS to_user,f.rating,f.comment,f.created_at FROM feedback f JOIN users u ON f.from_user=u.user_id JOIN users u2 ON f.to_user=u2.user_id WHERE f.role_view='customer_to_technician' ORDER BY f.created_at DESC LIMIT 200");
$tech = $mysqli->query("SELECT f.feedback_id,f.request_id,u.username AS from_user,u2.username AS to_user,f.rating,f.comment,f.created_at FROM feedback f JOIN users u ON f.from_user=u.user_id JOIN users u2 ON f.to_user=u2.user_id WHERE f.role_view='technician_to_customer' ORDER BY f.created_at DESC LIMIT 200");
$avgCust = $mysqli->query("SELECT AVG(rating) a, COUNT(*) c FROM feedback WHERE role_view='customer_to_technician'")->fetch_assoc();
$avgTech = $mysqli->query("SELECT AVG(rating) a, COUNT(*) c FROM feedback WHERE role_view='technician_to_customer'")->fetch_assoc();

include __DIR__.'/../partials/header.php';
?>
<h1>Feedback Overview</h1>
<div class="flex" style="gap:1rem;flex-wrap:wrap;">
  <div class="card" style="flex:1 1 24rem;min-width:20rem;">
    <h3>Customer → Technician (<?= $avgCust['c'] ?>)</h3>
    <p style="font-size:.75rem;">Average: <strong><?= number_format($avgCust['a']??0,2) ?></strong></p>
    <table class="table mini">
      <tr><th>ID</th><th>Req</th><th>From</th><th>To</th><th>Rating</th><th>Comment</th><th>Date</th></tr>
      <?php while($f=$cust->fetch_assoc()): ?>
        <tr>
          <td><?= $f['feedback_id'] ?></td>
          <td><?= $f['request_id'] ?></td>
          <td><?= htmlspecialchars($f['from_user']) ?></td>
          <td><?= htmlspecialchars($f['to_user']) ?></td>
          <td><?= (int)$f['rating'] ?></td>
          <td><?= htmlspecialchars($f['comment']) ?></td>
          <td><?= substr($f['created_at'],0,10) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
  <div class="card" style="flex:1 1 24rem;min-width:20rem;">
    <h3>Technician → Customer (<?= $avgTech['c'] ?>)</h3>
    <p style="font-size:.75rem;">Average: <strong><?= number_format($avgTech['a']??0,2) ?></strong></p>
    <table class="table mini">
      <tr><th>ID</th><th>Req</th><th>From</th><th>To</th><th>Rating</th><th>Comment</th><th>Date</th></tr>
      <?php while($f=$tech->fetch_assoc()): ?>
        <tr>
          <td><?= $f['feedback_id'] ?></td>
          <td><?= $f['request_id'] ?></td>
          <td><?= htmlspecialchars($f['from_user']) ?></td>
          <td><?= htmlspecialchars($f['to_user']) ?></td>
          <td><?= (int)$f['rating'] ?></td>
          <td><?= htmlspecialchars($f['comment']) ?></td>
          <td><?= substr($f['created_at'],0,10) ?></td>
        </tr>
      <?php endwhile; ?>
    </table>
  </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>
