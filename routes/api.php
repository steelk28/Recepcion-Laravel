<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OutboundController;

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');
 */

Route::apiResource('/outbound', OutboundController::class)->except('destroy')->parameters(['outbound'=>'outbound']);
Route::patch('/outbound/{outbound}/status', [OutboundController::class, 'status']);