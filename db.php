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
?>
