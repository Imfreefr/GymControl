<?php

namespace Controller;

use Model\Aluno;
use Model\Usuario;

/**
 * Controller de Alunos
 *
 * Intermedia View ↔ Model para o CRUD de alunos.
 */
class AlunoController
{
    private Aluno $aluno;
    private Usuario $usuario;

    public function __construct()
    {
        $this->aluno = new Aluno();
        $this->usuario = new Usuario();
    }

    public function listar(?string $busca = null): array
    {
        return $this->aluno->listar($busca);
    }

    public function porId(int $id): array|bool
    {
        return $this->aluno->porId($id);
    }

    public function porUsuario(int $usuarioId): array|bool
    {
        return $this->aluno->porUserId($usuarioId);
    }

    /**
     * Cadastra um aluno (cria usuário + registro em alunos).
     *
     * @return array [bool $sucesso, string $mensagem]
     */
    public function cadastrarAluno(
        string $nome,
        string $email,
        string $telefone,
        ?string $nascimento,
        ?string $objetivo,
        string $status,
        ?string $professor,
        string $senha = 'Aluno123!'
    ): array {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return [false, 'E-mail inválido.'];
        }

        if ((new Usuario())->porEmail($email)) {
            return [false, 'E-mail já cadastrado.'];
        }

        $hash = password_hash($senha, PASSWORD_ARGON2ID, [
            'memory_cost' => 1 << 17,
            'time_cost'   => 4,
            'threads'     => 2,
        ]);

        $usuarioModel = new Usuario();

        if (!$usuarioModel->cadastrar($nome, $email, $hash, 'aluno')) {
            return [false, 'Erro ao criar usuário.'];
        }

        $usuarioId = $usuarioModel->ultimoId();
        $this->aluno->criar($usuarioId, $telefone, $nascimento, $objetivo, $status, $professor);

        return [true, 'Aluno cadastrado.'];
    }

    public function atualizar(int $id, array $dados): bool
    {
        return $this->aluno->atualizar($id, $dados);
    }

    public function excluir(int $id): bool
    {
        return $this->aluno->excluir($id);
    }
}
