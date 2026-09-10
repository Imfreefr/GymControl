<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

/**
 * GymControl - Editar Aluno (Admin)
 *
 * Permite editar dados cadastrais de um aluno existente. Carrega
 * o registro pelo ID via Controller e, em POST, valida CSRF e
 * persiste as alterações no banco.
 *
 * Fluxo: Autenticação admin -> GET id -> Controller -> View (form)
 *        -> POST + CSRF -> Controller atualizar -> Redirect
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

// Dados
$ctrl = new AlunoController();

$id = (int) ($_GET['id'] ?? 0);
$aluno = $ctrl->porId($id);

if (!$aluno) {
    die('Aluno não encontrado.');
}

// Processamento (POST)
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? null)))) {
        $msg = 'Token inválido.';
    } else {
        $dados = [
            'nome'            => trim($_POST['nome'] ?? ''),
            'email'           => trim($_POST['email'] ?? ''),
            'telefone'        => trim($_POST['telefone'] ?? ''),
            'data_nascimento' => $_POST['data_nascimento'] ?: null,
            'objetivo'        => trim($_POST['objetivo'] ?? ''),
            'status'          => $_POST['status'] ?? 'ativo',
            'professor'       => trim($_POST['professor'] ?? ''),
        ];

        $ok = $ctrl->atualizar($id, $dados);
        $msg = $ok ? 'Atualizado!' : 'Erro ao atualizar.';

        if ($ok) {
            header('Location: alunos.php?msg=' . urlencode($msg));
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
        <title>GymControl | Editar Aluno</title>
    </head>
    <body style="background: #f5f5f7;">
        <div class="d-flex">
            <!-- Sidebar -->
            <aside class="sidebar p-3" style="width: 260px;">
                <strong>GymControl</strong>
            </aside>

            <!-- Conteúdo -->
            <main class="flex-grow-1 p-4" style="max-width: 720px;">
                <h3 class="fw-bold">Editar Aluno</h3>

                <?php if ($msg) : ?>
                    <div class="alert alert-info py-2 small"><?= htmlspecialchars((string) $msg, ENT_QUOTES, 'UTF-8') ?></div>
                <?php endif; ?>

                <form method="POST" class="card card-gym border-0 p-4">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Nome</label>
                            <input type="text" name="nome" class="form-control" value="<?= htmlspecialchars((string) $aluno['nome'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">E-mail</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars((string) $aluno['email'], ENT_QUOTES, 'UTF-8') ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Telefone</label>
                            <input type="text" name="telefone" class="form-control" value="<?= htmlspecialchars((string) ($aluno['telefone'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Nascimento</label>
                            <input type="date" name="data_nascimento" class="form-control" value="<?= htmlspecialchars((string) ($aluno['data_nascimento'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Objetivo</label>
                            <input type="text" name="objetivo" class="form-control" value="<?= htmlspecialchars((string) ($aluno['objetivo'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="ativo" <?= $aluno['status'] === 'ativo' ? 'selected' : '' ?>>Ativo</option>
                                <option value="inativo" <?= $aluno['status'] === 'inativo' ? 'selected' : '' ?>>Inativo</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">Professor</label>
                            <input type="text" name="professor" class="form-control" value="<?= htmlspecialchars((string) ($aluno['professor'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                        </div>
                    </div>
                    <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
                    <div class="d-flex gap-2 mt-3">
                        <button class="btn btn-gym">Salvar</button>
                        <a href="alunos.php" class="btn btn-outline-dark">Voltar</a>
                    </div>
                </form>
            </main>
        </div>
    </body>
</html>
