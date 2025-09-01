<?php
/**
 * Database connection bootstrap.
 * Plain mysqli (logic unchanged); includes minimal auto-migration for payment confirmation fields.
 */

$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '2323';
$dbname = 'repair_tracker';

$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname);
if ($mysqli->connect_errno) {
    die('DB Connect failed: ' . $mysqli->connect_error);
}
$mysqli->set_charset('utf8mb4');

// Auto-add new payment columns if missing (backward compatibility)
try {
    if ($res = $mysqli->query("SHOW TABLES LIKE 'payments'")) {
        if ($res->num_rows) {
            if ($c1 = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'customer_confirmed'")) {
                if ($c1->num_rows === 0) {
                    $mysqli->query("ALTER TABLE payments ADD customer_confirmed TINYINT(1) NOT NULL DEFAULT 0 AFTER status");
                }
            }
            if ($c2 = $mysqli->query("SHOW COLUMNS FROM payments LIKE 'confirmed_at'")) {
                if ($c2->num_rows === 0) {
                    $mysqli->query("ALTER TABLE payments ADD confirmed_at DATETIME NULL AFTER customer_confirmed");
                }
            }
        }
    }
} catch (Throwable $e) {
    // Silent fail; non-critical.
}
?>
