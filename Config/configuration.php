<?php
define("DB_NAME", getenv("DB_NAME") ?: "gymcontrol");
define("DB_USER", getenv("DB_USER") ?: "root");
define("DB_PASSWORD", getenv("DB_PASSWORD") ?: "");
define("DB_HOST", getenv("DB_HOST") ?: "localhost");
define("DB_PORT", getenv("DB_PORT") ?: "3306");
define("APP_NAME", "GymControl");
define("APP_URL", getenv("APP_URL") ?: "http://localhost:8000");

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrf_token(): string {
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}
function csrf_validar(?string $token): bool {
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string)$token);
}
function e(mixed $v): string {
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}
function exigirLogin(): void {
    if (empty($_SESSION['usuario_id'])) {
        header('Location: ../index.php?msg=login');
        exit;
    }
}
function exigirAdmin(): void {
    exigirLogin();
    if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
        header('Location: painel_aluno.php');
        exit;
    }
}
