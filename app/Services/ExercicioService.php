<?php
namespace App\Services;
use App\Models\Exercicio;
class ExercicioService
{
    public function cadastrarExercicio(array $dados): Exercicio { return Exercicio::create($dados); }
    public function atualizarExercicio(Exercicio $exercicio, array $dados): bool { return $exercicio->update($dados); }
    public function listarExercicios(?string $busca = null) { $q = Exercicio::query(); if ($busca) { $q->where('nome','like',"%{$busca}%")->orWhere('grupoMuscular','like',"%{$busca}%"); } return $q->orderBy('nome')->paginate(10); }
}
