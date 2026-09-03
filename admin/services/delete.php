<?php
require_once __DIR__ . '/../../includes/admin_auth.php'; require_admin();
$id = valid_id($_GET['id'] ?? $_POST['id'] ?? null); if ($id === null) redirect('index.php');
$get = $pdo->prepare('SELECT id, title, image FROM services WHERE id = :id LIMIT 1'); $get->execute(['id' => $id]); $service = $get->fetch(); if (!$service) redirect('index.php');
if (is_post_request()) { if (!verify_csrf_token($_POST['csrf_token'] ?? null)) exit('Invalid request.'); $delete = $pdo->prepare('DELETE FROM services WHERE id = :id'); $delete->execute(['id' => $id]); delete_uploaded_image($service['image'], 'services'); redirect('index.php'); }
admin_header('Delete Service', 'services/index.php');
?><div class="card table-card"><div class="card-body p-4"><h2 class="h5">Delete “<?= e($service['title']) ?>”?</h2><p class="text-secondary">This action cannot be undone.</p><form method="post"><input type="hidden" name="id" value="<?= e($id) ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><button class="btn btn-danger" type="submit">Confirm Delete</button><a class="btn btn-outline-secondary ms-2" href="index.php">Cancel</a></form></div></div><?php admin_footer(); ?>
