<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Livewire\Component;

class JavaneseHeritage extends Component
{
    public Invitation $invitation;

    public ?array $metadata = null;

    public ?string $guestName = null;

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->guestName = request('kpd', 'Tamu Undangan');

        if ($metadata) {
            $this->metadata = $metadata;
        } else {
            $groom = $invitation->groom_nickname ?? 'Groom';
            $bride = $invitation->bride_nickname ?? 'Bride';
            $coverImage = $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200';

            $this->metadata = [
                'title' => "The Royal Wedding of $groom & $bride",
                'description' => "Kepada Yth. $this->guestName, Tanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.",
                'image' => $coverImage,
                'url' => url()->current(),
            ];
        }
    }

    public $rsvpName;
    public $rsvpStatus = 'confirmed';
    public $rsvpGuests = 1;
    public $rsvpMessage;

    protected $rules = [
        'rsvpName' => 'required|min:3',
        'rsvpStatus' => 'required',
        'rsvpGuests' => 'required|integer|min:1',
        'rsvpMessage' => 'required|min:5',
    ];

    public function submitRSVP()
    {
        $this->validate();

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'status' => $this->rsvpStatus === 'confirmed' ? 'Hadir' : 'Maaf, Tidak Bisa Hadir',
            'guests' => $this->rsvpGuests,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpName', 'rsvpStatus', 'rsvpGuests', 'rsvpMessage']);
        session()->flash('message', 'Matur nuwun, doa restu Anda telah kami terima.');
    }

    public function render()
    {
        return view('livewire.themes.javanese-heritage')
            ->layout('layouts.invitation-layout', ['metadata' => $this->metadata]);
    }
}
