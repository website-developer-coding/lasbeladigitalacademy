<?php
header('Content-Type: application/json; charset=utf-8');
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

if (!is_post_request()) {
    http_response_code(405);
    echo json_encode(['reply' => 'Please send a message to start chatting.']);
    exit;
}

$message = trim((string) ($_POST['message'] ?? ''));
if ($message === '') {
    echo json_encode(['reply' => 'Please type a question. I can help with courses, syllabus, fees, assignments and enrollment.']);
    exit;
}
$messageLower = strtolower($message);
$reply = 'I can help with course choices, syllabus, fees, assignments, quizzes and enrollment. Try asking “What will I learn in Python?” or “How do I enroll?”';
$link = null;
$courseStatement = $pdo->prepare('SELECT id, title FROM courses WHERE status = :status AND LOWER(CONCAT_WS(" ", title, slug)) LIKE :search LIMIT 1');
$course = null;
$courseAliases = [
    'website-development' => '/webdev|website|web development/', 'digital-marketing' => '/digital marketing|marketing|seo/',
    'graphic-designing' => '/graphic design|graphic designing|designing/', 'artificial-intelligence' => '/artificial intelligence|\bai\b/',
    'machine-learning' => '/machine learning|\bml\b/', 'cyber-security' => '/cyber security|cybersecurity/',
    'python-programming' => '/python|programming/', 'ui-ux-design' => '/ui.?ux|user experience/',
    'freelancing' => '/freelanc/', 'e-commerce' => '/e.?commerce|online store/', 'video-editing' => '/video edit/',
    'database-management' => '/database|\bsql\b/',
];
foreach ($courseAliases as $slug => $pattern) {
    if (preg_match($pattern, $messageLower)) {
        $courseStatement->execute(['status' => 1, 'search' => '%' . $slug . '%']);
        $course = $courseStatement->fetch();
        if ($course) break;
    }
}
if (!$course) {
    $courseStatement->execute(['status' => 1, 'search' => '%' . $messageLower . '%']);
    $course = $courseStatement->fetch();
}
if (!$course && preg_match('/webdev|website|web development/', $messageLower)) {
    $courseStatement->execute(['status' => 1, 'search' => '%website-development%']);
    $course = $courseStatement->fetch();
}

if (preg_match('/\b(hello|hi|assalam|salam|help)\b/', $messageLower)) {
    $reply = 'Welcome to Digital Skills Academy! Ask me about any course, what you will learn, fees, assignments, quizzes or enrollment.';
} elseif (preg_match('/enroll|admission|apply|join/', $messageLower)) {
    $reply = 'You can apply online by selecting a course and completing the enrollment form. Our team will contact you after receiving your application.';
    $link = ['url' => 'enrollment.php', 'label' => 'Open enrollment form'];
} elseif (preg_match('/fee|price|cost|payment/', $messageLower)) {
    $reply = 'Course fees and discounts are available on our Fees Structure page. Open a course detail page to see its specific fee and duration.';
    $link = ['url' => 'fees.php', 'label' => 'View fees'];
} elseif ($course) {
    $reply = 'For ' . $course['title'] . ', you can view the syllabus, skills, benefits, assignments, quizzes and fees before applying.';
    $link = ['url' => 'course-details.php?id=' . (int) $course['id'], 'label' => 'View ' . $course['title']];
} elseif (preg_match('/syllabus|module|learn|material|content|assignment|quiz/', $messageLower)) {
    $reply = 'Every course includes a structured syllabus, practical assignments and quiz checkpoints. Choose a course to see its complete learning path.';
    $link = ['url' => 'syllabus.php', 'label' => 'Explore syllabi'];
} elseif (preg_match('/contact|phone|email|address|team/', $messageLower)) {
    $reply = 'You can contact the academy in Lasbela, Balochistan by phone at +92 300 0000000 or email info@digitalskillsacademy.pk.';
    $link = ['url' => 'contact.php', 'label' => 'Contact the academy'];
}

echo json_encode(['reply' => $reply, 'link' => $link], JSON_UNESCAPED_UNICODE);
