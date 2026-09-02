<?php

/**
 * GymControl - Listagem de Treinos (Admin)
 *
 * Exibe todos os treinos cadastrados em cards, com aluno vinculado
 * e lista de exercícios associados a cada treino.
 *
 * Fluxo: Autenticação admin -> Controller Treino (PDO) -> View
 */

require_once '../../vendor/autoload.php';

// ============================================================
// Autenticação
// ============================================================
exigirAdmin();

use Controller\TreinoController;

// ============================================================
// Dados
// ============================================================
$ctrl = new TreinoController();
$lista = $ctrl->todos();

?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../../templates/css/global.css">
        <title>GymControl | Treinos</title>
    </head>
    <body style="background: #f5f5f7;">
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="sidebar p-3" style="width: 260px;">
                <div class="d-flex align-items-center gap-2 mb-4">
                    <span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>
                    <strong>GymControl</strong>
                </div>
                <nav class="d-flex flex-column gap-1">
                    <a href="painel_admin.php" class="p-2 text-decoration-none">Dashboard</a>
                    <a href="alunos.php" class="p-2 text-decoration-none">Alunos</a>
                    <a href="exercicios.php" class="p-2 text-decoration-none">Exercícios</a>
                    <a href="treinos.php" class="active p-2 text-decoration-none">Treinos</a>
                    <a href="../logout.php" class="p-2 text-decoration-none text-danger">Sair</a>
                </nav>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h3 class="fw-bold mb-0">Treinos</h3>
                    <a href="treino_novo.php" class="btn btn-gym btn-sm">
                        <i class="bi bi-plus-lg"></i> Novo Treino
                    </a>
                </div>

                <div class="row g-3">
                    <?php if (empty($lista)) : ?>
                        <div class="col-12">
                            <div class="alert alert-light border">Nenhum treino cadastrado.</div>
                        </div>
                    <?php else : ?>
                        <?php foreach ($lista as $t) : ?>
                            <?php $exs = $ctrl->exercicios((int) $t['id']); ?>
                            <div class="col-md-6">
                                <div class="card card-gym border-0 h-100">
                                    <div class="card-body">
                                        <h5 class="fw-bold mb-1"><?= e($t['nome']) ?></h5>
                                        <p class="small text-muted mb-1">
                                            <i class="bi bi-person"></i> <?= e($t['aluno_nome']) ?> • <?= e($t['objetivo'] ?? '') ?> • <?= e($t['created_at']) ?>
                                        </p>
                                        <?php if (!empty($t['observacoes'])) : ?>
                                            <p class="small"><em><?= e($t['observacoes']) ?></em></p>
                                        <?php endif; ?>
                                        <ul class="small mb-2">
                                            <?php foreach ($exs as $ex) : ?>
                                                <li><?= e($ex['nome']) ?> — <?= e($ex['series']) ?>×<?= e($ex['repeticoes']) ?> (<?= e($ex['carga']) ?>)</li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <a href="treino_excluir.php?id=<?= (int) $t['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir treino?')">Excluir</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
