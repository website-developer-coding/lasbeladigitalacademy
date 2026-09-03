<?php
/** Shared application helpers. */

if (session_status() !== PHP_SESSION_ACTIVE) {
	ini_set('session.use_strict_mode', '1');
	$sessionCookie = session_get_cookie_params();
	session_set_cookie_params([
		'lifetime' => $sessionCookie['lifetime'],
		'path' => $sessionCookie['path'],
		'domain' => $sessionCookie['domain'],
		'secure' => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
		'httponly' => true,
		'samesite' => 'Lax',
	]);
	session_start();
}

function e($value): string
{
	return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void
{
	header('Location: ' . $url);
	exit;
}

function csrf_token(): string
{
	if (empty($_SESSION['csrf_token'])) {
		$_SESSION['csrf_token'] = bin2hex(random_bytes(32));
	}

	return $_SESSION['csrf_token'];
}

function verify_csrf_token(?string $token): bool
{
	return is_string($token)
		&& !empty($_SESSION['csrf_token'])
		&& hash_equals($_SESSION['csrf_token'], $token);
}

function is_post_request(): bool
{
	return ($_SERVER['REQUEST_METHOD'] ?? 'GET') === 'POST';
}

function flash(string $key, ?string $message = null): ?string
{
	if ($message !== null) {
		$_SESSION['flash'][$key] = $message;
		return null;
	}

	$value = $_SESSION['flash'][$key] ?? null;
	unset($_SESSION['flash'][$key]);
	return $value;
}

function old(string $key, string $default = ''): string
{
	return e($_POST[$key] ?? $default);
}

function post_string(string $key, int $maxLength = 255): string
{
	$value = trim((string) ($_POST[$key] ?? ''));
	return mb_substr($value, 0, $maxLength);
}

function valid_email(string $email): bool
{
	return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
}

function valid_id($value): ?int
{
	$id = filter_var($value, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
	return $id === false ? null : (int) $id;
}

function image_url(?string $filename, string $folder = 'courses'): string
{
	$filename = basename((string) $filename);
	$uploadPath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . $filename;
	return $filename !== '' && is_file($uploadPath)
		? 'uploads/' . $folder . '/' . rawurlencode($filename)
		: 'assets/images/placeholder.svg';
}

function current_page(): string
{
	return basename($_SERVER['PHP_SELF'] ?? 'index.php');
}

function upload_image(string $field, string $folder, array &$errors, ?string $existing = null): ?string
{
	if (empty($_FILES[$field]) || $_FILES[$field]['error'] === UPLOAD_ERR_NO_FILE) {
		return $existing;
	}

	$file = $_FILES[$field];
	$allowedMimeTypes = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/gif' => 'gif', 'image/webp' => 'webp'];
	$extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
	$allowedExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
	$maxSize = 5 * 1024 * 1024;

	if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > $maxSize) {
		$errors[] = 'Images must be 5 MB or smaller.';
		return $existing;
	}
	if (!in_array($extension, $allowedExtensions, true)) {
		$errors[] = 'Only JPG, PNG, GIF, and WebP images are allowed.';
		return $existing;
	}
	$finfo = new finfo(FILEINFO_MIME_TYPE);
	$mimeType = $finfo->file($file['tmp_name']);
	if (!isset($allowedMimeTypes[$mimeType]) || @getimagesize($file['tmp_name']) === false) {
		$errors[] = 'The uploaded file is not a valid image.';
		return $existing;
	}

	$extension = $allowedMimeTypes[$mimeType];
	$directory = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder;
	if (!is_dir($directory) && !mkdir($directory, 0755, true)) {
		$errors[] = 'The image upload directory is unavailable.';
		return $existing;
	}
	$filename = bin2hex(random_bytes(16)) . '.' . $extension;
	if (!move_uploaded_file($file['tmp_name'], $directory . DIRECTORY_SEPARATOR . $filename)) {
		$errors[] = 'The image could not be uploaded.';
		return $existing;
	}

	if ($existing) {
		$oldPath = $directory . DIRECTORY_SEPARATOR . basename($existing);
		if (is_file($oldPath)) @unlink($oldPath);
	}
	return $filename;
}

function delete_uploaded_image(?string $filename, string $folder): void
{
	if (!$filename) return;
	$path = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . $folder . DIRECTORY_SEPARATOR . basename($filename);
	if (is_file($path)) @unlink($path);
}

function slugify(string $value): string
{
	$value = preg_replace('/[^a-z0-9]+/i', '-', trim($value));
	return strtolower(trim((string) $value, '-'));
}
