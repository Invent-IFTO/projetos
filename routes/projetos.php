<?php

use App\Http\Controllers\Projetos\AtividadesController;
use App\Http\Controllers\Projetos\ProjetosController;
use App\Http\Controllers\Projetos\SelecioneProjetoController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->name('projetos.')
    ->prefix('projetos')->group(function () {
        Route::get('/selecione/{action}', [SelecioneProjetoController::class, 'index'])->name('selecione');

        // Rotas que precisam verificar acesso ao projeto
        Route::middleware(['projeto.access'])->prefix('/{projeto}')->group(function () {
            Route::prefix('/atividades')->name('atividades.')->group(function () {
                Route::get('/', [AtividadesController::class, 'atividades'])->name('index');
                Route::get('/nova', [AtividadesController::class, 'nova'])->name('nova');
                Route::post('/criar', [AtividadesController::class, 'criar'])->name('criar');
                Route::get('/{atividade}', [AtividadesController::class, 'show'])->name('show');
                Route::get('/{atividade}/editar', [AtividadesController::class, 'editar'])->name('editar');
                Route::put('/{atividade}', [AtividadesController::class, 'alterar'])->name('alterar');
                Route::delete('/{atividade}', [AtividadesController::class, 'deletar'])->name('deletar');
                Route::put('/{atividade}/restaurar', [AtividadesController::class, 'restaurar'])->name('restaurar');
                Route::delete('/{atividade}/confirme_delete', [AtividadesController::class, 'confirmeDelete'])->name('confirme_delete');
            });
        });

        Route::middleware(['permission:Projetos: listar'])->prefix('/gestao')->group(function () {
            Route::get('/', [ProjetosController::class, 'listar'])->name('listar');
            Route::get('/novo', [ProjetosController::class, 'novo'])->name('novo');
            Route::post('/criar', [ProjetosController::class, 'criar'])->name('criar');
            Route::put('/projeto/{projeto}', [ProjetosController::class, 'alterar'])->name('alterar');
            Route::get('/projeto/{projeto}', [ProjetosController::class, 'show'])->name('show');
            Route::delete('/projeto/{projeto}', [ProjetosController::class, 'deletar'])->name('deletar');

            Route::middleware(['permission:Projetos: membros'])->prefix('/projeto/{projeto}/membros')->name('membros.')->group(function () {
                Route::get('/criar', [ProjetosController::class, 'criarMembro'])->name('novo');
                Route::post('/criar', [ProjetosController::class, 'inserirMembro'])->name('criar');
                Route::get('/{membro}/alterar', [ProjetosController::class, 'editarMembro'])->name('editar');
                Route::put('/{membro}/promover/gerente', [ProjetosController::class, 'promoverGerente'])->name('promover.gerente');
                Route::put('/{membro}/promover/lider', [ProjetosController::class, 'promoverLider'])->name('promover.lider');
                Route::put('/{membro}/alterar', [ProjetosController::class, 'alterarMembro'])->name('alterar');
                Route::delete('/{membro}', [ProjetosController::class, 'removerMembro'])->name('remover');
            });

            Route::middleware(['permission:Projetos: equipes'])->prefix('/projeto/{projeto}/equipe')->name('equipe.')->group(function () {
                Route::get('/{equipe}/editar', [ProjetosController::class, 'editarEquipe'])->name('editar');
                Route::put('/{equipe}', [ProjetosController::class, 'alterarEquipe'])->name('alterar');
                Route::get('/{equipe}/excluir', [ProjetosController::class, 'excluirEquipe'])->name('excluir');
                Route::delete('/{equipe}', [ProjetosController::class, 'deletarEquipe'])->name('deletar');
            });
        });
    });