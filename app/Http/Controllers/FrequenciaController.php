<?php
namespace App\Http\Controllers;
use App\Models\Aluno;
use App\Models\Frequencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class FrequenciaController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        if ($usuario->perfil === 'administrador') {
            $alunoId = $request->input('alunoId');
            $query = Frequencia::with('aluno')->latest('data');
            if ($alunoId) { $query->where('alunoId',$alunoId); }
            $frequencias = $query->paginate(15);
            $alunos = Aluno::orderBy('nome')->get();
        } else {
            $aluno = $usuario->aluno; if (!$aluno) { abort(403); }
            $frequencias = Frequencia::where('alunoId',$aluno->id)->latest('data')->paginate(15);
            $alunos = collect();
        }
        return view('frequencias.index', compact('frequencias','alunos'));
    }
    public function store(Request $request)
    {
        $request->validate(['alunoId'=>['required','exists:alunos,id'],'data'=>['required','date'],'presente'=>['required','boolean']]);
        $usuario = Auth::user();
        if ($usuario->perfil !== 'administrador') { $aluno = $usuario->aluno; if (!$aluno || (int)$request->alunoId !== $aluno->id) { abort(403); } }
        Frequencia::updateOrCreate(['alunoId'=>$request->alunoId,'data'=>$request->data],['presente'=>$request->presente]);
        return back()->with('sucesso','Frequência registrada.');
    }
    public function destroy(Frequencia $frequencia)
    {
        $usuario = Auth::user();
        if ($usuario->perfil !== 'administrador') { $aluno = $usuario->aluno; if (!$aluno || $frequencia->alunoId !== $aluno->id) { abort(403); } }
        $frequencia->delete(); return back()->with('sucesso','Frequência removida.');
    }
}
