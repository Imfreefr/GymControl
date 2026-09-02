<?php

/**
 * GymControl - Configuração Global
 *
 * Define constantes do banco e helpers de segurança.
 * Baseado na simplicidade do FitCalc.zip (Config/configuration.php).
 */

// ---------------------------------------------------------------------
// Banco de Dados
// ---------------------------------------------------------------------
define("DB_NAME", getenv("DB_NAME") ?: "gymcontrol");
define("DB_USER", getenv("DB_USER") ?: "root");
define("DB_PASSWORD", getenv("DB_PASSWORD") ?: "");
define("DB_HOST", getenv("DB_HOST") ?: "localhost");
define("DB_PORT", getenv("DB_PORT") ?: "3306");

// ---------------------------------------------------------------------
// Aplicação
// ---------------------------------------------------------------------
define("APP_NAME", "GymControl");
define("APP_URL", getenv("APP_URL") ?: "http://localhost:8000");

// ---------------------------------------------------------------------
// Sessão
// ---------------------------------------------------------------------
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// ---------------------------------------------------------------------
// Helpers de Segurança
// ---------------------------------------------------------------------

/**
 * Gera ou retorna o token CSRF da sessão.
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }

    return $_SESSION['csrf'];
}

/**
 * Valida o token CSRF recebido do formulário.
 */
function csrf_validar(?string $token): bool
{
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], (string) $token);
}

/**
 * Escapa saída HTML (proteção contra XSS).
 */
function e(mixed $valor): string
{
    return htmlspecialchars((string) $valor, ENT_QUOTES, 'UTF-8');
}

/**
 * Exige que o usuário esteja logado.
 */
function exigirLogin(): void
{
    if (empty($_SESSION['usuario_id'])) {
        header('Location: ../index.php?msg=login');
        exit;
    }
}

/**
 * Exige que o usuário seja administrador.
 */
function exigirAdmin(): void
{
    exigirLogin();

    if (($_SESSION['usuario_tipo'] ?? '') !== 'admin') {
        header('Location: ../View/painel_aluno.php');
        exit;
    }
}
