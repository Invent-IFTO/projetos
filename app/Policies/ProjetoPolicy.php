<?php

namespace App\Policies;

use App\Models\Projeto;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ProjetoPolicy
{



    public function update(User $user, Projeto $projeto): bool
    {
        // Só o responsável da atividade pode editar
        return $projeto->gerente?->id === $user->id;
    }

    /**
     * Determine whether the user can edit as responsible.
     * Método específico solicitado: responsavel_edit
     */
    public function isProjetoGrente(User $user, Projeto $projeto): bool
    {
        // Só o usuário responsável pela atividade pode editá-la
        return $projeto->gerente?->id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Projeto $projeto): bool
    {
        // Responsável pela atividade ou dono do projeto podem deletar
        return $projeto->criador_id === $user->id
            || $projeto->gerente?->id === $user->id;
    }

}
