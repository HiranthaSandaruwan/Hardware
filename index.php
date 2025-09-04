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
</head>

<body class="landing-bg">
	<div class="landing-wrapper">
		<div class="hero-box">
			<h1 class="hero-title">Hardware Repair<br>Request Tracker</h1>
			<p class="hero-tag">Your one‑stop solution for hardware and software repair management. Get expert assistance for all your tech needs.</p>
			<div class="hero-actions">
				<?php if ($user): ?>
					<a class="btn" href="auth/logout.php">Logout (<?= htmlspecialchars($user['username']) ?>)</a>
				<?php else: ?>
					<a class="btn" href="auth/login.php">Login</a>
					<a class="btn outline" href="auth/choose_role.php">Register</a>
				<?php endif; ?>
			</div>
		</div>
	</div>
</body>

</html>