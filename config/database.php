<?php
/** Shared PDO connection. Values may be supplied by the environment or .env. */

ini_set('display_errors', '0');
ini_set('log_errors', '1');

function database_setting(string $key, string $default = ''): string
{
	$value = getenv($key);
	if ($value !== false && $value !== '') return $value;

	$envFile = dirname(__DIR__) . DIRECTORY_SEPARATOR . '.env';
	if (is_readable($envFile)) {
		foreach (file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
			$line = trim($line);
			if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) continue;
			[$envKey, $envValue] = explode('=', $line, 2);
			if (trim($envKey) === $key) return trim($envValue, " \t\n\r\0\x0B\"'");
		}
	}

	return $default;
}

/** Read the app-specific name first, then Railway's MySQL service names. */
function database_setting_with_fallback(string $key, string $railwayKey, string $default = ''): string
{
	$value = database_setting($key);
	return $value !== '' ? $value : database_setting($railwayKey, $default);
}

$databaseHost = database_setting_with_fallback('DB_HOST', 'MYSQLHOST', 'localhost');
$databaseName = database_setting_with_fallback('DB_NAME', 'MYSQLDATABASE', 'digital_skills_academy');
$databaseUser = database_setting_with_fallback('DB_USER', 'MYSQLUSER', 'root');
$databasePassword = database_setting_with_fallback('DB_PASSWORD', 'MYSQLPASSWORD');
$databasePort = database_setting_with_fallback('DB_PORT', 'MYSQLPORT', '3306');

$databaseDsn = "mysql:host={$databaseHost};port={$databasePort};dbname={$databaseName};charset=utf8mb4";

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
