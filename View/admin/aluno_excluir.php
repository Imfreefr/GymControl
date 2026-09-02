<?php

/**
 * GymControl - Excluir Aluno (Admin)
 *
 * Remove um aluno pelo ID informado via GET. Valida o token CSRF
 * e delega a exclusão ao AlunoController, que remove o registro
 * via Model com PDO.
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
use Controller\AlunoController;

$ctrl = new AlunoController();
$id = (int) ($_GET['id'] ?? 0);

$ctrl->excluir($id);

header('Location: alunos.php?msg=' . urlencode('Aluno excluído.'));
exit;
