@extends('layouts.app')
@section('titulo','Exercício')
@section('conteudo')
<h3>{{ $exercicio->nome }}</h3>
<p>Grupo: {{ $exercicio->grupoMuscular ?? '-' }} | Status: {{ $exercicio->status }}</p>
<p>{{ $exercicio->descricao }}</p>
<p>Séries: {{ $exercicio->series }} | Repetições: {{ $exercicio->repeticoes }} | Carga: {{ $exercicio->carga }}kg | Descanso: {{ $exercicio->descansoSegundos }}s</p>
<a href="/exercicios" class="btn btn-secondary">Voltar</a>
@endsection
