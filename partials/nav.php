<?php
$u    = current_user();
$role = $u['role'] ?? null;
$base = $BASE_URL;
?>
<nav class="sidebar">
  <h2><a href="<?= $base ?>/index.php">Hardware Tracker</a></h2>
  <?php if ($u): ?>
    <?php if ($role === 'admin'): ?>
      <a href="<?= $base ?>/admin/index.php">Admin Dashboard</a>
      <a href="<?= $base ?>/admin/users_pending.php">Pending Users</a>
      <a href="<?= $base ?>/admin/users_manage.php">Manage Users</a>
      <a href="<?= $base ?>/admin/feedback.php">Feedback</a>
    <?php elseif ($role === 'technician'): ?>
      <a href="<?= $base ?>/technician/dashboard.php">Tech Dashboard</a>
      <a href="<?= $base ?>/technician/approved_requests.php">Requests</a>
      <a href="<?= $base ?>/technician/proposals_sent.php">Proposals Sent</a>
      <a href="<?= $base ?>/technician/accepted_appointments.php">Accepted Appointments</a>
      <a href="<?= $base ?>/technician/completed.php">Completed</a>
    <?php else: ?>
      <a href="<?= $base ?>/customer/dashboard.php">Customer Dashboard</a>
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
  <a href="<?= $base ?>/contact.php">Contact Us</a>
  <?php if ($u): ?>
    <a class="logout-bottom" href="<?= $base ?>/auth/logout.php">Logout</a>
  <?php endif; ?>
</nav>