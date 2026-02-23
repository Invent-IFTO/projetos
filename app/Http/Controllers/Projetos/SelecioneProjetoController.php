<?php

namespace App\Http\Controllers\Projetos;

use App\Http\Controllers\Controller;
use App\Models\Projeto;
use Illuminate\Http\Request;

class SelecioneProjetoController extends Controller
{

   
    public function index($action)
    {
        $user = auth()->user();
        $projetos = $user->membroProjetos()->count();
        if ($projetos == 0) {
            return redirect()->route('home')->with('warning', 'Você não tem projetos para acessar as atividades.');
        } elseif ($projetos == 1) {
            $projeto = $user->membroProjetos()->first()->projeto;
            return redirect()->route($action, ['projeto' => $projeto]);
        }
        //['action' => $action, 'user' => $user];
        return view('projetos.atividades.selecione', compact('action'));
    }

}
