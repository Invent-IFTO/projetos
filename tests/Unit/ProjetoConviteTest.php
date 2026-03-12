<?php

namespace Tests\Unit;

use App\Models\ProjetoConvite;
use App\Models\ProjetoEquipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjetoConviteTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cria_token_automaticamente()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();

        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'test@example.com',
            'lider_equipe' => true,
            'gerente_projeto' => false,
        ]);

        $this->assertNotNull($convite->token);
        $this->assertEquals(32, strlen($convite->token));
    }

    /** @test */
    public function define_data_expiracao_automaticamente()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();

        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'test@example.com',
        ]);

        $this->assertNotNull($convite->expires_at);
        $this->assertTrue($convite->expires_at->isFuture());
    }

    /** @test */
    public function gera_hash_corretamente()
    {
        $convite = new ProjetoConvite(['token' => 'test_token']);
        
        $hash = $convite->hash();
        
        $this->assertEquals(hash('sha256', 'test_token'), $hash);
    }

    /** @test */
    public function relacionamento_com_equipe()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'test@example.com',
        ]);

        $this->assertEquals($equipe->id, $convite->equipe->id);
    }

    /** @test */
    public function relacionamento_com_responsavel()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'test@example.com',
        ]);

        $this->assertEquals($responsavel->id, $convite->responsavel->id);
    }

    /** @test */
    public function oculta_token_em_serialize()
    {
        $convite = new ProjetoConvite([
            'email' => 'test@example.com',
            'token' => 'secret_token',
        ]);

        $array = $convite->toArray();
        
        $this->assertArrayNotHasKey('token', $array);
        $this->assertArrayHasKey('email', $array);
    }
}