<?php require_once __DIR__.'/../config.php'; require_role('technician'); require_once __DIR__.'/../db.php';
$tid=current_user()['id'];$msg='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $req=(int)$_POST['request_id'];$rating=(int)$_POST['rating'];$comment=trim($_POST['comment']??'');
  $ok=$mysqli->query("SELECT rq.user_id FROM receipts rc JOIN requests rq ON rc.request_id=rq.request_id WHERE rc.request_id=$req AND rc.technician_id=$tid LIMIT 1")->fetch_assoc();
  if($ok){
    $cust=$ok['user_id'];
    $stmt=$mysqli->prepare('INSERT INTO feedback(request_id,from_user,to_user,role_view,rating,comment,created_at) VALUES(?,?,?,?,?,?,NOW())');
    $role_view='technician_to_customer';
    $stmt->bind_param('iiisis',$req,$tid,$cust,$role_view,$rating,$comment);
    $stmt->execute();
    $msg='Feedback submitted';
  }
}
$eligible=$mysqli->query("SELECT rc.request_id FROM receipts rc WHERE rc.technician_id=$tid AND rc.request_id NOT IN (SELECT request_id FROM feedback WHERE from_user=$tid AND role_view='technician_to_customer')");
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Feedback for Customers</h1>
<?php if($msg): ?><div class="success"><?= htmlspecialchars($msg) ?></div><?php endif; ?>
<form method="post">
  <label>Request
    <select name="request_id" required>
      <?php while($e=$eligible->fetch_assoc()): ?>
        <option value="<?= $e['request_id'] ?>">Request #<?= $e['request_id'] ?></option>
      <?php endwhile; ?>
    </select>
  </label>
  <label>Rating (1-5)<input type="number" name="rating" min="1" max="5" required></label>
  <label>Comment<textarea name="comment"></textarea></label>
  <button class="btn" type="submit">Submit Feedback</button>
</form>
<?php include __DIR__.'/../partials/footer.php'; ?>
