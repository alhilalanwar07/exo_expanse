<?php

namespace App\Livewire;

use App\Models\Wish;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WishesFeed extends Component
{
    #[Locked]
    public int $invitationId;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string|min:5|max:1000')]
    public string $message = '';

    public function mount(int $invitationId): void
    {
        $this->invitationId = $invitationId;
    }

    public function submit(): void
    {
        $this->validate();

        Wish::create([
            'invitation_id' => $this->invitationId,
            'name' => $this->name,
            'message' => $this->message,
        ]);

        $this->reset(['name', 'message']);
        session()->flash('message', 'Ucapan terkirim!');
    }

    public function render()
    {
        return view('livewire.wishes-feed', [
            'wishes' => Wish::where('invitation_id', $this->invitationId)->latest()->get(),
        ]);
    }
}
