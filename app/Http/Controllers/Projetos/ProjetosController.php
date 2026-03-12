<?php

namespace App\Http\Controllers\Projetos;

use App\Http\Controllers\Controller;
use App\Jobs\EnviarConviteProjeto;
use App\Models\Projeto;
use App\Models\ProjetoAtividade;
use App\Models\ProjetoConvite;
use App\Models\ProjetoEquipe;
use App\Models\ProjetoMembro;
use App\Models\User;
use Auth;
use DB;
use Illuminate\Http\Request;

class ProjetosController extends Controller
{
    public function listar()
    {
        $projetos = Projeto::all();
        return view('projetos.gestao.index', compact('projetos'));
    }

    public function novo()
    {
        return view('projetos.gestao.novo');
    }

    public function show(Projeto $projeto)
    {
        return view('projetos.gestao.show', compact('projeto'));
    }

    public function criar(Request $request)
    {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'descricao' => 'nullable|string',
        ]);

        $projeto = Projeto::create([
            'titulo' => $request->titulo,
            'descricao' => $request->descricao,
            'criador_id' => Auth::id(),
        ]);

        return redirect()->route('projetos.show', ['projeto' => $projeto])->with('success', 'Projeto criado com sucesso!');
    }

    public function promoverLider(Projeto $projeto, ProjetoMembro $membro)
    {
        $membro->lider_equipe = true;
        $membro->save();
        return redirect()->route('projetos.show', ['projeto' => $membro->projeto])->with('success', 'Membro promovido a líder de equipe com sucesso!');
    }

    public function promoverGerente(Projeto $projeto, ProjetoMembro $membro)
    {
        $membro->gerente_projeto = true;
        $membro->save();
        return redirect()->route('projetos.show', ['projeto' => $membro->projeto])->with('success', 'Membro promovido a gerente do projeto com sucesso!');
    }

    public function removerMembro(Projeto $projeto, ProjetoMembro $membro)
    {
        $membro->delete();
        return redirect()->route('projetos.show', ['projeto' => $membro->projeto])->with('success', 'Membro removido do projeto com sucesso!');
    }

    public function criarMembro(Projeto $projeto)
    {
        $users_disponiveis = User::whereNotIn('id', $projeto->membros->pluck('user_id'))->pluck(DB::raw("CONCAT(name, ' (', email, ')') AS name_email"), 'id')->toArray(); // Substitua por sua lógica para obter os usuários disponíveis
        return view('projetos.gestao.modals.membros-add', compact('projeto', 'users_disponiveis'));
    }
    public function editarMembro(Projeto $projeto, ProjetoMembro $membro)
    {
        return view('projetos.gestao.modals.membros-edit', compact('projeto', 'membro'));
    }

    public function alterarMembro(Projeto $projeto, ProjetoMembro $membro, Request $request)
    {
        $request->validate([
            'equipe' => 'required|string|max:255',
        ]);
        $membro->equipe_id = $projeto->equipes()->firstOrCreate(['descricao' => $request->equipe])->id;
        $membro->lider_equipe = $request->has('lider');
        $membro->gerente_projeto = $request->has('gerente');
        $membro->save();
        return redirect()->route('projetos.show', ['projeto' => $membro->projeto])->with('success', 'Membro alterado com sucesso!');
    }

    public function inserirMembro(Projeto $projeto, Request $request)
    {
        $request->validate([
            'usuario' => 'required|string',
            'equipe' => 'required|string|max:255',
        ]);
        if (is_numeric($request->usuario)) {
            $request->validate([
                'usuario' => 'exists:users,id',
            ]);
            $membro =  new ProjetoMembro();
            $membro->projeto_id = $projeto->id;
            $membro->user_id = $request->usuario;
            $membro->equipe_id = $projeto->equipes()->firstOrCreate(['descricao' => $request->equipe])->id;
            $membro->lider_equipe = $request->has('lider');
            $membro->gerente_projeto = $request->has('gerente');
            $membro->save();
             return redirect()->route('projetos.show', ['projeto' => $membro->projeto])->with('success', 'Membro adicionado ao projeto com sucesso!');
        } else {
            return $this->convidarMembro($projeto, $request);
        }
       
    }

    private function convidarMembro(Projeto $projeto, Request $request)
    {
        $request->validate([
            'usuario' => 'email|required|unique:users,email',
        ]);

        $equipe = ProjetoEquipe::firstOrCreate([
            'descricao' => $request->equipe,
            'projeto_id' => $projeto->id
        ]);

        $convite = $equipe->convites()->create([
            'responsavel_convite_id' => Auth::id(),
            'email' => $request->usuario,
        ]);

        // Enfileirar o job para enviar o e-mail de convite
        EnviarConviteProjeto::dispatch($convite);

        return redirect()->route('projetos.show', ['projeto' => $projeto])->with('success', 'Convite enviado para o email do usuário!');
    }


    public function editarEquipe(Projeto $projeto, ProjetoEquipe $equipe)
    {
        return view('projetos.gestao.modals.equipes-edit', compact('projeto', 'equipe'));
    }
}

