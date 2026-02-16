<?php

declare(strict_types=1);

require_once __DIR__ . '/firebase_client.php';

$databaseUrl = getenv('FIREBASE_DATABASE_URL') ?: '';
$authToken = getenv('FIREBASE_DATABASE_SECRET') ?: null;
$path = $_GET['path'] ?? '/';

if ($databaseUrl === '') {
    $result = [
        'ok' => false,
        'data' => [],
        'error' => 'Missing FIREBASE_DATABASE_URL environment variable.',
    ];
} else {
    $client = new FirebaseClient($databaseUrl, $authToken);
    $result = $client->get($path);
}

$data = $result['data'];

$recordCount = 0;
$topLevelKeys = [];

if (is_array($data)) {
    $recordCount = count($data);
    $topLevelKeys = array_slice(array_keys($data), 0, 8);
}

$totalNestedItems = 0;
if (is_array($data)) {
    foreach ($data as $node) {
        if (is_array($node)) {
            $totalNestedItems += count($node);
        }
    }
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Firebase PHP Dashboard</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <header>
        <h1>Firebase Dashboard</h1>
        <p>Simple PHP dashboard for Firebase Realtime Database.</p>
    </header>

    <form class="path-form" method="get">
        <label for="path">Database path</label>
        <input type="text" id="path" name="path" value="<?= htmlspecialchars((string)$path) ?>" placeholder="/users">
        <button type="submit">Load</button>
    </form>

    <?php if (!$result['ok']): ?>
        <section class="card error">
            <h2>Connection Error</h2>
            <p><?= htmlspecialchars((string)$result['error']) ?></p>
            <p>Example: <code>export FIREBASE_DATABASE_URL="https://your-project-default-rtdb.firebaseio.com"</code></p>
        </section>
    <?php else: ?>
        <section class="stats-grid">
            <div class="card stat">
                <h2><?= $recordCount ?></h2>
                <p>Top-level records</p>
            </div>
            <div class="card stat">
                <h2><?= $totalNestedItems ?></h2>
                <p>Nested items counted</p>
            </div>
            <div class="card stat">
                <h2><?= count($topLevelKeys) ?></h2>
                <p>Keys previewed</p>
            </div>
        </section>

        <section class="card">
            <h2>Top-level keys</h2>
            <?php if (empty($topLevelKeys)): ?>
                <p>No keys found at this path.</p>
            <?php else: ?>
                <ul>
                    <?php foreach ($topLevelKeys as $key): ?>
                        <li><code><?= htmlspecialchars((string)$key) ?></code></li>
                    <?php endforeach; ?>
                </ul>
            <?php endif; ?>
        </section>

        <section class="card json-block">
            <h2>Raw JSON preview</h2>
            <pre><?= htmlspecialchars(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}') ?></pre>
        </section>
    <?php endif; ?>
</div>
</body>
</html>
