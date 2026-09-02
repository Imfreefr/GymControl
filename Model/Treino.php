<?php
namespace Model;

use Model\Connection;
use PDO;

class Treino {
    private PDO $db;
    public function __construct() { $this->db = Connection::getInstance(); }

    public function criar(int $alunoId, string $nome, ?string $objetivo, ?string $obs, ?string $prof): int {
        $st = $this->db->prepare("INSERT INTO treinos (aluno_id,nome,objetivo,observacoes,professor) VALUES (:a,:n,:o,:obs,:p)");
        $st->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $st->bindValue(':n', $nome);
        $st->bindValue(':o', $objetivo);
        $st->bindValue(':obs', $obs);
        $st->bindValue(':p', $prof);
        $st->execute();
        return (int)$this->db->lastInsertId();
    }

    public function vincularExercicio(int $treinoId, int $exercicioId, int $series, string $rep, string $carga, string $desc): bool {
        $st = $this->db->prepare("INSERT INTO treino_exercicios (treino_id,exercicio_id,series,repeticoes,carga,descanso) VALUES (:t,:e,:s,:r,:c,:d)");
        $st->bindValue(':t', $treinoId, PDO::PARAM_INT);
        $st->bindValue(':e', $exercicioId, PDO::PARAM_INT);
        $st->bindValue(':s', $series, PDO::PARAM_INT);
        $st->bindValue(':r', $rep);
        $st->bindValue(':c', $carga);
        $st->bindValue(':d', $desc);
        return $st->execute();
    }

    public function doAluno(int $alunoId): array {
        $st = $this->db->prepare("SELECT * FROM treinos WHERE aluno_id=:a ORDER BY id DESC");
        $st->bindValue(':a', $alunoId, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function todos(): array {
        return $this->db->query("SELECT t.*, u.nome as aluno_nome FROM treinos t JOIN alunos a ON a.id=t.aluno_id JOIN users u ON u.id=a.user_id ORDER BY t.id DESC")->fetchAll();
    }

    public function porId(int $id): array|bool {
        $st = $this->db->prepare("SELECT t.*, u.nome as aluno_nome FROM treinos t JOIN alunos a ON a.id=t.aluno_id JOIN users u ON u.id=a.user_id WHERE t.id=:id LIMIT 1");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch();
    }

    public function exerciciosDoTreino(int $treinoId): array {
        $st = $this->db->prepare("SELECT te.*, e.nome, e.grupo_muscular, e.descricao FROM treino_exercicios te JOIN exercicios e ON e.id=te.exercicio_id WHERE te.treino_id=:t");
        $st->bindValue(':t', $treinoId, PDO::PARAM_INT);
        $st->execute();
        return $st->fetchAll();
    }

    public function total(): int { return (int)$this->db->query("SELECT COUNT(*) FROM treinos")->fetchColumn(); }

    public function excluir(int $id): bool {
        $st = $this->db->prepare("DELETE FROM treinos WHERE id=:id");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        return $st->execute();
    }
}
