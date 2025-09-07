<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Aggregates
$agg = $mysqli->query("SELECT 
  SUM(CASE WHEN status='Paid' AND method='Online' THEN rc.total_amount ELSE 0 END) online_total,
  SUM(CASE WHEN status='Paid' AND method='Cash' THEN rc.total_amount ELSE 0 END) cash_total,
  SUM(CASE WHEN status!='Paid' THEN rc.total_amount ELSE 0 END) pending_total,
  COUNT(CASE WHEN status!='Paid' THEN 1 END) pending_count
FROM payments p JOIN receipts rc ON p.receipt_id=rc.receipt_id");
$aggRow = $agg->fetch_assoc();

$list = $mysqli->query("SELECT p.payment_id,r.request_id,rc.total_amount,p.method,p.status,p.paid_at,u.username cust,t.username tech, p.confirmed_at,p.customer_confirmed 
                        FROM payments p JOIN receipts rc ON p.receipt_id=rc.receipt_id 
                        JOIN requests r ON rc.request_id=r.request_id 
                        JOIN users u ON r.user_id=u.user_id 
                        LEFT JOIN users t ON rc.technician_id=t.user_id 
                        ORDER BY COALESCE(p.paid_at, rc.created_at) DESC LIMIT 200");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>Payments Overview</h1>
<div class="flex" style="margin-bottom:.9rem;">
  <div class="card">
    <h3>Paid Online</h3>
    <p class="big-num"><?= number_format($aggRow['online_total'] ?? 0, 2) ?></p>
  </div>
  <div class="card">
    <h3>Paid Cash</h3>
    <p class="big-num"><?= number_format($aggRow['cash_total'] ?? 0, 2) ?></p>
  </div>
  <div class="card">
    <h3>Pending Amount</h3>
    <p class="big-num"><?= number_format($aggRow['pending_total'] ?? 0, 2) ?></p><small><?= (int)$aggRow['pending_count'] ?> pending</small>
  </div>
</div>
<table class="table">
  <tr>
    <th>ID</th>
    <th>Req</th>
    <th>Customer</th>
    <th>Technician</th>
    <th>Amount</th>
    <th>Method</th>
    <th>Status</th>
    <th>Confirmed</th>
    <th>Paid At</th>
  </tr>
  <?php while ($p = $list->fetch_assoc()): ?>
    <tr>
      <td><?= $p['payment_id'] ?></td>
      <td>#<?= $p['request_id'] ?></td>
      <td><?= htmlspecialchars($p['cust']) ?></td>
      <td><?= htmlspecialchars($p['tech']) ?></td>
      <td><?= number_format($p['total_amount'], 2) ?></td>
      <td><?= htmlspecialchars($p['method']) ?></td>
      <td><span class="status-text status-<?= strtolower($p['status']) ?>"><?= $p['status'] ?></span></td>
      <td><?= $p['customer_confirmed'] ? 'Yes' : 'No' ?></td>
      <td><?= $p['paid_at'] ?: '-' ?></td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>