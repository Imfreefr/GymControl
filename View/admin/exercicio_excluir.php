<?php

/**
 * GymControl - Excluir Exercício (Admin)
 *
 * Remove um exercício pelo ID informado via GET. Valida o token
 * CSRF e delega a exclusão ao ExercicioController via Model/PDO.
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
use Controller\ExercicioController;

(new ExercicioController())->excluir((int) ($_GET['id'] ?? 0));

header('Location: exercicios.php');
exit;
