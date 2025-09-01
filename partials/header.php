<?php
require_once __DIR__ . '/../config.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<title><?= htmlspecialchars($APP_NAME) ?></title>
	<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style.css">
	<script src="<?= $BASE_URL ?>/assets/js/app.js" defer></script>
</head>

<body>
	<?php include __DIR__ . '/nav.php'; ?>
	<main class="with-sidebar">