<?php $year = date('Y'); ?>
</main>
<footer class="site-footer pt-5 pb-4">
	<div class="container">
		<div class="row g-4 mb-4">
			<div class="col-lg-4">
				<a class="footer-brand" href="index.php"><span class="brand-mark"><i class="bi bi-lightning-charge-fill"></i></span> Digital Skills Academy</a>
				<p class="text-white-50 mt-3 mb-0">Practical, project-based digital skills training that helps learners build confidence, careers and a future-ready portfolio.</p>
				<div class="social-links mt-4"><a href="#" aria-label="Facebook"><i class="bi bi-facebook"></i></a><a href="#" aria-label="Instagram"><i class="bi bi-instagram"></i></a><a href="#" aria-label="LinkedIn"><i class="bi bi-linkedin"></i></a><a href="#" aria-label="YouTube"><i class="bi bi-youtube"></i></a></div>
			</div>
			<div class="col-6 col-lg-2"><h6>Quick Links</h6><a href="about.php">About Us</a><a href="courses.php">Courses</a><a href="services.php">Services</a><a href="gallery.php">Gallery</a></div>
			<div class="col-6 col-lg-3"><h6>Popular Courses</h6><a href="courses.php">Website Development</a><a href="courses.php">Digital Marketing</a><a href="courses.php">Graphic Designing</a><a href="courses.php">Python Programming</a></div>
			<div class="col-lg-3"><h6>Contact Details</h6><p><i class="bi bi-geo-alt me-2"></i>Lasbela, Balochistan</p><p><i class="bi bi-telephone me-2"></i>+92 300 0000000</p><p><i class="bi bi-envelope me-2"></i>info@digitalskillsacademy.pk</p></div>
		</div>
		<div class="footer-bottom pt-3"><span>&copy; <?= e($year) ?> Digital Skills Academy. All rights reserved.</span><span>Learn. Create. Grow.</span></div>
	</div>
</footer>
<div class="chatbot-widget" id="academyChatbot"><button class="chatbot-toggle" type="button" aria-expanded="false" aria-controls="chatbotPanel"><i class="bi bi-chat-dots-fill"></i><span>Ask Academy Bot</span></button><div class="chatbot-panel" id="chatbotPanel" hidden><div class="chatbot-head"><strong>Academy Assistant</strong><button type="button" class="chatbot-close" aria-label="Close chat"><i class="bi bi-x-lg"></i></button></div><div class="chatbot-messages" id="chatbotMessages" aria-live="polite"><div class="chatbot-message bot">Hi! Ask me about courses, syllabus, fees, assignments, quizzes or enrollment.</div></div><form class="chatbot-form" id="chatbotForm"><input class="form-control" id="chatbotInput" name="message" placeholder="Type your question..." autocomplete="off" required><button class="btn btn-primary" type="submit" aria-label="Send message"><i class="bi bi-send"></i></button></form></div></div>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.7.1/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="assets/js/script.js"></script>
</body>
</html>
