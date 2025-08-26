<?php require_once __DIR__.'/../config.php'; require_role('user'); require_once __DIR__.'/../db.php';
$uid=current_user()['id'];$err='';$ok='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $device_type=trim($_POST['device_type']??'');
  $model=trim($_POST['model']??'');
  $serial_no=trim($_POST['serial_no']??'');
  $category=$_POST['category']??'Hardware';
  $description=trim($_POST['description']??'');
  if($device_type===''||$description==='') $err='Required fields missing';
  else {
  $stmt=$mysqli->prepare('INSERT INTO requests(user_id,device_type,model,serial_no,category,description,state,created_at) VALUES(?,?,?,?,?,?,"New",NOW())');
  $stmt->bind_param('isssss',$uid,$device_type,$model,$serial_no,$category,$description);
    if($stmt->execute()) {
      header('Location: dashboard.php?created=1');
      exit;
    } else $err='Insert failed';
  }
}
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>New Request</h1>
<?php if($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if($ok): ?><div class="success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
<form method="post" data-validate>
  <label>Device Type<input name="device_type" required value="<?= htmlspecialchars($_POST['device_type']??'') ?>"></label>
  <label>Model<input name="model" value="<?= htmlspecialchars($_POST['model']??'') ?>"></label>
  <label>Serial No<input name="serial_no" value="<?= htmlspecialchars($_POST['serial_no']??'') ?>"></label>
  <label>Category<select name="category">
    <?php foreach(['Hardware','Software','Other'] as $c): ?>
      <option value="<?= $c ?>" <?= (($c===($_POST['category']??'Hardware'))?'selected':'') ?>><?= $c ?></option>
    <?php endforeach; ?>
  </select></label>
  <label>Description<textarea name="description" required><?= htmlspecialchars($_POST['description']??'') ?></textarea></label>
  <button type="submit">Submit</button>
</form>
<?php include __DIR__.'/../partials/footer.php'; ?>
