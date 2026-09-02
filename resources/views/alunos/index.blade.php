@extends('layouts.app')
@section('titulo','Alunos')
@section('conteudo')
<div class="d-flex justify-content-between align-items-center"><h3>Alunos</h3><a href="/alunos/create" class="btn btn-dark">Novo Aluno</a></div>
<form method="GET" action="/alunos" class="my-3"><div class="input-group"><input type="text" name="busca" value="{{ request('busca') }}" placeholder="Buscar por nome ou email" class="form-control"><button class="btn btn-outline-secondary">Buscar</button></div></form>
<table class="table table-hover bg-white shadow-sm"><thead class="table-dark"><tr><th>Nome</th><th>Email</th><th>Status</th><th>Ações</th></tr></thead>
<tbody>@forelse($alunos as $a)<tr><td>{{ $a->nome }}</td><td>{{ $a->email }}</td><td><span class="badge {{ $a->status==='ativo' ? 'bg-success' : 'bg-secondary' }}">{{ $a->status }}</span></td><td><a href="/alunos/{{ $a->id }}" class="btn btn-sm btn-outline-primary">Ver</a> <a href="/alunos/{{ $a->id }}/edit" class="btn btn-sm btn-outline-dark">Editar</a> <form method="POST" action="/alunos/{{ $a->id }}" class="d-inline">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover?')">Excluir</button></form></td></tr>@empty<tr><td colspan="4" class="text-center text-muted">Nenhum aluno.</td></tr>@endforelse</tbody></table>
{{ $alunos->withQueryString()->links() }}
@endsection
