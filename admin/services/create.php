<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_admin();
$errors = [];
if (is_post_request()) {
	$title = post_string('title', 150); $slug = slugify(post_string('slug', 180) ?: $title); $description = post_string('description', 5000); $icon = post_string('icon', 100); $status = isset($_POST['status']) ? 1 : 0;
	if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Your form session expired. Please try again.';
	if ($title === '') $errors[] = 'Title is required.';
	if ($slug === '') $errors[] = 'A valid slug is required.';
	if ($description === '') $errors[] = 'Description is required.';
	$image = upload_image('image', 'services', $errors);
	if (!$errors) {
		$check = $pdo->prepare('SELECT id FROM services WHERE slug = :slug LIMIT 1'); $check->execute(['slug' => $slug]);
		if ($check->fetch()) $errors[] = 'That slug is already in use.';
	}
	if (!$errors) { $insert = $pdo->prepare('INSERT INTO services (title, slug, description, icon, image, status) VALUES (:title, :slug, :description, :icon, :image, :status)'); $insert->execute(['title'=>$title,'slug'=>$slug,'description'=>$description,'icon'=>$icon ?: null,'image'=>$image,'status'=>$status]); redirect('index.php'); }
}
admin_header('Add Service', 'services/index.php');
?><div class="card table-card"><div class="card-body p-4"><form method="post" enctype="multipart/form-data"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><div class="row g-3"><div class="col-md-6"><label class="form-label">Title *</label><input class="form-control" name="title" value="<?= old('title') ?>" required></div><div class="col-md-6"><label class="form-label">Slug</label><input class="form-control" name="slug" value="<?= old('slug') ?>" placeholder="Generated from title if blank"></div><div class="col-md-8"><label class="form-label">Icon</label><input class="form-control" name="icon" value="<?= old('icon') ?>" placeholder="bi-laptop"></div><div class="col-md-4"><label class="form-label">Image</label><input class="form-control" type="file" name="image" accept="image/jpeg,image/png,image/gif,image/webp"><small class="text-secondary">Max 5 MB</small></div><div class="col-12"><label class="form-label">Description *</label><textarea class="form-control" name="description" rows="6" required><?= old('description') ?></textarea></div><div class="col-12"><div class="form-check"><input class="form-check-input" type="checkbox" name="status" id="status" checked><label class="form-check-label" for="status">Active</label></div></div></div><div class="mt-4"><button class="btn btn-primary" type="submit">Save Service</button><a class="btn btn-outline-secondary ms-2" href="index.php">Cancel</a></div></form></div></div><?php admin_footer(); ?>
