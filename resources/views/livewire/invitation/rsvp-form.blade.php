<div>
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="font-display text-4xl font-bold text-center mb-4">Konfirmasi Kehadiran</h2>
        <p class="text-white/60 text-center mb-12">Mohon konfirmasi kehadiran Anda</p>
        
        @if(session('rsvp_success'))
            <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-6 text-center mb-8 animate-fade-in">
                <p class="text-green-400 font-medium">{{ session('rsvp_success') }}</p>
                <button wire:click="$set('isSubmitted', false)" class="text-sm text-green-400/70 underline mt-2 hover:text-green-300">
                    Ubah Konfirmasi
                </button>
            </div>
        @endif

        @if(!$isSubmitted)
        <form wire:submit="save" class="space-y-6">
            <div>
                <label class="block text-sm font-medium text-white/80 mb-2">Nama Lengkap</label>
                <input 
                    type="text" 
                    wire:model="name"
                    required
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all"
                    placeholder="Masukkan nama Anda"
                    @if($guest && $guest->id) readonly @endif
                >
                @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white/80 mb-2">Konfirmasi Kehadiran</label>
                <div class="grid grid-cols-2 gap-4">
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model.live="status" value="confirmed" class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 border-white/20 peer-checked:border-green-500 peer-checked:bg-green-500/20 transition-all text-center hover:bg-white/5">
                            <span class="text-2xl">✓</span>
                            <p class="mt-2 font-medium">Hadir</p>
                        </div>
                    </label>
                    <label class="relative cursor-pointer">
                        <input type="radio" wire:model.live="status" value="declined" class="sr-only peer">
                        <div class="p-4 rounded-xl border-2 border-white/20 peer-checked:border-red-500 peer-checked:bg-red-500/20 transition-all text-center hover:bg-white/5">
                            <span class="text-2xl">✗</span>
                            <p class="mt-2 font-medium">Tidak Hadir</p>
                        </div>
                    </label>
                </div>
                @error('status') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            @if($status === 'confirmed')
            <div x-transition>
                <label class="block text-sm font-medium text-white/80 mb-2">Jumlah Orang</label>
                <select wire:model="pax" class="w-full px-4 py-3 bg-slate-800 border border-white/20 rounded-xl text-white focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all">
                    @for($i = 1; $i <= 5; $i++)
                        <option value="{{ $i }}">{{ $i }} Orang</option>
                    @endfor
                </select>
                @error('pax') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            @endif

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Konfirmasi</span>
                <span wire:loading>Processing...</span>
            </button>
        </form>
        @endif
    </div>
</div>
