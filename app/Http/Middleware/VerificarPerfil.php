<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class VerificarPerfil
{
    public function handle(Request $request, Closure $next, string $perfil): Response
    {
        if (!auth()->check()) { return redirect('/login'); }
        if (auth()->user()->perfil !== $perfil) { abort(403, 'Acesso negado.'); }
        return $next($request);
    }
}
