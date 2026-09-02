<?php

namespace Model;

require_once __DIR__ . '/../Config/configuration.php';

use PDO;
use PDOException;

class Connection
{
    private static ?PDO $instancia = null;

    public static function getInstance(): PDO
    {
        if (self::$instancia !== null) {
            return self::$instancia;
        }

        try {
            $dsn = 'mysql:host=' . DB_HOST . ';port=' . DB_PORT . ';dbname=' . DB_NAME . ';charset=utf8mb4';
            $pdo = new PDO($dsn, DB_USER, DB_PASSWORD, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
            self::$instancia = $pdo;
            return $pdo;
        } catch (PDOException $e) {
            die('Erro conexão: ' . htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8'));
        }
    }
}
