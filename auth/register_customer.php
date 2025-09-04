<?php
/** Customer registration (auto-approved). Logic unchanged. */
require_once __DIR__.'/../config.php';
require_once __DIR__.'/../db.php';

$err='';
$ok='';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $full_name = trim($_POST['full_name'] ?? '');
    $phone     = trim($_POST['phone'] ?? '');
    $email     = trim($_POST['email'] ?? '');
    $address   = trim($_POST['address'] ?? '');
    $username  = trim($_POST['username'] ?? '');
    $password  = trim($_POST['password'] ?? '');

    // Validation rules
    $usernameOk = preg_match('/^[A-Za-z0-9_]{3,16}$/',$username); // 3-16 chars letters/digits/_
    $phoneOk    = preg_match('/^[0-9]{10}$/',$phone);             // exactly 10 digits
    $emailOk    = ($email==='') ? true : filter_var($email,FILTER_VALIDATE_EMAIL);
    $passOk     = preg_match('/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{1,8}$/',$password); // 1-8 chars, at least one letter & digit

    if($full_name==='' || $phone==='' || $username==='' || $password==='') {
      $err='Required fields missing';
    } elseif(!$usernameOk){
      $err='Invalid username (3-16 letters, numbers, underscore)';
    } elseif(!$phoneOk){
      $err='Invalid phone (10 digits required)';
    } elseif(!$emailOk){
      $err='Invalid email format';
    } elseif(!$passOk){
      $err='Password must be ≤8 chars incl. letters & numbers';
    } else {
    // Uniqueness check
    $stmt=$mysqli->prepare('SELECT user_id FROM users WHERE username=?');
    $stmt->bind_param('s',$username);
    $stmt->execute();
    $stmt->store_result();
    if($stmt->num_rows>0){
      $err='Username taken';
    } else {
      $status='approved'; // Auto-approval for customers
      $role='user';
        $hash = password_hash($password,PASSWORD_DEFAULT);
        $stmt2=$mysqli->prepare('INSERT INTO users(username,password,role,status,created_at) VALUES(?,?,?,?,NOW())');
        $stmt2->bind_param('ssss',$username,$hash,$role,$status);
      if($stmt2->execute()){
        $uid=$stmt2->insert_id;
        $stmt3=$mysqli->prepare('INSERT INTO customer_profile(customer_id,full_name,phone,email,address) VALUES(?,?,?,?,?)');
        $stmt3->bind_param('issss',$uid,$full_name,$phone,$email,$address);
        $stmt3->execute();
        header('Location: login.php?registered=1&type=customer');
        exit;
      } else {
        $err='Insert failed';
      }
    }
  }
}
?>
<?php include __DIR__.'/../partials/header.php'; ?>
<h1>Customer Registration</h1>
<?php if($err): ?><div class="error"><?= htmlspecialchars($err) ?></div><?php endif; ?>
<?php if($ok): ?><div class="success"><?= htmlspecialchars($ok) ?></div><?php endif; ?>
<form method="post" data-validate>
  <label>Full Name<input name="full_name" required value="<?= htmlspecialchars($_POST['full_name']??'') ?>"></label>
  <label>Phone<input name="phone" required value="<?= htmlspecialchars($_POST['phone']??'') ?>"></label>
  <label>Email<input type="email" name="email" value="<?= htmlspecialchars($_POST['email']??'') ?>"></label>
  <label>Address<textarea name="address"><?= htmlspecialchars($_POST['address']??'') ?></textarea></label>
  <label>Username<input name="username" required value="<?= htmlspecialchars($_POST['username']??'') ?>"></label>
  <label>Password<input type="password" name="password" required></label>
  <button type="submit">Register</button>
</form>
<?php include __DIR__.'/../partials/footer.php'; ?>
