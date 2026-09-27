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

$faculty = trim($_POST['faculty'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$teaching = (int)($_POST['teaching'] ?? 0);
$communication = (int)($_POST['communication'] ?? 0);
$knowledge = (int)($_POST['knowledge'] ?? 0);
$overall = (float)($_POST['overall'] ?? 0);
$comments = trim($_POST['comments'] ?? '');

if ($faculty === '' || $subject === '') {
    echo '<h2>Faculty and subject are required.</h2>';
    exit;
}

try {
    $bulk = new MongoDB\Driver\BulkWrite();
    $bulk->insert([
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
