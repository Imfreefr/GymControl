<?php

namespace Model;

use PDO;

/**
 * Model de Treino
 *
 * Treino pertence a um aluno e possui vários exercícios (N:N via treino_exercicios).
 */
class Treino
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function criar(int $alunoId, string $nome, ?string $objetivo, ?string $obs, ?string $prof): int
    {
        $sql = "INSERT INTO treinos (aluno_id, nome, objetivo, observacoes, professor)
                VALUES (:a, :n, :o, :obs, :p)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->bindValue(':n', $nome);
        $stmt->bindValue(':o', $objetivo);
        $stmt->bindValue(':obs', $obs);
        $stmt->bindValue(':p', $prof);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    public function vincularExercicio(
        int $treinoId,
        int $exercicioId,
        int $series,
        string $rep,
        string $carga,
        string $desc
    ): bool {
        $sql = "INSERT INTO treino_exercicios (treino_id, exercicio_id, series, repeticoes, carga, descanso)
                VALUES (:t, :e, :s, :r, :c, :d)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':t', $treinoId, PDO::PARAM_INT);
        $stmt->bindValue(':e', $exercicioId, PDO::PARAM_INT);
        $stmt->bindValue(':s', $series, PDO::PARAM_INT);
        $stmt->bindValue(':r', $rep);
        $stmt->bindValue(':c', $carga);
        $stmt->bindValue(':d', $desc);

        return $stmt->execute();
    }

    public function doAluno(int $alunoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM treinos WHERE aluno_id = :a ORDER BY id DESC");
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function todos(): array
    {
        $stmt = $this->db->prepare(
            "SELECT t.*, u.nome AS aluno_nome
             FROM treinos t
             JOIN alunos a ON a.id = t.aluno_id
             JOIN users u ON u.id = a.user_id
             ORDER BY t.id DESC"
        );
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function porId(int $id): array|bool
    {
        $stmt = $this->db->prepare(
            "SELECT t.*, u.nome AS aluno_nome
             FROM treinos t
             JOIN alunos a ON a.id = t.aluno_id
             JOIN users u ON u.id = a.user_id
             WHERE t.id = :id LIMIT 1"
        );
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function exerciciosDoTreino(int $treinoId): array
    {
        $sql = "SELECT te.*, e.nome, e.grupo_muscular, e.descricao
                FROM treino_exercicios te
                JOIN exercicios e ON e.id = te.exercicio_id
                WHERE te.treino_id = :t";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':t', $treinoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function total(): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM treinos");
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM treinos WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
