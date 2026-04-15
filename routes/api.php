<?php

use App\Http\Controllers\Api\InvitationController;
use App\Http\Controllers\Api\MobileAccessController;
use App\Http\Controllers\Api\MobileAuthController;
use App\Http\Controllers\Api\MobileInvitationController;
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
        Route::patch('/profile', [MobileAccessController::class, 'updateProfile']);
        Route::get('/invitations', [MobileInvitationController::class, 'index']);
        Route::post('/invitations', [MobileInvitationController::class, 'store']);
        Route::get('/invitations/{id}', [MobileInvitationController::class, 'show']);
        Route::patch('/invitations/{id}', [MobileInvitationController::class, 'update']);
        Route::patch('/invitations/{id}/theme', [MobileInvitationController::class, 'updateTheme']);
        Route::post('/invitations/{id}/publish', [MobileInvitationController::class, 'publish']);
        // Photos
        Route::post('/invitations/{id}/cover-photo', [MobileInvitationController::class, 'uploadCoverPhoto']);
        Route::post('/invitations/{id}/groom-photo', [MobileInvitationController::class, 'uploadGroomPhoto']);
        Route::post('/invitations/{id}/bride-photo', [MobileInvitationController::class, 'uploadBridePhoto']);
        Route::post('/invitations/{id}/photos', [MobileInvitationController::class, 'uploadGalleryPhoto']);
        Route::delete('/invitations/{id}/photos/{photoId}', [MobileInvitationController::class, 'deleteGalleryPhoto']);
        // Guests
        Route::get('/invitations/{id}/guests', [MobileInvitationController::class, 'getGuests']);
        Route::post('/invitations/{id}/guests', [MobileInvitationController::class, 'storeGuests']);
        Route::delete('/invitations/{id}/guests/{guestId}', [MobileInvitationController::class, 'deleteGuest']);
    });
});

Route::prefix('mobile/auth')->middleware('throttle:mobile-auth')->group(function () {
    Route::post('/forgot-password', [MobileAuthController::class, 'forgotPassword']);
    Route::post('/register', [MobileAuthController::class, 'register']);
    Route::post('/login', [MobileAuthController::class, 'login']);
});

// Telegram Bot Webhook
Route::post('/telegram/webhook', [TelegramWebhookController::class, 'handle']);
