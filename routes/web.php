<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('home');
});

Auth::routes(['register' => false]);

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');


Route::middleware('auth')->get('/perfil', function () {
    return view('auth.perfil');
})->name('perfil');


require __DIR__ . '/grupos.php';
require __DIR__ . '/projetos.php';