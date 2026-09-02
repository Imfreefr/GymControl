<?php
namespace Model;

use Model\Connection;
use PDO;
use PDOException;

class Usuario {
    private PDO $db;
    public function __construct() { $this->db = Connection::getInstance(); }

    public function cadastrar(string $nome, string $email, string $senhaHash, string $tipo = 'aluno'): bool {
        try {
            $sql = "INSERT INTO users (nome,email,senha,tipo) VALUES (:nome,:email,:senha,:tipo)";
            $st = $this->db->prepare($sql);
            $st->bindValue(':nome', $nome, PDO::PARAM_STR);
            $st->bindValue(':email', $email, PDO::PARAM_STR);
            $st->bindValue(':senha', $senhaHash, PDO::PARAM_STR);
            $st->bindValue(':tipo', $tipo, PDO::PARAM_STR);
            return $st->execute();
        } catch (PDOException $e) { error_log($e->getMessage()); return false; }
    }

    public function porEmail(string $email): array|bool {
        $st = $this->db->prepare("SELECT * FROM users WHERE email=:email LIMIT 1");
        $st->bindValue(':email', $email, PDO::PARAM_STR);
        $st->execute();
        return $st->fetch();
    }

    public function porId(int $id): array|bool {
        $st = $this->db->prepare("SELECT * FROM users WHERE id=:id LIMIT 1");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch();
    }

    public function ultimoId(): int { return (int)$this->db->lastInsertId(); }
}
