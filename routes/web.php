<?php
use App\Http\Controllers\AlunoController;
use App\Http\Controllers\AutenticacaoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\EvolucaoController;
use App\Http\Controllers\ExercicioController;
use App\Http\Controllers\FrequenciaController;
use App\Http\Controllers\PainelAlunoController;
use App\Http\Controllers\TreinoController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('inicio'))->name('inicio');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AutenticacaoController::class, 'exibirLogin'])->name('login');
    Route::post('/login', [AutenticacaoController::class, 'autenticar'])->name('login.autenticar');
    Route::get('/cadastro', [AutenticacaoController::class, 'exibirCadastro'])->name('cadastro');
    Route::post('/cadastro', [AutenticacaoController::class, 'cadastrar'])->name('cadastro.salvar');
});

Route::post('/logout', [AutenticacaoController::class, 'sair'])->middleware('auth')->name('logout');

Route::middleware(['auth','perfil:administrador'])->group(function () {
    Route::get('/painel-admin', [DashboardController::class, 'index'])->name('painel.admin');
    Route::resource('alunos', AlunoController::class);
    Route::get('/exercicios/create', [ExercicioController::class, 'create'])->name('exercicios.create');
    Route::post('/exercicios', [ExercicioController::class, 'store'])->name('exercicios.store');
    Route::get('/exercicios/{exercicio}/edit', [ExercicioController::class, 'edit'])->name('exercicios.edit');
    Route::put('/exercicios/{exercicio}', [ExercicioController::class, 'update'])->name('exercicios.update');
    Route::delete('/exercicios/{exercicio}', [ExercicioController::class, 'destroy'])->name('exercicios.destroy');
    Route::get('/treinos/create', [TreinoController::class, 'create'])->name('treinos.create');
    Route::post('/treinos', [TreinoController::class, 'store'])->name('treinos.store');
    Route::get('/treinos/{treino}/edit', [TreinoController::class, 'edit'])->name('treinos.edit');
    Route::put('/treinos/{treino}', [TreinoController::class, 'update'])->name('treinos.update');
    Route::delete('/treinos/{treino}', [TreinoController::class, 'destroy'])->name('treinos.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/painel-aluno', [PainelAlunoController::class, 'index'])->name('painel.aluno');
    Route::get('/painel-aluno/meu-treino', [PainelAlunoController::class, 'meuTreino'])->name('painel.aluno.treino');
    Route::get('/painel-aluno/treino/{id}', [PainelAlunoController::class, 'detalheTreino'])->name('painel.aluno.treino.detalhe');
    Route::get('/exercicios', [ExercicioController::class, 'index'])->name('exercicios.index');
    Route::get('/exercicios/{exercicio}', [ExercicioController::class, 'show'])->name('exercicios.show');
    Route::get('/treinos', [TreinoController::class, 'index'])->name('treinos.index');
    Route::get('/treinos/{treino}', [TreinoController::class, 'show'])->name('treinos.show');
    Route::get('/frequencias', [FrequenciaController::class, 'index'])->name('frequencias.index');
    Route::post('/frequencias', [FrequenciaController::class, 'store'])->name('frequencias.store');
    Route::delete('/frequencias/{frequencia}', [FrequenciaController::class, 'destroy'])->name('frequencias.destroy');
    Route::get('/evolucoes', [EvolucaoController::class, 'index'])->name('evolucoes.index');
    Route::post('/evolucoes', [EvolucaoController::class, 'store'])->name('evolucoes.store');
    Route::delete('/evolucoes/{evolucao}', [EvolucaoController::class, 'destroy'])->name('evolucoes.destroy');
});
