<?php

namespace App\Livewire\Themes;

use App\Models\Invitation;
use Carbon\Carbon;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class WeddingWalasuji extends Component
{
    #[Locked]
    public Invitation $invitation;

    #[Locked]
    public ?array $metadata = null;

    #[Locked]
    public ?string $guestName = null;

    #[Validate('required|string|min:2|max:255')]
    public string $rsvpName = '';

    #[Validate('nullable|string|max:30')]
    public string $rsvpPhone = '';

    #[Validate('required|in:confirmed,declined,tentative')]
    public string $rsvpStatus = 'confirmed';

    #[Validate('required|integer|min:1|max:20')]
    public int $rsvpGuests = 1;

    #[Validate('required|string|min:3|max:1000')]
    public string $rsvpMessage = '';

    public function mount(Invitation $invitation, ?array $metadata = null): void
    {
        $this->invitation = $invitation;
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
            $wishes = $this->invitation->wishes()->latest()->take(50)->get();
            $totalWishesCount = $this->invitation->wishes()->count();
        } else {
            $wishes = collect([
                (object) [
                    'id' => 'demo-1',
                    'name' => 'Karaeng Wijaya',
                    'attendance_status' => 'confirmed',
                    'pax' => 2,
                    'message' => 'Barakallahu lakuma wa baraka alaikuma. Semoga menjadi keluarga sakinah, mawaddah, warahmah.',
                    'created_at' => Carbon::now()->subMinutes(18),
                ],
                (object) [
                    'id' => 'demo-2',
                    'name' => 'Daeng Baji',
                    'attendance_status' => 'confirmed',
                    'pax' => 1,
                    'message' => 'Selamat menempuh hidup baru! Semoga langgeng dan penuh berkah selalu.',
                    'created_at' => Carbon::now()->subHours(2),
                ],
                (object) [
                    'id' => 'demo-3',
                    'name' => 'Andi Ratna',
                    'attendance_status' => 'tentative',
                    'pax' => 1,
                    'message' => 'Doa terbaik menyertai hari bahagia kalian berdua. Turut berbahagia!',
                    'created_at' => Carbon::now()->subDays(1),
                ],
            ]);
            $totalWishesCount = $wishes->count();
        }

        return view('livewire.themes.wedding-walasuji', [
            'wishes' => $wishes,
            'totalWishesCount' => $totalWishesCount,
        ]);
    }
}
