@extends('layouts.app')
@section('titulo','Novo Treino')
@section('conteudo')
<h3>Novo Treino</h3>
<form method="POST" action="/treinos">@csrf
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Aluno *</label><select name="alunoId" class="form-select" required><option value="">Selecione</option>@foreach($alunos as $a)<option value="{{ $a->id }}">{{ $a->nome }}</option>@endforeach</select></div>
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" value="{{ old('nome') }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Objetivo</label><input type="text" name="objetivo" value="{{ old('objetivo') }}" class="form-control"></div>
<div class="col-md-6"><label class="form-label">Status</label><select name="status" class="form-select"><option value="ativo" selected>ativo</option><option value="inativo">inativo</option></select></div>
<div class="col-12"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control">{{ old('observacoes') }}</textarea></div>
<div class="col-12"><h5>Exercícios</h5><p class="text-muted small">Adicione exercicios já cadastrados. Deixe vazio se quiser criar o treino sem exercícios.</p>
@foreach($exercicios as $idx=>$ex)
<div class="border p-2 mb-2">
<label><input type="checkbox" name="exercicios[{{ $idx }}][exercicioId]" value="{{ $ex->id }}"> {{ $ex->nome }} ({{ $ex->grupoMuscular }})</label>
<input type="number" name="exercicios[{{ $idx }}][series]" value="{{ $ex->series }}" placeholder="Séries" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][repeticoes]" value="{{ $ex->repeticoes }}" placeholder="Reps" class="form-control form-control-sm d-inline w-auto"> <input type="number" step="0.01" name="exercicios[{{ $idx }}][carga]" value="{{ $ex->carga }}" placeholder="Carga" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][descansoSegundos]" value="{{ $ex->descansoSegundos }}" placeholder="Descanso" class="form-control form-control-sm d-inline w-auto"> <input type="number" name="exercicios[{{ $idx }}][ordem]" value="{{ $idx }}" placeholder="Ordem" class="form-control form-control-sm d-inline w-auto">
</div>
@endforeach
</div>
<div class="col-12"><button class="btn btn-dark">Cadastrar</button> <a href="/treinos" class="btn btn-secondary">Voltar</a></div>
</div>
</form>
@endsection
