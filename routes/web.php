<?php

use App\Http\Controllers\ImageController;
use App\Http\Controllers\SitemapController;
use App\Http\Middleware\IsAdmin;
use App\Livewire\Admin\ArticleForm as AdminArticleForm;
use App\Livewire\Admin\ArticleManagement as AdminArticleManagement;
use App\Livewire\Admin\Dashboard as AdminDashboard;
use App\Livewire\Admin\InvitationManagement as AdminInvitationManagement;
use App\Livewire\Admin\Settings as AdminSettings;
use App\Livewire\Admin\ThemeManagement as AdminThemeManagement;
use App\Livewire\Admin\UserManagement as AdminUserManagement;
use App\Livewire\DemoPage;
use App\Livewire\Pages\Auth\Login;
use App\Livewire\Pages\Auth\Register;
use App\Livewire\Pages\BlogList;
use App\Livewire\Pages\BlogShow;
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
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/img/{token}', [ImageController::class, 'show'])->name('image.show');

// Blog Routes
Route::get('/blog', BlogList::class)->name('articles.index');
Route::get('/blog/{slug}', BlogShow::class)->name('articles.show');

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('dashboard');

    // Admin Routes
    Route::middleware(IsAdmin::class)->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', AdminDashboard::class)->name('dashboard');
        Route::get('/users', AdminUserManagement::class)->name('users');
        Route::get('/invitations', AdminInvitationManagement::class)->name('invitations');
        Route::get('/themes', AdminThemeManagement::class)->name('themes');
        Route::get('/settings', AdminSettings::class)->name('settings');
        Route::get('/articles', AdminArticleManagement::class)->name('articles');
        Route::get('/articles/create', AdminArticleForm::class)->name('articles.create');
        Route::get('/articles/{id}/edit', AdminArticleForm::class)->name('articles.edit');
    });

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
Route::get('/i/demo', DemoPage::class)->name('invitation.demo');
Route::get('/i/{slug}', ThemePage::class)->name('invitation.show');
