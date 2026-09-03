<?php $activePage = current_page(); ?>
<nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top border-bottom">
	<div class="container">
		<a class="navbar-brand d-flex align-items-center gap-2" href="index.php">
			<span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span>
			<span>Digital Skills <strong>Academy</strong></span>
		</a>
		<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNavigation" aria-controls="mainNavigation" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="collapse navbar-collapse" id="mainNavigation">
			<ul class="navbar-nav ms-auto align-items-lg-center gap-lg-1">
				<?php foreach ([
					'index.php' => 'Home', 'about.php' => 'About Us', 'courses.php' => 'Courses',
					'services.php' => 'Services', 'fees.php' => 'Fees Structure', 'syllabus.php' => 'Syllabus',
					'gallery.php' => 'Gallery', 'enrollment.php' => 'Enrollment', 'contact.php' => 'Contact'
				] as $url => $label): ?>
					<li class="nav-item"><a class="nav-link <?= $activePage === $url ? 'active' : '' ?>" href="<?= e($url) ?>"><?= e($label) ?></a></li>
				<?php endforeach; ?>
				<li class="nav-item ms-lg-2 mt-2 mt-lg-0"><a class="btn btn-primary btn-sm px-3" href="enrollment.php">Enroll Now <i class="bi bi-arrow-up-right"></i></a></li>
			</ul>
		</div>
	</div>
</nav>
