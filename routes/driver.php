<?php

use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/driver', [DriverController::class, 'index'])->name('driver.index');
    Route::post('/add-driver', [DriverController::class, 'store'])->name('driver.store');
    Route::get('/drivers/{driver}', [DriverController::class, 'show'])->name('driver.show');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('driver.update');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('driver.destroy');
    Route::delete('/drivers', [DriverController::class, 'destroyMultiple'])->name('driver.destroyMultiple');
});
