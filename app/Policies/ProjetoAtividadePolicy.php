<?php

namespace App\Policies;

use App\Models\ProjetoAtividade;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjetoAtividadePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true; // Usuários autenticados podem ver listas de atividades
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        // Pode visualizar se for membro do projeto
        return $projetoAtividade->projeto->membros()->where('user_id', $user->id)->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true; // Verificação será feita no controller por projeto
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        // Só o responsável da atividade pode editar
        return $projetoAtividade->membro->user_id === $user->id;
    }

    /**
     * Determine whether the user can edit as responsible.
     * Método específico solicitado: responsavel_edit
     */
    public function responsavel_edit(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        // Só o usuário responsável pela atividade pode editá-la
        return $projetoAtividade->membro->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        // Responsável pela atividade ou dono do projeto podem deletar
        return $projetoAtividade->membro->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        return $projetoAtividade->membro->user_id === $user->id
            || $projetoAtividade->projeto->gerente?->id === $user->id ;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, ProjetoAtividade $projetoAtividade): bool
    {
        // Apenas o dono do projeto pode fazer delete permanente
        return $projetoAtividade->projeto->gerente?->id === $user->id ;
    }

    
}
