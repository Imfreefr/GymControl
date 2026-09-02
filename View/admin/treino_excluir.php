<?php
if (session_status()===PHP_SESSION_NONE) session_start(); if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));

/**
 * GymControl - Excluir Treino (Admin)
 *
 * Remove um treino pelo ID informado via GET. Valida o token CSRF
 * e delega a exclusão ao TreinoController via Model com PDO.
 *
 * Fluxo: Autenticação admin -> GET id + CSRF -> Controller excluir -> Redirect
 */

require_once '../../vendor/autoload.php';

// ============================================================
// Autenticação
// ============================================================
if (session_status()===PHP_SESSION_NONE) session_start(); if (empty($_SESSION['usuario_id'])) { header('Location: ../index.php?msg=login'); exit; } if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') { header('Location: ../View/painel_aluno.php'); exit; }

if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_GET['csrf'] ?? null)))) {
    die('Token inválido.');
}

// ============================================================
// Processamento
// ============================================================
use Controller\TreinoController;

(new TreinoController())->excluir((int) ($_GET['id'] ?? 0));

header('Location: treinos.php');
exit;
