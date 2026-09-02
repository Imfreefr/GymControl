<?php

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
exigirAdmin();

if (!csrf_validar($_GET['csrf'] ?? null)) {
    die('Token inválido.');
}

// ============================================================
// Processamento
// ============================================================
use Controller\TreinoController;

(new TreinoController())->excluir((int) ($_GET['id'] ?? 0));

header('Location: treinos.php');
exit;
