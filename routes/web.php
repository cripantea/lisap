<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrdineController;
use App\Http\Controllers\SpedizioneController;
use App\Http\Controllers\ProvvigioneController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\AgentiController;
use App\Http\Controllers\ReportController;

// Dashboard
Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

// Ordini
Route::prefix('ordini')->name('ordini.')->group(function () {
    Route::get('/', [OrdineController::class, 'index'])->name('index');
    Route::get('/{ordine}', [OrdineController::class, 'show'])->name('show');
});

// Import da piattaforme
Route::prefix('import')->name('import.')->group(function () {
    Route::get('/', [ImportController::class, 'index'])->name('index');
    Route::post('/{platform}', [ImportController::class, 'importFromPlatform'])->name('platform');
});

// Spedizioni
Route::prefix('spedizioni')->name('spedizioni.')->group(function () {
    Route::get('/', [SpedizioneController::class, 'index'])->name('index');
    Route::post('/ordine/{ordine}/invia', [SpedizioneController::class, 'inviaAdAmazon'])->name('invia');
    Route::post('/{spedizione}/aggiorna', [SpedizioneController::class, 'aggiornaStato'])->name('aggiorna');
    Route::get('/tracking/{trackingNumber}', [SpedizioneController::class, 'tracking'])->name('tracking');
});

// Provvigioni
Route::prefix('provvigioni')->name('provvigioni.')->group(function () {
    Route::get('/', [ProvvigioneController::class, 'index'])->name('index');
    Route::get('/riepilogo', [ProvvigioneController::class, 'riepilogo'])->name('riepilogo');
    Route::get('/agente/{agente}', [ProvvigioneController::class, 'agente'])->name('agente');
});

// Agenti
Route::prefix('agenti')->name('agenti.')->group(function () {
    Route::get('/', [AgentiController::class, 'index'])->name('index');
    Route::get('/create', [AgentiController::class, 'create'])->name('create');
    Route::post('/', [AgentiController::class, 'store'])->name('store');
    Route::get('/{agente}', [AgentiController::class, 'show'])->name('show');
    Route::get('/{agente}/edit', [AgentiController::class, 'edit'])->name('edit');
    Route::put('/{agente}', [AgentiController::class, 'update'])->name('update');
    Route::delete('/{agente}', [AgentiController::class, 'destroy'])->name('destroy');
    Route::post('/sposta-cap', [AgentiController::class, 'spostaCAP'])->name('sposta-cap');
});

// Report
Route::prefix('report')->name('report.')->group(function () {
    Route::get('/', [ReportController::class, 'index'])->name('index');
    Route::get('/vendite-csv', [ReportController::class, 'venditeMensiliCSV'])->name('vendite-csv');
    Route::get('/vendite-pdf', [ReportController::class, 'venditeMensiliPDF'])->name('vendite-pdf');
    Route::get('/agente/{agente}/pdf', [ReportController::class, 'agenteDettaglioPDF'])->name('agente-pdf');
});


