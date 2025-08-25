<?php
// Basic configuration constants
$APP_NAME    = 'Hardware Repair Request Tracker';
$BASE_URL    = '/Hardware'; // adjust if folder name differs
$SESSION_NAME = 'repair_tracker_sess';

// Start session with custom name
if (session_status() === PHP_SESSION_NONE) {
    session_name($SESSION_NAME);
    session_start();
}

function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

function current_user()
{
    return $_SESSION['user'] ?? null;
}

function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . $GLOBALS['BASE_URL'] . '/auth/login.php');
        exit;
    }
}

function require_role($role): void
{
    if (!is_logged_in() || current_user()['role'] !== $role) {
        header('Location: ' . $GLOBALS['BASE_URL'] . '/auth/login.php');
        exit;
    }
}
?>
