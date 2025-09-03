<?php
require_once __DIR__ . '/../config.php';
$user = current_user();
?>
<!doctype html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title><?= htmlspecialchars($APP_NAME) ?></title>
	<!-- Google Fonts - Poppins -->
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<!-- Custom CSS -->
	<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/style.css">
	<link rel="stylesheet" href="<?= $BASE_URL ?>/assets/css/backgrounds.css">
	<script src="<?= $BASE_URL ?>/assets/js/app.js" defer></script>
</head>

<body class="<?= strpos($_SERVER['PHP_SELF'], 'request_new.php') !== false ? 'new-request-page' : '' ?>">
	<?php include __DIR__ . '/nav.php'; ?>
	<main class="with-sidebar">