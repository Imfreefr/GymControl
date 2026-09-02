<?php
namespace App\Services;
use App\Models\Aluno;
class AlunoService
{
    public function cadastrarAluno(array $dados): Aluno { return Aluno::create($dados); }
    public function atualizarAluno(Aluno $aluno, array $dados): bool { return $aluno->update($dados); }
    public function listarAlunos(string $busca = null) { $q = Aluno::query(); if ($busca) { $q->where('nome','like',"%{$busca}%")->orWhere('email','like',"%{$busca}%"); } return $q->orderBy('nome')->paginate(10); }
    public function buscarAlunoPorUsuario(int $usuarioId): ?Aluno { return Aluno::where('usuarioId', $usuarioId)->first(); }
}
