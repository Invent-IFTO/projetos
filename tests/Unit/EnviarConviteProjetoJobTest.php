<?php

namespace Tests\Unit;

use App\Jobs\EnviarConviteProjeto;
use App\Mail\ConviteProjeto;
use App\Models\ProjetoConvite;
use App\Models\ProjetoEquipe;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class EnviarConviteProjetoJobTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
    }

    /** @test */
    public function envia_email_para_convite_valido()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
            'expires_at' => now()->addDays(2),
        ]);

        $job = new EnviarConviteProjeto($convite);
        $job->handle();

        Mail::assertSent(ConviteProjeto::class, function ($mail) use ($convite) {
            return $mail->hasTo($convite->email);
        });
    }

    /** @test */
    public function nao_envia_email_para_convite_expirado()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
            'expires_at' => now()->subDay(),
        ]);

        $job = new EnviarConviteProjeto($convite);
        $job->handle();

        Mail::assertNotSent(ConviteProjeto::class);
    }

    /** @test */
    public function nao_envia_email_para_convite_usado()
    {
        $equipe = ProjetoEquipe::factory()->create();
        $responsavel = User::factory()->create();
        
        $convite = ProjetoConvite::create([
            'equipe_id' => $equipe->id,
            'responsavel_convite_id' => $responsavel->id,
            'email' => 'convidado@test.com',
            'used_at' => now(),
            'expires_at' => now()->addDays(2),
        ]);

        $job = new EnviarConviteProjeto($convite);
        $job->handle();

        Mail::assertNotSent(ConviteProjeto::class);
    }
}