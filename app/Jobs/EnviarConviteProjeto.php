<?php

namespace App\Jobs;

use App\Mail\ConviteProjeto;
use App\Models\ProjetoConvite;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class EnviarConviteProjeto implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public ProjetoConvite $convite;

    /**
     * Create a new job instance.
     */
    public function __construct(ProjetoConvite $convite)
    {
        $this->convite = $convite;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Verifica se o convite ainda é válido
        if (!$this->convite->used_at && $this->convite->expires_at > now()) {
            Mail::to($this->convite->email)->send(new ConviteProjeto($this->convite));
        }
    }
}