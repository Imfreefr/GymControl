@extends('layouts.app')
@section('titulo','GymControl - Início')
@section('conteudo')
<div class="row align-items-center py-5">
<div class="col-lg-6">
<h1 class="display-5 fw-bold"><i class="bi bi-lightning-charge-fill text-warning"></i> GymControl</h1>
<p class="lead">Sistema simples de gerenciamento de academia. Controle alunos, treinos, exercícios, frequência e evolução.</p>
<ul class="list-unstyled">
<li><i class="bi bi-check-circle-fill text-success"></i> Cadastro e listagem reais (POST/GET)</li>
<li><i class="bi bi-check-circle-fill text-success"></i> Treinos personalizados por aluno</li>
<li><i class="bi bi-check-circle-fill text-success"></i> Acompanhamento de frequência e evolução</li>
</ul>
<div class="d-flex gap-2 mt-3">
<a href="/login" class="btn btn-dark btn-lg">Entrar</a>
<a href="/cadastro" class="btn btn-warning btn-lg">Criar conta</a>
</div>
</div>
<div class="col-lg-6">
<div class="card shadow border-0">
<div class="card-body p-4 text-center">
<i class="bi bi-activity display-1 text-warning"></i>
<h5 class="mt-3">Energia • Saúde • Performance</h5>
<p class="text-muted">Acesso separado por perfil: administrador vê tudo, aluno vê apenas seus dados.</p>
<div class="row text-center mt-3">
<div class="col-4"><i class="bi bi-people fs-3 text-primary"></i><br><small>Alunos</small></div>
<div class="col-4"><i class="bi bi-bicycle fs-3 text-success"></i><br><small>Treinos</small></div>
<div class="col-4"><i class="bi bi-graph-up fs-3 text-danger"></i><br><small>Evolução</small></div>
</div>
</div>
</div>
</div>
</div>
@endsection
