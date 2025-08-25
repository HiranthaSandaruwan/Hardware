<?php require_once __DIR__.'/../config.php'; ?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Choose Registration Type</h1>
<div class="flex">
  <div class="card">
    <h3>Customer</h3>
    <p>Create a customer account to submit repair requests.</p>
    <p><a class="btn" href="register_customer.php">Register as Customer</a></p>
  </div>
  <div class="card">
    <h3>Technician</h3>
    <p>Register as a technician to propose appointments and handle repairs.</p>
    <p><a class="btn" href="register_technician.php">Register as Technician</a></p>
  </div>
</div>
<?php include __DIR__.'/../partials/footer.php'; ?>
