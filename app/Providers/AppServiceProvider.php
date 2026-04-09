<?php

namespace App\Providers;

use App\Livewire\Themes\FloralRomance;
use App\Livewire\Themes\Generic;
use App\Livewire\Themes\ModernElegance;
use App\Livewire\Themes\RoyalGold;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Livewire\Livewire;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('mobile-auth', function (Request $request): array {
            $ipAddress = $request->ip() ?? 'unknown';
            $email = strtolower(trim((string) $request->input('email')));
            $credentialKey = $email !== '' ? $email : 'anonymous';

            return [
                Limit::perMinute(20)
                    ->by('mobile-auth:ip:'.$ipAddress),
                Limit::perMinute(8)
                    ->by('mobile-auth:credential:'.$credentialKey),
            ];
        });

        RateLimiter::for('mobile-access', function (Request $request): array {
            $ipAddress = $request->ip() ?? 'unknown';
            $accessCode = strtoupper(trim((string) $request->input('access_code')));
            $codeKey = $accessCode !== '' ? $accessCode : 'anonymous';

            return [
                Limit::perMinute(20)
                    ->by('mobile-access:ip:'.$ipAddress),
                Limit::perMinute(8)
                    ->by('mobile-access:code:'.$codeKey),
            ];
        });

        RateLimiter::for('mobile-refresh', function (Request $request): array {
            $ipAddress = $request->ip() ?? 'unknown';
            $refreshToken = trim((string) $request->input('refresh_token'));
            $tokenHash = $refreshToken !== '' ? hash('sha256', $refreshToken) : 'anonymous';

            return [
                Limit::perMinute(30)
                    ->by('mobile-refresh:ip:'.$ipAddress),
                Limit::perMinute(20)
                    ->by('mobile-refresh:token:'.$tokenHash),
            ];
        });

        Livewire::component('themes.royal-gold', RoyalGold::class);
        Livewire::component('themes.floral-romance', FloralRomance::class);
        Livewire::component('themes.modern-elegance', ModernElegance::class);
        Livewire::component('themes.generic', Generic::class);
        Paginator::useTailwind();
    }
}
