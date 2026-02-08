<?php

use Livewire\Volt\Component;
use App\Models\Invitation;
use App\Models\Guest;
use App\Services\GuestService;
use App\Enums\GuestStatus;
use Livewire\Attributes\Validate;

new class extends Component
{
    public Invitation $invitation;
    public ?Guest $guest = null;
    public string $theme = 'rose';

    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|in:confirmed,declined')]
    public $status = 'confirmed';

    #[Validate('required|integer|min:1|max:10')]
    public $pax = 1;

    public $success = false;

    public function mount(Invitation $invitation, ?Guest $guest = null, string $theme = 'rose')
    {
        $this->invitation = $invitation;
        $this->guest = $guest;
        $this->theme = $theme;

        if ($guest) {
            $this->name = $guest->name;
            $this->status = $guest->status->value;
            $this->pax = $guest->pax;
        }

        if (request()->has('kpd')) {
            $this->name = request('kpd');
        }
    }

    public function save(GuestService $guestService)
    {
        $this->validate();

        if ($this->guest) {
            $guestService->updateRsvp(
                $this->guest, 
                GuestStatus::from($this->status), 
                $this->status === 'confirmed' ? $this->pax : 0
            );
        } else {
            $guestService->addGuest($this->invitation, [
                'name' => $this->name,
                'status' => $this->status,
                'pax' => $this->status === 'confirmed' ? $this->pax : 0,
            ]);
        }

        $this->success = true;
    }
};
?>

<div class="w-full max-w-xl mx-auto px-4 py-12" x-data="{ status: $wire.entangle('status') }">
    <div class="glass rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden group">
        {{-- Decorative Elements --}}
        <div class="absolute -top-12 -right-12 w-24 h-24 bg-rose-500/10 rounded-full blur-2xl group-hover:bg-rose-500/20 transition-all duration-700"></div>
        <div class="absolute -bottom-12 -left-12 w-32 h-32 bg-amber-500/10 rounded-full blur-2xl group-hover:bg-amber-500/20 transition-all duration-700"></div>

        <div x-show="!$wire.success" x-transition:enter="transition ease-out duration-500" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <h2 class="font-heading text-3xl md:text-4xl text-center mb-2">Konfirmasi Kehadiran</h2>
            <p class="font-body text-slate-500 text-center mb-10 text-sm md:text-base">Kehadiran Anda adalah kado terindah bagi kami</p>

            <form wire:submit="save" class="space-y-6">
                {{-- Name Input --}}
                <div class="space-y-2">
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 ml-1">Nama Undangan</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        @if($guest) readonly @endif
                        placeholder="Masukkan nama Anda"
                        class="w-full bg-white/50 border-2 border-slate-100 focus:border-rose-300 rounded-2xl px-6 py-4 outline-none transition-all font-body text-slate-800 placeholder-slate-300 shadow-sm"
                    >
                    @error('name') <span class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                {{-- Status Selector --}}
                <div class="space-y-4">
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 ml-1">Pernyataan Kehadiran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button 
                            type="button" 
                            @click="status = 'confirmed'"
                            :class="status === 'confirmed' ? 'border-rose-400 bg-rose-50/50 text-rose-600 ring-2 ring-rose-200' : 'border-slate-100 bg-white/50 text-slate-400 hover:border-slate-200'"
                            class="flex flex-col items-center justify-center p-6 border-2 rounded-3xl transition-all duration-300 group"
                        >
                            <span class="text-3xl mb-2 transition-transform duration-300" :class="status === 'confirmed' ? 'scale-110' : ''">💍</span>
                            <span class="font-bold text-sm tracking-wide">HADIR</span>
                        </button>

                        <button 
                            type="button" 
                            @click="status = 'declined'"
                            :class="status === 'declined' ? 'border-slate-400 bg-slate-50 text-slate-600 ring-2 ring-slate-200' : 'border-slate-100 bg-white/50 text-slate-400 hover:border-slate-200'"
                            class="flex flex-col items-center justify-center p-6 border-2 rounded-3xl transition-all duration-300 group"
                        >
                            <span class="text-3xl mb-2 transition-transform duration-300" :class="status === 'declined' ? 'scale-110' : ''">💌</span>
                            <span class="font-bold text-sm tracking-wide text-center">TIDAK HADIR</span>
                        </button>
                    </div>
                </div>

                {{-- Pax Selector (Conditional) --}}
                <div x-show="status === 'confirmed'" x-collapse x-cloak class="space-y-4 pt-2">
                    <div class="space-y-2">
                        <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 ml-1">Jumlah Tamu</label>
                        <div class="flex items-center gap-4 bg-white/50 border-2 border-slate-100 rounded-3xl p-2 shadow-sm">
                            <button type="button" @click="$wire.pax = Math.max(1, $wire.pax - 1)" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-600 hover:bg-slate-50 active:scale-90 transition-all">-</button>
                            <div class="flex-1 text-center font-bold text-slate-800 text-lg">
                                <span wire:model="pax">{{ $pax }}</span>
                                <span class="text-slate-400 font-normal text-sm ml-1">Orang</span>
                            </div>
                            <button type="button" @click="$wire.pax = Math.min(10, $wire.pax + 1)" class="w-12 h-12 flex items-center justify-center rounded-2xl bg-white border border-slate-100 text-slate-600 hover:bg-slate-50 active:scale-90 transition-all">+</button>
                        </div>
                    </div>
                </div>

                {{-- Submit Button --}}
                <div class="pt-4">
                    <button 
                        type="submit" 
                        class="w-full bg-gradient-to-r from-rose-500 to-rose-600 hover:from-rose-600 hover:to-rose-700 text-white font-bold py-5 rounded-3xl shadow-lg shadow-rose-200 transition-all active:scale-[0.98] flex items-center justify-center gap-3 group"
                        wire:loading.attr="disabled"
                    >
                        <span wire:loading.remove>Kirim Konfirmasi</span>
                        <span wire:loading class="animate-pulse">Mengirim...</span>
                        <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>

        {{-- Success State --}}
        <div x-show="$wire.success" x-transition:enter="transition ease-out duration-700 delay-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100" class="text-center py-12">
            <div class="w-20 h-20 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-6 text-4xl animate-bounce">✓</div>
            <h2 class="font-heading text-3xl mb-4">Terima Kasih</h2>
            <p class="font-body text-slate-600 mb-8 max-w-xs mx-auto">Konfirmasi Anda telah kami terima. Kami sangat menantikan kehadiran Anda.</p>
            <button 
                @click="$wire.success = false" 
                class="text-sm font-bold text-rose-500 hover:text-rose-600 transition-colors uppercase tracking-widest"
            >
                Ubah Konfirmasi
            </button>
        </div>
    </div>
</div>