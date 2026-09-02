@extends('layouts.app')
@section('titulo','Painel do Aluno')
@section('conteudo')
<h3><i class="bi bi-speedometer2"></i> Painel do Aluno</h3>
@if(!$aluno)<div class="alert alert-warning">Seu usuário ainda não está vinculado a um aluno.</div>
@else
<div class="row g-3 mt-1">
<div class="col-md-4"><div class="card"><div class="card-body"><h6>Olá, {{ $aluno->nome }}</h6><p class="mb-1">Objetivo: {{ $aluno->objetivo ?? '-' }}</p><p class="mb-0">Status: <span class="badge bg-success">{{ $aluno->status }}</span></p></div></div></div>
<div class="col-md-8"><div class="card"><div class="card-body"><h6>Treinos ativos: {{ $treinos->count() }}</h6><a href="/painel-aluno/meu-treino" class="btn btn-sm btn-dark">Ver meus treinos</a> <a href="/frequencias" class="btn btn-sm btn-outline-dark">Frequência</a> <a href="/evolucoes" class="btn btn-sm btn-outline-dark">Evolução</a></div></div></div>
</div>
<div class="row g-3 mt-2">
<div class="col-md-6"><div class="card"><div class="card-header">Frequências recentes</div><ul class="list-group list-group-flush">@forelse($frequencias as $f)<li class="list-group-item">{{ $f->data->format('d/m/Y') }} - {{ $f->presente ? 'Presente' : 'Falta' }}</li>@empty<li class="list-group-item text-muted">Nenhuma frequência.</li>@endforelse</ul></div></div>
<div class="col-md-6"><div class="card"><div class="card-header">Evoluções recentes</div><ul class="list-group list-group-flush">@forelse($evolucoes as $e)<li class="list-group-item">{{ $e->data->format('d/m/Y') }} - {{ $e->peso ?? '-' }} kg</li>@empty<li class="list-group-item text-muted">Nenhuma evolução.</li>@endforelse</ul></div></div>
</div>
@endif
@endsection
