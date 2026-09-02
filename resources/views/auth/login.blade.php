@extends('layouts.app')
@section('titulo','Entrar - GymControl')
@section('conteudo')
<div class="row justify-content-center"><div class="col-md-5">
<div class="card shadow"><div class="card-body p-4">
<h4 class="mb-3"><i class="bi bi-box-arrow-in-right"></i> Entrar</h4>
<form method="POST" action="/login">@csrf
<div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" required></div>
<button class="btn btn-dark w-100">Entrar</button>
</form>
<p class="mt-3 text-center"><a href="/cadastro">Criar conta</a></p>
</div></div>
</div></div>
@endsection
