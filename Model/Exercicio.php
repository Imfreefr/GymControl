<?php
namespace Model;

use Model\Connection;
use PDO;

class Exercicio {
    private PDO $db;
    public function __construct() { $this->db = Connection::getInstance(); }

    public function criar(array $d): bool {
        $st = $this->db->prepare("INSERT INTO exercicios (nome,grupo_muscular,descricao,series,repeticoes,carga,descanso) VALUES (:n,:g,:d,:s,:r,:c,:de)");
        $st->bindValue(':n', $d['nome']);
        $st->bindValue(':g', $d['grupo_muscular']);
        $st->bindValue(':d', $d['descricao'] ?? null);
        $st->bindValue(':s', (int)($d['series'] ?? 3), PDO::PARAM_INT);
        $st->bindValue(':r', $d['repeticoes'] ?? '12');
        $st->bindValue(':c', $d['carga'] ?? '-');
        $st->bindValue(':de', $d['descanso'] ?? '60s');
        return $st->execute();
    }

    public function listar(?string $busca = null): array {
        if ($busca) {
            $st = $this->db->prepare("SELECT * FROM exercicios WHERE nome LIKE :b OR grupo_muscular LIKE :b ORDER BY id DESC");
            $st->bindValue(':b', "%$busca%");
            $st->execute();
            return $st->fetchAll();
        }
        return $this->db->query("SELECT * FROM exercicios ORDER BY id DESC")->fetchAll();
    }

    public function porId(int $id): array|bool {
        $st = $this->db->prepare("SELECT * FROM exercicios WHERE id=:id LIMIT 1");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch();
    }

    public function total(): int { return (int)$this->db->query("SELECT COUNT(*) FROM exercicios")->fetchColumn(); }

    public function excluir(int $id): bool {
        $st = $this->db->prepare("DELETE FROM exercicios WHERE id=:id");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }
}
