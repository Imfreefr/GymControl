<?php

/**
 * GymControl - Painel do Aluno
 *
 * Exibe dados pessoais, treino, frequência e evolução.
 * Acesso restrito: apenas alunos (admin é redirecionado).
 */

require_once '../vendor/autoload.php';

use Controller\AlunoController;
use Model\Evolucao;
use Model\Frequencia;
use Model\Treino;

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

if (($_SESSION['usuario_tipo'] ?? '') === 'admin') {
    header('Location: admin/painel_admin.php');
    exit;
}

// Dados do aluno logado
$alunoCtrl = new AlunoController();
$aluno = $alunoCtrl->porUsuario((int) $_SESSION['usuario_id']);

$freq   = new Frequencia();
$evo    = new Evolucao();
$treino = new Treino();

$totalPresencas = 0;
$frequenciaMes  = 0;
$evolucoes      = [];
$meusTreinos    = [];

if ($aluno) {
    $totalPresencas = $freq->totalPresencas((int) $aluno['id']);
    $frequenciaMes  = $freq->doMes((int) $aluno['id'], date('Y-m'));
    $evolucoes      = $evo->doAluno((int) $aluno['id']);
    $meusTreinos    = $treino->doAluno((int) $aluno['id']);
}

$proximoTreino = $meusTreinos[0] ?? null;
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
    <title>GymControl | Painel do Aluno</title>
</head>
<body style="background: #f5f5f7;">

    <!-- Navbar -->
    <header class="navbar-gym text-white">
        <nav class="container d-flex justify-content-between align-items-center py-3 flex-wrap gap-2">
            <div class="d-flex align-items-center gap-3">
                <span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                    <i class="bi bi-lightning-charge-fill"></i>
                </span>
                <strong>GymControl</strong>
            </div>

            <div class="d-flex align-items-center gap-2">
                <span class="small opacity-75"><?= htmlspecialchars((string) $_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?></span>
                <a href="meu_treino.php" class="btn btn-sm btn-outline-light">Meu Treino</a>
                <a href="frequencia.php" class="btn btn-sm btn-outline-light">Frequência</a>
                <a href="evolucao.php" class="btn btn-sm btn-outline-light">Evolução</a>
                <form method="POST" action="logout.php" class="d-inline m-0">
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-sm bgLinearGradient text-white">Sair</button>
                </form>
            </div>
        </nav>
    </header>

    <main class="container py-4">

        <h2 class="fw-bold">Olá, <?= htmlspecialchars((string) $_SESSION['usuario_nome'], ENT_QUOTES, 'UTF-8') ?>! 👋</h2>
        <p class="text-muted">Bem-vindo ao seu painel. Acompanhe seu treino e evolução.</p>

        <!-- Cards de resumo -->
        <div class="row g-3 mb-4">
            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <small class="text-muted">Meu Treino</small>
                    <h4 class="fw-bold mb-1"><?= count($meusTreinos) ?> treino(s)</h4>
                    <a href="meu_treino.php" class="small" style="color: #ea580c;">Ver treino →</a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <small class="text-muted">Frequência</small>
                    <h4 class="fw-bold mb-1"><?= $totalPresencas ?> presenças</h4>
                    <span class="small text-muted"><?= $frequenciaMes ?> no mês</span>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <small class="text-muted">Evolução</small>
                    <h4 class="fw-bold mb-1"><?= count($evolucoes) ?> registros</h4>
                    <a href="evolucao.php" class="small" style="color: #ea580c;">Ver histórico →</a>
                </div>
            </div>

            <div class="col-6 col-lg-3">
                <div class="stat-card p-3">
                    <small class="text-muted">Status</small>
                    <h4 class="fw-bold mb-1">
                        <span class="badge bg-success"><?= htmlspecialchars((string) ($aluno['status'] ?? 'ativo'), ENT_QUOTES, 'UTF-8') ?></span>
                    </h4>
                    <span class="small text-muted"><?= htmlspecialchars((string) ($aluno['objetivo'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></span>
                </div>
            </div>
        </div>

        <div class="row g-3">
            <!-- Coluna principal -->
            <div class="col-lg-8">

                <!-- Meus dados -->
                <div class="card card-gym border-0">
                    <div class="card-body">
                        <h5 class="fw-bold">
                            <i class="bi bi-person-badge"></i> Meus Dados
                        </h5>
                        <div class="row small">
                            <div class="col-6">
                                <p class="mb-1"><strong>E-mail:</strong> <?= htmlspecialchars((string) $_SESSION['usuario_email'], ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="mb-1"><strong>Telefone:</strong> <?= htmlspecialchars((string) ($aluno['telefone'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                            <div class="col-6">
                                <p class="mb-1"><strong>Objetivo:</strong> <?= htmlspecialchars((string) ($aluno['objetivo'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></p>
                                <p class="mb-1"><strong>Professor:</strong> <?= htmlspecialchars((string) ($aluno['professor'] ?? '—'), ENT_QUOTES, 'UTF-8') ?></p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Próximo treino -->
                <?php if ($proximoTreino): ?>
                    <div class="card card-gym border-0 mt-3">
                        <div class="card-body">
                            <h5 class="fw-bold">Próximo Treino</h5>
                            <p class="mb-1 fw-semibold">
                                <?= htmlspecialchars((string) $proximoTreino['nome'], ENT_QUOTES, 'UTF-8') ?>
                                <small class="text-muted">— <?= htmlspecialchars((string) ($proximoTreino['objetivo'] ?? ''), ENT_QUOTES, 'UTF-8') ?></small>
                            </p>
                            <p class="small text-muted mb-0">
                                Criado em <?= htmlspecialchars((string) $proximoTreino['created_at'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <a href="meu_treino.php" class="btn btn-sm btn-gym mt-2">Ver detalhes</a>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Coluna lateral -->
            <div class="col-lg-4">

                <!-- Frequência do mês -->
                <div class="card card-gym border-0">
                    <div class="card-body">
                        <h5 class="fw-bold">
                            <i class="bi bi-calendar-check"></i> Frequência do mês
                        </h5>
                        <h2 class="fw-bold" style="color: #ea580c;"><?= $frequenciaMes ?></h2>
                        <p class="small text-muted">presenças em <?= date('m/Y') ?></p>
                        <a href="frequencia.php" class="btn btn-sm btn-outline-dark w-100">Ver histórico</a>
                    </div>
                </div>

                <!-- Última evolução -->
                <?php if (!empty($evolucoes)): $ultima = $evolucoes[0]; ?>
                    <div class="card card-gym border-0 mt-3">
                        <div class="card-body">
                            <h5 class="fw-bold">
                                <i class="bi bi-graph-up"></i> Última evolução
                            </h5>
                            <p class="mb-1">
                                <strong><?= htmlspecialchars((string) $ultima['peso'], ENT_QUOTES, 'UTF-8') ?> kg</strong> • <?= htmlspecialchars((string) $ultima['altura'], ENT_QUOTES, 'UTF-8') ?> m
                            </p>
                            <p class="small text-muted mb-0">
                                <?= htmlspecialchars((string) ($ultima['observacao'] ?? ''), ENT_QUOTES, 'UTF-8') ?> — <?= htmlspecialchars((string) $ultima['data'], ENT_QUOTES, 'UTF-8') ?>
                            </p>
                            <canvas id="grafPeso" height="120" class="mt-2"></canvas>
                        </div>
                    </div>
                <?php endif; ?>

            </div>
        </div>

    </main>

    <!-- Gráfico de peso -->
    <?php if (!empty($evolucoes)): ?>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <script>
            const labels = <?= json_encode(array_reverse(array_column($evolucoes, 'data'))) ?>;
            const pesos  = <?= json_encode(array_reverse(array_column($evolucoes, 'peso'))) ?>;

            new Chart(document.getElementById('grafPeso'), {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        data: pesos,
                        borderColor: '#ea580c',
                        backgroundColor: 'rgba(234, 88, 12, .12)',
                        tension: .3,
                        fill: true
                    }]
                },
                options: {
                    plugins: { legend: { display: false } },
                    scales: { y: { beginAtZero: false } }
                }
            });
        </script>
    <?php endif; ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
