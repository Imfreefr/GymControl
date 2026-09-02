<?php
namespace Controller;
use Model\Exercicio;
class ExercicioController {
    private Exercicio $model;
    public function __construct(){ $this->model=new Exercicio(); }
    public function listar(?string $busca=null): array { return $this->model->listar($busca); }
    public function criar(array $dados): array {
        if(empty(trim($dados['nome']??''))) return [false,'Nome obrigatório.'];
        if(empty(trim($dados['grupo_muscular']??''))) return [false,'Grupo muscular obrigatório.'];
        $ok=$this->model->criar($dados);
        return $ok ? [true,'Exercício cadastrado!'] : [false,'Erro ao cadastrar.'];
    }
    public function excluir(int $id): bool { return $this->model->excluir($id); }
}
