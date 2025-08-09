<?php

use App\Http\Controllers\TrailerController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/trailers', [TrailerController::class, 'index'])
        ->name('trailers.index');
    Route::post('/trailers', [TrailerController::class, 'store'])
        ->name('trailer.store');
    Route::get('/trailers/{trailer}', [TrailerController::class, 'show'])
        ->name('trailer.show');
    Route::put('/trailers/{trailer}', [TrailerController::class, 'update'])
        ->name('trailer.update');
    Route::delete('/trailers/{trailer}', [TrailerController::class, 'destroy'])
        ->name('trailer.destroy');
    Route::delete('/trailers', [TrailerController::class, 'destroyMultiple'])
        ->name('trailers.destroyMultiple');
});
