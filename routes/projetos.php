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
        });
    });