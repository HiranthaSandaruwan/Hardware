<?php
// Simple MySQLi connection (plain per spec)
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '2323';
$dbname = 'repair_tracker';

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
    die('DB Connect failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');
// --- Lightweight auto-migration for new payment confirmation columns ---
// Adds payments.customer_confirmed & payments.confirmed_at if they don't exist yet.
// This enables the customer-facing "choose payment method then wait for technician" workflow.
try {
    if ($res = $mysqli->query("SHOW TABLES LIKE 'payments'")) {
        if ($res->num_rows) {
            // customer_confirmed
            if ($c1 = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'customer_confirmed'")) {
                if ($c1->num_rows === 0) {
                    $mysqli->query("ALTER TABLE payments ADD customer_confirmed TINYINT(1) NOT NULL DEFAULT 0 AFTER status");
                }
            }
            // confirmed_at
            if ($c2 = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'confirmed_at'")) {
                if ($c2->num_rows === 0) {
                    $mysqli->query("ALTER TABLE payments ADD confirmed_at DATETIME NULL AFTER customer_confirmed");
                }
            }
        }
    }
} catch (Throwable $e) {
    // Fail silently; core app must still run even if migration fails.
}
// --- End auto-migration ---
?>
