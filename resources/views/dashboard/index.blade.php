@extends('layouts.app')
@section('titulo','Painel Admin')
@section('conteudo')
<h3><i class="bi bi-shield-lock"></i> Painel Administrativo</h3>
<div class="row g-3 mt-1">
<div class="col-md-3"><div class="card text-bg-primary"><div class="card-body"><h5>{{ $totalAlunos }}</h5><small>Alunos</small></div></div></div>
<div class="col-md-3"><div class="card text-bg-success"><div class="card-body"><h5>{{ $totalExercicios }}</h5><small>Exercícios</small></div></div></div>
<div class="col-md-3"><div class="card text-bg-warning"><div class="card-body"><h5>{{ $totalTreinos }}</h5><small>Treinos</small></div></div></div>
<div class="col-md-3"><div class="card text-bg-dark"><div class="card-body"><h5>{{ $totalFrequencias }}</h5><small>Presenças hoje</small></div></div></div>
</div>
<div class="row g-3 mt-2">
<div class="col-md-6"><div class="card"><div class="card-header">Alunos recentes</div><ul class="list-group list-group-flush">@foreach($alunosRecentes as $a)<li class="list-group-item">{{ $a->nome }} - {{ $a->status }}</li>@endforeach</ul></div></div>
<div class="col-md-6"><div class="card"><div class="card-header">Treinos recentes</div><ul class="list-group list-group-flush">@foreach($treinosRecentes as $t)<li class="list-group-item">{{ $t->nome }} ({{ $t->aluno->nome }})</li>@endforeach</ul></div></div>
</div>
@endsection
