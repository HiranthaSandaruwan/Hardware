<?php
$u    = current_user();
$role = $u['role'] ?? null;
$base = $BASE_URL;
?>
<nav class="sidebar">
  <h2>Tracker</h2>
  <a href="<?= $base ?>/index.php">Home</a>
  <a href="<?= $base ?>/features.php">Features</a>
  <a href="<?= $base ?>/help.php">Help</a>

  <?php if ($u): ?>
    <?php if ($role === 'admin'): ?>
      <a href="<?= $base ?>/admin/index.php">Admin Dashboard</a>
      <a href="<?= $base ?>/admin/users_pending.php">Pending Users</a>
      <a href="<?= $base ?>/admin/users_manage.php">Manage Users</a>
  <!-- Removed request approval (v2 flow) -->
  <!-- Reports & feedback links moved into Admin Dashboard -->
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
      <a href="<?= $base ?>/customer/payments.php">Payments</a>
      <a href="<?= $base ?>/customer/completed.php">Completed</a>
      <a href="<?= $base ?>/customer/feedback.php">Feedback</a>
    <?php endif; ?>
    <a href="<?= $base ?>/auth/logout.php">Logout</a>
  <?php else: ?>
    <a href="<?= $base ?>/auth/login.php">Login</a>
    <a href="<?= $base ?>/auth/choose_role.php">Register</a>
  <?php endif; ?>
</nav>
