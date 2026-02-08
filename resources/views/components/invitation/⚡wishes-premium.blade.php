<?php

use Livewire\Volt\Component;
use App\Models\Invitation;
use App\Models\Wish;
use Livewire\Attributes\Validate;

new class extends Component
{
    public Invitation $invitation;
    
    #[Validate('required|string|max:255')]
    public $name = '';

    #[Validate('required|string|max:1000')]
    public $message = '';

    public $limit = 6;

    public function mount(Invitation $invitation)
    {
        $this->invitation = $invitation;
    }

    public function submit()
    {
        $this->validate();

        Wish::create([
            'invitation_id' => $this->invitation->id,
            'name' => $this->name,
            'message' => $this->message,
        ]);

        $this->name = '';
        $this->message = '';
        
        $this->dispatch('wish-sent');
    }

    public function loadMore()
    {
        $this->limit += 6;
    }

    public function with()
    {
        return [
            'wishes' => $this->invitation->wishes()
                ->latest()
                ->paginate($this->limit),
            'total' => $this->invitation->wishes()->count(),
        ];
    }
};
?>

<div class="w-full max-w-4xl mx-auto px-4 py-12">
    {{-- Input Section --}}
    <div class="glass rounded-3xl p-8 mb-16 shadow-xl relative overflow-hidden group">
        <h2 class="font-heading text-3xl text-center mb-8">Ucapan & Doa</h2>
        
        <form wire:submit="submit" class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 ml-1">Nama Anda</label>
                    <input 
                        type="text" 
                        wire:model="name"
                        placeholder="Masukkan nama Anda"
                        class="w-full bg-white/50 border-2 border-slate-100 focus:border-rose-300 rounded-2xl px-6 py-4 outline-none transition-all font-body text-slate-800 placeholder-slate-300 shadow-sm"
                    >
                    @error('name') <span class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>
                
                <div class="hidden md:block pt-4">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-5 rounded-2xl shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        Kirim Ucapan
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-4">
                <div class="space-y-2">
                    <label class="block text-xs uppercase tracking-widest font-bold text-slate-400 ml-1">Pesan Kebahagiaan</label>
                    <textarea 
                        wire:model="message"
                        rows="4"
                        placeholder="Tulis ucapan & doa restu..."
                        class="w-full bg-white/50 border-2 border-slate-100 focus:border-rose-300 rounded-2xl px-6 py-4 outline-none transition-all font-body text-slate-800 placeholder-slate-300 shadow-sm resize-none"
                    ></textarea>
                    @error('message') <span class="text-xs text-rose-500 font-medium ml-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:hidden pt-2">
                    <button type="submit" class="w-full bg-slate-900 hover:bg-slate-800 text-white font-bold py-5 rounded-2xl shadow-lg transition-all active:scale-[0.98] flex items-center justify-center gap-3">
                        Kirim Ucapan
                    </button>
                </div>
            </div>
        </form>
    </div>

    {{-- Wishes List --}}
    <div class="space-y-8">
        <div class="flex items-center justify-between mb-8 px-2">
            <h3 class="font-heading text-2xl">Kiriman Doa Restu</h3>
            <span class="bg-rose-100 text-rose-600 px-4 py-1 rounded-full text-xs font-bold tracking-widest">
                {{ $total }} UCAPAN
            </span>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6" wire:poll.10s>
            @forelse($wishes as $wish)
                <div 
                    wire:key="wish-{{ $wish->id }}"
                    x-data
                    x-intersect="$el.classList.add('opacity-100', 'translate-y-0')"
                    class="bg-white border border-slate-100 rounded-3xl p-6 shadow-sm hover:shadow-md transition-all duration-700 opacity-0 translate-y-8 flex flex-col"
                >
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-rose-50 to-amber-50 flex items-center justify-center text-rose-500 font-bold text-lg border border-rose-100">
                            {{ substr($wish->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-slate-800 leading-none">{{ $wish->name }}</h4>
                            <span class="text-[10px] uppercase tracking-tighter text-slate-400">{{ $wish->created_at->diffForHumans() }}</span>
                        </div>
                    </div>
                    <p class="text-slate-600 text-sm leading-relaxed flex-1 italic">
                        "{{ $wish->message }}"
                    </p>
                    <div class="mt-4 pt-4 border-t border-slate-50 flex justify-end">
                        <span class="text-[10px] text-rose-300">#WeddingWish</span>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-20 text-center glass rounded-3xl">
                    <span class="text-4xl mb-4 block opacity-20">✉️</span>
                    <p class="text-slate-400 font-body">Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                </div>
            @endforelse
        </div>

        @if($wishes->hasMorePages())
            <div class="pt-12 text-center">
                <button 
                    wire:click="loadMore" 
                    class="px-8 py-3 rounded-2xl bg-white border-2 border-slate-100 text-slate-600 font-bold text-sm tracking-widest hover:border-rose-200 hover:text-rose-500 transition-all active:scale-95"
                >
                    LIHAT LEBIH BANYAK
                </button>
            </div>
        @endif
    </div>
</div>