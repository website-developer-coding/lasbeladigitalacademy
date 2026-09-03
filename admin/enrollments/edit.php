<?php
require_once __DIR__ . '/../../includes/admin_auth.php';
require_admin();

$id = valid_id($_GET['id'] ?? $_POST['id'] ?? null);
if ($id === null) redirect('index.php');
$get = $pdo->prepare('SELECT enrollments.*, courses.title AS course_title FROM enrollments INNER JOIN courses ON courses.id = enrollments.course_id WHERE enrollments.id = :id LIMIT 1');
$get->execute(['id' => $id]);
$enrollment = $get->fetch();
if (!$enrollment) redirect('index.php');
$errors = [];

if (is_post_request()) {
    if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Invalid request.';
    $newStatus = post_string('status', 30);
    if (!in_array($newStatus, ['Pending', 'Approved', 'Rejected', 'Contacted'], true)) $errors[] = 'Invalid status.';
    if (!$errors) {
        $update = $pdo->prepare('UPDATE enrollments SET status = :status WHERE id = :id');
        $update->execute(['status' => strtolower($newStatus), 'id' => $id]);
        redirect('view.php?id=' . $id);
    }
}

admin_header('Update Enrollment Status', 'enrollments/index.php');
?>
<div class="card table-card"><div class="card-body p-4"><h2 class="h5">Update enrollment</h2><p class="text-secondary">Student: <?= e($enrollment['name']) ?> · <?= e($enrollment['course_title']) ?></p><?php if ($errors): ?><div class="alert alert-danger"><?= e(implode(' ', $errors)) ?></div><?php endif; ?><form method="post"><input type="hidden" name="id" value="<?= e($id) ?>"><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><label class="form-label">Status</label><select class="form-select mb-4" name="status"><?php foreach (['Pending', 'Approved', 'Rejected', 'Contacted'] as $option): ?><option value="<?= e($option) ?>" <?= strtolower((string) $enrollment['status']) === strtolower($option) ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select><button class="btn btn-primary" type="submit">Save Status</button><a class="btn btn-outline-secondary ms-2" href="view.php?id=<?= e($id) ?>">Cancel</a></form></div></div>
<?php admin_footer(); ?>