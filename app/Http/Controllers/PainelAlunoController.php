<?php
namespace App\Http\Controllers;
use App\Models\Treino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class PainelAlunoController extends Controller
{
    public function index()
    {
        $usuario = Auth::user();
        $aluno = $usuario->aluno;
        $treinos = $aluno ? $aluno->treinos()->with('treinoExercicios.exercicio')->latest()->get() : collect();
        $frequencias = $aluno ? $aluno->frequencias()->latest('data')->limit(5)->get() : collect();
        $evolucoes = $aluno ? $aluno->evolucoes()->latest('data')->limit(5)->get() : collect();
        return view('painel-aluno.index', compact('aluno','treinos','frequencias','evolucoes'));
    }
    public function meuTreino()
    {
        $usuario = Auth::user();
        $aluno = $usuario->aluno;
        if (!$aluno) { abort(403, 'Aluno não vinculado.'); }
        $treinos = Treino::where('alunoId', $aluno->id)->with('treinoExercicios.exercicio')->latest()->get();
        return view('painel-aluno.meu-treino', compact('aluno','treinos'));
    }
    public function detalheTreino(int $id)
    {
        $usuario = Auth::user();
        $aluno = $usuario->aluno;
        if (!$aluno) { abort(403); }
        $treino = Treino::with('treinoExercicios.exercicio','aluno')->findOrFail($id);
        if ($treino->alunoId !== $aluno->id && $usuario->perfil !== 'administrador') { abort(403); }
        return view('painel-aluno.treino-detalhe', compact('treino','aluno'));
    }
}
