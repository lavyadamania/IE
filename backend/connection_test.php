<?php

include 'db.php';

if ($mongoManager !== null) {
	echo 'MongoDB connected successfully!';
} else {
	http_response_code(500);
	echo 'MongoDB connection failed: ' . htmlspecialchars($databaseError ?? 'Unknown error');
}
?>
