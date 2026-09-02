<?php
namespace Model;

use Model\Connection;
use PDO;

class Aluno {
    private PDO $db;
    public function __construct() { $this->db = Connection::getInstance(); }

    public function criar(int $userId, ?string $telefone, ?string $nascimento, ?string $objetivo, string $status = 'ativo', ?string $professor = null): bool {
        $st = $this->db->prepare("INSERT INTO alunos (user_id,telefone,data_nascimento,objetivo,status,professor) VALUES (:uid,:tel,:nasc,:obj,:st,:prof)");
        $st->bindValue(':uid', $userId, PDO::PARAM_INT);
        $st->bindValue(':tel', $telefone);
        $st->bindValue(':nasc', $nascimento);
        $st->bindValue(':obj', $objetivo);
        $st->bindValue(':st', $status);
        $st->bindValue(':prof', $professor);
        return $st->execute();
    }

    public function porUserId(int $userId): array|bool {
        $st = $this->db->prepare("SELECT a.*, u.nome, u.email, u.tipo, u.created_at as user_created FROM alunos a JOIN users u ON u.id=a.user_id WHERE a.user_id=:uid LIMIT 1");
        $st->bindValue(':uid', $userId, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch();
    }

    public function porId(int $id): array|bool {
        $st = $this->db->prepare("SELECT a.*, u.nome, u.email FROM alunos a JOIN users u ON u.id=a.user_id WHERE a.id=:id LIMIT 1");
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $st->execute();
        return $st->fetch();
    }

    public function listar(?string $busca = null): array {
        if ($busca) {
            $st = $this->db->prepare("SELECT a.*, u.nome, u.email FROM alunos a JOIN users u ON u.id=a.user_id WHERE u.nome LIKE :b OR u.email LIKE :b ORDER BY a.id DESC");
            $st->bindValue(':b', "%$busca%");
            $st->execute();
            return $st->fetchAll();
        }
        return $this->db->query("SELECT a.*, u.nome, u.email FROM alunos a JOIN users u ON u.id=a.user_id ORDER BY a.id DESC")->fetchAll();
    }

    public function atualizar(int $id, array $dados): bool {
        $sql = "UPDATE alunos SET telefone=:tel, data_nascimento=:nasc, objetivo=:obj, status=:st, professor=:prof WHERE id=:id";
        $st = $this->db->prepare($sql);
        $st->bindValue(':tel', $dados['telefone'] ?? null);
        $st->bindValue(':nasc', $dados['data_nascimento'] ?? null);
        $st->bindValue(':obj', $dados['objetivo'] ?? null);
        $st->bindValue(':st', $dados['status'] ?? 'ativo');
        $st->bindValue(':prof', $dados['professor'] ?? null);
        $st->bindValue(':id', $id, PDO::PARAM_INT);
        $ok = $st->execute();
        if (isset($dados['nome']) || isset($dados['email'])) {
            $a = $this->porId($id);
            if ($a) {
                $st2 = $this->db->prepare("UPDATE users SET nome=:n, email=:e WHERE id=:uid");
                $st2->bindValue(':n', $dados['nome'] ?? $a['nome']);
                $st2->bindValue(':e', $dados['email'] ?? $a['email']);
                $st2->bindValue(':uid', $a['user_id'], PDO::PARAM_INT);
                $st2->execute();
            }
        }
        return $ok;
    }

    public function excluir(int $id): bool {
        $a = $this->porId($id);
        if (!$a) return false;
        $st = $this->db->prepare("DELETE FROM users WHERE id=:uid");
        $st->bindValue(':uid', $a['user_id'], PDO::PARAM_INT);
        return $st->execute();
    }

    public function total(): int {
        return (int)$this->db->query("SELECT COUNT(*) FROM alunos")->fetchColumn();
    }
}
