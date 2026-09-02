@extends('layouts.app')
@section('titulo','Frequências')
@section('conteudo')
<h3>Frequência</h3>
<form method="POST" action="/frequencias" class="card p-3 my-3">@csrf
<div class="row g-2">
@if(auth()->user()->perfil==='administrador')
<div class="col-md-4"><label class="form-label">Aluno</label><select name="alunoId" class="form-select" required><option value="">Selecione</option>@foreach($alunos as $a)<option value="{{ $a->id }}">{{ $a->nome }}</option>@endforeach</select></div>
@else
<input type="hidden" name="alunoId" value="{{ auth()->user()->aluno->id ?? '' }}">
@endif
<div class="col-md-3"><label class="form-label">Data</label><input type="date" name="data" value="{{ date('Y-m-d') }}" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Presente</label><select name="presente" class="form-select"><option value="1" selected>Sim</option><option value="0">Não</option></select></div>
<div class="col-md-2 d-flex align-items-end"><button class="btn btn-dark w-100">Registrar</button></div>
</div>
</form>
<table class="table table-hover bg-white"><thead class="table-dark"><tr><th>Aluno</th><th>Data</th><th>Presente</th><th>Ações</th></tr></thead>
<tbody>@forelse($frequencias as $f)<tr><td>{{ $f->aluno->nome }}</td><td>{{ $f->data->format('d/m/Y') }}</td><td>{{ $f->presente ? 'Sim' : 'Não' }}</td><td><form method="POST" action="/frequencias/{{ $f->id }}">@csrf @method('DELETE')<button class="btn btn-sm btn-outline-danger" onclick="return confirm('Remover?')">Excluir</button></form></td></tr>@empty<tr><td colspan="4" class="text-center text-muted">Nenhuma frequência.</td></tr>@endforelse</tbody></table>
{{ $frequencias->links() }}
@endsection
