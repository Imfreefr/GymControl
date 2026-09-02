<?php
namespace Model;
use Model\Connection;
use PDO;
class Evolucao {
    private PDO $db;
    public function __construct(){ $this->db=Connection::getInstance(); }
    public function registrar(int $alunoId, ?float $peso, ?float $altura, ?string $obs, string $data): bool {
        $st=$this->db->prepare("INSERT INTO evolucoes (aluno_id,peso,altura,observacao,data) VALUES (:a,:p,:al,:o,:d)");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->bindValue(':p',$peso); $st->bindValue(':al',$altura); $st->bindValue(':o',$obs); $st->bindValue(':d',$data);
        return $st->execute();
    }
    public function doAluno(int $alunoId): array {
        $st=$this->db->prepare("SELECT * FROM evolucoes WHERE aluno_id=:a ORDER BY data DESC, id DESC");
        $st->bindValue(':a',$alunoId,PDO::PARAM_INT); $st->execute(); return $st->fetchAll();
    }
    public function excluir(int $id, int $alunoId): bool {
        $st=$this->db->prepare("DELETE FROM evolucoes WHERE id=:id AND aluno_id=:a");
        $st->bindValue(':id',$id,PDO::PARAM_INT); $st->bindValue(':a',$alunoId,PDO::PARAM_INT); return $st->execute();
    }
}
