<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Projeto;
use App\Models\ProjetoMembro;

class CheckProjetoAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Se não estiver autenticado, redireciona para login
        if (!$user) {
            return redirect()->route('login');
        }

        // Pega o projeto da rota (pode ser ID ou model binding)
        $projeto = $request->route('projeto');
        
        if (!$projeto) {
            abort(404, 'Projeto não encontrado');
        }

        // Se for um ID, busca o projeto
        if (!($projeto instanceof Projeto)) {
            $projeto = Projeto::find($projeto);
            
            if (!$projeto) {
                abort(404, 'Projeto não encontrado');
            }
        }

        // Verifica se o usuário é o responsável pelo projeto
        if ($projeto->criador_id === $user->id) {
            return $next($request);
        }

        // Verifica se o usuário é membro de alguma equipe do projeto

        if ($projeto->membros()->where('user_id', $user->id)->exists()) {
            return $next($request);
        }

        // Se chegou até aqui, não tem acesso
        abort(403, 'Você não tem acesso a este projeto');
    }
}
