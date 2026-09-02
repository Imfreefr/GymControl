<?php
if (session_status()===PHP_SESSION_NONE) session_start(); if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));

/**
 * GymControl - Novo Exercício (Admin)
 *
 * Formulário para cadastro de novos exercícios na base. Valida
 * token CSRF, envia os dados ao ExercicioController e persiste
 * via Model com PDO.
 *
 * Fluxo: Autenticação admin -> POST + CSRF -> Controller criar -> Redirect
 */

require_once '../../vendor/autoload.php';

// Autenticação
if (session_status()===PHP_SESSION_NONE) session_start(); if (empty($_SESSION['usuario_id'])) { header('Location: ../index.php?msg=login'); exit; } if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') { header('Location: ../View/painel_aluno.php'); exit; }

use Controller\ExercicioController;

// Processamento (POST)
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? null)))) {
        $msg = 'Token inválido.';
    } else {
        $ctrl = new ExercicioController();

        [$ok, $ret] = $ctrl->criar($_POST);
        $msg = $ret;

        if ($ok) {
            header('Location: exercicios.php');
            exit;
        }
    }
}

?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../../templates/css/global.css">
        <title>GymControl | Novo Exercício</title>
    </head>
    <body style="background: #f5f5f7;">
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="sidebar p-3" style="width: 260px;">
                <strong>GymControl</strong>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4" style="max-width: 720px;">
                <h3 class="fw-bold">Novo Exercício</h3>
                <p class="small text-muted">POST → Controller → Validação → Model → PDO → MySQL/SQLite → Redirect</p>

                <?php if ($msg) : ?>
                    <div class="alert alert-warning py-2 small"><?= htmlspecialchars((string) $msg, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" class="card card-gym border-0 p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control" placeholder="Supino reto" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Grupo muscular *</label>
                            <select name="grupo_muscular" class="form-select" required>
                                <option value="">Selecione</option>
                                <option>Peito</option>
                                <option>Costas</option>
                                <option>Pernas</option>
                                <option>Ombros</option>
                                <option>Bíceps</option>
                                <option>Tríceps</option>
                                <option>Abdômen</option>
                                <option>Glúteos</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Descrição</label>
                            <input type="text" name="descricao" class="form-control" placeholder="Ex: Supino reto com barra">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Séries</label>
                            <input type="number" name="series" class="form-control" value="3">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Repetições</label>
                            <input type="text" name="repeticoes" class="form-control" value="12">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Carga</label>
                            <input type="text" name="carga" class="form-control" value="-">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Descanso</label>
                            <input type="text" name="descanso" class="form-control" value="60s">
                        </div>
                    </div>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-gym">Salvar</button>
                        <a href="exercicios.php" class="btn btn-outline-dark">Voltar</a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>
