<?php

use App\Http\Controllers\OutboundController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum'); */

Route::apiResource('/salidas',OutboundController::class)
->parameters(['salidas'=>'outbound'])->except('destroy');

Route::patch('/salidas/{outbound}/status', [OutboundController::class, 'status']);