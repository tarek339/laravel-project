<?php

use App\Http\Controllers\DriverController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/drivers', [DriverController::class, 'index'])->name('drivers.index');
    Route::post('/add-driver', [DriverController::class, 'store'])->name('driver.store');
    Route::get('/drivers/{driver}', [DriverController::class, 'show'])->name('driver.show');
    Route::put('/drivers/{driver}', [DriverController::class, 'update'])->name('driver.update');
    Route::delete('/drivers/{driver}', [DriverController::class, 'destroy'])->name('driver.destroy');
    Route::delete('/drivers', [DriverController::class, 'destroyMultiple'])->name('drivers.destroyMultiple');
    Route::post('drivers/{driver}/assign-truck', [DriverController::class, 'assignTruck'])->name('driver.assignTruck');
    Route::post('drivers/{driver}/set-active', [DriverController::class, 'setActive'])->name('driver.setActive');
    Route::post('drivers/{driver}/set-inactive', [DriverController::class, 'setInactive'])->name('driver.setInactive');
});
