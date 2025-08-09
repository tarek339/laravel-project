<?php

use App\Http\Controllers\CompanyController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/companies', [CompanyController::class, 'index'])
        ->name('companies.index');
    Route::post('/companies', [CompanyController::class, 'store'])
        ->name('company.store');
    Route::get('/companies/{company}', [CompanyController::class, 'show'])
        ->name('company.show');
    Route::put('/companies/{company}', [CompanyController::class, 'update'])
        ->name('company.update');
    Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])
        ->name('company.destroy');
    Route::delete('/companies', [CompanyController::class, 'destroyMultiple'])
        ->name('companies.destroyMultiple');
});
