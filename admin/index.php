<?php
require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// --- Summary counts ---
$pendingTech = $mysqli->query("SELECT COUNT(*) c FROM users WHERE role='technician' AND status='pending'")->fetch_assoc()['c'];
$activeCustomers = $mysqli->query("SELECT COUNT(*) c FROM users WHERE role='user' AND status='approved' AND (is_disabled=0 OR is_disabled IS NULL)")->fetch_assoc()['c'];
$activeTechs = $mysqli->query("SELECT COUNT(*) c FROM users WHERE role='technician' AND status='approved' AND (is_disabled=0 OR is_disabled IS NULL)")->fetch_assoc()['c'];
$completedRepairs = $mysqli->query("SELECT COUNT(*) c FROM requests WHERE state='Completed'")->fetch_assoc()['c'];

// --- Latest activity (limit 5 each) ---
$latestUsers = $mysqli->query("SELECT user_id,username,role,status,created_at FROM users ORDER BY created_at DESC LIMIT 5");
$latestRequests = $mysqli->query("SELECT r.request_id,u.username,r.device_type,r.state,r.created_at FROM requests r JOIN users u ON r.user_id=u.user_id ORDER BY r.created_at DESC LIMIT 5");
$latestPayments = $mysqli->query("SELECT p.payment_id,p.method,p.status,p.paid_at,rc.receipt_id,r.request_id,u.username cust,t.username tech,rc.total_amount FROM payments p JOIN receipts rc ON p.receipt_id=rc.receipt_id JOIN requests r ON rc.request_id=r.request_id JOIN users u ON r.user_id=u.user_id LEFT JOIN users t ON rc.technician_id=t.user_id ORDER BY COALESCE(p.paid_at, rc.created_at) DESC LIMIT 5");
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1 style="margin-bottom: 1rem">Admin Dashboard</h1>
<div class="flex" style="margin-bottom:1rem;">
  <div class="card stat-card">
    <h3>Pending Registrations</h3>
    <p class="big-num"><?= $pendingTech ?></p>
    <p><a href="users_pending.php">Review &raquo;</a></p>
  </div>
  <div class="card stat-card">
    <h3>Active Customers</h3>
    <p class="big-num"><?= $activeCustomers ?></p>
    <p><a href="users_manage.php?filter=active_customers">View &raquo;</a></p>
  </div>
  <div class="card stat-card">
    <h3>Active Technicians</h3>
    <p class="big-num"><?= $activeTechs ?></p>
    <p><a href="users_manage.php?filter=active_techs">View &raquo;</a></p>
  </div>
  <div class="card stat-card">
    <h3>Completed Repairs</h3>
    <p class="big-num"><?= $completedRepairs ?></p>
    <p><a href="requests.php?state=Completed">Browse &raquo;</a></p>
  </div>
</div>

<fieldset style="border:1px solid #dfe3e8;padding:.75rem 1rem;border-radius:.45rem;background:#fff;">
  <legend style="padding:0 .4rem;font-weight:600;font-size:.85rem;">Latest Activity</legend>
  <div class="flex" style="gap:1rem;flex-wrap:wrap;">
    <div class="activity-block" style="flex:1 1 18rem;min-width:16rem;">
      <h3 style="font-size:.85rem;margin:.2rem 0 .4rem;">Recent Registrations</h3>
      <table class="table mini">
        <tr>
          <th>User</th>
          <th>Role</th>
          <th>Status</th>
          <th>Date</th>
        </tr>
        <?php while ($u = $latestUsers->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($u['username']) ?></td>
            <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
            <td><span class="status-text status-<?= $u['status'] ?>"><?= $u['status'] ?></span></td>
            <td><?= substr($u['created_at'], 0, 10) ?></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
    <div class="activity-block" style="flex:1 1 20rem;min-width:18rem;">
      <h3 style="font-size:.85rem;margin:.2rem 0 .4rem;">Latest Requests</h3>
      <table class="table mini">
        <tr>
          <th>ID</th>
          <th>Customer</th>
          <th>Device</th>
          <th>State</th>
        </tr>
        <?php while ($r = $latestRequests->fetch_assoc()): ?>
          <tr>
            <td>#<?= $r['request_id'] ?></td>
            <td><?= htmlspecialchars($r['username']) ?></td>
            <td><?= htmlspecialchars($r['device_type']) ?></td>
            <td><span class="status-text status-<?= str_replace(' ', '-', $r['state']) ?>"><?= $r['state'] ?></span></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
    <div class="activity-block" style="flex:1 1 20rem;min-width:18rem;">
      <h3 style="font-size:.85rem;margin:.2rem 0 .4rem;">Recent Payments</h3>
      <table class="table mini">
        <tr>
          <th>Req</th>
          <th>Cust</th>
          <th>Tech</th>
          <th>Method</th>
          <th>Status</th>
        </tr>
        <?php while ($p = $latestPayments->fetch_assoc()): ?>
          <tr>
            <td>#<?= $p['request_id'] ?></td>
            <td><?= htmlspecialchars($p['cust']) ?></td>
            <td><?= htmlspecialchars($p['tech']) ?></td>
            <td><?= htmlspecialchars($p['method']) ?></td>
            <td><span class="status-text status-<?= $p['status'] ?>"><?= $p['status'] ?></span></td>
          </tr>
        <?php endwhile; ?>
      </table>
    </div>
  </div>
</fieldset>

<div style="margin-top:1.2rem;display:flex;gap:.75rem;flex-wrap:wrap;">
  <div class="card" style="flex:1 1 15rem;">
    <h3>Reports</h3>
    <p><a href="reports.php">Open Reports &raquo;</a></p>
  </div>
  <div class="card" style="flex:1 1 15rem;">
    <h3>Feedback</h3>
  <p><a href="feedback.php">Open Feedback Overview &raquo;</a></p>
  </div>
  <div class="card" style="flex:1 1 15rem;">
    <h3>Payments</h3>
    <p><a href="payments.php">View Payments &raquo;</a></p>
  </div>
</div>
<?php include __DIR__ . '/../partials/footer.php'; ?>