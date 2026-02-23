<?php

namespace App\Http\Controllers\Projetos;

use App\Http\Controllers\Controller;
use App\Models\Projeto;
use App\Models\ProjetoAtividade;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;
use Session;

class AtividadesController extends Controller
{

    public function atividades(Projeto $projeto)
    {
        $membro = $projeto->membros()->where('user_id', auth()->user()->id)->first();
        $equipe =  $membro?->equipe;
        $gerente = $projeto->gerente?->id === auth()->user()->id;
        return view('projetos.atividades.index', compact('projeto', 'membro', 'equipe', 'gerente'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function nova(Projeto $projeto)
    {
        return view('projetos.atividades.nova', compact('projeto'));
    }
    public function show(Projeto $projeto, ProjetoAtividade $atividade)
    {
        $this->authorize('view', $atividade);
        if($atividade->trashed()){
            $this->authorize('restore', $atividade);
        }
        return view('projetos.atividades.show', compact('atividade'));
    }

    public function criar(Projeto $projeto, Request $request)
    {
        $request->validate([
            'anterior' => 'required|string',
            'atual' => 'required|string',
            'problemas' => 'required|string',
        ]);

        $atividade = $projeto->atividades()->create([
            'anterior' => $request->anterior,
            'atual' => $request->atual,
            'problemas' => $request->problemas,
            'projeto_membro_id' => $projeto->membroId(Auth::user()->id),
        ]);

        return redirect()->route('projetos.atividades.index', ['projeto' => $projeto])->with('success', 'Atividade criada com sucesso.');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editar(Projeto $projeto, ProjetoAtividade $atividade)
    {
        // Verifica se o usuário pode editar como responsável
        $this->authorize('responsavel_edit', $atividade);

        return view('projetos.atividades.edit', compact('projeto', 'atividade'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function alterar(Request $request, Projeto $projeto, ProjetoAtividade $atividade)
    {
        // Verifica se o usuário pode editar como responsável
        $this->authorize('responsavel_edit', $atividade);

        $request->validate([
            'anterior' => 'required|string',
            'atual' => 'required|string',
            'problemas' => 'required|string',
        ]);

        $atividade->update([
            'anterior' => $request->anterior,
            'atual' => $request->atual,
            'problemas' => $request->problemas,
        ]);

        return redirect()->route('projetos.atividades.index', ['projeto' => $projeto])
            ->with('success', 'Atividade atualizada com sucesso.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function deletar(Projeto $projeto, ProjetoAtividade $atividade)
    {
        // Usa a policy de delete (responsável ou dono do projeto)
        $this->authorize('delete', $atividade);
        if ($atividade->status === 'verificada') {
            $atividade->delete();
        } else {
            $atividade->forceDelete();
        }
        return redirect()->route('projetos.atividades.index', ['projeto' => $projeto])
            ->with('success', 'Atividade excluída com sucesso.');
    }

    /**
     * Restore the specified soft-deleted resource.
     */
    public function restaurar(Projeto $projeto, ProjetoAtividade $atividade)
    {
        $this->authorize('restore', $atividade);

        $atividade->restore();

        return redirect()->route('projetos.atividades.index', ['projeto' => $projeto])
            ->with('success', 'Atividade restaurada com sucesso.');
    }

    /**
     * Permanently delete the specified resource.
     */
    public function confirmeDelete(Projeto $projeto, ProjetoAtividade $atividade)
    {
        $this->authorize('forceDelete', $atividade);

        $atividade->forceDelete();

        return redirect()->route('projetos.atividades.index', ['projeto' => $projeto])
            ->with('success', 'Atividade excluída permanentemente.');
    }
}

