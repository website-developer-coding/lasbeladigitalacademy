<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$courseStatement = $pdo->prepare('SELECT id, title FROM courses WHERE status = :status ORDER BY title');
$courseStatement->execute(['status' => 1]);
$courses = $courseStatement->fetchAll();
$courseIds = array_map('intval', array_column($courses, 'id'));
$selectedCourseId = valid_id($_GET['course_id'] ?? ($_POST['course_id'] ?? null));
$errors = [];
$success = isset($_GET['success']) && $_GET['success'] === '1';

if (empty($_SESSION['enrollment_nonce'])) {
	$_SESSION['enrollment_nonce'] = bin2hex(random_bytes(24));
}

if (is_post_request()) {
	$postedCourseId = valid_id($_POST['course_id'] ?? null);
	$name = post_string('name', 150);
	$email = post_string('email', 150);
	$phone = post_string('phone', 30);
	$gender = post_string('gender', 30);
	$education = post_string('education', 150);
	$address = post_string('address', 1000);
	$message = post_string('message', 2000);

	if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Your form session expired. Please try again.';
	if (!hash_equals($_SESSION['enrollment_nonce'], (string) ($_POST['submission_nonce'] ?? ''))) $errors[] = 'This form has already been submitted. Please start a new enrollment.';
	if ($postedCourseId === null || !in_array($postedCourseId, $courseIds, true)) $errors[] = 'Please select a valid course.';
	if ($name === '') $errors[] = 'Please enter your full name.';
	if (!valid_email($email)) $errors[] = 'Please enter a valid email address.';
	if ($phone === '') $errors[] = 'Please enter your phone number.';
	if ($gender === '') $errors[] = 'Please select your gender.';
	if ($education === '') $errors[] = 'Please enter your education.';
	if ($address === '') $errors[] = 'Please enter your address.';

	if (!$errors) {
		$insert = $pdo->prepare('INSERT INTO enrollments (course_id, name, email, phone, gender, education, address, message, status) VALUES (:course_id, :name, :email, :phone, :gender, :education, :address, :message, :status)');
		$insert->execute(['course_id' => $postedCourseId, 'name' => $name, 'email' => $email, 'phone' => $phone, 'gender' => $gender, 'education' => $education, 'address' => $address, 'message' => $message, 'status' => 'pending']);
		unset($_SESSION['enrollment_nonce']);
		redirect('enrollment.php?success=1');
	}
	$selectedCourseId = $postedCourseId;
}

$pageTitle = 'Enroll Now';
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow text-info">Take the next step</span><h1 class="mt-3">Start your learning journey.</h1><p class="lead mb-0">Complete the form and our academy team will contact you with the next steps.</p></div></section>
<section class="section-space"><div class="container"><div class="row justify-content-center"><div class="col-xl-9"><div class="row g-0 shadow-sm rounded-4 overflow-hidden"><div class="col-lg-4 bg-primary text-white p-4 p-md-5"><i class="bi bi-mortarboard fs-1"></i><h3 class="mt-4">Your future starts here.</h3><p class="text-white-50">Choose a course, commit to learning and build skills you can use in the real world.</p><ul class="list-unstyled small text-white-50 mt-4"><li class="mb-3"><i class="bi bi-check-circle me-2"></i>Practical project-based lessons</li><li class="mb-3"><i class="bi bi-check-circle me-2"></i>Supportive learning community</li><li><i class="bi bi-check-circle me-2"></i>Career-focused guidance</li></ul></div><div class="col-lg-8 bg-white p-4 p-md-5"><?php if ($success): ?><div class="alert alert-success border-0"><i class="bi bi-check-circle me-2"></i><strong>Enrollment received!</strong><br>Thank you. Our team will contact you soon.</div><?php endif; ?><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><form method="post" action="enrollment.php" novalidate><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="submission_nonce" value="<?= e($_SESSION['enrollment_nonce']) ?>"><div class="mb-3"><label class="form-label" for="course_id">Course <span class="text-danger">*</span></label><select class="form-select" id="course_id" name="course_id" required><option value="">Choose a course</option><?php foreach ($courses as $course): ?><option value="<?= e($course['id']) ?>" <?= $selectedCourseId === (int) $course['id'] ? 'selected' : '' ?>><?= e($course['title']) ?></option><?php endforeach; ?></select></div><div class="row g-3"><div class="col-md-6"><label class="form-label" for="name">Full Name <span class="text-danger">*</span></label><input class="form-control" id="name" name="name" value="<?= old('name') ?>" required></div><div class="col-md-6"><label class="form-label" for="email">Email <span class="text-danger">*</span></label><input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required></div><div class="col-md-6"><label class="form-label" for="phone">Phone <span class="text-danger">*</span></label><input class="form-control" id="phone" name="phone" value="<?= old('phone') ?>" required></div><div class="col-md-6"><label class="form-label" for="gender">Gender <span class="text-danger">*</span></label><select class="form-select" id="gender" name="gender" required><option value="">Select gender</option><?php foreach (['Male', 'Female', 'Other'] as $option): ?><option <?= old('gender') === $option ? 'selected' : '' ?>><?= e($option) ?></option><?php endforeach; ?></select></div></div><div class="mt-3 mb-3"><label class="form-label" for="education">Education <span class="text-danger">*</span></label><input class="form-control" id="education" name="education" value="<?= old('education') ?>" required></div><div class="mb-3"><label class="form-label" for="address">Address <span class="text-danger">*</span></label><textarea class="form-control" id="address" name="address" rows="2" required><?= old('address') ?></textarea></div><div class="mb-4"><label class="form-label" for="message">Message <span class="text-secondary small">(optional)</span></label><textarea class="form-control" id="message" name="message" rows="3"><?= old('message') ?></textarea></div><button class="btn btn-primary btn-lg" type="submit">Submit Enrollment <i class="bi bi-arrow-right ms-1"></i></button></form></div></div></div></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
