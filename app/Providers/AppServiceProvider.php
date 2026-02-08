<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

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
        \Livewire\Livewire::component('themes.royal-gold', \App\Livewire\Themes\RoyalGold::class);
        \Livewire\Livewire::component('themes.floral-romance', \App\Livewire\Themes\FloralRomance::class);
        \Livewire\Livewire::component('themes.modern-elegance', \App\Livewire\Themes\ModernElegance::class);
        \Livewire\Livewire::component('themes.generic', \App\Livewire\Themes\Generic::class);
    }
}
