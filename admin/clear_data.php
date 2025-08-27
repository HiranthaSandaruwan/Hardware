<?php
require_once __DIR__.'/../config.php';
require_role('admin');
require_once __DIR__.'/../db.php';

$tablesToTruncate = [
  'feedback','payments','receipts','repair_updates','appointments',
  'appointment_proposals','comments','requests'
];
$done = false; $error = '';
if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['confirm']) && $_POST['confirm']==='YES'){
  $mysqli->query('SET FOREIGN_KEY_CHECKS=0');
  foreach($tablesToTruncate as $t){
    if(!$mysqli->query("TRUNCATE TABLE `$t`")){
      $error = 'Failed truncating '+$t; break;
    }
  }
  $mysqli->query('SET FOREIGN_KEY_CHECKS=1');
  if(!$error) $done = true;
}

// Simple counts (excluding users since we keep them)
$counts = [];
foreach($tablesToTruncate as $t){
  $res=$mysqli->query("SELECT COUNT(*) c FROM `$t`");
  $counts[$t]=$res?$res->fetch_assoc()['c']:null;
}
include __DIR__.'/../partials/header.php';
?>
<h1>Clear Data (Keep Users)</h1>
<p>This tool removes ALL transactional data (requests, appointments, updates, receipts, payments, feedback, comments) while keeping registered users and their profiles.</p>
<ul>
  <?php foreach($counts as $tbl=>$c): ?>
    <li><?= htmlspecialchars($tbl) ?>: <?= (int)$c ?> rows</li>
  <?php endforeach; ?>
</ul>
<?php if($done): ?>
  <div class="success">Data cleared successfully.</div>
<?php elseif($error): ?>
  <div class="error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>
<form method="post" onsubmit="return confirm('This will permanently delete listed data. Continue?');">
  <input type="hidden" name="confirm" value="YES">
  <button class="btn" type="submit">Clear Data Now</button>
</form>
<p style="margin-top:15px;"><a href="index.php">Back to Dashboard</a></p>
<?php include __DIR__.'/../partials/footer.php'; ?>
