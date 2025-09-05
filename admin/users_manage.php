<?php require_once __DIR__ . '/../config.php';
require_role('admin');
require_once __DIR__ . '/../db.php';

// Actions
if (isset($_GET['toggle'])) {
  $id = (int)$_GET['toggle'];
  $mysqli->query("UPDATE users SET is_disabled = IF(is_disabled=1,0,1) WHERE user_id=$id AND role!='admin'");
  header('Location: users_manage.php');
  exit;
}
if (isset($_GET['approve'])) {
  $id = (int)$_GET['approve'];
  $mysqli->query("UPDATE users SET status='approved' WHERE user_id=$id AND status='pending'");
  header('Location: users_manage.php?tab=pending');
  exit;
}
if (isset($_GET['disable'])) {
  $id = (int)$_GET['disable'];
  $mysqli->query("UPDATE users SET is_disabled=1 WHERE user_id=$id AND role!='admin'");
  header('Location: users_manage.php');
  exit;
}
if (isset($_GET['enable'])) {
  $id = (int)$_GET['enable'];
  $mysqli->query("UPDATE users SET is_disabled=0 WHERE user_id=$id");
  header('Location: users_manage.php?tab=disabled');
  exit;
}
if (isset($_GET['delete'])) {
  $id = (int)$_GET['delete'];
  if ($id != 1) {
    $mysqli->query("DELETE FROM users WHERE user_id=$id AND role!='admin'");
  }
  header('Location: users_manage.php');
  exit;
}

$tab = $_GET['tab'] ?? 'active';
// Optional role filter for active users in dashboard cards
$roleFilter = null;
if (isset($_GET['filter'])) {
  if ($_GET['filter'] === 'active_customers') {
    $tab = 'active';
    $roleFilter = 'user';
  } elseif ($_GET['filter'] === 'active_techs') {
    $tab = 'active';
    $roleFilter = 'technician';
  }
}

$baseQuery = "SELECT u.user_id,u.username,u.role,u.status,u.is_disabled,u.created_at,
  cp.full_name AS c_full_name, cp.phone AS c_phone, cp.email AS c_email, cp.address AS c_address,
  tp.full_name AS t_full_name, tp.phone AS t_phone, tp.email AS t_email, tp.specialization, tp.experience_years, tp.availability_notes
  FROM users u
  LEFT JOIN customer_profile cp ON u.user_id = cp.customer_id
  LEFT JOIN technician_profile tp ON u.user_id = tp.technician_id";
switch ($tab) {
  case 'pending':
    $q = $baseQuery . " WHERE status='pending' ORDER BY created_at ASC";
    break;
  case 'disabled':
    $q = $baseQuery . " WHERE (is_disabled=1) ORDER BY created_at DESC";
    break;
  default:
    $q = $baseQuery . " WHERE status='approved' AND (is_disabled=0 OR is_disabled IS NULL)";
    if ($roleFilter) {
      $q .= " AND role='" . $mysqli->real_escape_string($roleFilter) . "'";
    }
    $q .= " ORDER BY created_at DESC";
    $tab = 'active';
}
$users = $mysqli->query($q);
// Counts for tabs
$cntPending = $mysqli->query("SELECT COUNT(*) c FROM users WHERE status='pending'")->fetch_assoc()['c'];
$cntActive = $mysqli->query("SELECT COUNT(*) c FROM users WHERE status='approved' AND (is_disabled=0 OR is_disabled IS NULL)")->fetch_assoc()['c'];
$cntDisabled = $mysqli->query("SELECT COUNT(*) c FROM users WHERE is_disabled=1")->fetch_assoc()['c'];
?>
<?php include __DIR__ . '/../partials/header.php'; ?>
<h1>User Management</h1>
<div class="tabs" style="margin-top:.5rem;margin-bottom:.75rem;display:flex;gap:.5rem;flex-wrap:wrap;">
  <a class="tab-link<?= $tab === 'pending' ? ' active' : '' ?>" href="?tab=pending">Pending (<?= $cntPending ?>)</a>
  <a class="tab-link<?= $tab === 'active' ? ' active' : '' ?>" href="?tab=active">Active (<?= $cntActive ?>)</a>
  <a class="tab-link<?= $tab === 'disabled' ? ' active' : '' ?>" href="?tab=disabled">Disabled (<?= $cntDisabled ?>)</a>
</div>
<?php if ($roleFilter): ?>
  <div style="margin:.3rem 0 .5rem;font-size:.7rem;">Filtered: <strong><?= $roleFilter === 'user' ? 'Active Customers' : 'Active Technicians' ?></strong> <a href="users_manage.php?tab=active" style="margin-left:.5rem;">(clear)</a></div>
<?php endif; ?>
<table class="table">
  <tr>
    <th>ID</th>
    <th>User</th>
    <th>Role</th>
    <th>Name</th>
    <th>Email</th>
    <th>Phone</th>
    <th>Specialization</th>
    <th>Experience</th>
    <th>Address</th>
    <th>Created</th>
    <th>Actions</th>
  </tr>
  <?php while ($u = $users->fetch_assoc()): ?>
    <tr>
      <td><?= $u['user_id'] ?></td>
      <td><?= htmlspecialchars($u['username']) ?></td>
      <td><span class="role-badge role-<?= $u['role'] ?>"><?= $u['role'] ?></span></td>
      <td><?php
          if ($u['role'] === 'user') {
            echo htmlspecialchars($u['c_full_name'] ?? '-');
          } elseif ($u['role'] === 'technician') {
            echo htmlspecialchars($u['t_full_name'] ?? '-');
          } else {
            echo '-';
          }
        ?></td>
      <td><?= htmlspecialchars($u['c_email'] ?? $u['t_email'] ?? '-') ?></td>
      <td><?= htmlspecialchars($u['c_phone'] ?? $u['t_phone'] ?? '-') ?></td>
      <td><?= htmlspecialchars($u['specialization'] ?? '-') ?></td>
      <td><?= $u['experience_years'] !== null ? (int)$u['experience_years'] : '-' ?></td>
  <td><?= htmlspecialchars($u['c_address'] ?? '-') ?></td>
      <td><?= substr($u['created_at'], 0, 10) ?></td>
      <td style="white-space:nowrap;">
        <?php if ($u['role'] != 'admin'): ?>
          <?php if ($u['status'] === 'pending'): ?>
            <a class="btn" href="?approve=<?= $u['user_id'] ?>">Approve ✓</a>
          <?php endif; ?>
          <?php if (!$u['is_disabled']): ?>
            <a class="btn outline" href="?disable=<?= $u['user_id'] ?>">Disable ✗</a>
          <?php else: ?>
            <a class="btn" href="?enable=<?= $u['user_id'] ?>">Enable ↺</a>
          <?php endif; ?>
          <a class="btn outline" style="background:#fff3f3;border-color:#d33;color:#b00" href="?delete=<?= $u['user_id'] ?>" onclick="return confirm('Delete user?');">Delete</a>
        <?php endif; ?>
      </td>
    </tr>
  <?php endwhile; ?>
</table>
<?php include __DIR__ . '/../partials/footer.php'; ?>