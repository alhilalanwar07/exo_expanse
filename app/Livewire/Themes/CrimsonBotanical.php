<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class CrimsonBotanical extends Component
{
    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public ?array $metadata = null;

    #[Locked]
    public ?string $guestName = null;

    #[Validate('required|string|min:2|max:255')]
    public string $rsvpName = '';

    #[Validate('required|in:confirmed,declined,tentative')]
    public string $rsvpStatus = 'confirmed';

    #[Validate('required|integer|min:1|max:20')]
    public int $rsvpGuests = 1;

    #[Validate('required|string|min:3|max:1000')]
    public string $rsvpMessage = '';

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
        if ($this->invitation->exists) {
            $this->invitation->loadMissing(['backgroundMusic', 'photos']);
        }
        $this->metadata = $metadata;

        $guest = trim((string) request('to', request('kpd', 'Tamu Undangan')));
        $this->guestName = $guest !== '' ? $guest : 'Tamu Undangan';

        if (! in_array($this->guestName, ['Tamu Undangan', 'Nama Tamu'], true)) {
            $this->rsvpName = $this->guestName;
        }
    }

    public function submitRSVP(): void
    {
        $this->validate();

        if (! $this->invitation->exists) {
            $this->reset(['rsvpMessage']);
            session()->flash('rsvp_success', 'Terima kasih! Pada mode demo, ucapan Anda ditampilkan sebagai simulasi.');

            return;
        }

        $this->invitation->wishes()->create([
            'name' => $this->rsvpName,
            'attendance_status' => $this->rsvpStatus,
            'pax' => $this->rsvpStatus === 'confirmed' ? $this->rsvpGuests : 1,
            'message' => $this->rsvpMessage,
        ]);

        $this->reset(['rsvpMessage']);
        session()->flash('rsvp_success', 'Terima kasih, ucapan dan konfirmasi kehadiran Anda telah tersimpan!');
    }

    public function render()
    {
        if ($this->invitation->exists) {
            $this->invitation->loadMissing(['backgroundMusic', 'photos']);
            $wishes = $this->invitation->wishes()->latest()->take(50)->get();
            $totalWishesCount = $this->invitation->wishes()->count();
        } else {
            $wishes = collect([
                (object) [
                    'id' => 'demo-1',
                    'name' => 'Rizky Pratama',
                    'attendance_status' => 'confirmed',
                    'pax' => 2,
                    'message' => 'Selamat menempuh hidup baru! Semoga menjadi keluarga sakinah, mawaddah, warahmah.',
                    'created_at' => Carbon::now()->subMinutes(15),
                ],
                (object) [
                    'id' => 'demo-2',
                    'name' => 'Nadia Putri',
                    'attendance_status' => 'confirmed',
                    'pax' => 1,
                    'message' => 'Barakallahu lakuma wa baraka alaikuma. Bahagia selalu dunia akhirat!',
                    'created_at' => Carbon::now()->subHours(3),
                ],
                (object) [
                    'id' => 'demo-3',
                    'name' => 'Hendra Wijaya',
                    'attendance_status' => 'tentative',
                    'pax' => 1,
                    'message' => 'Semoga acara akad dan resepsi berjalan lancar penuh keberkahan.',
                    'created_at' => Carbon::now()->subDays(1),
                ],
            ]);
            $totalWishesCount = $wishes->count();
        }

        return view('livewire.themes.crimson-botanical', [
            'wishes' => $wishes,
            'totalWishesCount' => $totalWishesCount,
        ]);
    }
}
