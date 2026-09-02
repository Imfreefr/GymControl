@extends('layouts.app')
@section('titulo','Meus Treinos')
@section('conteudo')
<h3>Meus Treinos</h3>
@forelse($treinos as $treino)
<div class="card mb-3"><div class="card-body">
<h5>{{ $treino->nome }} <span class="badge bg-secondary">{{ $treino->status }}</span></h5>
<p class="text-muted">{{ $treino->objetivo }}</p>
<ul class="list-group">@foreach($treino->treinoExercicios as $te)<li class="list-group-item">{{ $te->exercicio->nome }} - {{ $te->series }}x{{ $te->repeticoes }} ({{ $te->carga }}kg)</li>@endforeach</ul>
<a href="/painel-aluno/treino/{{ $treino->id }}" class="btn btn-sm btn-dark mt-2">Detalhes</a>
</div></div>
@empty<p class="text-muted">Nenhum treino cadastrado.</p>@endforelse
@endsection
