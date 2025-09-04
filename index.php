<?php
require __DIR__ . '/config.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title>Welcome - <?= htmlspecialchars($APP_NAME) ?></title>
	<link rel="stylesheet" href="assets/css/style.css">
	</style>
</head>

<body>
	<div class="container center div">
		<h1><?= htmlspecialchars($APP_NAME) ?></h1>
		<p>Track and manage hardware/software repair requests.</p>

		<?php if ($user): ?>
			<p>
				Signed in as
				<strong><?= htmlspecialchars($user['username']) ?></strong>
				(<?= htmlspecialchars($user['role']) ?>)
			</p>
			<p>
				<a class="btn" href="auth/logout.php">Logout</a>
			</p>
		<?php else: ?>
			<p>
				<a class="btn" href="auth/login.php">Login</a>
				<a class="btn outline" href="auth/choose_role.php">Register</a>
			</p>
		<?php endif; ?>
	</div>
	
</body>

</html>