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
    Route::post('/trucks/{truck}/assign-driver', [TruckController::class, 'assignDriver'])
        ->name('truck.assignDriver');
    Route::post('/trucks/assign-driver-from-table', [TruckController::class, 'assignDriverFromTable'])
        ->name('truck.assignDriverFromTable');
    Route::post('/trucks/{truck}/assign-trailer', [TruckController::class, 'assignTrailer'])
        ->name('truck.assignTrailer');
    Route::post('/trucks/assign-trailer-from-table', [TruckController::class, 'assignTrailerFromTable'])
        ->name('truck.assignTrailerFromTable');
    Route::post('/trucks/{truck}/set-active', [TruckController::class, 'setActive'])
        ->name('truck.setActive');
    Route::post('/trucks/{truck}/set-inactive', [TruckController::class, 'setInactive'])
        ->name('truck.setInactive');
});
