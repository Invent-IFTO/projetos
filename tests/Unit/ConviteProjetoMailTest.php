<?php

namespace Tests\Unit;

use App\Mail\ConviteProjeto;
use App\Models\ProjetoConvite;
use App\Models\ProjetoEquipe;
use App\Models\Projeto;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ConviteProjetoMailTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function cria_mailable_corretamente()
    {
        $projeto = Projeto::factory()->create(['titulo' => 'Projeto Teste']);
        $equipe = ProjetoEquipe::factory()->create([
            'projeto_id' => $projeto->id,
            'descricao' => 'Equipe Frontend'
        ]);
        $responsavel = User::factory()->create(['name' => 'João Silva']);
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
            'lider_equipe' => true,
            'gerente_projeto' => false,
        ]);

        $mail = new ConviteProjeto($convite);

        $this->assertEquals($convite, $mail->convite);
        $this->assertEquals($projeto, $mail->projeto);
        $this->assertEquals($responsavel, $mail->responsavel);
        $this->assertStringContainsString('registro.convite', $mail->linkConvite);
    }

    /** @test */
    public function envelope_tem_subject_correto()
    {
        $projeto = Projeto::factory()->create(['titulo' => 'Meu Projeto']);
        $equipe = ProjetoEquipe::factory()->create(['projeto_id' => $projeto->id]);
        $responsavel = User::factory()->create(['email' => 'gerente@test.com']);
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
        ]);

        $mail = new ConviteProjeto($convite);
        $envelope = $mail->envelope();

        $this->assertEquals('Convite para participar do projeto: Meu Projeto', $envelope->subject);
        $this->assertContains($responsavel->email, array_keys($envelope->replyTo));
    }

    /** @test */
    public function content_usa_view_correta()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
        ]);

        $mail = new ConviteProjeto($convite);
        $content = $mail->content();

        $this->assertEquals('emails.convite-projeto', $content->view);
        $this->assertArrayHasKey('convite', $content->with);
        $this->assertArrayHasKey('projeto', $content->with);
        $this->assertArrayHasKey('responsavel', $content->with);
        $this->assertArrayHasKey('linkConvite', $content->with);
    }

    /** @test */
    public function link_convite_contem_parametros_corretos()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
        ]);

        $mail = new ConviteProjeto($convite);

        $this->assertStringContainsString($convite->id, $mail->linkConvite);
        $this->assertStringContainsString($convite->hash(), $mail->linkConvite);
    }
}