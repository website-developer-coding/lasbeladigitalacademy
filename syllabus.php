<?php
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';
$pageTitle = 'Course Syllabus';
$courseStatement = $pdo->prepare('SELECT id, title, description FROM courses WHERE status = :status ORDER BY title');
$courseStatement->execute(['status' => 1]);
$courses = $courseStatement->fetchAll();
$courseId = valid_id($_GET['course_id'] ?? null);
$selectedCourse = null;
$modules = [];
$errorMessage = null;
if ($courseId !== null) {
	$selectedStatement = $pdo->prepare('SELECT id, title, description FROM courses WHERE id = :id AND status = :status LIMIT 1');
	$selectedStatement->execute(['id' => $courseId, 'status' => 1]);
	$selectedCourse = $selectedStatement->fetch();
	if (!$selectedCourse) $errorMessage = 'The selected course could not be found.';
	else {
		$moduleStatement = $pdo->prepare('SELECT module_order, module_title, module_description FROM syllabus WHERE course_id = :course_id AND status = :status ORDER BY module_order ASC, id ASC');
		$moduleStatement->execute(['course_id' => $courseId, 'status' => 1]);
		$modules = $moduleStatement->fetchAll();
	}
}
require __DIR__ . '/includes/header.php';
?>
<section class="page-hero"><div class="container"><span class="eyebrow text-info">Plan your progress</span><h1 class="mt-3">Course syllabus.</h1><p class="lead mb-0">Explore the modules and download a complete PDF for your chosen course.</p></div></section>
<section class="section-space"><div class="container"><div class="row justify-content-center"><div class="col-xl-9"><label class="form-label fw-bold" for="courseSearch">Find your course</label><div class="input-group mb-3"><span class="input-group-text bg-white"><i class="bi bi-search"></i></span><input class="form-control form-control-lg" id="courseSearch" type="search" placeholder="Search by course name, e.g. webdev, Python, design" autocomplete="off"></div><label class="form-label fw-bold" for="courseSelector">Select a course</label><select class="form-select form-select-lg mb-2" id="courseSelector"><option value="syllabus.php">Choose a course</option><?php foreach ($courses as $course): ?><option value="syllabus.php?course_id=<?= e($course['id']) ?>" data-course-name="<?= e(strtolower($course['title'])) ?>" <?= $courseId === (int) $course['id'] ? 'selected' : '' ?>><?= e($course['title']) ?></option><?php endforeach; ?></select><small id="courseSearchStatus" class="text-secondary d-block mb-5" aria-live="polite">Search the list to find a syllabus quickly.</small><?php if ($errorMessage): ?><div class="alert alert-warning"><i class="bi bi-exclamation-circle me-2"></i><?= e($errorMessage) ?></div><?php elseif ($selectedCourse): ?><div class="mb-4"><span class="eyebrow">Selected course</span><h2 class="section-title mt-2"><?= e($selectedCourse['title']) ?></h2><p class="section-copy"><?= nl2br(e($selectedCourse['description'])) ?></p></div><?php if ($modules): ?><div class="accordion" id="syllabusAccordion"><?php foreach ($modules as $index => $module): ?><div class="accordion-item border-0 mb-2 rounded-3 overflow-hidden"><h2 class="accordion-header"><button class="accordion-button <?= $index !== 0 ? 'collapsed' : '' ?>" type="button" data-bs-toggle="collapse" data-bs-target="#syllabusModule<?= e($index) ?>"><span class="badge badge-soft me-3">Module <?= e($module['module_order']) ?></span><?= e($module['module_title']) ?></button></h2><div id="syllabusModule<?= e($index) ?>" class="accordion-collapse collapse <?= $index === 0 ? 'show' : '' ?>" data-bs-parent="#syllabusAccordion"><div class="accordion-body text-secondary"><?= nl2br(e($module['module_description'])) ?></div></div></div><?php endforeach; ?></div><?php else: ?><div class="alert alert-light">Modules for this course will be published soon.</div><?php endif; ?><?php else: ?><div class="text-center py-5"><div class="icon-box mx-auto mb-3"><i class="bi bi-journal-text"></i></div><h3>Choose a course to view its syllabus</h3><p class="text-secondary">Select a course above to explore the learning modules.</p></div><?php endif; ?></div></div></div></section>
<script>const courseSelector=document.getElementById('courseSelector');function addPdfLink(){const selected=courseSelector.value.match(/course_id=(\d+)/);const oldLink=document.getElementById('syllabusPdfLink');if(oldLink)oldLink.remove();if(selected){const link=document.createElement('a');link.id='syllabusPdfLink';link.className='btn btn-outline-primary float-end mb-3';link.href='syllabus-pdf.php?course_id='+selected[1];link.innerHTML='<i class="bi bi-file-earmark-pdf me-1"></i>Download PDF';courseSelector.parentElement.insertBefore(link,courseSelector.nextSibling);}}courseSelector.addEventListener('change',function(){if(this.value)window.location.href=this.value;});addPdfLink();</script>
<?php require __DIR__ . '/includes/footer.php'; ?>
