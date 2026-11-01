<?php
// Database connection.
// Default: a local SQLite file (data/kiki.sqlite), so the site runs with just PHP.
// For MySQL hosting, set DB_HOST, DB_NAME, DB_USER and DB_PASS as environment variables.
$dbHost = getenv('DB_HOST');

try {
    if ($dbHost) {
        $conn = new PDO(
            "mysql:host=$dbHost;dbname=" . getenv('DB_NAME') . ";charset=utf8mb4",
            getenv('DB_USER'),
            getenv('DB_PASS')
        );
    } else {
        $dir = __DIR__ . '/data';
        if (!is_dir($dir)) mkdir($dir, 0775, true);
        $conn = new PDO('sqlite:' . $dir . '/kiki.sqlite');
        $conn->exec("CREATE TABLE IF NOT EXISTS users (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            fullname TEXT NOT NULL,
            email TEXT NOT NULL UNIQUE,
            password TEXT NOT NULL,
            created_at TEXT DEFAULT CURRENT_TIMESTAMP
        )");
    }
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Database connection failed.');
}
