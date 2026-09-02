<?php

namespace Controller;

use Model\Treino;

/**
 * Controller de Treinos
 *
 * Cria treinos e vincula exercícios aos alunos.
 */
class TreinoController
{
    private Treino $model;

    public function __construct()
    {
        $this->model = new Treino();
    }

    /**
     * Cria um treino e vincula os exercícios selecionados.
     *
     * @param int   $alunoId     ID do aluno (tabela alunos)
     * @param array $exercicios  Lista de IDs de exercícios
     * @return array [bool $sucesso, string $mensagem]
     */
    public function criar(
        int $alunoId,
        string $nome,
        ?string $objetivo,
        ?string $observacoes,
        ?string $professor,
        array $exercicios
    ): array {
        if (empty(trim($nome))) {
            return [false, 'Nome do treino obrigatório.'];
        }

        if (!$alunoId) {
            return [false, 'Selecione um aluno.'];
        }

        $treinoId = $this->model->criar($alunoId, $nome, $objetivo, $observacoes, $professor);

        foreach ($exercicios as $exercicioId) {
            $this->model->vincularExercicio(
                $treinoId,
                (int) $exercicioId,
                3,
                '12',
                '-',
                '60s'
            );
        }

        return [true, 'Treino criado!'];
    }

    public function doAluno(int $alunoId): array
    {
        return $this->model->doAluno($alunoId);
    }

    public function todos(): array
    {
        return $this->model->todos();
    }

    public function porId(int $id): array|bool
    {
        return $this->model->porId($id);
    }

    public function exercicios(int $treinoId): array
    {
        return $this->model->exerciciosDoTreino($treinoId);
    }

    public function excluir(int $id): bool
    {
        return $this->model->excluir($id);
    }
}
