<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/**
 * GymControl - Registrar Frequência (Admin)
 *
 * Permite ao administrador registrar presenças por aluno e data.
 * Carrega a lista de alunos via Controller e persiste a presença
 * via Model Frequencia com PDO.
 *
 * Fluxo: Autenticação admin -> GET alunos -> POST + CSRF -> Model registrar -> View
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
use Model\Frequencia;

// Dados
$alunos = (new AlunoController())->listar();

// Processamento (POST)
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? null)))) {
        $msg = 'Token inválido.';
    } else {
        $freq = new Frequencia();
        $ok = $freq->registrar((int) $_POST['aluno_id'], $_POST['data'], 1);
        $msg = $ok ? 'Presença registrada!' : 'Erro.';
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
        <title>GymControl | Frequência Admin</title>
    </head>
    <body style="background: #f5f5f7;">
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="sidebar p-3" style="width: 260px;">
                <strong>GymControl</strong>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4" style="max-width: 600px;">
                <h3 class="fw-bold">Registrar Frequência</h3>

                <?php if ($msg) : ?>
                    <div class="alert alert-info py-2 small"><?= htmlspecialchars((string) $msg, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" class="card card-gym border-0 p-4">
                    <div class="mb-3">
                        <label class="form-label">Aluno</label>
                        <select name="aluno_id" class="form-select" required>
                            <option value="">Selecione</option>
                            <?php foreach ($alunos as $a) : ?>
                                <option value="<?= (int) $a['id'] ?>"><?= htmlspecialchars((string) $a['nome'], ENT_QUOTES, 'UTF-8') ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Data</label>
                        <input type="date" name="data" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <button class="btn btn-gym">Registrar presença</button>
                    <a href="painel_admin.php" class="btn btn-outline-dark">Voltar</a>
                </form>
            </main>
        </div>
    </body>
</html>
