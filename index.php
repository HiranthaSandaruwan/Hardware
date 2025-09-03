<?php
require __DIR__ . '/config.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome - <?= htmlspecialchars($APP_NAME) ?></title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/landing.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
</head>

<body>
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title"><?= htmlspecialchars($APP_NAME) ?></h1>
            <p class="hero-subtitle">Your one-stop solution for hardware and software repair management. Get expert assistance for all your tech needs.</p>

            <?php if ($user): ?>
                <div class="hero-user-info">
                    <p>Welcome back, <strong><?= htmlspecialchars($user['username']) ?></strong></p>
                    <p style="margin-top: 0.5rem; font-size: 0.9rem;">Logged in as <?= htmlspecialchars($user['role']) ?></p>
                </div>
                <div class="hero-actions">
                    <?php if ($user['role'] === 'admin'): ?>
                        <a class="btn" href="admin/index.php">Admin Dashboard</a>
                    <?php elseif ($user['role'] === 'technician'): ?>
                        <a class="btn" href="technician/dashboard.php">Technician Dashboard</a>
                    <?php else: ?>
                        <a class="btn" href="customer/dashboard.php">Customer Dashboard</a>
                    <?php endif; ?>
                    <a class="btn outline" href="auth/logout.php">Logout</a>
                </div>
            <?php else: ?>
                <div class="hero-actions">
                    <a class="btn" href="auth/login.php">Login</a>
                    <a class="btn outline" href="auth/choose_role.php">Register</a>
                </div>
            <?php endif; ?>

            <div class="hero-features">
                <div class="feature-card">
                    <h3>🔧 Expert Repairs</h3>
                    <p>Professional technicians ready to help with your hardware and software issues.</p>
                </div>
                <div class="feature-card">
                    <h3>📱 Easy Tracking</h3>
                    <p>Track your repair requests in real-time with our user-friendly interface.</p>
                </div>
                <div class="feature-card">
                    <h3>💬 Direct Communication</h3>
                    <p>Connect directly with technicians and get updates on your repairs.</p>
                </div>
                <div class="feature-card">
                    <h3>⚡ Fast Service</h3>
                    <p>Quick response times and efficient repair processes to get you back up and running.</p>
                </div>
            </div>
        </div>
    </div>
</body>

</html>