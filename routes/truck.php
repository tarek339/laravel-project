<?php

use App\Http\Controllers\TruckController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/trucks', [TruckController::class, 'index'])
        ->name('trucks.index');
    Route::post('/trucks', [TruckController::class, 'store'])
        ->name('truck.store');
    Route::get('/trucks/{truck}', [TruckController::class, 'show'])
        ->name('truck.show');
    Route::put('/trucks/{truck}', [TruckController::class, 'update'])
        ->name('truck.update');
    Route::delete('/trucks/{truck}', [TruckController::class, 'destroy'])
        ->name('truck.destroy');
    Route::delete('/trucks', [TruckController::class, 'destroyMultiple'])
        ->name('trucks.destroyMultiple');
});
