<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CadastrarTreinoRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->perfil === 'administrador'; }
    public function rules(): array {
        return [
            'alunoId' => ['required','exists:alunos,id'],
            'nome' => ['required','string','max:255'],
            'objetivo' => ['nullable','string','max:255'],
            'observacoes' => ['nullable','string'],
            'status' => ['required','in:ativo,inativo'],
            'exercicios' => ['nullable','array'],
            'exercicios.*.exercicioId' => ['required_with:exercicios','exists:exercicios,id'],
            'exercicios.*.series' => ['required_with:exercicios','integer','min:1'],
            'exercicios.*.repeticoes' => ['required_with:exercicios','integer','min:1'],
            'exercicios.*.carga' => ['nullable','numeric','min:0'],
            'exercicios.*.descansoSegundos' => ['nullable','integer','min:0'],
            'exercicios.*.ordem' => ['nullable','integer','min:0'],
        ];
    }
}
