<?php

namespace Model;

use PDO;

/**
 * Model de Frequência
 *
 * Registra presenças por aluno e data.
 */
class Frequencia
{
    private PDO $db;

    public function __construct()
    {
        $this->db = Connection::getInstance();
    }

    /**
     * Registra ou atualiza presença.
     */
    public function registrar(int $alunoId, string $data, int $presente = 1): bool
    {
        $sql = "INSERT INTO frequencias (aluno_id, data, presente)
                VALUES (:a, :d, :p)
                ON DUPLICATE KEY UPDATE presente = VALUES(presente)";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->bindValue(':d', $data);
        $stmt->bindValue(':p', $presente, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function doAluno(int $alunoId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM frequencias WHERE aluno_id = :a ORDER BY data DESC");
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll();
    }

    public function totalPresencas(int $alunoId): int
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM frequencias WHERE aluno_id = :a AND presente = 1");
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }

    public function doMes(int $alunoId, string $mes): int
    {
        $stmt = $this->db->prepare(
            "SELECT COUNT(*) FROM frequencias
              WHERE aluno_id = :a AND presente = 1 AND DATE_FORMAT(data, '%Y-%m') = :m"
        );
        $stmt->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $stmt->bindValue(':m', $mes);
        $stmt->execute();

        return (int) $stmt->fetchColumn();
    }
}
