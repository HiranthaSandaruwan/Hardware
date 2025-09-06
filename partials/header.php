<?php
require_once __DIR__ . '/../config.php';
$user = current_user();
// Ensure UTF-8 via HTTP header
header('Content-Type: text/html; charset=utf-8');
?>
<!doctype html>
<html lang="en">

<head>
	<title><?= htmlspecialchars($APP_NAME) ?></title>
	<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style.css">
	<script src="<?= $BASE_URL ?>/assets/js/app.js" defer></script>
</head>

<body>
	<?php include __DIR__ . '/nav.php'; ?>
	<main class="with-sidebar">