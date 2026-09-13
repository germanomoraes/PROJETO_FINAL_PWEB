<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ConfiguracaoTaxaController;
use App\Http\Controllers\ConsumidorController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\FaturaController;
use App\Http\Controllers\LeituraController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('dashboard'));

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::resource('consumidores', ConsumidorController::class)->except(['show', 'destroy']);

    Route::get('/leituras', [LeituraController::class, 'index'])->name('leituras.index');
    Route::get('/leituras/nova', [LeituraController::class, 'create'])->name('leituras.create');
    Route::post('/leituras', [LeituraController::class, 'store'])->name('leituras.store');

    Route::get('/faturas', [FaturaController::class, 'index'])->name('faturas.index');
    Route::patch('/faturas/{fatura}/pagar', [FaturaController::class, 'marcarPaga'])->name('faturas.pagar');

    // Apenas gestor (autorização verificada no FormRequest/Policy)
    Route::get('/configuracoes/taxa', [ConfiguracaoTaxaController::class, 'edit'])->name('configuracoes.edit');
    Route::put('/configuracoes/taxa', [ConfiguracaoTaxaController::class, 'update'])->name('configuracoes.update');
});
