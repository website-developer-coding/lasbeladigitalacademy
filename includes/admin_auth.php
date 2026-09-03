<?php
require_once __DIR__ . '/functions.php';
require_once __DIR__ . '/../config/database.php';

function admin_is_authenticated(): bool
{
	if (empty($_SESSION['admin_id'])) return false;
	global $pdo;
	static $verified = null;
	if ($verified !== null) return $verified;
	$statement = $pdo->prepare('SELECT id FROM admins WHERE id = :id LIMIT 1');
	$statement->execute(['id' => (int) $_SESSION['admin_id']]);
	$verified = (bool) $statement->fetchColumn();
	if (!$verified) {
		unset($_SESSION['admin_id'], $_SESSION['admin_name'], $_SESSION['admin_email']);
	}
	return $verified;
}

function require_admin(): void
{
	if (!admin_is_authenticated()) {
		$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
		$loginUrl = preg_match('#/admin/[^/]+/#', $script) ? '../login.php' : 'login.php';
		redirect($loginUrl);
	}
}

function admin_password_hash(string $password): string
{
	return password_hash($password, PASSWORD_DEFAULT);
}

function admin_logout(): void
{
	$_SESSION = [];
	if (ini_get('session.use_cookies')) {
		$params = session_get_cookie_params();
		setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
	}
	session_destroy();
}

function admin_base_url(): string
{
	$script = str_replace('\\', '/', $_SERVER['SCRIPT_NAME'] ?? '');
	return preg_match('#/admin/[^/]+/#', $script) ? '../' : '';
}

function admin_header(string $title, string $active): void
{
	$base = admin_base_url();
	$adminName = e($_SESSION['admin_name'] ?? 'Administrator');
	$links = ['dashboard.php' => ['Dashboard', 'bi-grid-1x2'], 'courses/index.php' => ['Courses', 'bi-mortarboard'], 'services/index.php' => ['Services', 'bi-stars'], 'fees/index.php' => ['Fees', 'bi-receipt'], 'syllabus/index.php' => ['Syllabus', 'bi-journal-text'], 'gallery/index.php' => ['Gallery', 'bi-images'], 'enrollments/index.php' => ['Enrollments', 'bi-people'], 'messages/index.php' => ['Messages', 'bi-chat-left-text']];
	?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= e($title) ?> | Academy Admin</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"><link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet"><style>body{background:#f4f7fb;color:#19324d}.admin-sidebar{min-height:100vh;background:#102a43}.admin-sidebar a{color:#b9ccdc;text-decoration:none}.admin-sidebar a:hover,.admin-sidebar a.active{color:#fff;background:#1769e0}.admin-brand{color:#fff;font-weight:700}.admin-main{min-height:100vh}.stat-card{border:0;border-radius:14px;box-shadow:0 5px 20px rgba(16,42,67,.06)}.table-card{border:0;border-radius:14px;box-shadow:0 5px 20px rgba(16,42,67,.06)}.brand-mark{display:inline-flex;align-items:center;justify-content:center;width:34px;height:34px;color:#fff;background:#1769e0;border-radius:10px}.form-control,.form-select{border-radius:9px}.btn{border-radius:9px}</style></head><body><div class="d-flex"><aside class="admin-sidebar p-3 d-none d-lg-block" style="width:250px"><a href="<?= e($base) ?>dashboard.php" class="admin-brand d-flex align-items-center gap-2 mb-4"><span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span> Academy Admin</a><small class="text-uppercase text-white-50">Main menu</small><nav class="nav flex-column gap-1 mt-2"><?php foreach ($links as $url => [$label, $icon]): ?><a class="rounded p-2 <?= $active === $url ? 'active' : '' ?>" href="<?= e($base . $url) ?>"><i class="bi <?= e($icon) ?> me-2"></i><?= e($label) ?></a><?php endforeach; ?></nav><a class="rounded p-2 d-block mt-4" href="<?= e($base) ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></aside><div class="admin-main flex-grow-1"><nav class="navbar bg-white border-bottom px-3 px-lg-4"><button class="btn btn-outline-secondary d-lg-none" data-bs-toggle="offcanvas" data-bs-target="#adminMenu"><i class="bi bi-list"></i></button><span class="ms-auto text-secondary small"><i class="bi bi-person-circle me-1"></i><?= $adminName ?></span></nav><div class="offcanvas offcanvas-start text-bg-dark" tabindex="-1" id="adminMenu"><div class="offcanvas-header"><h5>Academy Admin</h5><button class="btn-close btn-close-white" data-bs-dismiss="offcanvas"></button></div><div class="offcanvas-body"><nav class="nav flex-column gap-2"><?php foreach ($links as $url => [$label, $icon]): ?><a class="p-2 text-white text-decoration-none" href="<?= e($base . $url) ?>"><i class="bi <?= e($icon) ?> me-2"></i><?= e($label) ?></a><?php endforeach; ?><a class="p-2 text-white text-decoration-none" href="<?= e($base) ?>logout.php"><i class="bi bi-box-arrow-right me-2"></i>Logout</a></nav></div></div><main class="p-3 p-lg-4"><div class="d-flex justify-content-between align-items-center mb-4"><div><h1 class="h3 mb-1"><?= e($title) ?></h1><p class="text-secondary mb-0">Manage your academy content.</p></div></div><?php
}

function admin_footer(): void
{
	?><script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script></main></div></div></body></html><?php
}
