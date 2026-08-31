<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BrewController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/ingresar', [AuthController::class, 'create'])->name('login');
    Route::post('/ingresar', [AuthController::class, 'store'])
        ->middleware('throttle:5,1')
        ->name('login.store');
});

Route::middleware('auth')->group(function () {
    Route::get('/', [BrewController::class, 'index'])->name('home');
    Route::post('/salir', [AuthController::class, 'destroy'])->name('logout');

    Route::post('/recetas', [BrewController::class, 'storeRecipe'])->name('recipes.store');
    Route::patch('/recetas/{recipe}', [BrewController::class, 'updateRecipe'])->name('recipes.update');
    Route::delete('/recetas/{recipe}', [BrewController::class, 'destroyRecipe'])->name('recipes.destroy');

    Route::post('/lotes', [BrewController::class, 'storeBatch'])->name('batches.store');
    Route::patch('/lotes/{batch}', [BrewController::class, 'updateBatch'])->name('batches.update');
    Route::post('/lotes/{batch}/mediciones', [BrewController::class, 'storeReading'])->name('readings.store');
    Route::post('/lotes/{batch}/bitacora', [BrewController::class, 'storeLog'])->name('logs.store');

    Route::post('/recordatorios', [BrewController::class, 'storeReminder'])->name('reminders.store');
    Route::patch('/recordatorios/{reminder}', [BrewController::class, 'toggleReminder'])->name('reminders.toggle');

    Route::post('/inventario', [BrewController::class, 'storeInventory'])->name('inventory.store');
    Route::patch('/inventario/{item}', [BrewController::class, 'adjustInventory'])->name('inventory.adjust');
});
