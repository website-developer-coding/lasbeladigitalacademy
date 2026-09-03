<?php
require_once __DIR__ . '/../includes/admin_auth.php';
require_admin();

$counts = [];
$countQueries = [
	'total_courses' => 'SELECT COUNT(*) FROM courses', 'active_courses' => 'SELECT COUNT(*) FROM courses WHERE status = :status',
	'total_services' => 'SELECT COUNT(*) FROM services', 'active_services' => 'SELECT COUNT(*) FROM services WHERE status = :status',
	'total_enrollments' => 'SELECT COUNT(*) FROM enrollments', 'pending_enrollments' => "SELECT COUNT(*) FROM enrollments WHERE LOWER(status) = 'pending'",
	'gallery_images' => 'SELECT COUNT(*) FROM gallery', 'fee_records' => 'SELECT COUNT(*) FROM fees', 'unread_messages' => "SELECT COUNT(*) FROM contact_messages WHERE status = 'unread'",
];
foreach ($countQueries as $key => $sql) {
	$statement = $pdo->prepare($sql);
	if (strpos($sql, ':status') !== false) $statement->execute(['status' => 1]); else $statement->execute();
	$counts[$key] = (int) $statement->fetchColumn();
}
$enrollmentStatement = $pdo->prepare('SELECT enrollments.name, enrollments.email, enrollments.status, enrollments.created_at, courses.title AS course_title FROM enrollments INNER JOIN courses ON courses.id = enrollments.course_id ORDER BY enrollments.created_at DESC LIMIT 5');
$enrollmentStatement->execute();
$recentEnrollments = $enrollmentStatement->fetchAll();
$messageStatement = $pdo->prepare('SELECT name, email, subject, status, created_at FROM contact_messages ORDER BY created_at DESC LIMIT 5');
$messageStatement->execute();
$recentMessages = $messageStatement->fetchAll();
admin_header('Dashboard', 'dashboard.php');
?>
<div class="row g-3 mb-4"><?php $stats = [['total_courses','Total Courses','bi-mortarboard','primary'],['active_courses','Active Courses','bi-check-circle','success'],['total_services','Total Services','bi-stars','info'],['active_services','Active Services','bi-patch-check','success'],['total_enrollments','Total Enrollments','bi-people','primary'],['pending_enrollments','Pending Enrollments','bi-hourglass-split','warning'],['gallery_images','Gallery Images','bi-images','info'],['fee_records','Fee Records','bi-receipt','primary'],['unread_messages','Unread Messages','bi-envelope','danger']]; foreach ($stats as [$key,$label,$icon,$color]): ?><div class="col-6 col-md-4 col-xl-3"><div class="card stat-card h-100"><div class="card-body d-flex justify-content-between align-items-center"><div><small class="text-secondary d-block"><?= e($label) ?></small><strong class="fs-3 text-<?= e($color) ?>"><?= e($counts[$key]) ?></strong></div><i class="bi <?= e($icon) ?> fs-3 text-<?= e($color) ?>"></i></div></div></div><?php endforeach; ?></div>
<div class="row g-4"><div class="col-xl-7"><div class="card table-card"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Recent Enrollments</h2><a href="enrollments/index.php" class="small">View all</a></div><div class="table-responsive"><table class="table align-middle mb-0"><thead><tr><th>Student</th><th>Course</th><th>Status</th><th>Date</th></tr></thead><tbody><?php foreach ($recentEnrollments as $row): ?><tr><td><strong><?= e($row['name']) ?></strong><small class="d-block text-secondary"><?= e($row['email']) ?></small></td><td><?= e($row['course_title']) ?></td><td><span class="badge text-bg-<?= strtolower($row['status']) === 'pending' ? 'warning' : 'success' ?>"><?= e($row['status']) ?></span></td><td class="small text-secondary"><?= e(date('d M Y', strtotime($row['created_at']))) ?></td></tr><?php endforeach; ?><?php if (!$recentEnrollments): ?><tr><td colspan="4" class="text-center text-secondary py-4">No enrollments yet.</td></tr><?php endif; ?></tbody></table></div></div></div></div><div class="col-xl-5"><div class="card table-card"><div class="card-body"><div class="d-flex justify-content-between align-items-center mb-3"><h2 class="h5 mb-0">Recent Messages</h2><a href="messages/index.php" class="small">View all</a></div><?php foreach ($recentMessages as $row): ?><div class="border-bottom py-2"><div class="d-flex justify-content-between"><strong><?= e($row['name']) ?></strong><small class="text-secondary"><?= e(date('d M', strtotime($row['created_at']))) ?></small></div><p class="small mb-1"><?= e($row['subject']) ?></p><span class="badge text-bg-<?= strtolower((string) $row['status']) === 'unread' ? 'danger' : 'secondary' ?>"><?= e($row['status']) ?></span></div><?php endforeach; ?><?php if (!$recentMessages): ?><p class="text-secondary text-center py-4 mb-0">No messages yet.</p><?php endif; ?></div></div></div></div>
<?php admin_footer(); ?>
