@extends('layouts.app')
@section('titulo','Treino')
@section('conteudo')
<h3>{{ $treino->nome }}</h3>
<p>Aluno: {{ $treino->aluno->nome }} | Professor: {{ $treino->professor->name ?? '-' }} | Status: {{ $treino->status }}</p>
<p>Objetivo: {{ $treino->objetivo }} | Observações: {{ $treino->observacoes }}</p>
<h5>Exercícios</h5>
<table class="table table-bordered"><thead><tr><th>#</th><th>Exercício</th><th>Séries</th><th>Reps</th><th>Carga</th><th>Descanso</th></tr></thead>
<tbody>@foreach($treino->treinoExercicios as $te)<tr><td>{{ $te->ordem }}</td><td>{{ $te->exercicio->nome }}</td><td>{{ $te->series }}</td><td>{{ $te->repeticoes }}</td><td>{{ $te->carga }}kg</td><td>{{ $te->descansoSegundos }}s</td></tr>@endforeach</tbody></table>
<a href="/treinos" class="btn btn-secondary">Voltar</a>
@endsection
