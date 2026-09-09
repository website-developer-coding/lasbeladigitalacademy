<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_admin();

$errors = [];

if (is_post_request()) {
	$name = post_string('name', 100);
	$email = strtolower(post_string('email', 150));
	$password = (string) ($_POST['password'] ?? '');
	$passwordConfirmation = (string) ($_POST['password_confirmation'] ?? '');

	if (!verify_csrf_token($_POST['csrf_token'] ?? null)) {
		$errors[] = 'Your form session expired. Please try again.';
	}
	if ($name === '') $errors[] = 'Name is required.';
	if (!valid_email($email)) $errors[] = 'Enter a valid email address.';
	if (strlen($password) < 8) $errors[] = 'Password must be at least 8 characters.';
	if ($password !== $passwordConfirmation) $errors[] = 'Passwords do not match.';

	if (!$errors) {
		$check = $pdo->prepare('SELECT id FROM admins WHERE email = :email LIMIT 1');
		$check->execute(['email' => $email]);
		if ($check->fetch()) {
			$errors[] = 'That email is already registered.';
		} else {
			$insert = $pdo->prepare('INSERT INTO admins (name, email, password) VALUES (:name, :email, :password)');
			$insert->execute([
				'name' => $name,
				'email' => $email,
				'password' => admin_password_hash($password),
			]);
			flash('success', 'The new administrator was added successfully.');
			redirect('../dashboard.php');
		}
	}
}

admin_header('Add Administrator', 'admins/create.php');
$success = flash('success');
?>
<div class="d-flex justify-content-between align-items-center mb-3">
	<div>
		<h1 class="h4 mb-1">Add Administrator</h1>
		<p class="text-secondary mb-0">Create another secure admin login.</p>
	</div>
	<a class="btn btn-outline-secondary" href="../dashboard.php">Back to Dashboard</a>
</div>
<div class="card table-card" style="max-width: 720px;">
	<div class="card-body p-4">
		<?php if ($success): ?><div class="alert alert-success"><?= e($success) ?></div><?php endif; ?>
		<?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?>
		<form method="post" action="create.php">
			<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
			<div class="mb-3"><label class="form-label" for="name">Name</label><input class="form-control" id="name" name="name" value="<?= old('name') ?>" required></div>
			<div class="mb-3"><label class="form-label" for="email">Email address</label><input class="form-control" type="email" id="email" name="email" value="<?= old('email') ?>" required autocomplete="email"></div>
			<div class="row g-3">
				<div class="col-md-6"><label class="form-label" for="password">Password</label><input class="form-control" type="password" id="password" name="password" minlength="8" required autocomplete="new-password"></div>
				<div class="col-md-6"><label class="form-label" for="password_confirmation">Confirm password</label><input class="form-control" type="password" id="password_confirmation" name="password_confirmation" minlength="8" required autocomplete="new-password"></div>
			</div>
			<button class="btn btn-primary mt-4" type="submit">Add Administrator</button>
		</form>
	</div>
</div>
<?php admin_footer(); ?>
