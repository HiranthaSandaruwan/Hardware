<?php
// Simple shim to preserve links pointing to dashboard.php
require_once __DIR__ . '/../config.php';
require_role('technician');
// Reuse existing index implementation
require_once __DIR__ . '/index.php';
