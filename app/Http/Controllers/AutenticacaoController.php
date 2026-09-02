<?php
namespace App\Http\Controllers;
use App\Http\Requests\CadastrarUsuarioRequest;
use App\Http\Requests\LoginRequest;
use App\Models\Aluno;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
class AutenticacaoController extends Controller
{
    public function exibirLogin() { return view('auth.login'); }
    public function exibirCadastro() { return view('auth.cadastro'); }
    public function cadastrar(CadastrarUsuarioRequest $request)
    {
        $dados = $request->validated();
        $usuario = User::create([
            'name' => $dados['name'],
            'email' => $dados['email'],
            'password' => Hash::make($dados['password']),
            'perfil' => 'aluno',
        ]);
        Aluno::create([
            'usuarioId' => $usuario->id,
            'nome' => $dados['name'],
            'email' => $dados['email'],
            'status' => 'ativo',
        ]);
        Auth::login($usuario);
        $request->session()->regenerate();
        return redirect('/painel-aluno');
    }
    public function autenticar(LoginRequest $request)
    {
        $credenciais = $request->only('email','password');
        if (Auth::attempt($credenciais)) {
            $request->session()->regenerate();
            $usuario = Auth::user();
            if ($usuario->perfil === 'administrador') { return redirect()->intended('/painel-admin'); }
            return redirect()->intended('/painel-aluno');
        }
        return back()->withErrors(['email'=>'Credenciais inválidas.'])->onlyInput('email');
    }
    public function sair(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
