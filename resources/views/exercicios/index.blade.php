@extends('layouts.app')
@section('titulo','Exercícios')
@section('conteudo')
<div class="d-flex justify-content-between"><h3>Exercícios</h3>@if(auth()->user()->perfil==='administrador')<a href="/exercicios/create" class="btn btn-dark">Novo</a>@endif</div>
<form method="GET" action="/exercicios" class="my-3"><div class="input-group"><input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome ou grupo muscular" class="form-control"><button class="btn btn-outline-secondary">Buscar</button></div></form>
<div class="row g-3">@forelse($exercicios as $e)<div class="col-md-4"><div class="card h-100"><div class="card-body"><h6>{{ $e->nome }}</h6><small class="text-muted">{{ $e->grupoMuscular }}</small><p class="small">{{ $e->descricao }}</p><span class="badge bg-secondary">{{ $e->series }}x{{ $e->repeticoes }}</span> <span class="badge bg-dark">{{ $e->carga }}kg</span><div class="mt-2"><a href="/exercicios/{{ $e->id }}" class="btn btn-sm btn-outline-primary">Ver</a>@if(auth()->user()->perfil==='administrador') <a href="/exercicios/{{ $e->id }}/edit" class="btn btn-sm btn-outline-dark">Editar</a> <form method="POST" action="/exercicios/{{ $e->id }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover?')">Excluir</button></form>@endif</div></div></div></div>@empty<p class="text-muted">Nenhum exercício.</p>@endforelse</div>
<div class="mt-3">{{ $exercicios->withQueryString()->links() }}</div>
@endsection
