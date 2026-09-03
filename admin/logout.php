<?php
require_once __DIR__ . '/../includes/admin_auth.php';
if (!admin_is_authenticated()) redirect('login.php');
if (is_post_request() && verify_csrf_token($_POST['csrf_token'] ?? null)) {
	admin_logout();
	redirect('login.php');
}
?><!doctype html><html lang="en"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title>Logout | Academy Admin</title><link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"></head><body class="bg-light"><div class="container min-vh-100 d-flex align-items-center justify-content-center"><div class="card border-0 shadow-sm p-4 text-center" style="max-width:420px"><h1 class="h4">Sign out?</h1><p class="text-secondary">Are you sure you want to end your admin session?</p><form method="post" action="logout.php"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="btn btn-primary" type="submit">Logout</button><a class="btn btn-outline-secondary ms-2" href="dashboard.php">Cancel</a></form></div></div></body></html>
