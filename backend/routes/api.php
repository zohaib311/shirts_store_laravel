<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ShirtController;


Route::controller(ShirtController::class)->group(function () {

    Route::get('/products', 'getApi');
    Route::post('/products', 'insertApi');
    Route::put('/products/{id}', 'updateApi');
    Route::delete('/products/{id}', 'deleteApi');
});
