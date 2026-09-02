@extends('layouts.app')
@section('titulo','Novo Exercício')
@section('conteudo')
<h3>Novo Exercício</h3>
<form method="POST" action="/exercicios">@csrf
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" value="{{ old('nome') }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Grupo Muscular</label><input type="text" name="grupoMuscular" value="{{ old('grupoMuscular') }}" class="form-control"></div>
<div class="col-12"><label class="form-label">Descrição</label><textarea name="descricao" class="form-control">{{ old('descricao') }}</textarea></div>
<div class="col-md-3"><label class="form-label">Séries</label><input type="number" name="series" value="{{ old('series',3) }}" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Repetições</label><input type="number" name="repeticoes" value="{{ old('repeticoes',12) }}" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Carga (kg)</label><input type="number" step="0.01" name="carga" value="{{ old('carga',0) }}" class="form-control" required></div>
<div class="col-md-3"><label class="form-label">Descanso (s)</label><input type="number" name="descansoSegundos" value="{{ old('descansoSegundos',60) }}" class="form-control" required></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select"><option value="ativo" selected>ativo</option><option value="inativo">inativo</option></select></div>
<div class="col-12"><button class="btn btn-dark">Cadastrar</button> <a href="/exercicios" class="btn btn-secondary">Voltar</a></div>
</div>
</form>
@endsection
