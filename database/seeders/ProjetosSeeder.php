<?php

namespace Database\Seeders;

use App\Models\Projeto;
use App\Models\ProjetoEquipe;
use App\Models\ProjetoMembro;
use App\Models\ProjetoAtividade;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjetosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Criar projeto usando factory (aleatório)
        $projeto = Projeto::factory()->create();

        // Criar usuário gerente do projeto
        $usuarioGerente = User::factory()->create();
        
        // Equipes do projeto (pelo menos 3)
        $descricoesEquipes = [
            'Equipe de Desenvolvimento',
            'Equipe de Testes',
            'Equipe de Documentação',
        ];

        $equipes = [];
        $todosOsMembros = [];
        $gerenteJaAdicionado = false;

        foreach ($descricoesEquipes as $index => $descricaoEquipe) {
            // Criar equipe individualmente
            $equipe = new ProjetoEquipe();
            $equipe->projeto_id = $projeto->id;
            $equipe->descricao = $descricaoEquipe;
            $equipe->save();
            $equipes[] = $equipe;

            // Adicionar gerente do projeto como primeiro membro da primeira equipe
            if (!$gerenteJaAdicionado) {
                $membroGerente = new ProjetoMembro();
                $membroGerente->user_id = $usuarioGerente->id;
                $membroGerente->projeto_id = $projeto->id;
                $membroGerente->equipe_id = $equipe->id;
                $membroGerente->lider_equipe = true; // Gerente também é líder da primeira equipe
                $membroGerente->gerente_projeto = true;
                $membroGerente->save();
                $todosOsMembros[] = $membroGerente;
                $gerenteJaAdicionado = true;
            } else {
                // Criar líder da equipe como primeiro membro 
                $usuarioLider = User::factory()->create();
                $membroLider = new ProjetoMembro();
                $membroLider->user_id = $usuarioLider->id;
                $membroLider->projeto_id = $projeto->id;
                $membroLider->equipe_id = $equipe->id;
                $membroLider->lider_equipe = true;
                $membroLider->gerente_projeto = false;
                $membroLider->save();
                $todosOsMembros[] = $membroLider;
            }

            // Criar membros adicionais (pelo menos mais 2 para ter 3 por equipe)
            for ($i = 0; $i < 3; $i++) {
                $usuarioMembro = User::factory()->create();
                $membro = new ProjetoMembro();
                $membro->user_id = $usuarioMembro->id;
                $membro->projeto_id = $projeto->id;
                $membro->equipe_id = $equipe->id;
                $membro->lider_equipe = false;
                $membro->gerente_projeto = false;
                $membro->save();
                $todosOsMembros[] = $membro;
            }
        }

        // Criar atividades para cada membro individualmente
        foreach ($todosOsMembros as $membro) {
            // Primeira atividade
            $atividade1 = new ProjetoAtividade();
            $atividade1->projeto_membro_id = $membro->id;
            $atividade1->anterior = 'Atividade anterior 1';
            $atividade1->atual = 'Atividade atual 1';
            $atividade1->problemas = 'Nenhum problema';
            $atividade1->status = 'pendente';
            $atividade1->avaliacao = null;
            $atividade1->save();

            // Segunda atividade
            $atividade2 = new ProjetoAtividade();
            $atividade2->projeto_membro_id = $membro->id;
            $atividade2->anterior = 'Atividade anterior 2';
            $atividade2->atual = 'Atividade atual 2';
            $atividade2->problemas = 'Nenhum problema';
            $atividade2->status = 'verificada';
            $atividade2->avaliacao = 4.5;
            $atividade2->save();
        }
    }
}
