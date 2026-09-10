<?php

/**
 * GymControl - Meu Treino (Aluno)
 *
 * Lista apenas os treinos do aluno logado.
 * Fluxo: GET → Controller → Model → PDO → View
 */

require_once '../vendor/autoload.php';

use Controller\AlunoController;
use Controller\TreinoController;

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

if (empty($_SESSION['usuario_id'])) {
    header('Location: ../index.php?msg=login');
    exit;
}

$alunoCtrl  = new AlunoController();
$treinoCtrl = new TreinoController();

// Busca o registro de aluno vinculado ao usuário logado
$aluno = $alunoCtrl->porUsuario((int) $_SESSION['usuario_id']);

if (!$aluno) {
    die('Aluno não encontrado.');
}

// Treinos vinculados exclusivamente a este aluno (isolamento de dados)
$meusTreinos = $treinoCtrl->doAluno((int) $aluno['id']);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../templates/css/global.css">
    <link rel="shortcut icon" href="../templates/assets/img/favicon.svg">
    <title>GymControl | Meu Treino</title>
</head>
<body style="background: #f5f5f7;">

    <!-- Navbar -->
    <header class="navbar-gym text-white">
        <nav class="container d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-2">
                <a href="painel_aluno.php" class="text-white text-decoration-none d-flex align-items-center gap-2">
                    <span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>
                    <strong>GymControl</strong>
                </a>
            </div>
            <div class="d-flex gap-2">
                <a href="painel_aluno.php" class="btn btn-sm btn-outline-light">Painel</a>
                <form method="POST" action="logout.php" class="d-inline m-0">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-sm bgLinearGradient text-white">Sair</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container py-4">

        <h3 class="fw-bold">Meu Treino</h3>
        <p class="text-muted small">
            Treinos vinculados ao seu perfil. Dados vêm do banco
            (GET → Controller → Model → PDO → MySQL/SQLite → View).
        </p>

        <?php if (empty($meusTreinos)): ?>
            <div class="alert alert-light border">
                Nenhum treino cadastrado ainda. Procure a recepção.
            </div>

        <?php else: ?>
            <?php foreach ($meusTreinos as $treino): ?>
                <?php $exercicios = $treinoCtrl->exercicios((int) $treino['id']); ?>

                <div class="card card-gym border-0 mb-3">
                    <div class="card-body">

                        <!-- Cabeçalho do treino -->
                        <div class="d-flex justify-content-between flex-wrap gap-2">
                            <div>
                                <h5 class="fw-bold mb-1"><?= htmlspecialchars((string) $treino['nome'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <p class="small text-muted mb-1">
                                    <i class="bi bi-bullseye"></i> Objetivo: <?= htmlspecialchars((string) ($treino['objetivo'] ?? '—'), ENT_QUOTES, 'UTF-8') ?>
                                    • <i class="bi bi-person"></i> Prof: <?= htmlspecialchars((string) ($treino['professor'] ?? '—'), ENT_QUOTES, 'UTF-8') ?>
                                    • <?= htmlspecialchars((string) $treino['created_at'], ENT_QUOTES, 'UTF-8') ?>
                                </p>
                                <?php if (!empty($treino['observacoes'])): ?>
                                    <p class="small mb-2"><em><?= htmlspecialchars((string) $treino['observacoes'], ENT_QUOTES, 'UTF-8') ?></em></p>
                                <?php endif; ?>
                            </div>
                            <span class="badge bg-dark align-self-start">
                                <?= count($exercicios) ?> exercícios
                            </span>
                        </div>

                        <!-- Exercícios do treino -->
                        <?php if (empty($exercicios)): ?>
                            <p class="small text-muted">Sem exercícios vinculados.</p>
                        <?php else: ?>
                            <div class="table-responsive">
                                <table class="table table-sm align-middle mb-0">
                                    <thead>
                                        <tr class="small text-muted">
                                            <th>Exercício</th>
                                            <th>Grupo</th>
                                            <th>Séries</th>
                                            <th>Reps</th>
                                            <th>Carga</th>
                                            <th>Descanso</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($exercicios as $ex): ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars((string) $ex['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars((string) $ex['grupo_muscular'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                                <td><?= htmlspecialchars((string) $ex['series'], ENT_QUOTES, 'UTF-8') ?>×</td>
                                                <td><?= htmlspecialchars((string) $ex['repeticoes'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars((string) $ex['carga'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars((string) $ex['descanso'], ENT_QUOTES, 'UTF-8') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>

            <?php endforeach; ?>
        <?php endif; ?>

    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
