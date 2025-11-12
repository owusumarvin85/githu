<?php
// Database connection using mysqli with error handling
// Update credentials as needed for your local XAMPP MySQL

$DB_HOST = '127.0.0.1';
$DB_USER = 'root';
$DB_PASS = '';
$DB_NAME = 'patient_records';
$DB_PORT = 3306;

$mysqli = @new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME, $DB_PORT);

if ($mysqli->connect_errno) {
	http_response_code(500);
	echo "Database connection failed: " . htmlspecialchars($mysqli->connect_error);
	exit;
}

// Set charset
if (!$mysqli->set_charset('utf8mb4')) {
	http_response_code(500);
	echo "Error setting charset: " . htmlspecialchars($mysqli->error);
	exit;
}

// Simple helper for CSRF token
if (session_status() === PHP_SESSION_NONE) {
	session_start();
}

function getCsrfToken() {
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}
	return $_SESSION['csrf_token'];
}

function verifyCsrfToken($token) {
	return isset($_SESSION['csrf_token']) && hash_equals($_SESSION['csrf_token'], $token ?? '');
}
?>


