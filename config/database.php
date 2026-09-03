<?php
/**
 * Shared PDO connection for the XAMPP development database.
 * Database errors are logged server-side and never displayed to visitors.
 */

ini_set('display_errors', '0');
ini_set('log_errors', '1');

$databaseHost = 'localhost';
$databaseName = 'digital_skills_academy';
$databaseUser = 'root';
$databasePassword = '';

$databaseDsn = "mysql:host={$databaseHost};dbname={$databaseName};charset=utf8mb4";

try {
	$pdo = new PDO($databaseDsn, $databaseUser, $databasePassword, [
		PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
		PDO::ATTR_EMULATE_PREPARES => false,
	]);
} catch (PDOException $exception) {
	error_log('Database connection failed: ' . $exception->getMessage());
	http_response_code(500);
	exit('The website is temporarily unavailable. Please try again later.');
}
