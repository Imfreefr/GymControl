<?php
namespace Controller;
use Model\Usuario;
use Model\Aluno;

class UsuarioController {
    private Usuario $usuarioModel;
    private Aluno $alunoModel;
    public function __construct(){ $this->usuarioModel=new Usuario(); $this->alunoModel=new Aluno(); }

    private function validarSenha(string $senha): bool {
        return (bool)preg_match('/^(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,33}$/', $senha);
    }

    public function cadastrar(string $nome, string $email, string $senha, string $confirma): array {
        $nome=trim($nome);
        if(empty($nome)||empty($email)||empty($senha)) return [false,'Preencha todos os campos.'];
        if(!filter_var($email,FILTER_VALIDATE_EMAIL)) return [false,'E-mail inválido.'];
        if($senha!==$confirma) return [false,'As senhas não conferem.'];
        if(!$this->validarSenha($senha)) return [false,'Senha: 8-33 caracteres, com maiúscula, minúscula e número.'];
        if($this->usuarioModel->porEmail($email)) return [false,'E-mail já cadastrado.'];
        $hash=password_hash($senha,PASSWORD_ARGON2ID,['memory_cost'=>1<<17,'time_cost'=>4,'threads'=>2]);
        $ok=$this->usuarioModel->cadastrar($nome,$email,$hash,'aluno');
        if(!$ok) return [false,'Erro ao criar conta.'];
        $uid=$this->usuarioModel->ultimoId();
        $this->alunoModel->criar($uid,null,null,null,'ativo',null);
        return [true,'Conta criada! Faça login.'];
    }

    public function login(string $email, string $senha): bool {
        $u=$this->usuarioModel->porEmail($email);
        if(!$u || !password_verify($senha,$u['senha'])) return false;
        $_SESSION['usuario_id']=$u['id'];
        $_SESSION['usuario_nome']=$u['nome'];
        $_SESSION['usuario_email']=$u['email'];
        $_SESSION['usuario_tipo']=$u['tipo'];
        return true;
    }

    public function estaLogado(): bool { return isset($_SESSION['usuario_id']); }
    public function ehAdmin(): bool { return ($_SESSION['usuario_tipo']??'')==='admin'; }
    public function logout(): void { session_destroy(); }
}
