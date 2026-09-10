<?php

/**
 * GymControl - Excluir Treino (Admin)
 *
 * Remove um treino pelo id informado via POST.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

require_once '../../vendor/autoload.php';

if (empty($_SESSION['usuario_id'])) {
    header('Location: ../index.php?msg=login');
    exit;
}

if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../View/painel_aluno.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    die('Método não permitido. Use POST.');
}

if (!isset($_SESSION['csrf'], $_POST['csrf']) || !hash_equals($_SESSION['csrf'], (string) $_POST['csrf'])) {
    http_response_code(403);
    die('Token CSRF inválido.');
}

use Controller\TreinoController;

(new TreinoController())->excluir((int) ($_POST['id'] ?? 0));

header('Location: treinos.php');
exit;
