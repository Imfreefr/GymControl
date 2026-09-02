<?php

namespace Model;

use PDO;

/**
 * Model de Exercício
 *
 * Cadastro e listagem com busca por nome (GET).
 */
class Exercicio
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function criar(array $dados): bool
    {
        $sql = "INSERT INTO exercicios (nome, grupo_muscular, descricao, series, repeticoes, carga, descanso)
                VALUES (:n, :g, :d, :s, :r, :c, :de)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':n', $dados['nome']);
        $stmt->bindValue(':g', $dados['grupo_muscular']);
        $stmt->bindValue(':d', $dados['descricao'] ?? null);
        $stmt->bindValue(':s', (int) ($dados['series'] ?? 3), PDO::PARAM_INT);
        $stmt->bindValue(':r', $dados['repeticoes'] ?? '12');
        $stmt->bindValue(':c', $dados['carga'] ?? '-');
        $stmt->bindValue(':de', $dados['descanso'] ?? '60s');

        return $stmt->execute();
    }

    public function listar(?string $busca = null): array
    {
        if ($busca) {
            $stmt = $this->db->prepare(
                "SELECT * FROM exercicios WHERE nome LIKE :b OR grupo_muscular LIKE :b ORDER BY id DESC"
            );
            $stmt->bindValue(':b', "%$busca%");
            $stmt->execute();

            return $stmt->fetchAll();
        }

        return $this->db->query("SELECT * FROM exercicios ORDER BY id DESC")->fetchAll();
    }

    public function porId(int $id): array|bool
    {
        $stmt = $this->db->prepare("SELECT * FROM exercicios WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch();
    }

    public function total(): int
    {
        return (int) $this->db->query("SELECT COUNT(*) FROM exercicios")->fetchColumn();
    }

    public function excluir(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM exercicios WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
