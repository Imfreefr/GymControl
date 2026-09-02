@extends('layouts.app')
@section('titulo','Detalhe do Treino')
@section('conteudo')
<h3>{{ $treino->nome }}</h3>
<p>Aluno: {{ $treino->aluno->nome }} | Objetivo: {{ $treino->objetivo }}</p>
<p>{{ $treino->observacoes }}</p>
<table class="table table-bordered"><thead><tr><th>#</th><th>Exercício</th><th>Séries</th><th>Reps</th><th>Carga</th><th>Descanso</th></tr></thead>
<tbody>@foreach($treino->treinoExercicios as $te)<tr><td>{{ $te->ordem }}</td><td>{{ $te->exercicio->nome }}</td><td>{{ $te->series }}</td><td>{{ $te->repeticoes }}</td><td>{{ $te->carga }}kg</td><td>{{ $te->descansoSegundos }}s</td></tr>@endforeach</tbody></table>
<a href="/painel-aluno/meu-treino" class="btn btn-secondary">Voltar</a>
@endsection
