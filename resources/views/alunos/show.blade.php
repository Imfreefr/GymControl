@extends('layouts.app')
@section('titulo','Detalhe Aluno')
@section('conteudo')
<h3>{{ $aluno->nome }}</h3>
<p>Email: {{ $aluno->email ?? '-' }} | Telefone: {{ $aluno->telefone ?? '-' }} | Status: {{ $aluno->status }}</p>
<p>Objetivo: {{ $aluno->objetivo ?? '-' }}</p>
<p>Observações: {{ $aluno->observacoes ?? '-' }}</p>
<a href="/alunos" class="btn btn-secondary">Voltar</a> <a href="/alunos/{{ $aluno->id }}/edit" class="btn btn-dark">Editar</a>
@endsection
