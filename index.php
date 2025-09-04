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
		<div class="hero-features">
			<div class="f-card">
				<h3>🔧 Expert Repairs</h3>
				<p>Professional technicians ready to help with your hardware and software issues.</p>
			</div>
			<div class="f-card">
				<h3>📱 Easy Tracking</h3>
				<p>Follow repair requests in real time with a simple interface.</p>
			</div>
			<div class="f-card">
				<h3>💬 Direct Communication</h3>
				<p>Stay updated by connecting directly with technicians.</p>
			</div>
			<div class="f-card">
				<h3>⚡ Fast Service</h3>
				<p>Quick response and efficient processes to get you running again.</p>
			</div>
		</div>
	</div>
</body>

</html>