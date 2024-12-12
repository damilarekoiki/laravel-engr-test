<?php

use App\Http\Controllers\ClaimController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user', function (Request $request) {
        return $request->user();
    });

    
});

Route::prefix('/claims')->group(function () {
    Route::post('/', [ClaimController::class, 'store']);
});
