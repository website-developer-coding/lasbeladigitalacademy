<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$slug = trim((string) ($_GET['slug'] ?? ''));
$statement = $pdo->prepare('SELECT title, slug, description, icon, image FROM services WHERE slug = :slug AND status = :status LIMIT 1');
$statement->execute(['slug' => $slug, 'status' => 1]);
$service = $statement->fetch();
$errorMessage = $service ? null : 'The requested service could not be found.';
$pageTitle = $service ? $service['title'] : 'Service Details';
require __DIR__ . '/includes/header.php';
?>
<?php if ($errorMessage): ?><section class="section-space"><div class="container text-center py-5"><div class="icon-box mx-auto mb-3"><i class="bi bi-exclamation-circle"></i></div><h1 class="section-title">Service unavailable</h1><p class="text-secondary"><?= e($errorMessage) ?></p><a class="btn btn-primary" href="services.php">Browse Services</a></div></section>
<?php else: ?><section class="page-hero"><div class="container"><span class="eyebrow text-info">Academy support</span><h1 class="mt-3"><?= e($service['title']) ?></h1><p class="lead mb-0">Practical support to help you learn, create and grow.</p></div></section><section class="section-space"><div class="container"><div class="row align-items-center g-5"><div class="col-lg-6"><img src="<?= e(image_url($service['image'], 'services')) ?>" class="w-100 rounded-4 shadow-sm" style="height:360px;object-fit:cover" alt="<?= e($service['title']) ?>"></div><div class="col-lg-6"><div class="icon-box mb-3"><i class="bi <?= e($service['icon'] ?: 'bi-stars') ?>"></i></div><span class="eyebrow">How it helps</span><h2 class="section-title mt-2"><?= e($service['title']) ?></h2><p class="section-copy mt-3"><?= nl2br(e($service['description'])) ?></p><ul class="list-unstyled mt-4"><li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Clear guidance from experienced mentors</li><li class="mb-3"><i class="bi bi-check-circle-fill text-success me-2"></i>Practical examples connected to real work</li><li><i class="bi bi-check-circle-fill text-success me-2"></i>Support focused on your next step</li></ul><a class="btn btn-primary mt-3" href="contact.php?subject=<?= e(rawurlencode($service['title'])) ?>">Ask about this service <i class="bi bi-arrow-right"></i></a></div></div></div></section><?php endif; ?>
<?php require __DIR__ . '/includes/footer.php'; ?>
