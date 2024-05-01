<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TransactionsController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::prefix('wallet')->group(function () {
    Route::post('transfer', [TransactionsController::class, 'transfer'])
        ->name('transfer');
});
