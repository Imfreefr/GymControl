@extends('layouts.app')
@section('titulo','Treinos')
@section('conteudo')
<div class="d-flex justify-content-between"><h3>Treinos</h3>@if(auth()->user()->perfil==='administrador')<a href="/treinos/create" class="btn btn-dark">Novo Treino</a>@endif</div>
<table class="table table-hover bg-white shadow-sm mt-3"><thead class="table-dark"><tr><th>Nome</th><th>Aluno</th><th>Status</th><th>Ações</th></tr></thead>
<tbody>@forelse($treinos as $t)<tr><td>{{ $t->nome }}</td><td>{{ $t->aluno->nome ?? '-' }}</td><td><span class="badge {{ $t->status==='ativo'?'bg-success':'bg-secondary' }}">{{ $t->status }}</span></td><td><a href="/treinos/{{ $t->id }}" class="btn btn-sm btn-outline-primary">Ver</a>@if(auth()->user()->perfil==='administrador') <a href="/treinos/{{ $t->id }}/edit" class="btn btn-sm btn-outline-dark">Editar</a> <form method="POST" action="/treinos/{{ $t->id }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover?')">Excluir</button></form>@endif</td></tr>@empty<tr><td colspan="4" class="text-center text-muted">Nenhum treino.</td></tr>@endforelse</tbody></table>
{{ $treinos->links() }}
@endsection
