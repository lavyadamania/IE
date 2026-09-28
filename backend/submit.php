<?php

require __DIR__ . '/db.php';

if ($mongoManager === null) {
    http_response_code(500);
    echo '<h2>Database connection failed.</h2>';
    echo '<p>Please start MongoDB and check the MONGODB_URI setting.</p>';
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo 'Invalid request method.';
    exit;
}

$allowedClasses = ['SE IT', 'TE IT', 'BE IT'];
$allowedDivisions = ['A', 'B', 'C', 'D'];
$allowedFaculty = [
    'Mrs. Trupti Shah',
    'Mr. Saurabh Srivastava',
    'Mrs. Apeksha Waghmare',
    'Mrs. Tamanna Upadhyay',
];
$allowedSubjects = [
    'Web Programming',
    'Computer Network Security',
    'Automata Theory',
    'SSIC',
];
$studentName = trim((string)($_POST['student_name'] ?? ''));
$className = trim((string)($_POST['class_name'] ?? ''));
$division = trim((string)($_POST['division'] ?? ''));
$rollNumber = filter_var($_POST['roll_number'] ?? null, FILTER_VALIDATE_INT);
$email = trim((string)($_POST['email'] ?? ''));
$phone = trim((string)($_POST['phone'] ?? ''));
$isAnonymous = ($_POST['anonymous'] ?? '') === '1';
$faculty = trim((string)($_POST['faculty'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? ''));
$teaching = filter_var($_POST['teaching'] ?? null, FILTER_VALIDATE_INT);
$communication = filter_var($_POST['communication'] ?? null, FILTER_VALIDATE_INT);
$knowledge = filter_var($_POST['knowledge'] ?? null, FILTER_VALIDATE_INT);
$submittedOverall = filter_var($_POST['overall'] ?? null, FILTER_VALIDATE_FLOAT);
$comments = trim((string)($_POST['comments'] ?? ''));

if (
    !preg_match("/^[A-Za-z][A-Za-z .'-]{1,99}$/", $studentName) ||
    !in_array($className, $allowedClasses, true) ||
    !in_array($division, $allowedDivisions, true) ||
    !is_int($rollNumber) || $rollNumber < 1 || $rollNumber > 100 ||
    !filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100 ||
    !preg_match('/^[6-9][0-9]{9}$/', $phone) ||
    !in_array($faculty, $allowedFaculty, true) ||
    !in_array($subject, $allowedSubjects, true) ||
    !is_int($teaching) || $teaching < 1 || $teaching > 5 ||
    !is_int($communication) || $communication < 1 || $communication > 5 ||
    !is_int($knowledge) || $knowledge < 1 || $knowledge > 5 ||
    ($comments !== '' && mb_strlen($comments) > 250)
) {
    http_response_code(422);
    echo '<h2>Invalid feedback.</h2>';
    echo '<p>Please provide valid selections, ratings from 1 to 5, and comments up to 250 characters.</p>';
    exit;
}

$overall = round(($teaching + $communication + $knowledge) / 3, 1);
if ($submittedOverall === false || abs($submittedOverall - $overall) > 0.01) {
    http_response_code(422);
    echo '<h2>Invalid overall rating.</h2>';
    echo '<p>The overall rating must match the three selected ratings.</p>';
    exit;
}

if ($isAnonymous) {
    $studentName = 'Anonymous';
    $email = null;
    $phone = null;
    $rollNumber = null;
}

try {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert([
        'student_name' => $studentName,
        'class_name' => $className,
        'division' => $division,
        'roll_number' => $rollNumber,
        'email' => $email,
        'phone' => $phone,
        'is_anonymous' => $isAnonymous,
        'faculty' => $faculty,
        'subject' => $subject,
        'teaching' => $teaching,
        'communication' => $communication,
        'knowledge' => $knowledge,
        'overall' => $overall,
        'comments' => $comments,
        'feedback_date' => new MongoDB\BSON\UTCDateTime(),
    ]);
    $mongoManager->executeBulkWrite($mongoDatabase . '.' . $mongoCollection, $bulk);

    echo '<h2>Feedback submitted successfully! 🎉</h2>';
    echo "<a href='dashboard.php'>View Feedback Dashboard</a>";
} catch (Throwable $exception) {
    http_response_code(500);
    echo '<h2>Database insert failed.</h2>';
    echo '<p>' . htmlspecialchars($exception->getMessage()) . '</p>';
}
?>
