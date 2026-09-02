<?php
namespace Model;
use Model\Connection;
use PDO;
class Frequencia {
    private PDO $db;
    public function __construct(){ $this->db=Connection::getInstance(); }
    public function registrar(int $alunoId, string $data, int $presente=1): bool {
        $st=$this->db->prepare("INSERT INTO frequencias (aluno_id,data,presente) VALUES (:a,:d,:p) ON CONFLICT(aluno_id,data) DO UPDATE SET presente=:p");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->bindValue(':d',$data); $st->bindValue(':p',$presente,PDO::PARAM_INT);
        return $st->execute();
    }
    public function doAluno(int $alunoId): array {
        $st=$this->db->prepare("SELECT * FROM frequencias WHERE aluno_id=:a ORDER BY data DESC");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->execute(); return $st->fetchAll();
    }
    public function totalPresencas(int $alunoId): int {
        $st=$this->db->prepare("SELECT COUNT(*) FROM frequencias WHERE aluno_id=:a AND presente=1");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->execute(); return (int)$st->fetchColumn();
    }
    public function doMes(int $alunoId, string $mes): int {
        $st=$this->db->prepare("SELECT COUNT(*) FROM frequencias WHERE aluno_id=:a AND presente=1 AND strftime('%Y-%m',data)=:m");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->bindValue(':m',$mes); $st->execute(); return (int)$st->fetchColumn();
    }
}
