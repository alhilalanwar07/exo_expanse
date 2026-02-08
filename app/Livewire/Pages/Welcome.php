<?php

namespace App\Livewire\Pages;

use App\Models\Theme;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('layouts.guest')]
#[Title('Undangan Digital Premium & Elegan')]
class Welcome extends Component
{
    #[Computed]
    public function themes()
    {
        // Pastikan Model Theme ada, jika tidak, mock
        if (class_exists(\App\Models\Theme::class)) {
            return \App\Models\Theme::where('is_active', true)->limit(4)->get();
        }

        return collect([]);
    }

    public function render()
    {
        return view('livewire.pages.welcome');
    }
}
