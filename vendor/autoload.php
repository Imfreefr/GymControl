<?php

/**
 * GymControl - Autoloader
 *
 * Registra o autoload PSR-4 simplificado para os namespaces
 * Controller\ e Model\ e carrega a configuração global da aplicação.
 *
 * Fluxo: Autoload -> Mapeamento de prefixos -> Resolução de arquivo -> Config
 */

// ============================================================
// Autoload - Registro de Namespaces
// ============================================================
spl_autoload_register(function ($class) {
    $map = [
        'Controller\\' => __DIR__ . '/../Controller/',
        'Model\\'      => __DIR__ . '/../Model/',
    ];

    foreach ($map as $prefix => $base) {
        if (str_starts_with($class, $prefix)) {
            $rel = substr($class, strlen($prefix));
            $file = $base . str_replace('\\', '/', $rel) . '.php';

            if (file_exists($file)) {
                require $file;
            }

            return;
        }
    }
});

// ============================================================
// Configuração Global
// ============================================================
require_once __DIR__ . '/../Config/configuration.php';
