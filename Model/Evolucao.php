<?php

namespace Model;

use PDO;

/**
 * Model de Evolução
 *
 * Histórico de peso, altura e observações por aluno.
 */
class Evolucao
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    public function registrar(int $alunoId, ?float $peso, ?float $altura, ?string $obs, string $data): bool
    {
        $sql = "INSERT INTO evolucoes (aluno_id, peso, altura, observacao, data)
                VALUES (:a, :p, :al, :o, :d)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->bindValue(':p', $peso);
        $stmt->bindValue(':al', $altura);
        $stmt->bindValue(':o', $obs);
        $stmt->bindValue(':d', $data);

        return $stmt->execute();
    }

    public function doAluno(int $alunoId): array
    {
        $stmt = $this->db->prepare(
            "SELECT * FROM evolucoes WHERE aluno_id = :a ORDER BY data DESC, id DESC"
        );
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function excluir(int $id, int $alunoId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM evolucoes WHERE id = :id AND aluno_id = :a");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
