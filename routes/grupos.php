<?php

use App\Http\Controllers\Administracao\GruposController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth', 'permission:Grupos: listar'])->name('administracao.grupos.')
->prefix('administracao/grupos')->group(function(){
    Route::get('/',[GruposController::class, 'listar'])->name('listar');
    Route::middleware('permission:Grupos: criar')->get('/criar',[GruposController::class, 'criar'])->name('criar');
    Route::middleware('permission:Grupos: criar')->post('/salvar',[GruposController::class, 'salvar'])->name('salvar');
    Route::middleware('permission:Grupos: editar')->get('/editar/{grupo}',[GruposController::class, 'editar'])->name('editar');
    Route::middleware('permission:Grupos: editar')->put('/alterar/{grupo}',[GruposController::class, 'atualizar'])->name('atualizar');
    Route::middleware('permission:Grupos: deletar')->delete('/deletar/{grupo}',[GruposController::class, 'deletar'])->name('deletar');
});