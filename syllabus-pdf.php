<?php
/**
 * Generates a simple downloadable PDF syllabus for one active course.
 * This uses the existing Core PHP/PDO stack and does not require a framework.
 */
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/functions.php';

$courseId = valid_id($_GET['course_id'] ?? null);
if ($courseId === null) {
    http_response_code(400);
    exit('Invalid course selected.');
}

$courseStatement = $pdo->prepare('SELECT title, description, duration, level FROM courses WHERE id = :id AND status = :status LIMIT 1');
$courseStatement->execute(['id' => $courseId, 'status' => 1]);
$course = $courseStatement->fetch();

if (!$course) {
    http_response_code(404);
    exit('Course not found.');
}

$moduleStatement = $pdo->prepare('SELECT module_order, module_title, module_description FROM syllabus WHERE course_id = :course_id AND status = :status ORDER BY module_order ASC, id ASC');
$moduleStatement->execute(['course_id' => $courseId, 'status' => 1]);
$modules = $moduleStatement->fetchAll();

function pdf_text(string $text): string
{
    $text = iconv('UTF-8', 'Windows-1252//TRANSLIT//IGNORE', $text);
    return str_replace(['\\', '(', ')', "\r", "\n"], ['\\\\', '\\(', '\\)', '', ' '], $text);
}

function pdf_wrap(string $text, int $width = 92): array
{
    $text = preg_replace('/\s+/', ' ', trim($text));
    return $text === '' ? [''] : explode("\n", wordwrap($text, $width, "\n", false));
}

$lines = [
    'DIGITAL SKILLS ACADEMY',
    $course['title'],
    'Course Syllabus',
    '',
    'Duration: ' . $course['duration'],
    'Level: ' . $course['level'],
    '',
];
foreach (pdf_wrap($course['description'], 88) as $line) $lines[] = $line;
$lines[] = '';
$lines[] = 'LEARNING MODULES';
if (!$modules) {
    $lines[] = 'Syllabus modules will be published soon.';
} else {
    foreach ($modules as $module) {
        $lines[] = 'Module ' . $module['module_order'] . ': ' . $module['module_title'];
        foreach (pdf_wrap($module['module_description'], 84) as $line) $lines[] = '  ' . $line;
        $lines[] = '';
    }
}
$lines[] = 'Digital Skills Academy | Practical training for the digital world.';

$pageStreams = [];
$chunks = array_chunk($lines, 42);
foreach ($chunks as $chunk) {
    $stream = "BT\n/F1 18 Tf\n50 750 Td\n";
    foreach ($chunk as $index => $line) {
        if ($index === 1) $stream .= "/F1 15 Tf\n0 -28 Td\n";
        elseif ($index === 2) $stream .= "/F1 11 Tf\n0 -24 Td\n";
        elseif ($index > 2) $stream .= "/F1 10 Tf\n0 -17 Td\n";
        $stream .= '(' . pdf_text($line) . ") Tj\n";
    }
    $stream .= "ET";
    $pageStreams[] = $stream;
}

$objects = [];
$objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
$pageNumbers = [];
$fontNumber = 4 + (count($pageStreams) * 2);
$kids = [];
foreach ($pageStreams as $index => $stream) {
    $pageNumber = 4 + ($index * 2);
    $streamNumber = $pageNumber + 1;
    $pageNumbers[] = $pageNumber;
    $kids[] = $pageNumber . ' 0 R';
}
$objects[] = '<< /Type /Pages /Kids [' . implode(' ', $kids) . '] /Count ' . count($pageStreams) . ' >>';
$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';
foreach ($pageStreams as $index => $stream) {
    $pageNumber = 4 + ($index * 2);
    $streamNumber = $pageNumber + 1;
    $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] /Resources << /Font << /F1 ' . $fontNumber . ' 0 R >> >> /Contents ' . $streamNumber . ' 0 R >>';
    $objects[] = '<< /Length ' . strlen($stream) . " >>\nstream\n" . $stream . "\nendstream";
}
$objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>';

$pdf = "%PDF-1.4\n";
$offsets = [0];
foreach ($objects as $number => $object) {
    $objectNumber = $number + 1;
    $offsets[$objectNumber] = strlen($pdf);
    $pdf .= $objectNumber . " 0 obj\n" . $object . "\nendobj\n";
}
$xref = strlen($pdf);
$pdf .= "xref\n0 " . (count($objects) + 1) . "\n0000000000 65535 f \n";
for ($number = 1; $number <= count($objects); $number++) $pdf .= sprintf('%010d 00000 n \n', $offsets[$number]);
$pdf .= "trailer\n<< /Size " . (count($objects) + 1) . " /Root 1 0 R >>\nstartxref\n" . $xref . "\n%%EOF";

header('Content-Type: application/pdf');
header('Content-Disposition: attachment; filename="' . preg_replace('/[^a-z0-9]+/i', '-', $course['title']) . '-syllabus.pdf"');
header('Content-Length: ' . strlen($pdf));
echo $pdf;
