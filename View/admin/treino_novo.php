<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/**
 * GymControl - Novo Treino (Admin)
 *
 * Formulário para criação de treinos vinculados a um aluno,
 * com seleção múltipla de exercícios. Valida CSRF e delega
 * a criação ao TreinoController via Model com PDO.
 *
 * Fluxo: Autenticação admin -> GET listas -> POST + CSRF -> Controller criar -> Redirect
 */

require_once '../../vendor/autoload.php';

// Autenticação
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['usuario_id'])) {
    header('Location: ../index.php?msg=login');
    exit;
}

if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
    header('Location: ../View/painel_aluno.php');
    exit;
}

use Controller\AlunoController;
use Controller\TreinoController;
use Model\Exercicio;

// Dados
$msg = '';
$alunos = (new AlunoController())->listar();
$exs = (new Exercicio())->listar();

// Processamento (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? null)))) {
        $msg = 'Token inválido.';
    } else {
        $ctrl = new TreinoController();

        [$ok, $ret] = $ctrl->criar(
            (int) ($_POST['aluno_id'] ?? 0),
            trim($_POST['nome'] ?? ''),
            trim($_POST['objetivo'] ?? ''),
            trim($_POST['observacoes'] ?? ''),
            trim($_POST['professor'] ?? ''),
            $_POST['exercicios'] ?? []
        );

        $msg = $ret;

        if ($ok) {
            header('Location: treinos.php');
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
        <title>GymControl | Novo Treino</title>
    </head>
    <body style="background: #f5f5f7;">
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="sidebar p-3" style="width: 260px;">
                <strong>GymControl</strong>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4" style="max-width: 780px;">
                <h3 class="fw-bold">Novo Treino</h3>
                <p class="small text-muted">Aluno → Criar Treino → Selecionar Exercícios → Salvar → Aluno visualiza</p>

                <?php if ($msg) : ?>
                    <div class="alert alert-warning py-2 small"><?= htmlspecialchars((string) $msg, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" class="card card-gym border-0 p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Aluno *</label>
                            <select name="aluno_id" class="form-select" required>
                                <option value="">Selecione</option>
                                <?php foreach ($alunos as $a) : ?>
                                    <option value="<?= (int) $a['id'] ?>"><?= htmlspecialchars((string) $a['nome'], ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars((string) $a['email'], ENT_QUOTES, 'UTF-8') ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nome *</label>
                            <input type="text" name="nome" class="form-control" placeholder="TREINO A - PEITO E TRÍCEPS" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Objetivo</label>
                            <input type="text" name="objetivo" class="form-control" placeholder="Hipertrofia">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Professor</label>
                            <input type="text" name="professor" class="form-control" placeholder="Prof. Carlos">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Observações</label>
                            <input type="text" name="observacoes" class="form-control">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Exercícios (selecione)</label>
                            <div class="border rounded-3 p-3" style="max-height: 220px; overflow: auto;">
                                <?php if (empty($exs)) : ?>
                                    <p class="small text-muted">Cadastre exercícios primeiro.</p>
                                <?php else : ?>
                                    <?php foreach ($exs as $ex) : ?>
                                        <label class="d-flex align-items-center gap-2 small mb-1">
                                            <input type="checkbox" name="exercicios[]" value="<?= (int) $ex['id'] ?>">
                                            <?= htmlspecialchars((string) $ex['nome'], ENT_QUOTES, 'UTF-8') ?>
                                            <span class="badge bg-secondary"><?= htmlspecialchars((string) $ex['grupo_muscular'], ENT_QUOTES, 'UTF-8') ?></span>
                                            <?= htmlspecialchars((string) $ex['series'], ENT_QUOTES, 'UTF-8') ?>×<?= htmlspecialchars((string) $ex['repeticoes'], ENT_QUOTES, 'UTF-8') ?>
                                        </label>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-gym">Salvar</button>
                        <a href="treinos.php" class="btn btn-outline-dark">Voltar</a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>
