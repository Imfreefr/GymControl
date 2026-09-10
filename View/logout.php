<?php

/**
 * GymControl - Logout
 *
 * Encerra a sessão do utilizador de forma segura.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

require_once '../vendor/autoload.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Método não permitido. Use POST.');
}

if (!isset($_SESSION['csrf'], $_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string) $_POST['csrf'])) {
    http_response_code(403);
    die('Token CSRF inválido.');
}

$_SESSION = [];

if (ini_get('session.use_cookies')) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000, $params['path'], $params['domain'], $params['secure'], $params['httponly']);
}

session_destroy();

header('Location: ../index.php?msg=logout');
exit;
