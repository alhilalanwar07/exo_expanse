<?php

use App\Http\Controllers\InvitationController;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::livewire('/', 'pages::welcome')->name('home');

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::livewire('/login', 'pages::auth.login')->name('login');
    Route::livewire('/register', 'pages::auth.register')->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::livewire('/dashboard', 'pages::dashboard')->name('dashboard');
    
    // Invitation Management
    Route::livewire('/invitations/new', 'pages::invitation.type-selector')->name('invitations.new');
    Route::livewire('/invitations/create', 'pages::invitation.builder')->name('invitations.create');
    Route::livewire('/invitations/{id}/edit', 'pages::invitation.builder')->name('invitations.edit');
    Route::livewire('/invitations/{id}/sebar', 'pages::invitation.sebar')->name('invitations.sebar');
    
    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/');
    })->name('logout');
});

// Public Invitation View
Route::get('/i/{slug}', [InvitationController::class, 'show'])->name('invitation.show');
Route::post('/i/{slug}/rsvp', [InvitationController::class, 'rsvp'])->name('invitation.rsvp');
Route::post('/i/{slug}/wish', [InvitationController::class, 'wish'])->name('invitation.wish');
