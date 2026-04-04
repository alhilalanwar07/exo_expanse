<?php

namespace App\Providers;

use App\Livewire\Themes\FloralRomance;
use App\Livewire\Themes\Generic;
use App\Livewire\Themes\ModernElegance;
use App\Livewire\Themes\RoyalGold;
use Illuminate\Pagination\Paginator;
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
        Livewire::component('themes.royal-gold', RoyalGold::class);
        Livewire::component('themes.floral-romance', FloralRomance::class);
        Livewire::component('themes.modern-elegance', ModernElegance::class);
        Livewire::component('themes.generic', Generic::class);
        Paginator::useTailwind();
    }
}
