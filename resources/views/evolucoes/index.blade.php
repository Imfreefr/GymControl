@extends('layouts.app')
@section('titulo','Evolução')
@section('conteudo')
<h3>Evolução</h3>
<form method="POST" action="/evolucoes" class="card p-3 my-3">@csrf
<div class="row g-2">
@if(auth()->user()->perfil==='administrador')
<div class="col-md-3"><label class="form-label">Aluno</label><select name="alunoId" class="form-select" required><option value="">Selecione</option>@foreach($alunos as $a)<option value="{{ $a->id }}">{{ $a->nome }}</option>@endforeach</select></div>
@else
<input type="hidden" name="alunoId" value="{{ auth()->user()->aluno->id ?? '' }}">
@endif
<div class="col-md-2"><label class="form-label">Data</label><input type="date" name="data" value="{{ date('Y-m-d') }}" class="form-control" required></div>
<div class="col-md-2"><label class="form-label">Peso (kg)</label><input type="number" step="0.01" name="peso" class="form-control"></div>
<div class="col-md-2"><label class="form-label">Altura (m)</label><input type="number" step="0.01" name="altura" class="form-control"></div>
<div class="col-md-3"><label class="form-label">Observação</label><input type="text" name="observacao" class="form-control"></div>
<div class="col-12"><button class="btn btn-dark">Registrar</button></div>
</div>
</form>
<table class="table table-hover bg-white"><thead class="table-dark"><tr><th>Aluno</th><th>Data</th><th>Peso</th><th>Altura</th><th>Obs</th><th>Ações</th></tr></thead>
<tbody>@forelse($evolucoes as $e)<tr><td>{{ $e->aluno->nome }}</td><td>{{ $e->data->format('d/m/Y') }}</td><td>{{ $e->peso }}</td><td>{{ $e->altura }}</td><td>{{ $e->observacao }}</td><td><form method="POST" action="/evolucoes/{{ $e->id }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover?')">Excluir</button></form></td></tr>@empty<tr><td colspan="6" class="text-center text-muted">Nenhuma evolução.</td></tr>@endforelse</tbody></table>
{{ $evolucoes->links() }}
@endsection
