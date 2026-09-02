@extends('layouts.app')
@section('titulo','Editar Treino')
@section('conteudo')
<h3>Editar Treino</h3>
<form method="POST" action="/treinos/{{ $treino->id }}">@csrf @method('PUT')
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Aluno *</label><select name="alunoId" class="form-select" required>@foreach($alunos as $a)<option value="{{ $a->id }}" {{ $treino->alunoId==$a->id?'selected':'' }}>{{ $a->nome }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" value="{{ old('nome',$treino->nome) }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Objetivo</label><input type="text" name="objetivo" value="{{ old('objetivo',$treino->objetivo) }}" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="ativo" {{ $treino->status==='ativo'?'selected':'' }}>ativo</option><option value="inativo" {{ $treino->status==='inativo'?'selected':'' }}>inativo</option></select></div>
<div class="col-12"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control">{{ old('observacoes',$treino->observacoes) }}</textarea></div>
<div class="col-12"><h5>Exercícios (recria lista)</h5>
@foreach($exercicios as $idx=>$ex)
<div class="border p-2 mb-2">
<label><input type="checkbox" name="exercicios[{{ $idx }}][exercicioId]" value="{{ $ex->id }}"> {{ $ex->nome }}</label>
<input type="number" name="exercicios[{{ $idx }}][series]" value="{{ $ex->series }}" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][repeticoes]" value="{{ $ex->repeticoes }}" class="form-control form-control-sm d-inline w-auto"> <input type="number" step="0.01" name="exercicios[{{ $idx }}][carga]" value="{{ $ex->carga }}" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][descansoSegundos]" value="{{ $ex->descansoSegundos }}" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][ordem]" value="{{ $idx }}" class="form-control form-control-sm d-inline w-auto">
</div>
@endforeach
</div>
<div class="col-12"><button class="btn btn-dark">Salvar</button> <a href="/treinos" class="btn btn-secondary">Voltar</a></div>
</div>
</form>
@endsection
