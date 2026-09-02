<!doctype html>
<html lang="pt-BR">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title>@yield('titulo','GymControl')</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<style>.sidebar{min-height:calc(100vh - 56px);}</style>
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
<div class="container-fluid">
<a class="navbar-brand fw-bold" href="/"><i class="bi bi-lightning-charge-fill text-warning"></i> GymControl</a>
<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menuNav"><span class="navbar-toggler-icon"></span></button>
<div class="collapse navbar-collapse" id="menuNav">
<ul class="navbar-nav ms-auto">
@guest
<li class="nav-item"><a class="nav-link" href="/login">Entrar</a></li>
<li class="nav-item"><a class="btn btn-warning btn-sm mt-1 ms-2" href="/cadastro">Criar conta</a></li>
@else
<li class="nav-item"><span class="nav-link">{{ auth()->user()->name }} ({{ auth()->user()->perfil }})</span></li>
<li class="nav-item"><form method="POST" action="/logout" class="d-inline">@csrf<button class="btn btn-outline-light btn-sm mt-1">Sair</button></form></li>
@endguest
</ul>
</div>
</div>
</nav>
<div class="container-fluid">
<div class="row">
@auth
<div class="col-md-2 bg-white border-end sidebar p-0">
<div class="list-group list-group-flush">
<a href="/" class="list-group-item"><i class="bi bi-house"></i> Início</a>
@if(auth()->user()->perfil==='administrador')
<a href="/painel-admin" class="list-group-item"><i class="bi bi-speedometer2"></i> Painel Admin</a>
<a href="/alunos" class="list-group-item"><i class="bi bi-people"></i> Alunos</a>
<a href="/exercicios" class="list-group-item"><i class="bi bi-activity"></i> Exercícios</a>
<a href="/treinos" class="list-group-item"><i class="bi bi-bicycle"></i> Treinos</a>
<a href="/frequencias" class="list-group-item"><i class="bi bi-calendar-check"></i> Frequência</a>
<a href="/evolucoes" class="list-group-item"><i class="bi bi-graph-up"></i> Evolução</a>
@else
<a href="/painel-aluno" class="list-group-item"><i class="bi bi-speedometer2"></i> Meu Painel</a>
<a href="/painel-aluno/meu-treino" class="list-group-item"><i class="bi bi-bicycle"></i> Meu Treino</a>
<a href="/exercicios" class="list-group-item"><i class="bi bi-activity"></i> Exercícios</a>
<a href="/treinos" class="list-group-item"><i class="bi bi-card-list"></i> Treinos</a>
<a href="/frequencias" class="list-group-item"><i class="bi bi-calendar-check"></i> Frequência</a>
<a href="/evolucoes" class="list-group-item"><i class="bi bi-graph-up"></i> Evolução</a>
@endif
</div>
</div>
<div class="col-md-10 p-4">
@else
<div class="col-12 p-4">
@endauth
@if(session('sucesso'))<div class="alert alert-success">{{ session('sucesso') }}</div>@endif
@if($errors->any())<div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>@endif
@yield('conteudo')
</div>
</div>
</div>
<footer class="bg-dark text-white text-center py-3"><small>GymControl &copy; {{ date('Y') }} - Energia e saúde</small></footer>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
