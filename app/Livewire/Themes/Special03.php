<?php

namespace App\Livewire\Themes;

use Livewire\Component;
use App\Models\Invitation;

class Special03 extends Component
{
    public Invitation $invitation;

    public function render()
    {
        return view('livewire.themes.special-03');
    }
}
