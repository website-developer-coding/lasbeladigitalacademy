<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();

$errors = [];
$adminId = (int) $_SESSION['admin_id'];
$statement = $pdo->prepare('SELECT name, email, password FROM admins WHERE id = :id LIMIT 1');
$statement->execute(['id' => $adminId]);
$admin = $statement->fetch();
if (!$admin) {
	admin_logout();
	redirect('login.php');
}

if (is_post_request()) {
	$name = post_string('name', 100);
	$email = strtolower(post_string('email', 150));
	$currentPassword = (string) ($_POST['current_password'] ?? '');
	$newPassword = (string) ($_POST['new_password'] ?? '');
	$passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

	if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Your form session expired. Please try again.';
	if ($name === '') $errors[] = 'Name is required.';
	if (!valid_email($email)) $errors[] = 'Enter a valid email address.';
	if (!password_verify($currentPassword, $admin['password'])) $errors[] = 'Current password is incorrect.';
	if ($newPassword !== '' && strlen($newPassword) < 8) $errors[] = 'New password must be at least 8 characters.';
	if ($newPassword !== '' && $newPassword !== $passwordConfirmation) $errors[] = 'New passwords do not match.';

	$check = $pdo->prepare('SELECT id FROM admins WHERE email = :email AND id != :id LIMIT 1');
	$check->execute(['email' => $email, 'id' => $adminId]);
	if ($check->fetch()) $errors[] = 'That email is already registered.';

	if (!$errors) {
		$password = $newPassword !== '' ? admin_password_hash($newPassword) : $admin['password'];
		$update = $pdo->prepare('UPDATE admins SET name = :name, email = :email, password = :password WHERE id = :id');
		$update->execute(['name' => $name, 'email' => $email, 'password' => $password, 'id' => $adminId]);
		$_SESSION['admin_name'] = $name;
		$_SESSION['admin_email'] = $email;
		flash('success', 'Your admin account was updated successfully.');
		redirect('account.php');
	}
}

admin_header('My Account', 'account.php');
$success = flash('success');
?>
<div class="mb-3"><h1 class="h4 mb-1">My Account</h1><p class="text-secondary mb-0">Change your admin email or password.</p></div>
<div class="card table-card" style="max-width: 720px;"><div class="card-body p-4">
<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
<form method="post" action="account.php">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
<div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control" id="name" name="name" value="<?= old('name', $admin['name']) ?>" required></div>
<div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control" type="email" id="email" name="email" value="<?= old('email', $admin['email']) ?>" required autocomplete="email"></div>
<hr class="my-4"><p class="text-secondary small">Leave new password fields empty if you only want to change your email or name.</p>
<div class="mb-3"><label class="form-label" for="current_password">Current password</label><input class="form-control" type="password" id="current_password" name="current_password" required autocomplete="current-password"></div>
<div class="row g-3"><div class="col-md-6"><label class="form-label" for="new_password">New password</label><input class="form-control" type="password" id="new_password" name="new_password" minlength="8" autocomplete="new-password"></div><div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm new password</label><input class="form-control" type="password" id="password_confirmation" name="password_confirmation" minlength="8" autocomplete="new-password"></div></div>
<button class="btn btn-primary mt-4" type="submit">Save Changes</button>
</form></div></div>
<?php admin_footer(); ?>
