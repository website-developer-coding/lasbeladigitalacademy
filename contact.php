<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Contact Us';
$errors = [];
$success = isset($_GET['success']) && $_GET['success'] === '1';
if (empty($_SESSION['contact_nonce'])) $_SESSION['contact_nonce'] = bin2hex(random_bytes(24));
if (is_post_request()) {
	$name = post_string('name', 100);
	$email = post_string('email', 150);
	$phone = post_string('phone', 30);
	$subject = post_string('subject', 180);
	$message = post_string('message', 3000);
	if (!verify_csrf_token($_POST['csrf_token'] ?? null)) $errors[] = 'Your form session expired. Please try again.';
	if (!hash_equals($_SESSION['contact_nonce'], (string) ($_POST['submission_nonce'] ?? ''))) $errors[] = 'This form has already been submitted. Please start a new message.';
	if ($name === '') $errors[] = 'Please enter your name.';
	if (!valid_email($email)) $errors[] = 'Please enter a valid email address.';
	if ($subject === '') $errors[] = 'Please enter a subject.';
	if ($message === '') $errors[] = 'Please enter your message.';
	if (!$errors) {
		$insert = $pdo->prepare('INSERT INTO contact_messages (name, email, phone, subject, message, status) VALUES (:name, :email, :phone, :subject, :message, :status)');
		$insert->execute(['name' => $name, 'email' => $email, 'phone' => $phone, 'subject' => $subject, 'message' => $message, 'status' => 'unread']);
		unset($_SESSION['contact_nonce']);
		redirect('contact.php?success=1');
	}
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow text-info">We are here to help</span><h1 class="mt-3">Let’s start a conversation.</h1><p class="lead mb-0">Have a question about our courses or enrollment? Send us a message.</p></div></section>
<section class="section-space"><div class="container"><div class="row g-5"><div class="col-lg-5"><span class="eyebrow">Contact details</span><h2 class="section-title mt-3">Connect with the academy.</h2><p class="section-copy mt-3">Our team is ready to help you find the right learning path and answer your questions.</p><div class="mt-4"><div class="d-flex gap-3 mb-4"><div class="icon-box flex-shrink-0"><i class="bi bi-telephone"></i></div><div><small class="text-secondary d-block">Phone</small><strong>+92 300 0000000</strong></div></div><div class="d-flex gap-3 mb-4"><div class="icon-box flex-shrink-0"><i class="bi bi-envelope"></i></div><div><small class="text-secondary d-block">Email</small><strong>info@digitalskillsacademy.pk</strong></div></div><div class="d-flex gap-3 mb-4"><div class="icon-box flex-shrink-0"><i class="bi bi-geo-alt"></i></div><div><small class="text-secondary d-block">Address</small><strong>Lasbela, Balochistan</strong></div></div><div class="d-flex gap-3"><div class="icon-box flex-shrink-0"><i class="bi bi-clock"></i></div><div><small class="text-secondary d-block">Business hours</small><strong>Mon – Sat, 9:00 AM – 6:00 PM</strong></div></div></div><div class="social-links mt-4"><a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a><a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a><a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a></div></div><div class="col-lg-7"><div class="info-card p-4 p-md-5"><?php if ($success): ?><div class="alert alert-success border-0"><i class="bi bi-check-circle me-2"></i><strong>Message sent successfully!</strong><br>Thank you. Our team will get back to you soon.</div><?php endif; ?><?php if ($errors): ?><div class="alert alert-danger"><ul class="mb-0"><?php foreach ($errors as $error): ?><li><?= e($error) ?></li><?php endforeach; ?></ul></div><?php endif; ?><h3 class="mb-4">Send us a message</h3><form method="post" action="contact.php" novalidate><input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="submission_nonce" value="<?= e($_SESSION['contact_nonce']) ?>"><div class="row g-3"><div class="col-md-6"><label class="form-label" for="name">Name <span class="text-danger">*</span></label><input class="form-control" id="name" name="name" value="<?= old('name') ?>" required></div><div class="col-md-6"><label class="form-label" for="email">Email <span class="text-danger">*</span></label><input type="email" class="form-control" id="email" name="email" value="<?= old('email') ?>" required></div><div class="col-md-6"><label class="form-label" for="phone">Phone</label><input class="form-control" id="phone" name="phone" value="<?= old('phone') ?>"></div><div class="col-md-6"><label class="form-label" for="subject">Subject <span class="text-danger">*</span></label><input class="form-control" id="subject" name="subject" value="<?= old('subject') ?>" required></div><div class="col-12"><label class="form-label" for="message">Message <span class="text-danger">*</span></label><textarea class="form-control" id="message" name="message" rows="5" required><?= old('message') ?></textarea></div></div><button class="btn btn-primary btn-lg mt-4" type="submit">Send Message <i class="bi bi-send ms-1"></i></button></form></div></div></div></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
