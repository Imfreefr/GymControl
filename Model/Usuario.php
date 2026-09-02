<?php

namespace Model;

use PDO;
use PDOException;

/**
 * Model de Usuário
 *
 * Acesso à tabela `users` com prepared statements (PDO).
 */
class Usuario
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Cadastra um novo usuário.
     */
    public function cadastrar(string $nome, string $email, string $senhaHash, string $tipo = 'aluno'): bool
    {
        try {
            $sql = "INSERT INTO users (nome, email, senha, tipo)
                    VALUES (:nome, :email, :senha, :tipo)";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':nome', $nome, PDO::PARAM_STR);
            $stmt->bindValue(':email', $email, PDO::PARAM_STR);
            $stmt->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
            $stmt->bindValue(':tipo', $tipo, PDO::PARAM_STR);

            return $stmt->execute();
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    /**
     * Busca usuário por e-mail (usado no login).
     */
    public function porEmail(string $email): array|bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function porId(int $id): array|bool
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function ultimoId(): int
    {
        return (int) $this->db->lastInsertId();
    }
}
