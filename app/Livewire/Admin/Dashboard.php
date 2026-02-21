<?php

namespace App\Livewire\Admin;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use App\Models\User;
use App\Models\Invitation;
use App\Models\Theme;

#[Layout('components.layouts.admin')]
class Dashboard extends Component
{
    #[Computed]
    public function stats()
    {
        return [
            'total_users' => User::count(),
            'total_invitations' => Invitation::count(),
            'total_themes' => Theme::count(),
            // Mocking active invitations for demonstration
            'active_invitations' => Invitation::where('is_published', true)->count() ?? 0, 
        ];
    }

    public function render()
    {
        return view('livewire.admin.dashboard');
    }
}
