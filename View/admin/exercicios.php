<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/**
 * GymControl - Listagem de Exercícios (Admin)
 *
 * Exibe exercícios cadastrados com busca opcional por nome
 * e ação de exclusão protegida por CSRF.
 *
 * Fluxo: Autenticação admin -> GET busca -> Controller Exercicio (PDO) -> View
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

use Controller\ExercicioController;

// Dados
$ctrl = new ExercicioController();

$busca = trim($_GET['busca'] ?? '');
$lista = $ctrl->listar($busca ?: null);

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
        <title>GymControl | Exercícios</title>
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
                    <a href="alunos.php" class="p-2 text-decoration-none">
                        <i class="bi bi-people"></i> Alunos
                    </a>
                    <a href="exercicios.php" class="active p-2 text-decoration-none">
                        <i class="bi bi-activity"></i> Exercícios
                    </a>
                    <a href="treinos.php" class="p-2 text-decoration-none">
                        <i class="bi bi-clipboard-check"></i> Treinos
                    </a>
                    <form method="POST" action="../logout.php" class="m-0 p-2"><input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>"><button class="btn btn-link p-0 text-decoration-none text-danger"><i class="bi bi-box-arrow-right"></i> Sair</button></form>
                </nav>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h3 class="fw-bold mb-0">Exercícios</h3>
                    <a href="exercicio_novo.php" class="btn btn-gym btn-sm">
                        <i class="bi bi-plus-lg"></i> Novo Exercício
                    </a>
                </div>

                <!-- Busca -->
                <form method="GET" class="mb-3">
                    <div class="input-group" style="max-width: 360px;">
                        <input type="text" name="busca" value="<?= htmlspecialchars((string) $busca, ENT_QUOTES, 'UTF-8') ?>" class="form-control" placeholder="Buscar por nome (GET)">
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
                                        <th>Grupo</th>
                                        <th>Séries</th>
                                        <th>Reps</th>
                                        <th>Carga</th>
                                        <th>Descanso</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (empty($lista)) : ?>
                                        <tr>
                                            <td colspan="7" class="text-center small text-muted py-3">Nenhum exercício.</td>
                                        </tr>
                                    <?php else : ?>
                                        <?php foreach ($lista as $ex) : ?>
                                            <tr>
                                                <td class="fw-semibold"><?= htmlspecialchars((string) $ex['nome'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><span class="badge bg-secondary"><?= htmlspecialchars((string) $ex['grupo_muscular'], ENT_QUOTES, 'UTF-8') ?></span></td>
                                                <td><?= htmlspecialchars((string) $ex['series'], ENT_QUOTES, 'UTF-8') ?>×</td>
                                                <td><?= htmlspecialchars((string) $ex['repeticoes'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars((string) $ex['carga'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td><?= htmlspecialchars((string) $ex['descanso'], ENT_QUOTES, 'UTF-8') ?></td>
                                                <td>
                                                    <form method="POST" action="exercicio_excluir.php" class="d-inline m-0" onsubmit="return confirm('Excluir?')"><input type="hidden" name="id" value="<?= (int) $ex['id'] ?>"><input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>"><button class="btn btn-sm btn-outline-danger">Excluir</button></form>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <p class="small text-muted mt-2">GET → Controller → Model → PDO (prepared) → MySQL/SQLite → View. Busca por nome com LIKE.</p>
            </main>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    </body>
</html>
