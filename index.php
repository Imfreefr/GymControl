<?php
if (session_status()===PHP_SESSION_NONE) session_start(); if (empty($_SESSION['csrf'])) $_SESSION['csrf']=bin2hex(random_bytes(32));

/**
 * GymControl - Tela de Login
 *
 * GET  → Exibe o formulário
 * POST → Controller → Model → PDO → Autentica
 *
 * Estrutura inspirada em FitCalc/index.php
 */

require_once 'vendor/autoload.php';

use Controller\UsuarioController;

$controller = new UsuarioController();
$loginMessage = '';

// Mensagem de logout
if (isset($_GET['msg']) && $_GET['msg'] === 'logout') {
    $loginMessage = 'Sessão encerrada.';
}

// Processa login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!(isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) ($_POST['csrf'] ?? null)))) {
        $loginMessage = 'Token inválido.';
    } else {
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';

        if ($controller->login($email, $senha)) {
            // Redireciona conforme o tipo de usuário
            if (($_SESSION['usuario_tipo'] ?? '') === 'admin') {
                header('Location: View/admin/painel_admin.php');
            } else {
                header('Location: View/painel_aluno.php');
            }
            exit;
        }

        $loginMessage = 'E-mail ou senha inválidos!';
    }
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="templates/css/login.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="shortcut icon" href="templates/assets/img/favicon.svg" type="image/x-icon">
    <title>GymControl | Entrar</title>
</head>
<body class="bgLinearGradient">

    <main class="d-flex justify-content-center align-items-center flex-column h-100 p-3">
        <form method="POST" class="bg-light rounded-4">

            <!-- Cabeçalho -->
            <div class="form__create-account d-flex flex-column justify-content-center align-items-center">
                <figure class="bgLinearGradient rounded-circle d-flex justify-content-center align-items-center">
                    <i class="bi bi-lightning-charge-fill fs-4 text-white"></i>
                </figure>
                <h2 class="fw-bold">GymControl</h2>
                <p>Sistema de Gerenciamento de Academia</p>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input
                        type="email"
                        name="email"
                        class="form-control border-start-0 p-2"
                        placeholder="seu@email.com"
                        required
                    >
                </div>
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input
                        type="password"
                        name="senha"
                        class="form-control border-start-0 p-2"
                        placeholder="Sua senha"
                        required
                    >
                </div>
            </div>

            <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">

            <button type="submit" class="bgLinearGradient rounded-3 w-100 mb-3">
                Entrar
            </button>

            <p class="text-center small">
                Não tem conta? <a href="View/register.php">Cadastre-se</a>
            </p>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        <?php if ($loginMessage): ?>
        Toastify({
            text: <?= json_encode($loginMessage) ?>,
            duration: 3000,
            close: true,
            gravity: "bottom",
            position: "right",
            style: { background: "hsl(6 78% 57%)" }
        }).showToast();
        <?php endif; ?>
    </script>

</body>
</html>
