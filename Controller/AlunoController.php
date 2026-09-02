<?php
namespace Controller;
use Model\Aluno;
use Model\Usuario;
class AlunoController {
    private Aluno $aluno; private Usuario $usuario;
    public function __construct(){ $this->aluno=new Aluno(); $this->usuario=new Usuario(); }
    public function listar(?string $busca=null): array { return $this->aluno->listar($busca); }
    public function porId(int $id): array|bool { return $this->aluno->porId($id); }
    public function porUsuario(int $uid): array|bool { return $this->aluno->porUserId($uid); }
    public function cadastrarAluno(string $nome,string $email,string $telefone,?string $nasc,?string $objetivo,string $status,?string $prof,string $senha='12345678Aa'): array {
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) return [false,'E-mail inválido.'];
        if((new Usuario())->porEmail($email)) return [false,'E-mail já cadastrado.'];
        $hash=password_hash($senha,PASSWORD_ARGON2ID,['memory_cost'=>1<<17,'time_cost'=>4,'threads'=>2]);
        $u=new Usuario(); if(!$u->cadastrar($nome,$email,$hash,'aluno')) return [false,'Erro ao criar usuário.'];
        $uid=$u->ultimoId(); $this->aluno->criar($uid,$telefone,$nasc,$objetivo,$status,$prof);
        return [true,'Aluno cadastrado.'];
    }
    public function atualizar(int $id,array $dados): bool { return $this->aluno->atualizar($id,$dados); }
    public function excluir(int $id): bool { return $this->aluno->excluir($id); }
}
