<?php
namespace App\Http\Controllers;
use App\Models\Aluno;
use App\Models\Evolucao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class EvolucaoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        if ($usuario->perfil === 'administrador') {
            $alunoId = $request->input('alunoId');
            $query = Evolucao::with('aluno')->latest('data');
            if ($alunoId) { $query->where('alunoId',$alunoId); }
            $evolucoes = $query->paginate(15);
            $alunos = Aluno::orderBy('nome')->get();
        } else {
            $aluno = $usuario->aluno; if (!$aluno) { abort(403); }
            $evolucoes = Evolucao::where('alunoId',$aluno->id)->latest('data')->paginate(15);
            $alunos = collect();
        }
        return view('evolucoes.index', compact('evolucoes','alunos'));
    }
    public function store(Request $request)
    {
        $request->validate(['alunoId'=>['required','exists:alunos,id'],'data'=>['required','date'],'peso'=>['nullable','numeric'],'altura'=>['nullable','numeric'],'observacao'=>['nullable','string']]);
        $usuario = Auth::user();
        if ($usuario->perfil !== 'administrador') { $aluno = $usuario->aluno; if (!$aluno || (int)$request->alunoId !== $aluno->id) { abort(403); } }
        Evolucao::create($request->only(['alunoId','data','peso','altura','observacao']));
        return back()->with('sucesso','Evolução registrada.');
    }
    public function destroy(Evolucao $evolucao)
    {
        $usuario = Auth::user();
        if ($usuario->perfil !== 'administrador') { $aluno = $usuario->aluno; if (!$aluno || $evolucao->alunoId !== $aluno->id) { abort(403); } }
        $evolucao->delete(); return back()->with('sucesso','Evolução removida.');
    }
}
