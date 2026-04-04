<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Component;

class Special03 extends Component
{
    public Invitation $invitation;

    public function render()
    {
        return view('livewire.themes.special-03');
    }
}
