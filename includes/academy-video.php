<?php
$introCourse = $introCourse ?? null;
?>
<section class="academy-video-section section-space" aria-labelledby="academy-video-title">
    <div class="container">
        <div class="row align-items-center g-5">
            <div class="col-lg-7">
                <div class="academy-video" role="img" aria-label="Thirty-second Digital Skills Academy introduction presentation">
                    <div class="academy-video-slide slide-one"><img src="uploads/gallery/classroom-web-development.jpg" alt="Students learning digital skills"><div><span>Digital Skills Academy</span><strong>Learn. Create. Grow.</strong></div></div>
                    <div class="academy-video-slide slide-two"><img src="uploads/courses/website-development.jpg" alt="Website development learning"><div><span>Practical training</span><strong>Build skills that work in the real world.</strong></div></div>
                    <div class="academy-video-slide slide-three"><img src="uploads/gallery/student-project-presentation.jpg" alt="Student project presentation"><div><span>Project-based learning</span><strong>Turn every lesson into something you can show.</strong></div></div>
                    <div class="academy-video-slide slide-four"><img src="uploads/gallery/completion-certificates.jpg" alt="Student completion certificates"><div><span>Your next chapter</span><strong>Build confidence for a brighter future.</strong></div></div>
                    <div class="academy-video-controls"><span><i class="bi bi-play-fill"></i> 30 sec intro</span><span>Digital Skills Academy</span></div>
                </div>
            </div>
            <div class="col-lg-5">
                <span class="eyebrow">Watch our introduction</span>
                <h2 id="academy-video-title" class="section-title mt-2"><?= $introCourse ? 'See what ' . e($introCourse['title']) . ' can unlock.' : 'A better way to build your future.' ?></h2>
                <p class="section-copy mt-3">Our 30-second introduction shows the learning experience: practical lessons, hands-on assignments, supportive guidance and skills for real opportunities.</p>
                <div class="d-flex gap-3 mt-4"><div class="icon-box flex-shrink-0"><i class="bi bi-play-circle"></i></div><div><strong><?= $introCourse ? 'Course-focused learning' : 'Learn with confidence' ?></strong><small class="d-block text-secondary">Skills, projects and career momentum</small></div></div>
            </div>
        </div>
    </div>
</section>
