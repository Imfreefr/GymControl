<?php
namespace App\Http\Controllers;
use App\Http\Requests\CadastrarExercicioRequest;
use App\Models\Exercicio;
use App\Services\ExercicioService;
use Illuminate\Http\Request;
class ExercicioController extends Controller
{
    public function __construct(private ExercicioService $exercicioService) {}
    public function index(Request $request) { $exercicios = $this->exercicioService->listarExercicios($request->input('busca')); return view('exercicios.index', compact('exercicios')); }
    public function create() { return view('exercicios.create'); }
    public function store(CadastrarExercicioRequest $request) { $this->exercicioService->cadastrarExercicio($request->validated()); return redirect('/exercicios')->with('sucesso','Exercício cadastrado.'); }
    public function show(Exercicio $exercicio) { return view('exercicios.show', compact('exercicio')); }
    public function edit(Exercicio $exercicio) { return view('exercicios.edit', compact('exercicio')); }
    public function update(CadastrarExercicioRequest $request, Exercicio $exercicio) { $this->exercicioService->atualizarExercicio($exercicio, $request->validated()); return redirect('/exercicios')->with('sucesso','Exercício atualizado.'); }
    public function destroy(Exercicio $exercicio) { $exercicio->delete(); return redirect('/exercicios')->with('sucesso','Exercício removido.'); }
}
