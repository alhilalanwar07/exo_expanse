<div>
    @if(session('rsvp_success'))
        <div class="bg-green-100 border border-green-300 text-green-700 rounded-xl p-4 text-center mb-6">
            <p class="font-medium">{{ session('rsvp_success') }}</p>
            <button wire:click="$set('isSubmitted', false)" class="text-sm underline mt-2 hover:opacity-80">
                Ubah Konfirmasi
            </button>
        </div>
    @endif

    @if(!$isSubmitted)
    <form wire:submit="save" class="space-y-5">
        <div>
            <label class="block text-sm font-medium mb-2 opacity-80">Nama Lengkap</label>
            <input 
                type="text" 
                wire:model="name"
                required
                class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-current focus:border-transparent transition-all"
                placeholder="Masukkan nama Anda"
                @if($guest && $guest->id) readonly @endif
            >
            @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-medium mb-3 opacity-80">Konfirmasi Kehadiran</label>
            <div class="grid grid-cols-2 gap-4">
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="status" value="confirmed" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-green-500 peer-checked:bg-green-50 transition-all text-center hover:bg-gray-50">
                        <span class="text-2xl">✓</span>
                        <p class="mt-2 font-medium text-sm">Hadir</p>
                    </div>
                </label>
                <label class="relative cursor-pointer">
                    <input type="radio" wire:model.live="status" value="declined" class="sr-only peer">
                    <div class="p-4 rounded-xl border-2 border-gray-200 peer-checked:border-red-500 peer-checked:bg-red-50 transition-all text-center hover:bg-gray-50">
                        <span class="text-2xl">✗</span>
                        <p class="mt-2 font-medium text-sm">Tidak Hadir</p>
                    </div>
                </label>
            </div>
            @error('status') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>

        @if($status === 'confirmed')
        <div x-transition>
            <label class="block text-sm font-medium mb-2 opacity-80">Jumlah Orang</label>
            <select wire:model="pax" class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-current transition-all bg-white">
                @for($i = 1; $i <= 5; $i++)
                    <option value="{{ $i }}">{{ $i }} Orang</option>
                @endfor
            </select>
            @error('pax') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
        </div>
        @endif

        <button type="submit" class="w-full py-4 bg-current text-white font-semibold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2" wire:loading.attr="disabled" style="background: linear-gradient(135deg, #C9A227, #8B6914); {{ $theme === 'rose' ? 'background: linear-gradient(135deg, #D4A5A5, #B88B8B);' : '' }}">
            <span wire:loading.remove>Kirim Konfirmasi</span>
            <span wire:loading class="flex items-center gap-2">
                <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button>
    </form>
    @endif
</div>
