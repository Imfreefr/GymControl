<?php
namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;
class CadastrarAlunoRequest extends FormRequest
{
    public function authorize(): bool { return auth()->check() && auth()->user()->perfil === 'administrador'; }
    public function rules(): array {
        return [
            'nome' => ['required','string','max:255'],
            'email' => ['nullable','email','max:255'],
            'telefone' => ['nullable','string','max:20'],
            'dataNascimento' => ['nullable','date'],
            'objetivo' => ['nullable','string','max:255'],
            'status' => ['required','in:ativo,inativo'],
            'observacoes' => ['nullable','string'],
            'usuarioId' => ['nullable','exists:users,id'],
        ];
    }
}
