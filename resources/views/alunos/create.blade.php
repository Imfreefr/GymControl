@extends('layouts.app')
@section('titulo','Novo Aluno')
@section('conteudo')
<h3>Novo Aluno</h3>
<form method="POST" action="/alunos">@csrf
<div class="row g-3">
<div class="col-md-6"><label class="form-label">Nome *</label><input type="text" name="nome" value="{{ old('nome') }}" class="form-control" required></div>
<div class="col-md-6"><label class="form-label">Email</label><input type="email" name="email" value="{{ old('email') }}" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Telefone</label><input type="text" name="telefone" value="{{ old('telefone') }}" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Data Nascimento</label><input type="date" name="dataNascimento" value="{{ old('dataNascimento') }}" class="form-control"></div>
<div class="col-md-4"><label class="form-label">Status</label><select name="status" class="form-select" required><option value="ativo" selected>ativo</option><option value="inativo">inativo</option></select></div>
<div class="col-md-6"><label class="form-label">Objetivo</label><input type="text" name="objetivo" value="{{ old('objetivo') }}" class="form-control"></div>
<div class="col-12"><label class="form-label">Observações</label><textarea name="observacoes" class="form-control">{{ old('observacoes') }}</textarea></div>
<div class="col-12"><button class="btn btn-dark">Cadastrar</button> <a href="/alunos" class="btn btn-secondary">Voltar</a></div>
</div>
</form>
@endsection
