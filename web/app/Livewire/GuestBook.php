<?php

namespace App\Livewire;

use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Validate;
use Livewire\Component;

class GuestBook extends Component
{
    #[Locked]
    public int $invitationId;

    #[Validate('required|string|min:3|max:255')]
    public string $name = '';

    #[Validate('required|string')]
    public string $status = 'confirmed';

    #[Validate('nullable|string|max:1000')]
    public string $message = '';

    protected $listeners = ['refreshWishes' => '$refresh'];

    public function mount(int $invitationId): void
    {
        $this->invitationId = $invitationId;
        $this->name = request()->query('kpd', '');
    }

    public function submit(): void
    {
        $this->validate();

        DB::transaction(function () {
            // 1. Simpan Data Tamu (RSVP)
            $guest = Guest::create([
                'invitation_id' => $this->invitationId,
                'name' => $this->name,
                'slug' => Str::slug($this->name).'-'.Str::random(6),
                'status' => $this->status,
                'pax' => 1, // Default 1 tamu
            ]);

            // 2. Simpan Ucapan (jika ada)
            if (! empty($this->message)) {
                Wish::create([
                    'invitation_id' => $this->invitationId,
                    'name' => $this->name, // Pakai nama dari input RSVP
                    'message' => $this->message,
                ]);
            }
        });

        // Reset form
        $this->reset(['name', 'status', 'message']);

        // Notifikasi sukses
        session()->flash('message', 'Terima kasih! Konfirmasi kehadiran dan ucapan Anda telah tersimpan.');
    }

    public function render()
    {
        return view('livewire.guest-book', [
            // Ambil daftar ucapan untuk ditampilkan di bawah form
            'wishes' => Wish::where('invitation_id', $this->invitationId)->latest()->get(),
        ]);
    }
}
