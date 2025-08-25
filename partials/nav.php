<?php $u=current_user(); $role=$u['role']??null; $base=$BASE_URL; ?>
<nav class="sidebar">
  <h2>Tracker</h2>
  <a href="<?= $base ?>/index.php">Home</a>
  <?php if($u): ?>
    <?php if($role==='admin'): ?>
      <a href="<?= $base ?>/admin/index.php">Admin Dashboard</a>
      <a href="<?= $base ?>/admin/users.php">Users</a>
      <a href="<?= $base ?>/admin/requests.php">Requests</a>
      <a href="<?= $base ?>/admin/reports.php">Reports</a>
    <?php elseif($role==='technician'): ?>
      <a href="<?= $base ?>/technician/index.php">Tech Dashboard</a>
      <a href="<?= $base ?>/technician/requests.php">My Requests</a>
    <?php else: ?>
      <a href="<?= $base ?>/user/dashboard.php">Dashboard</a>
      <a href="<?= $base ?>/user/request_new.php">New Request</a>
      <a href="<?= $base ?>/user/my_requests.php">My Requests</a>
    <?php endif; ?>
    <a href="<?= $base ?>/auth/logout.php">Logout</a>
  <?php else: ?>
    <a href="<?= $base ?>/auth/login.php">Login</a>
    <a href="<?= $base ?>/auth/register.php">Register</a>
  <?php endif; ?>
</nav>
