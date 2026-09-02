<?php
namespace Controller;
use Model\Treino;
class TreinoController {
    private Treino $model;
    public function __construct(){ $this->model=new Treino(); }
    public function criar(int $alunoId,string $nome,?string $obj,?string $obs,?string $prof,array $exercicios): array {
        if(empty(trim($nome))) return [false,'Nome do treino obrigatório.'];
        if(!$alunoId) return [false,'Selecione um aluno.'];
        $id=$this->model->criar($alunoId,$nome,$obj,$obs,$prof);
        foreach($exercicios as $exId){
            $this->model->vincularExercicio($id,(int)$exId,3,'12','-','60s');
        }
        return [true,'Treino criado!'];
    }
    public function doAluno(int $alunoId): array { return $this->model->doAluno($alunoId); }
    public function todos(): array { return $this->model->todos(); }
    public function porId(int $id): array|bool { return $this->model->porId($id); }
    public function exercicios(int $treinoId): array { return $this->model->exerciciosDoTreino($treinoId); }
    public function excluir(int $id): bool { return $this->model->excluir($id); }
}
