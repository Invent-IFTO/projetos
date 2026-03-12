<?php

namespace Tests\Unit;

use App\Models\Projeto;
use App\Models\ProjetoEquipe;
use App\Models\ProjetoMembro;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetoMembroMudancaLiderancaTest extends TestCase
{
    use RefreshDatabase;

    public function test_mudanca_lideranca_funciona()
    {
        // Arrange
        $projeto = Projeto::factory()->create();
        $equipe = ProjetoEquipe::factory()->create(['projeto_id' => $projeto->id]);
        
        $joao = User::factory()->create(['name' => 'João']);
        $maria = User::factory()->create(['name' => 'Maria']);
        $pedro = User::factory()->create(['name' => 'Pedro']);
        
        // João é líder inicial
        $membroJoao = ProjetoMembro::factory()->create([
            'projeto_id' => $projeto->id,
            'equipe_id' => $equipe->id,
            'user_id' => $joao->id,
            'lider_equipe' => true,
        ]);
        
        // Maria e Pedro são membros normais
        $membroMaria = ProjetoMembro::factory()->create([
            'projeto_id' => $projeto->id,
            'equipe_id' => $equipe->id,
            'user_id' => $maria->id,
            'lider_equipe' => false,
        ]);
        
        $membroPedro = ProjetoMembro::factory()->create([
            'projeto_id' => $projeto->id,
            'equipe_id' => $equipe->id,
            'user_id' => $pedro->id,
            'lider_equipe' => false,
        ]);

        // Act 1: Maria vira líder
        $membroMaria->lider_equipe = true;
        $membroMaria->save();
        $membroMaria = $equipe->membros()->where('user_id', $maria->id)->first();
        $membroJoao = $equipe->membros()->where('user_id', $joao->id)->first();
        // Assert 1: Deve ter apenas Maria como líder
        $this->assertTrue($membroMaria->fresh()->lider_equipe, 'Maria deve ser líder');
        $this->assertFalse($membroJoao->fresh()->lider_equipe, 'João não deve mais ser líder');
        $this->assertFalse($membroPedro->fresh()->lider_equipe, 'Pedro não deve ser líder');
        
        // Verificar quantidade de líderes
        $lideres = ProjetoMembro::where('equipe_id', $equipe->id)
            ->where('lider_equipe', true)
            ->get();
        $this->assertCount(1, $lideres, 'Deve ter apenas 1 líder na equipe');

        // Act 2: Pedro vira líder
        $membroPedro->lider_equipe = true;
        $membroPedro->save();
        $membroMaria = $equipe->membros()->where('user_id', $maria->id)->first();
        $membroPedro = $equipe->membros()->where('user_id', $pedro->id)->first();
        // Assert 2: Deve ter apenas Pedro como líder
        $this->assertTrue($membroPedro->fresh()->lider_equipe, 'Pedro deve ser líder');
        $this->assertFalse($membroMaria->fresh()->lider_equipe, 'Maria não deve mais ser líder');  
        $this->assertFalse($membroJoao->fresh()->lider_equipe, 'João não deve ser líder');
        
        // Verificar quantidade de líderes novamente
        $lideres = ProjetoMembro::where('equipe_id', $equipe->id)
            ->where('lider_equipe', true)
            ->get();
        $this->assertCount(1, $lideres, 'Deve ter apenas 1 líder na equipe');
        $this->assertEquals($pedro->id, $lideres->first()->user_id, 'Pedro deve ser o líder');
    }

    public function test_mudanca_gerencia_funciona()
    {
        // Arrange
        $projeto = Projeto::factory()->create();
        $equipe1 = ProjetoEquipe::factory()->create(['projeto_id' => $projeto->id]);
        $equipe2 = ProjetoEquipe::factory()->create(['projeto_id' => $projeto->id]);
        
        $ana = User::factory()->create(['name' => 'Ana']);
        $carlos = User::factory()->create(['name' => 'Carlos']);
        
        // Ana é gerente inicial (equipe 1)
        $membroAna = ProjetoMembro::factory()->create([
            'projeto_id' => $projeto->id,
            'equipe_id' => $equipe1->id,
            'user_id' => $ana->id,
            'gerente_projeto' => true,
        ]);
        
        // Carlos é membro normal (equipe 2)
        $membroCarlos = ProjetoMembro::factory()->create([
            'projeto_id' => $projeto->id,
            'equipe_id' => $equipe2->id,
            'user_id' => $carlos->id,
            'gerente_projeto' => false,
        ]);

        // Act: Carlos vira gerente
        $membroCarlos->gerente_projeto = true;
        $membroCarlos->save();

        $membroCarlos = $projeto->membros()->where('user_id', $carlos->id)->first();
        $membroAna = $projeto->membros()->where('user_id', $ana->id)->first();

        // Assert: Deve ter apenas Carlos como gerente do projeto
        $this->assertTrue($membroCarlos->fresh()->gerente_projeto, 'Carlos deve ser gerente');
        $this->assertFalse($membroAna->fresh()->gerente_projeto, 'Ana não deve mais ser gerente');
        
        // Verificar quantidade de gerentes do projeto
        $gerentes = ProjetoMembro::where('projeto_id', $projeto->id)
            ->where('gerente_projeto', true)
            ->get();
        $this->assertCount(1, $gerentes, 'Deve ter apenas 1 gerente no projeto');
        $this->assertEquals($carlos->id, $gerentes->first()->user_id, 'Carlos deve ser o gerente');
    }
}