<?php
namespace App\Http\Controllers;
use App\Http\Requests\CadastrarTreinoRequest;
use App\Models\Aluno;
use App\Models\Exercicio;
use App\Models\Treino;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class TreinoController extends Controller
{
    public function index(Request $request)
    {
        $usuario = Auth::user();
        if ($usuario->perfil === 'administrador') {
            $treinos = Treino::with(['aluno','professor'])->latest()->paginate(10);
        } else {
            $aluno = $usuario->aluno;
            if (!$aluno) { $treinos = Treino::whereRaw('1=0')->paginate(10); } else { $treinos = Treino::where('alunoId',$aluno->id)->with(['aluno','professor'])->latest()->paginate(10); }
        }
        return view('treinos.index', compact('treinos'));
    }
    public function create() { $alunos = Aluno::orderBy('nome')->get(); $exercicios = Exercicio::where('status','ativo')->orderBy('nome')->get(); return view('treinos.create', compact('alunos','exercicios')); }
    public function store(CadastrarTreinoRequest $request)
    {
        $dados = $request->validated();
        $treino = Treino::create(['alunoId'=>$dados['alunoId'],'professorId'=>Auth::id(),'nome'=>$dados['nome'],'objetivo'=>$dados['objetivo']??null,'observacoes'=>$dados['observacoes']??null,'status'=>$dados['status']]);
        if (!empty($dados['exercicios'])) { foreach ($dados['exercicios'] as $item) { $treino->treinoExercicios()->create(['exercicioId'=>$item['exercicioId'],'series'=>$item['series'],'repeticoes'=>$item['repeticoes'],'carga'=>$item['carga']??0,'descansoSegundos'=>$item['descansoSegundos']??60,'ordem'=>$item['ordem']??0]); } }
        return redirect('/treinos')->with('sucesso','Treino cadastrado.');
    }
    public function show(Treino $treino)
    {
        $usuario = Auth::user();
        if ($usuario->perfil !== 'administrador') { $aluno = $usuario->aluno; if (!$aluno || $treino->alunoId !== $aluno->id) { abort(403); } }
        $treino->load(['aluno','professor','treinoExercicios.exercicio']); return view('treinos.show', compact('treino'));
    }
    public function edit(Treino $treino) { $alunos = Aluno::orderBy('nome')->get(); $exercicios = Exercicio::where('status','ativo')->orderBy('nome')->get(); $treino->load('treinoExercicios'); return view('treinos.edit', compact('treino','alunos','exercicios')); }
    public function update(CadastrarTreinoRequest $request, Treino $treino)
    {
        $dados = $request->validated();
        $treino->update(['alunoId'=>$dados['alunoId'],'nome'=>$dados['nome'],'objetivo'=>$dados['objetivo']??null,'observacoes'=>$dados['observacoes']??null,'status'=>$dados['status']]);
        $treino->treinoExercicios()->delete();
        if (!empty($dados['exercicios'])) { foreach ($dados['exercicios'] as $item) { $treino->treinoExercicios()->create(['exercicioId'=>$item['exercicioId'],'series'=>$item['series'],'repeticoes'=>$item['repeticoes'],'carga'=>$item['carga']??0,'descansoSegundos'=>$item['descansoSegundos']??60,'ordem'=>$item['ordem']??0]); } }
        return redirect('/treinos')->with('sucesso','Treino atualizado.');
    }
    public function destroy(Treino $treino) { $treino->delete(); return redirect('/treinos')->with('sucesso','Treino removido.'); }
}
