<?php

use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\MobileAccessController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileThemeController;
use App\Http\Controllers\Api\TelegramWebhookController;
use Illuminate\Support\Facades\Route;

// ── Public theme catalog (no auth) ──────────────────────────────────────────
Route::get('mobile/themes', [MobileThemeController::class, 'index']);

Route::prefix('invitations/{invitation}')->group(function () {
    Route::post('/rsvp', [InvitationController::class, 'submitRsvp']);
    Route::post('/wishes', [InvitationController::class, 'submitWish']);
    Route::get('/wishes', [InvitationController::class, 'getWishes']);
    Route::get('/stats', [InvitationController::class, 'getStats']);
});

Route::prefix('mobile/access')->group(function () {
    Route::post('/exchange', [MobileAccessController::class, 'exchange'])
        ->middleware('throttle:mobile-access');
    Route::post('/refresh', [MobileAccessController::class, 'refresh'])
        ->middleware('throttle:mobile-refresh');

    Route::middleware('mobile.session')->group(function () {
        Route::post('/revoke', [MobileAccessController::class, 'revoke']);
        Route::get('/devices', [MobileAccessController::class, 'devices']);
        Route::get('/invitations', [\App\Http\Controllers\Api\MobileInvitationController::class, 'index']);
        Route::get('/invitations/{id}', [\App\Http\Controllers\Api\MobileInvitationController::class, 'show']);
    });
});

Route::prefix('mobile/auth')->middleware('throttle:mobile-auth')->group(function () {
    Route::post('/register', [MobileAuthController::class, 'register']);
    Route::post('/login', [MobileAuthController::class, 'login']);
});

// Telegram Bot Webhook
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
