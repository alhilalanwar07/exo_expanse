<?php

use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

Route::prefix('invitations/{invitation}')->group(function () {
    Route::post('/rsvp', [InvitationController::class, 'submitRsvp']);
    Route::post('/wishes', [InvitationController::class, 'submitWish']);
    Route::get('/wishes', [InvitationController::class, 'getWishes']);
    Route::get('/stats', [InvitationController::class, 'getStats']);
});

// Telegram Bot Webhook
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
