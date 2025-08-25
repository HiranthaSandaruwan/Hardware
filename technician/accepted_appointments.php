<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];
if(isset($_GET['received'])){ $aid=(int)$_GET['received']; $mysqli->query("UPDATE appointments SET device_received=1 WHERE appointment_id=$aid AND technician_id=$tid"); header('Location: accepted_appointments.php'); exit; }
if(isset($_GET['noshow'])){ $aid=(int)$_GET['noshow']; $mysqli->query("UPDATE appointments SET no_show=1 WHERE appointment_id=$aid AND technician_id=$tid"); header('Location: accepted_appointments.php'); exit; }
$apps=$mysqli->query("SELECT a.appointment_id,a.request_id,a.chosen_slot,a.device_received,a.no_show FROM appointments a WHERE a.technician_id=$tid ORDER BY a.created_at DESC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Accepted Appointments</h1>
<table class="table"><tr><th>ID</th><th>Request</th><th>Slot</th><th>Received</th><th>No-Show</th><th>Action</th></tr>
<?php while($a=$apps->fetch_assoc()): ?>
<tr>
 <td><?= $a['appointment_id'] ?></td>
 <td><?= $a['request_id'] ?></td>
 <td><?= $a['chosen_slot'] ?></td>
 <td><?= $a['device_received']?'Yes':'No' ?></td>
 <td><?= $a['no_show']?'Yes':'No' ?></td>
 <td>
   <?php if(!$a['device_received'] && !$a['no_show']): ?>
     <a class="btn" href="?received=<?= $a['appointment_id'] ?>">Device Received</a>
     <a class="btn outline" href="?noshow=<?= $a['appointment_id'] ?>">No-Show</a>
   <?php endif; ?>
 </td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
