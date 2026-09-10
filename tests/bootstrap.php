<?php
require_once __DIR__ . '/../vendor/autoload.php';
$_SESSION = [];
if (session_status() === PHP_SESSION_NONE) {
    @session_start();
}
putenv('DB_DRIVER=sqlite');
$dbPath = __DIR__ . '/test.sqlite';
putenv('DB_PATH=' . $dbPath);
if (file_exists($dbPath)) {
    unlink($dbPath);
}
touch($dbPath);
$pdo = new PDO('sqlite:' . $dbPath, null, null, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);
$pdo->exec('PRAGMA foreign_keys = ON');
$schema = file_get_contents(__DIR__ . '/../database/schema.sqlite.sql');
$pdo->exec($schema);
\Model\Connection::setInstance($pdo);
