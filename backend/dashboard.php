<?php

require __DIR__ . '/db.php';

if ($mongoManager === null) {
    $totalFeedback = 0;
    $averageRating = 0.0;
    $feedbackRows = [];
} else {
    try {
        $namespace = $mongoDatabase . '.' . $mongoCollection;
        $countResult = $mongoManager->executeCommand($mongoDatabase, new MongoDB\Driver\Command([
            'count' => $mongoCollection,
            'query' => new stdClass(),
        ]))->toArray();
        $totalFeedback = (int)($countResult[0]->n ?? 0);

        $averageResult = $mongoManager->executeCommand($mongoDatabase, new MongoDB\Driver\Command([
            'aggregate' => $mongoCollection,
            'pipeline' => [
                ['$group' => ['_id' => null, 'average' => ['$avg' => '$overall']]],
            ],
            'cursor' => new stdClass(),
        ]))->toArray();
        $averageValue = $averageResult[0]->cursor->firstBatch[0]->average ?? 0;
        $averageRating = $averageValue
            ? round((float)$averageValue, 1)
            : 0.0;

        $query = new MongoDB\Driver\Query(
            [],
            ['sort' => ['feedback_date' => -1]]
        );
        $feedbackRows = $mongoManager->executeQuery($namespace, $query)->toArray();
    } catch (Throwable $exception) {
        $totalFeedback = 0;
        $averageRating = 0.0;
        $feedbackRows = [];
        $databaseError = $exception->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Feedback Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3eef8;
            padding: 40px;
            margin: 0;
        }

        .container {
            max-width: 1100px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
        }

        h1 {
            text-align: center;
            color: #62447a;
            margin-bottom: 20px;
        }

        .stats {
            display: flex;
            gap: 20px;
            margin-top: 20px;
            margin-bottom: 25px;
        }

        .card {
            flex: 1;
            background: #f3eef8;
            padding: 20px;
            text-align: center;
            border-radius: 10px;
            border: 1px solid #e6dff0;
        }

        .card h2 {
            margin: 0;
            color: #4f3565;
            font-size: 2rem;
        }

        .card p {
            margin: 8px 0 0;
            color: #5d4d71;
            font-weight: 600;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 25px;
        }

        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: center;
        }

        th {
            background: #68477f;
            color: white;
        }

        tr:nth-child(even) {
            background: #f8f5fa;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Faculty Feedback Dashboard</h1>

        <div class="stats">
            <div class="card">
                <h2><?php echo $totalFeedback; ?></h2>
                <p>Total Feedback</p>
            </div>

            <div class="card">
                <h2>⭐ <?php echo $averageRating; ?>/5</h2>
                <p>Average Rating</p>
            </div>
        </div>

        <table>
            <tr>
                <th>ID</th>
                <th>Faculty</th>
                <th>Subject</th>
                <th>Teaching</th>
                <th>Communication</th>
                <th>Knowledge</th>
                <th>Overall</th>
                <th>Comments</th>
            </tr>

            <?php if (count($feedbackRows) > 0): ?>
                <?php foreach ($feedbackRows as $document): ?>
                    <?php $row = (array)$document; ?>
                    <tr>
                        <td><?php echo htmlspecialchars((string)$row['_id']); ?></td>
                        <td><?php echo htmlspecialchars($row['faculty']); ?></td>
                        <td><?php echo htmlspecialchars($row['subject']); ?></td>
                        <td>⭐ <?php echo htmlspecialchars($row['teaching']); ?></td>
                        <td>⭐ <?php echo htmlspecialchars($row['communication']); ?></td>
                        <td>⭐ <?php echo htmlspecialchars($row['knowledge']); ?></td>
                        <td>⭐ <?php echo htmlspecialchars($row['overall']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['comments'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php elseif ($mongoManager === null || isset($databaseError)): ?>
                <tr>
                    <td colspan="8">Database query failed. Refresh the page and try again.</td>
                </tr>
            <?php else: ?>
                <tr>
                    <td colspan="8">No feedback submitted yet.</td>
                </tr>
            <?php endif; ?>
        </table>
    </div>
</body>
</html>
