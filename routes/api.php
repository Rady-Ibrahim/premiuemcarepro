<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProviderController;
use App\Http\Controllers\API\TicketController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login']);

Route::get('/settings', function () {
    return response()->json(['settings' => setting()->all()]);
});

Route::middleware('auth:sanctum')->group(function ($request) {
    Route::post('/change-password', [AuthController::class, 'changePassword']);

    Route::get('/providers', [ProviderController::class, 'index']);
    Route::get('/provider/{id}', [ProviderController::class, 'show']);

    Route::get('tickets', [TicketController::class, 'index']);
    Route::get('ticket/{id}', [TicketController::class, 'show']);
    Route::post('ticket', [TicketController::class, 'store']);
    Route::put('ticket/{id}', [TicketController::class, 'update']);
    Route::delete('ticket/{id}', [TicketController::class, 'destroy']);
    Route::post('/ticket/{ticket}/details', [TicketController::class, 'detailStore']);
});
