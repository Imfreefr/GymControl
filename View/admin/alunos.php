<?php

/**
 * GymControl - Listagem de Alunos (Admin)
 *
 * Exibe todos os alunos cadastrados com busca opcional por nome
 * ou e-mail e ações de edição e exclusão.
 *
 * Fluxo: Autenticação admin -> GET busca -> Controller Aluno (PDO) -> View
 */

require_once '../../vendor/autoload.php';

// ============================================================
// Autenticação
// ============================================================
exigirAdmin();

use Controller\AlunoController;

// ============================================================
// Dados
// ============================================================
$ctrl = new AlunoController();

$busca = trim($_GET['busca'] ?? '');
$alunos = $ctrl->listar($busca ?: null);
$msg = $_GET['msg'] ?? '';

?>
<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
        <link rel="stylesheet" href="../../templates/css/global.css">
        <link rel="shortcut icon" href="../../templates/assets/img/favicon.svg">
        <title>GymControl | Alunos</title>
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
                    <a href="painel_admin.php" class="p-2 text-decoration-none">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                    <a href="alunos.php" class="active p-2 text-decoration-none">
                        <i class="bi bi-people"></i> Alunos
                    </a>
                    <a href="exercicios.php" class="p-2 text-decoration-none">
                        <i class="bi bi-activity"></i> Exercícios
                    </a>
                    <a href="treinos.php" class="p-2 text-decoration-none">
                        <i class="bi bi-clipboard-check"></i> Treinos
                    </a>
                    <a href="../logout.php" class="p-2 text-decoration-none text-danger">
                        <i class="bi bi-box-arrow-right"></i> Sair
                    </a>
                </nav>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h3 class="fw-bold mb-0">Alunos</h3>
                    <a href="aluno_novo.php" class="btn btn-gym btn-sm">
                        <i class="bi bi-person-plus"></i> Novo Aluno
                    </a>
                </div>

                <?php if ($msg) : ?>
                    <div class="alert alert-success py-2 small"><?= e($msg) ?></div>
                <?php endif; ?>

                <!-- Busca -->
                <form method="GET" class="mb-3">
                    <div class="input-group" style="max-width: 360px;">
                        <input type="text" name="busca" value="<?= e($busca) ?>" class="form-control" placeholder="Buscar por nome ou e-mail">
                        <button class="btn btn-dark">Buscar</button>
                    </div>
                </form>

                <!-- Tabela -->
                <div class="card card-gym border-0">
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Nome</th>
                                        <th>E-mail</th>
                                        <th>Telefone</th>
                                        <th>Objetivo</th>
                                        <th>Status</th>
                                        <th>Ações</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($alunos)) : ?>
                                        <tr>
                                            <td colspan="6" class="text-center small text-muted py-3">Nenhum aluno encontrado.</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($alunos as $a) : ?>
                                            <tr>
                                                <td class="fw-semibold"><?= e($a['nome']) ?></td>
                                                <td class="small"><?= e($a['email']) ?></td>
                                                <td class="small"><?= e($a['telefone'] ?? '—') ?></td>
                                                <td><span class="badge bg-secondary"><?= e($a['objetivo'] ?? '—') ?></span></td>
                                                <td>
                                                    <span class="badge <?= $a['status'] === 'ativo' ? 'bg-success' : 'bg-secondary' ?>">
                                                        <?= e($a['status']) ?>
                                                    </span>
                                                </td>
                                                <td class="d-flex gap-1">
                                                    <a href="aluno_editar.php?id=<?= (int) $a['id'] ?>" class="btn btn-sm btn-outline-dark">Editar</a>
                                                    <a href="aluno_excluir.php?id=<?= (int) $a['id'] ?>&csrf=<?= e(csrf_token()) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Excluir aluno?')">Excluir</a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <p class="small text-muted mt-2">GET → Controller → Model → PDO → MySQL/SQLite → View. Busca com prepared statement (LIKE).</p>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
