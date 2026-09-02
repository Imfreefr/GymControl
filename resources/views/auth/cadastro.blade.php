@extends('layouts.app')
@section('titulo','Criar conta - GymControl')
@section('conteudo')
<div class="row justify-content-center"><div class="col-md-6">
<div class="card shadow"><div class="card-body p-4">
<h4 class="mb-3"><i class="bi bi-person-plus"></i> Criar conta</h4>
<form method="POST" action="/cadastro">@csrf
<div class="mb-3"><label class="form-label">Nome</label><input type="text" name="name" value="{{ old('name') }}" class="form-control" required></div>
<div class="mb-3"><label class="form-label">E-mail</label><input type="email" name="email" value="{{ old('email') }}" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Senha</label><input type="password" name="password" class="form-control" required></div>
<div class="mb-3"><label class="form-label">Confirmar senha</label><input type="password" name="password_confirmation" class="form-control" required></div>
<button class="btn btn-warning w-100">Cadastrar</button>
</form>
<p class="mt-3 text-center"><a href="/login">Já tenho conta</a></p>
</div></div>
</div></div>
@endsection
