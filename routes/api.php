<?php

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ChatController;
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

    // Chat Routes
    Route::prefix('chat')->group(function () {
        Route::get('/conversations', [ChatController::class, 'index']);
        Route::post('/conversations', [ChatController::class, 'store']);
        Route::get('/conversations/{id}', [ChatController::class, 'show']);
        Route::delete('/conversations/{id}', [ChatController::class, 'destroy']);
        Route::get('/conversations/{id}/messages', [ChatController::class, 'messages']);
        Route::post('/messages', [ChatController::class, 'sendMessage']);
        Route::put('/messages/{id}/read', [ChatController::class, 'markAsRead']);
        Route::put('/conversations/{id}/read', [ChatController::class, 'markConversationAsRead']);
        Route::delete('/messages/{id}', [ChatController::class, 'deleteMessage']);
        Route::post('/upload', [ChatController::class, 'upload']);
        Route::get('/unread-count', [ChatController::class, 'unreadCount']);
    });
});
