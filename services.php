<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Our Services';
$serviceStatement = $pdo->prepare('SELECT id, title, slug, description, icon, image FROM services WHERE status = :status ORDER BY title');
$serviceStatement->execute(['status' => 1]);
$services = $serviceStatement->fetchAll();
$categories = [];
foreach ($services as $service) {
    $firstWord = trim((string) strtok($service['title'], ' '));
    if ($firstWord !== '') $categories[$firstWord] = true;
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow text-info">Learn with purpose</span><h1 class="mt-3">Services designed for progress.</h1><p class="lead mb-0">Practical guidance, projects and support for every stage of your digital journey.</p></div></section>
<section class="section-space"><div class="container"><div class="filter-bar mb-5"><div class="row g-3 align-items-center"><div class="col-lg-8"><div class="input-group"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input type="search" class="form-control" placeholder="Search services..." data-filter-input></div></div><div class="col-lg-4"><select class="form-select" data-filter-category><option value="">All categories</option><?php foreach (array_keys($categories) as $category): ?><option value="<?= e($category) ?>"><?= e($category) ?></option><?php endforeach; ?></select></div></div></div><div class="row g-4"><?php foreach ($services as $service): $category = trim((string) strtok($service['title'], ' ')); ?><div class="col-md-6 col-lg-4" data-filter-item data-category="<?= e($category) ?>"><article class="service-card h-100"><?php if (!empty($service['image'])): ?><img src="<?= e(image_url($service['image'], 'services')) ?>" class="w-100" alt="<?= e($service['title']) ?>"><?php else: ?><div class="placeholder-image"><i class="bi <?= e($service['icon'] ?: 'bi-stars') ?>"></i></div><?php endif; ?><div class="card-body"><div class="icon-box mb-3"><i class="bi <?= e($service['icon'] ?: 'bi-stars') ?>"></i></div><h4><?= e($service['title']) ?></h4><p class="text-secondary"><?= e($service['description']) ?></p><a href="service-details.php?slug=<?= e($service['slug']) ?>" class="btn btn-sm btn-outline-primary">Learn More <i class="bi bi-arrow-right"></i></a></div></article></div><?php endforeach; ?></div><div class="empty-state" data-empty-state><i class="bi bi-search fs-2 d-block mb-2"></i>No services match your search.</div><?php if (!$services): ?><div class="empty-state d-block"><i class="bi bi-info-circle fs-2 d-block mb-2"></i>Services will be available soon.</div><?php endif; ?></div></section>
<?php require __DIR__ . '/includes/footer.php'; ?>
