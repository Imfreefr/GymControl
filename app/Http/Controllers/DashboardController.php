<?php
namespace App\Http\Controllers;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\Treino;
use App\Models\Frequencia;
use Illuminate\Http\Request;
class DashboardController extends Controller
{
    public function index()
    {
        $totalAlunos = Aluno::count();
        $totalExercicios = Exercicio::count();
        $totalTreinos = Treino::count();
        $totalFrequencias = Frequencia::whereDate('data', today())->count();
        $alunosRecentes = Aluno::latest()->limit(5)->get();
        $treinosRecentes = Treino::with('aluno')->latest()->limit(5)->get();
        return view('dashboard.index', compact('totalAlunos','totalExercicios','totalTreinos','totalFrequencias','alunosRecentes','treinosRecentes'));
    }
}
