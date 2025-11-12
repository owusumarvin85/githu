<?php
require_once __DIR__ . '/db.php';

const REMEMBER_COOKIE_NAME = 'pd_remember';
const REMEMBER_COOKIE_LIFETIME_DAYS = 30;

/**
 * Attempt auto-login from remember-me cookie if a user session is not yet active.
 */
function bootstrapAuth(): void
{
	if (!empty($_SESSION['user_id'])) {
		return;
	}

	if (empty($_COOKIE[REMEMBER_COOKIE_NAME])) {
		return;
	}

	$parts = explode(':', $_COOKIE[REMEMBER_COOKIE_NAME], 2);
	if (count($parts) !== 2) {
		clearRememberCookie();
		return;
	}

	[$userIdPart, $token] = $parts;
	if ($token === '' || !ctype_digit($userIdPart)) {
		clearRememberCookie();
		return;
	}

	$userId = (int)$userIdPart;
	global $mysqli;
	$stmt = $mysqli->prepare("SELECT id, remember_token_hash, remember_token_expires FROM users WHERE id = ?");
	$stmt->bind_param('i', $userId);
	$stmt->execute();
	$user = $stmt->get_result()->fetch_assoc();
	$stmt->close();

	if (!$user) {
		clearRememberCookie();
		return;
	}

	if (empty($user['remember_token_hash']) || empty($user['remember_token_expires'])) {
		clearRememberCookie();
		return;
	}

	$expectedHash = $user['remember_token_hash'];
	$expiresAt = strtotime($user['remember_token_expires']);
	if ($expiresAt === false || $expiresAt < time()) {
		clearRememberCookie();
		return;
	}

	$tokenHash = hash('sha256', $token);
	if (!hash_equals($expectedHash, $tokenHash)) {
		clearRememberCookie();
		return;
	}

	$_SESSION['user_id'] = $user['id'];
}

/**
 * Returns the currently authenticated user or null.
 */
function currentUser(): ?array
{
	static $cache = null;
	if ($cache !== null) {
		return $cache;
	}

	if (empty($_SESSION['user_id'])) {
		return null;
	}

	$userId = (int)$_SESSION['user_id'];
	global $mysqli;
	$stmt = $mysqli->prepare("SELECT id, name, email FROM users WHERE id = ?");
	$stmt->bind_param('i', $userId);
	$stmt->execute();
	$result = $stmt->get_result();
	$user = $result->fetch_assoc() ?: null;
	$stmt->close();

	if (!$user) {
		logoutUser();
		return null;
	}

	$cache = $user;
	return $cache;
}

/**
 * Require user authentication for the current page.
 */
function requireLogin(): void
{
	if (currentUser()) {
		return;
	}

	$redirectTo = $_SERVER['REQUEST_URI'] ?? '/';
	$_SESSION['flash_error'] = 'Please sign in to continue.';
	header('Location: login.php?redirect=' . urlencode($redirectTo));
	exit;
}

/**
 * Handles the result of a successful login, optionally persisting a remember-me cookie.
 */
function finishLogin(int $userId, bool $remember = false): void
{
	$_SESSION['user_id'] = $userId;

	if ($remember) {
		$token = bin2hex(random_bytes(32));
		$tokenHash = hash('sha256', $token);
		$expiresAt = (new DateTimeImmutable('+' . REMEMBER_COOKIE_LIFETIME_DAYS . ' days'))->format('Y-m-d H:i:s');

		global $mysqli;
		$stmt = $mysqli->prepare("UPDATE users SET remember_token_hash = ?, remember_token_expires = ? WHERE id = ?");
		$stmt->bind_param('ssi', $tokenHash, $expiresAt, $userId);
		$stmt->execute();
		$stmt->close();

		setcookie(
			REMEMBER_COOKIE_NAME,
			$userId . ':' . $token,
			[
				'expires' => time() + (REMEMBER_COOKIE_LIFETIME_DAYS * 86400),
				'path' => '/',
				'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
				'httponly' => true,
				'samesite' => 'Lax',
			]
		);
	} else {
		clearRememberCookie();
		clearRememberToken($userId);
	}
}

/**
 * Log the current user out and clear all auth state.
 */
function logoutUser(): void
{
	if (!empty($_SESSION['user_id'])) {
		clearRememberToken((int)$_SESSION['user_id']);
	}
	clearRememberCookie();
	$_SESSION = [];
	if (ini_get("session.use_cookies")) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
	}
	session_destroy();
	session_start();
}

/**
 * Remove the remember-me token stored in the database.
 */
function clearRememberToken(int $userId): void
{
	global $mysqli;
	$stmt = $mysqli->prepare("UPDATE users SET remember_token_hash = NULL, remember_token_expires = NULL WHERE id = ?");
	$stmt->bind_param('i', $userId);
	$stmt->execute();
	$stmt->close();
}

/**
 * Clear the remember-me cookie from the browser.
 */
function clearRememberCookie(): void
{
	if (!isset($_COOKIE[REMEMBER_COOKIE_NAME])) {
		return;
	}
	setcookie(
		REMEMBER_COOKIE_NAME,
		'',
		[
			'expires' => time() - 3600,
			'path' => '/',
			'secure' => isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
			'httponly' => true,
			'samesite' => 'Lax',
		]
	);
	unset($_COOKIE[REMEMBER_COOKIE_NAME]);
}

/**
 * Attempt automatic authentication as soon as this file loads.
 */
bootstrapAuth();

?>


