<?php

/**
 ** Global configuration & auth helpers.
 */

// App meta
$APP_NAME      = 'Hardware Repair Request Tracker';
$BASE_URL      = '/Hardware';          // Adjust if folder name differs
$SESSION_NAME  = 'repair_tracker_sess';

// Start session (idempotent)
if (session_status() === PHP_SESSION_NONE) {
    session_name($SESSION_NAME);
    session_start();
}

/**
 * Check if a user session is present.
 */
function is_logged_in(): bool
{
    return isset($_SESSION['user']);
}

/**
 * Return current user array or null.
 */
function current_user()
{
    return $_SESSION['user'] ?? null;
}

/**
 * Require any authenticated user.
 */
function require_login(): void
{
    if (!is_logged_in()) {
        header('Location: ' . $GLOBALS['BASE_URL'] . '/auth/login.php');
        exit;
    }
}

/**
 * Require an authenticated user with a specific role.
 * @param string $role expected role value.
 */
function require_role($role): void
{
    if (!is_logged_in() || current_user()['role'] !== $role) {
        header('Location: ' . $GLOBALS['BASE_URL'] . '/auth/login.php');
        exit;
    }
}
