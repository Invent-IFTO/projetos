<?php

namespace Database\Seeders;

use App\Models\Projeto;
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
        $projeto = Projeto::factory()->create();
        $equipes = $projeto->equipes()->createMany([
            ['descricao' => 'Equipe de Desenvolvimento'],
            ['descricao' => 'Equipe de Testes'],
            ['descricao' => 'Equipe de Documentação'],
            ['descricao' => 'Equipe de Design'],
        ]);

        foreach ($equipes as $equipe) {
          $membros[] =  $equipe->membros()->createMany([
                [
                    'user_id' => User::factory()->create()->id,
                    'lider_equipe' => true,
                    'gerente_projeto' => true,
                    'projeto_id' => $projeto->id,
                ],
                [
                    'user_id' => User::factory()->create()->id,
                    'lider_equipe' => false,
                    'gerente_projeto' => false,
                    'projeto_id' => $projeto->id,
                ],
                [
                    'user_id' => User::factory()->create()->id,
                    'lider_equipe' => false,
                    'gerente_projeto' => false,
                    'projeto_id' => $projeto->id,
                ],
            ]);
        }

        foreach($membros as $membroEquipe){
            foreach($membroEquipe as $membro){
                $membro->atividades()->createMany([
                    [
                        'anterior' => 'Atividade anterior 1',
                        'atual' => 'Atividade atual 1',
                        'problemas' => 'Nenhum problema',
                        'status' => 'pendente',
                        'avaliacao' => null,
                    ],
                    [
                        'anterior' => 'Atividade anterior 2',
                        'atual' => 'Atividade atual 2',
                        'problemas' => 'Nenhum problema',
                        'status' => 'verificada',
                        'avaliacao' => 4.5,
                    ],
                ]);
            }
        }

    }
}
