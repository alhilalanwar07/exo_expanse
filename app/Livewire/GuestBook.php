<?php

namespace App\Livewire;

use App\Enums\GuestStatus;
use App\Models\Guest;
use App\Models\Wish;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Livewire\Component;

class GuestBook extends Component
{
    public $invitationId;

    // Form State
    public $name;

    public $status = 'confirmed';

    // public $total_guests = 1; // Removed from UI
    public $message;

    protected $listeners = ['refreshWishes' => '$refresh'];

    public function mount($invitationId)
    {
        $this->invitationId = $invitationId;
        // Ambil nama dari URL parameter ?kpd=NamaTamu
        $this->name = request()->query('kpd');
    }

    protected function rules()
    {
        return [
            'name' => 'required|min:3|string|max:255',
            'status' => ['required', Rule::enum(GuestStatus::class)],
            // 'total_guests' => 'required|integer|min:1|max:5', // Removed validation
            'message' => 'nullable|string|max:1000',
        ];
    }

    public function submit()
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
