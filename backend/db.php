<?php

$mongoUri = getenv('MONGODB_URI') ?: 'mongodb://127.0.0.1:27017';
$mongoDatabase = getenv('MONGODB_DB') ?: 'faculty_feedback';
$mongoCollection = 'feedback';
$mongoManager = null;
$databaseError = null;

try {
    $mongoManager = new MongoDB\Driver\Manager($mongoUri);
    $mongoManager->executeCommand($mongoDatabase, new MongoDB\Driver\Command(['ping' => 1]))->toArray();
} catch (Throwable $exception) {
    $databaseError = $exception->getMessage();
}
?>
