<?php

/**
 * GymControl - Minha Evolução (Aluno)
 *
 * Permite ao aluno registrar medidas (peso, altura, observação e data)
 * e visualiza o histórico com tabela e gráfico de evolução de peso.
 *
 * Fluxo: Autenticação -> POST validação CSRF -> Model Evolucao (PDO)
 *        -> Redirect -> GET histórico -> View com Chart.js.
 */

require_once '../vendor/autoload.php';

use Controller\AlunoController;
use Model\Evolucao;

// ============================================================
// Autenticação e Autorização
// ============================================================
exigirLogin();

if (($_SESSION['usuario_tipo'] ?? '') === 'admin') {
    header('Location: admin/painel_admin.php');
    exit;
}

// ============================================================
// Dados
// ============================================================
$alunoCtrl = new AlunoController();
$evo = new Evolucao();

$aluno = $alunoCtrl->porUsuario((int) $_SESSION['usuario_id']);

if (!$aluno) {
    die('Aluno não encontrado.');
}

// ============================================================
// Processamento (POST)
// ============================================================
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validar($_POST['csrf'] ?? null)) {
        $msg = 'Token inválido.';
    } else {
        $peso = $_POST['peso'] !== '' ? (float) $_POST['peso'] : null;
        $altura = $_POST['altura'] !== '' ? (float) $_POST['altura'] : null;
        $obs = trim($_POST['observacao'] ?? '');
        $data = $_POST['data'] ?? date('Y-m-d');

        $ok = $evo->registrar((int) $aluno['id'], $peso, $altura, $obs ?: null, $data);
        $msg = $ok ? 'Evolução registrada!' : 'Erro ao registrar.';

        if ($ok) {
            header('Location: evolucao.php');
        }
    }
}

// ============================================================
// Consulta
// ============================================================
$historico = $evo->doAluno((int) $aluno['id']);

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
        <title>GymControl | Evolução</title>
    </head>
    <body style="background: #f5f5f7;">
        <!-- Cabeçalho -->
        <header class="navbar-gym text-white">
            <nav class="container d-flex justify-content-between align-items-center py-3">
                <a href="painel_aluno.php" class="text-white text-decoration-none d-flex align-items-center gap-2">
                    <span class="bgLinearGradient rounded-3 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                        <i class="bi bi-lightning-charge-fill"></i>
                    </span>
                    <strong>GymControl</strong>
                </a>
                <div class="d-flex gap-2">
                    <a href="painel_aluno.php" class="btn btn-sm btn-outline-light">Painel</a>
                    <a href="logout.php" class="btn btn-sm bgLinearGradient text-white">Sair</a>
                </div>
            </nav>
        </header>

        <!-- Conteúdo -->
        <main class="container py-4">
            <h3 class="fw-bold">Minha Evolução</h3>
            <p class="text-muted small">Registre peso/altura. POST → Controller → Model → PDO → MySQL/SQLite.</p>

            <?php if ($msg) : ?>
                <div class="alert alert-info py-2 small"><?= e($msg) ?></div>
            <?php endif; ?>

            <div class="row g-3">
                <!-- Formulário -->
                <div class="col-lg-4">
                    <div class="card card-gym border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">Novo registro</h5>
                            <form method="POST">
                                <div class="mb-2">
                                    <label class="form-label small">Peso (kg)</label>
                                    <input type="number" step="0.1" name="peso" class="form-control" placeholder="78.5">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Altura (m)</label>
                                    <input type="number" step="0.01" name="altura" class="form-control" placeholder="1.75">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Data</label>
                                    <input type="date" name="data" class="form-control" value="<?= date('Y-m-d') ?>">
                                </div>
                                <div class="mb-2">
                                    <label class="form-label small">Observação</label>
                                    <input type="text" name="observacao" class="form-control" placeholder="Ex: Após 30 dias">
                                </div>
                                <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">
                                <button class="btn btn-gym w-100">Salvar evolução</button>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Histórico e gráfico -->
                <div class="col-lg-8">
                    <?php if (!empty($historico)) : ?>
                        <div class="card card-gym border-0 mb-3">
                            <div class="card-body">
                                <canvas id="grafPeso" height="140"></canvas>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="card card-gym border-0">
                        <div class="card-body">
                            <h5 class="fw-bold">Histórico</h5>
                            <div class="table-responsive">
                                <table class="table table-sm">
                                    <thead>
                                        <tr>
                                            <th>Data</th>
                                            <th>Peso</th>
                                            <th>Altura</th>
                                            <th>Obs</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($historico)) : ?>
                                            <tr>
                                                <td colspan="4" class="small text-muted">Nenhum registro.</td>
                                            </tr>
                                        <?php else : ?>
                                            <?php foreach ($historico as $h) : ?>
                                                <tr>
                                                    <td><?= e($h['data']) ?></td>
                                                    <td><?= e($h['peso']) ?> kg</td>
                                                    <td><?= e($h['altura']) ?> m</td>
                                                    <td class="small"><?= e($h['observacao'] ?? '—') ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>

        <?php if (!empty($historico)) : ?>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                const labels = <?= json_encode(array_reverse(array_column($historico, 'data'))) ?>;
                const pesos = <?= json_encode(array_reverse(array_column($historico, 'peso'))) ?>;

                new Chart(document.getElementById('grafPeso'), {
                    type: 'line',
                    data: {
                        labels,
                        datasets: [{
                            label: 'Peso (kg)',
                            data: pesos,
                            borderColor: '#ea580c',
                            backgroundColor: 'rgba(234, 88, 12, .12)',
                            tension: .3,
                            fill: true
                        }]
                    },
                    options: {
                        plugins: {
                            legend: { display: false }
                        }
                    }
                });
            </script>
        <?php endif; ?>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
