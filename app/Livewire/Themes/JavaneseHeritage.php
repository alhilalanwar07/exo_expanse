<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use App\Services\ImageService;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class JavaneseHeritage extends Component
{
    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public ?array $metadata = null;

    #[Locked]
    public ?string $guestName = null;

    #[Validate('required|string|min:3|max:255')]
    public string $rsvpName = '';

    #[Validate('required|in:confirmed,declined')]
    public string $rsvpStatus = 'confirmed';

    #[Validate('required|integer|min:1|max:10')]
    public int $rsvpGuests = 1;

    #[Validate('required|string|min:5|max:1000')]
    public string $rsvpMessage = '';

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        $this->guestName = request('kpd', 'Tamu Undangan');

        if ($metadata) {
            $this->metadata = $metadata;
        } else {
            $groom = $invitation->groom_nickname ?? 'Groom';
            $bride = $invitation->bride_nickname ?? 'Bride';
            $title = trim((string) ($invitation->title ?? '')) !== ''
                ? trim((string) $invitation->title)
                : "The Royal Wedding of $groom & $bride";
            $ogMeta = app(ImageService::class)->resolveOgImageMetadata(
                $invitation->cover_image,
                $invitation->theme?->thumbnail_url
            );

            $this->metadata = [
                'title' => $title,
                'description' => "Kepada Yth. $this->guestName, Tanpa mengurangi rasa hormat, kami bermaksud mengundang Bapak/Ibu/Saudara/i untuk menghadiri acara pernikahan kami.",
                'image' => $ogMeta['url'],
                'image_alt' => $title,
                'image_type' => $ogMeta['type'],
                'image_width' => $ogMeta['width'],
                'image_height' => $ogMeta['height'],
                'url' => url()->current(),
            ];
        }
    }

    public function submitRSVP(): void
    {
        $this->validate();

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'attendance_status' => $this->rsvpStatus,
            'pax' => $this->rsvpGuests,
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
