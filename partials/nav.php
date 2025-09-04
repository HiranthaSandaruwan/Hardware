<?php
$u    = current_user();
$role = $u['role'] ?? null;
$base = $BASE_URL;
?>
<nav class="sidebar">
  <h2 style="margin:0 0 .75rem;">
    <a href="<?= $base ?>/index.php" class="app-logo" style="display:inline-block;font-size:1.05rem;font-weight:700;letter-spacing:.5px;">
      <span style="display:inline-block;padding:.35rem .6rem;border:2px solid #1e3952;border-radius:.5rem;">Tracker</span>
    </a>
  </h2>
  <?php if ($u): ?>
    <?php if ($role === 'admin'): ?>
      <a href="<?= $base ?>/admin/index.php">Dashboard</a>
      <a href="<?= $base ?>/admin/users_pending.php">Pending Users</a>
      <a href="<?= $base ?>/admin/users_manage.php">Manage Users</a>
      <a href="<?= $base ?>/admin/feedback.php">Feedback</a>
    <?php elseif ($role === 'technician'): ?>
      <a href="<?= $base ?>/technician/dashboard.php">Dashboard</a>
      <a href="<?= $base ?>/technician/approved_requests.php">Requests</a>
      <a href="<?= $base ?>/technician/proposals_sent.php">Proposals Sent</a>
      <a href="<?= $base ?>/technician/accepted_appointments.php">Accepted Appointments</a>
      <a href="<?= $base ?>/technician/completed.php">Completed</a>
    <?php else: ?>
      <a href="<?= $base ?>/customer/dashboard.php">Dashboard</a>
      <a href="<?= $base ?>/customer/request_new.php">New Request</a>
      <a href="<?= $base ?>/customer/my_requests.php">My Requests</a>
      <a href="<?= $base ?>/customer/proposals.php">Proposals</a>
      <a href="<?= $base ?>/customer/completed.php">Completed</a>
    <?php endif; ?>
  <?php else: ?>
    <!-- <a href="<?= $base ?>/auth/login.php">Login</a>
    <a href="<?= $base ?>/auth/choose_role.php">Register</a> -->
  <?php endif; ?>
  <a href="<?= $base ?>/features.php">Features</a>
  <a href="<?= $base ?>/help.php">Help</a>
  <?php if ($u): ?>
    <a class="logout-bottom" href="<?= $base ?>/auth/logout.php">Logout</a>
  <?php endif; ?>
</nav>