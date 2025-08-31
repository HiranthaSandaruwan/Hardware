<?php require_once __DIR__.'/../config.php'; require_role('admin'); require_once __DIR__.'/../db.php';
if(isset($_GET['approve'])){ $id=(int)$_GET['approve']; $mysqli->query("UPDATE users SET status='approved' WHERE user_id=$id AND status='pending'"); header('Location: users_pending.php'); exit; }
if(isset($_GET['reject'])){ $id=(int)$_GET['reject']; $mysqli->query("UPDATE users SET status='rejected' WHERE user_id=$id AND status='pending'"); header('Location: users_pending.php'); exit; }
// Only technicians now require approval; join profile for detail display
$pending=$mysqli->query("SELECT u.user_id,u.username,u.role,u.created_at,tp.full_name,tp.phone,tp.email,tp.specialization,tp.experience_years,tp.availability_notes FROM users u LEFT JOIN technician_profile tp ON tp.technician_id=u.user_id WHERE u.status='pending' AND u.role='technician' ORDER BY u.created_at ASC");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Pending User Accounts</h1>
<table class="table"><tr><th>ID</th><th>Username</th><th>Name</th><th>Phone</th><th>Email</th><th>Specialization</th><th>Exp(Y)</th><th>Availability</th><th>Created</th><th>Action</th></tr>
<?php while($r=$pending->fetch_assoc()): ?>
<tr>
 <td><?= $r['user_id'] ?></td>
 <td><?= htmlspecialchars($r['username']) ?></td>
 <td><?= htmlspecialchars($r['full_name']??'') ?></td>
 <td><?= htmlspecialchars($r['phone']??'') ?></td>
 <td><?= htmlspecialchars($r['email']??'') ?></td>
 <td><?= htmlspecialchars($r['specialization']??'') ?></td>
 <td><?= (int)($r['experience_years']??0) ?></td>
 <td><?= htmlspecialchars($r['availability_notes']??'') ?></td>
 <td><?= $r['created_at'] ?></td>
 <td>
   <a class="btn" href="?approve=<?= $r['user_id'] ?>">Approve</a>
   <a class="btn outline" href="?reject=<?= $r['user_id'] ?>">Reject</a>
 </td>
</tr>
<?php endwhile; ?>
</table>
<?php include __DIR__.'/../partials/footer.php'; ?>
