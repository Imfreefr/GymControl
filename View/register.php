<?php

/**
 * GymControl - Cadastro de Usuário
 *
 * POST → Controller → Validação → Model → PDO → Redirect
 */

require_once '../vendor/autoload.php';

use Controller\UsuarioController;

$controller = new UsuarioController();
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_validar($_POST['csrf'] ?? null)) {
        $msg = 'Token inválido.';
    } else {
        $nome  = filter_var($_POST['nome'] ?? '', FILTER_SANITIZE_SPECIAL_CHARS);
        $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
        $senha = $_POST['senha'] ?? '';
        $conf  = $_POST['confirma'] ?? '';

        [$ok, $ret] = $controller->cadastrar($nome, $email, $senha, $conf);
        $msg = $ret;

        if ($ok) {
            header('Location: ../index.php');
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="../templates/css/register.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/toastify-js/src/toastify.min.css">
    <link rel="shortcut icon" href="../templates/assets/img/favicon.svg" type="image/x-icon">
    <title>GymControl | Criar Conta</title>
</head>
<body class="bgLinearGradient">

    <main class="d-flex justify-content-center align-items-center flex-column h-100 p-3">
        <form method="POST" class="bg-light p-4 rounded-4">

            <div class="form__create-account d-flex flex-column justify-content-center align-items-center">
                <figure class="rounded-circle d-flex justify-content-center align-items-center bgLinearGradient">
                    <i class="bi bi-lightning-charge-fill fs-5 text-white"></i>
                </figure>
                <h2 class="fw-bold">Criar Conta</h2>
                <p>Preencha seus dados</p>
            </div>

            <!-- Nome -->
            <div class="mb-3">
                <label class="form-label">Nome completo</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-person"></i>
                    </span>
                    <input type="text" name="nome" class="form-control border-start-0 p-2" placeholder="Seu nome completo" required>
                </div>
            </div>

            <!-- E-mail -->
            <div class="mb-3">
                <label class="form-label">E-mail</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-envelope"></i>
                    </span>
                    <input type="email" name="email" class="form-control border-start-0 p-2" placeholder="seu@email.com" required>
                </div>
            </div>

            <!-- Senha -->
            <div class="mb-3">
                <label class="form-label">Senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="senha" class="form-control border-start-0 p-2" placeholder="Mín. 8, com maiúscula e número" required>
                </div>
            </div>

            <!-- Confirmar senha -->
            <div class="mb-3">
                <label class="form-label">Confirmar senha</label>
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0">
                        <i class="bi bi-lock"></i>
                    </span>
                    <input type="password" name="confirma" class="form-control border-start-0 p-2" placeholder="Confirme sua senha" required>
                </div>
            </div>

            <input type="hidden" name="csrf" value="<?= e(csrf_token()) ?>">

            <button type="submit" class="border-0 text-white rounded-3 w-100 mb-3 p-2 bgLinearGradient">
                Cadastrar
            </button>

            <p class="text-center small">
                Já tem conta? <a href="../index.php">Faça login</a>
            </p>

        </form>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/toastify-js"></script>
    <script>
        <?php if (!empty($msg)): ?>
        Toastify({
            text: <?= json_encode(strip_tags($msg)) ?>,
            duration: 3500,
            close: true,
            gravity: "bottom",
            position: "right",
            style: { background: "hsl(6 78% 57%)" }
        }).showToast();
        <?php endif; ?>
    </script>

</body>
</html>
