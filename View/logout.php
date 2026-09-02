<?php
if (session_status()===PHP_SESSION_NONE) session_start();

/**
 * GymControl - Logout
 *
 * Encerra a sessão do utilizador de forma segura: limpa o array
 * de sessão, remove o cookie de sessão quando aplicável, destrói
 * a sessão no servidor e redireciona para a página inicial.
 */

// Autenticação
require_once '../vendor/autoload.php';

// Processamento - Encerramento de Sessão

// Limpa todos os dados da sessão atual.
$_SESSION = [];

// Remove o cookie de sessão, se cookies estiverem habilitados.
if (ini_get('session.use_cookies')) {
    $p = session_get_cookie_params();

    setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
}

// Destrói a sessão no servidor.
session_destroy();

// Redireciona para a página inicial com mensagem de logout.
header('Location: ../index.php?msg=logout');
exit;
