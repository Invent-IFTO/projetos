<?php

namespace App\Providers;

use App\Models\Projeto;
use App\Policies\ProjetoPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Route;
use App\Models\ProjetoAtividade;
use App\Policies\ProjetoAtividadePolicy;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Registrar policies
        Gate::policy(ProjetoAtividade::class, ProjetoAtividadePolicy::class);
        Gate::policy(Projeto::class, ProjetoPolicy::class);
        
        // Permitir que route model binding inclua registros soft-deleted para ProjetoAtividade
        Route::bind('atividade', function (string $value) {
            return ProjetoAtividade::withTrashed()->findOrFail($value);
        });
        
    }
}
