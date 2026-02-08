<div>
    @if (session()->has('message'))
        <div class="mb-6 p-4 rounded-lg bg-green-500/10 border border-green-500/20 text-green-600 dark:text-green-400 flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
            <span>{{ session('message') }}</span>
        </div>
    @endif

    <form wire:submit="submit" class="space-y-5">
        <div>
            <label class="block mb-2 text-sm font-medium opacity-80 uppercase tracking-wider">Nama Tamu</label>
            <input type="text" 
                   wire:model="name" 
                   class="w-full px-4 py-3 rounded-lg border-2 border-stone-200 focus:border-amber-400 focus:ring-0 bg-white/50 backdrop-blur-sm transition duration-200 placeholder-stone-400 text-stone-800"
                   placeholder="Masukkan nama Anda">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block mb-2 text-sm font-medium opacity-80 uppercase tracking-wider">Konfirmasi Kehadiran</label>
            <div class="relative">
                <select wire:model.live="status" 
                        class="w-full px-4 py-3 rounded-lg border-2 border-stone-200 focus:border-amber-400 focus:ring-0 bg-white/50 backdrop-blur-sm transition duration-200 appearance-none text-stone-800">
                    <option value="confirmed">Hadir</option>
                    <option value="declined">Maaf, Tidak Bisa Hadir</option>
                </select>
                <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-stone-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                </div>
            </div>
        </div>

        @if($status === 'confirmed')
            <div class="animate-fade-in-up">
                <label class="block mb-2 text-sm font-medium opacity-80 uppercase tracking-wider">Jumlah Tamu</label>
                <input type="number" 
                       wire:model="total_guests" 
                       min="1" max="5"
                       class="w-full px-4 py-3 rounded-lg border-2 border-stone-200 focus:border-amber-400 focus:ring-0 bg-white/50 backdrop-blur-sm transition duration-200 text-stone-800"
                       placeholder="1">
                <p class="text-xs opacity-60 mt-1">Maksimal 5 orang</p>
            </div>
        @endif

        <button type="submit" 
                class="w-full py-4 px-6 rounded-lg bg-amber-600 hover:bg-amber-700 text-white font-bold tracking-widest uppercase transition duration-300 shadow-lg hover:shadow-amber-500/30 transform hover:-translate-y-0.5 disabled:opacity-50 disabled:cursor-not-allowed">
            <span wire:loading.remove>Kirim Konfirmasi</span>
            <span wire:loading class="flex items-center justify-center gap-2">
                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                Processing...
            </span>
        </button>
    </form>
</div>
