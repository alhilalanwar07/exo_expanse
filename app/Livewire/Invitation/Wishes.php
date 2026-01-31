<?php

namespace App\Livewire\Invitation;

use App\Models\Invitation;
use App\Models\Wish;
use Livewire\Component;

class Wishes extends Component
{
    public Invitation $invitation;
    public string $theme = 'rose';

    public string $name = '';
    public string $message = '';
    
    public int $limit = 10;
    public bool $isSuccess = false;

    public function mount(Invitation $invitation, string $theme = 'rose')
    {
        $this->invitation = $invitation;
        $this->theme = $theme;
    }

    public function submit()
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
        ], [
            'name.required' => 'Nama wajib diisi.',
            'message.required' => 'Pesan wajib diisi.',
        ]);

        Wish::create([
            'invitation_id' => $this->invitation->id,
            'name' => $validated['name'],
            'message' => $validated['message'],
        ]);

        $this->name = '';
        $this->message = '';
        $this->isSuccess = true;
        
        // Hide success message after 3 seconds
        $this->dispatch('wish-submitted');
    }

    public function loadMore()
    {
        $this->limit += 10;
    }

    public function render()
    {
        $wishes = $this->invitation->wishes()
            ->latest()
            ->take($this->limit)
            ->get();
            
        return view('livewire.invitation.wishes', [
            'wishes' => $wishes,
            'totalWishes' => $this->invitation->wishes()->count()
        ]);
    }
}
