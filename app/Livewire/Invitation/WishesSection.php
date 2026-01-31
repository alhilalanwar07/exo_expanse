<?php

namespace App\Livewire\Invitation;

use App\Models\Invitation;
use App\Models\Wish;
use Livewire\Component;
use Livewire\WithPagination;

class WishesSection extends Component
{
    use WithPagination;

    public Invitation $invitation;

    public string $name = '';
    public string $message = '';

    public function mount(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function save()
    {
        $this->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ]);

        Wish::create([
            'invitation_id' => $this->invitation->id,
            'name' => $this->name,
            'message' => $this->message,
        ]);

        $this->name = '';
        $this->message = '';
        
        session()->flash('wish_success', 'Terima kasih atas ucapan Anda!');
    }

    public function render()
    {
        return view('livewire.invitation.wishes-section', [
            'wishes' => $this->invitation->wishes()->latest()->paginate(10),
        ]);
    }
}
