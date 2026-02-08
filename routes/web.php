<?php

use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\Dashboard;
use App\Livewire\Pages\Welcome;
use App\Livewire\ThemePage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Public Routes
Route::get('/', Welcome::class)->name('home');

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Invitation Management
    Route::get('/invitations/new', \App\Livewire\Pages\Invitation\TypeSelector::class)->name('invitations.new');
    Route::get('/invitations/create', \App\Livewire\Pages\Invitation\Builder::class)->name('invitations.create');
    Route::get('/invitations/{id}/edit', \App\Livewire\Pages\Invitation\Builder::class)->name('invitations.edit');

    // To be migrated
    Route::get('/invitations/{id}/sebar', \App\Livewire\Pages\Invitation\Sebar::class)->name('invitations.sebar');
    // Route::get('/invitations/{invitation}/customize', \App\Livewire\ThemeCustomizer::class)->name('invitations.customize');

    // Logout
    Route::post('/logout', function () {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect('/');
    })->name('logout');
});

// Public Invitation View
Route::get('/i/{slug}', ThemePage::class)->name('invitation.show');
