<?php

use App\Http\Controllers\Api\MobileAccessController;
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
use App\Livewire\Pages\Invitation\Builder;
use App\Livewire\Pages\Invitation\Sebar;
use App\Livewire\Pages\Invitation\TypeSelector;
use App\Livewire\Pages\Welcome;
use App\Livewire\ThemePage;
use App\Models\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
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

// Block direct storage access — all files served through /img/{token}
Route::get('/storage/{path}', fn () => abort(403))->where('path', '.*');

// Blog Routes
Route::get('/blog', BlogList::class)->name('articles.index');
Route::get('/blog/{slug}', BlogShow::class)->name('articles.show');

// Guest Routes (Not Authenticated)
Route::middleware('guest')->group(function () {
    Route::get('/login', Login::class)->name('login');
    Route::get('/register', Register::class)->name('register');
});

// Email Verification Notice (accessible without auth — needed after logout-on-unverified)
Route::get('/email/verify', function (\Illuminate\Http\Request $request) {
    return view('auth.verification-notice', [
        'email' => $request->user()?->email ?? session('verification.email'),
    ]);
})->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, string $id, string $hash) {
    if (! $request->hasValidSignature()) {
        abort(403);
    }

    $user = User::query()->findOrFail($id);

    if (! hash_equals($hash, sha1($user->getEmailForVerification()))) {
        abort(403);
    }

    if (! $user->hasVerifiedEmail()) {
        $user->markEmailAsVerified();
        event(new Verified($user));
    }

    return redirect()->route('verification.success');
})->middleware('throttle:6,1')->name('verification.verify');

Route::get('/email/verified/success', fn () => view('auth.activation-success'))
    ->name('verification.success');

// Authenticated Routes
Route::middleware('auth')->group(function () {
    Route::post('/email/verification-notification', function (Request $request) {
        $user = $request->user();

        if ($user && ! $user->hasVerifiedEmail()) {
            $user->sendEmailVerificationNotification();
        }

        return back()->with('status', 'verification-link-sent');
    })->middleware('throttle:6,1')->name('verification.send');

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
    Route::get('/invitations/new', TypeSelector::class)->name('invitations.new');
    Route::get('/invitations/create', Builder::class)->name('invitations.create');
    Route::get('/invitations/{id}/edit', Builder::class)->name('invitations.edit');
    Route::post('/mobile/access/issue', [MobileAccessController::class, 'issue'])->name('mobile.access.issue');

    // To be migrated
    Route::get('/invitations/{id}/sebar', Sebar::class)->name('invitations.sebar');
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
// The demo route removes X-Frame-Options so it can be embedded in the mobile app's in-app browser (iframe on web).
Route::get('/i/demo', DemoPage::class)
    ->name('invitation.demo')
    ->middleware(function (\Illuminate\Http\Request $request, \Closure $next) {
        /** @var \Illuminate\Http\Response $response */
        $response = $next($request);
        $response->headers->remove('X-Frame-Options');
        $response->headers->set('Content-Security-Policy', "frame-ancestors *");
        return $response;
    });
Route::get('/i/{slug}', ThemePage::class)->name('invitation.show');
