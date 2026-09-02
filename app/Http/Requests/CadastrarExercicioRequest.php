<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CadastrarExercicioRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->perfil === 'administrador'; }
    public function rules(): array {
        return [
            'nome' => ['required','string','max:255'],
            'grupoMuscular' => ['nullable','string','max:255'],
            'descricao' => ['nullable','string'],
            'series' => ['required','integer','min:1'],
            'repeticoes' => ['required','integer','min:1'],
            'carga' => ['required','numeric','min:0'],
            'descansoSegundos' => ['required','integer','min:0'],
            'status' => ['required','in:ativo,inativo'],
        ];
    }
}
