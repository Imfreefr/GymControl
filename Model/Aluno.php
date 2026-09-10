<?php

namespace Model;

use PDO;

/**
 * Model de Aluno
 *
 * Tabela `alunos` vinculada a `users` (user_id).
 */
class Aluno
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function criar(
        int $userId,
        ?string $telefone,
        ?string $nascimento,
        ?string $objetivo,
        string $status = 'ativo',
        ?string $professor = null
    ): bool {
        $sql = "INSERT INTO alunos (user_id, telefone, data_nascimento, objetivo, status, professor)
                VALUES (:uid, :tel, :nasc, :obj, :st, :prof)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->bindValue(':tel', $telefone);
        $stmt->bindValue(':nasc', $nascimento);
        $stmt->bindValue(':obj', $objetivo);
        $stmt->bindValue(':st', $status);
        $stmt->bindValue(':prof', $professor);

        return $stmt->execute();
    }

    public function porUserId(int $userId): array|bool
    {
        $sql = "SELECT a.*, u.nome, u.email, u.tipo, u.created_at AS user_created
                FROM alunos a
                JOIN users u ON u.id = a.user_id
                WHERE a.user_id = :uid LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':uid', $userId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function porId(int $id): array|bool
    {
        $sql = "SELECT a.*, u.nome, u.email
                FROM alunos a
                JOIN users u ON u.id = a.user_id
                WHERE a.id = :id LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function listar(?string $busca = null): array
    {
        if ($busca) {
            $sql = "SELECT a.*, u.nome, u.email
                    FROM alunos a
                    JOIN users u ON u.id = a.user_id
                    WHERE u.nome LIKE :b OR u.email LIKE :b
                    ORDER BY a.id DESC";

            $stmt = $this->db->prepare($sql);
            $stmt->bindValue(':b', "%$busca%");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        $stmt = $this->db->prepare("SELECT a.*, u.nome, u.email FROM alunos a JOIN users u ON u.id = a.user_id ORDER BY a.id DESC");
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function atualizar(int $id, array $dados): bool
    {
        $sql = "UPDATE alunos
                SET telefone = :tel, data_nascimento = :nasc, objetivo = :obj, status = :st, professor = :prof
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':tel', $dados['telefone'] ?? null);
        $stmt->bindValue(':nasc', $dados['data_nascimento'] ?? null);
        $stmt->bindValue(':obj', $dados['objetivo'] ?? null);
        $stmt->bindValue(':st', $dados['status'] ?? 'ativo');
        $stmt->bindValue(':prof', $dados['professor'] ?? null);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $ok = $stmt->execute();

        // Atualiza também nome/e-mail na tabela users, se enviados
        if (isset($dados['nome']) || isset($dados['email'])) {
            $aluno = $this->porId($id);

            if ($aluno) {
                $stmt2 = $this->db->prepare("UPDATE users SET nome = :n, email = :e WHERE id = :uid");
                $stmt2->bindValue(':n', $dados['nome'] ?? $aluno['nome']);
                $stmt2->bindValue(':e', $dados['email'] ?? $aluno['email']);
                $stmt2->bindValue(':uid', $aluno['user_id'], PDO::PARAM_INT);
                $stmt2->execute();
            }
        }

        return $ok;
    }

    public function excluir(int $id): bool
    {
        $aluno = $this->porId($id);

        if (!$aluno) {
            return false;
        }

        $stmt = $this->db->prepare("DELETE FROM users WHERE id = :uid");
        $stmt->bindValue(':uid', $aluno['user_id'], PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function total(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM alunos");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
