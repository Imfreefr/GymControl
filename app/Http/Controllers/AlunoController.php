<?php
namespace App\Http\Controllers;
use App\Http\Requests\CadastrarAlunoRequest;
use App\Http\Requests\AtualizarAlunoRequest;
use App\Models\Aluno;
use App\Services\AlunoService;
use Illuminate\Http\Request;
class AlunoController extends Controller
{
    public function __construct(private AlunoService $alunoService) {}
    public function index(Request $request) { $alunos = $this->alunoService->listarAlunos($request->input('busca')); return view('alunos.index', compact('alunos')); }
    public function create() { return view('alunos.create'); }
    public function store(CadastrarAlunoRequest $request) { $this->alunoService->cadastrarAluno($request->validated()); return redirect('/alunos')->with('sucesso','Aluno cadastrado com sucesso.'); }
    public function show(Aluno $aluno) { $aluno->load(['usuario','treinos','frequencias','evolucoes']); return view('alunos.show', compact('aluno')); }
    public function edit(Aluno $aluno) { return view('alunos.edit', compact('aluno')); }
    public function update(AtualizarAlunoRequest $request, Aluno $aluno) { $this->alunoService->atualizarAluno($aluno, $request->validated()); return redirect('/alunos')->with('sucesso','Aluno atualizado.'); }
    public function destroy(Aluno $aluno) { $aluno->delete(); return redirect('/alunos')->with('sucesso','Aluno removido.'); }
}
