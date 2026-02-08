<div class="max-w-4xl mx-auto">
    
    {{-- FORM SECTION --}}
    <div class="bg-white/80 backdrop-blur-md rounded-2xl shadow-xl border border-white/50 p-6 md:p-10 mb-12 relative overflow-hidden">
        {{-- Decorative Header --}}
        <div class="text-center mb-8">
            <h3 class="text-2xl md:text-3xl font-serif mb-2">Konfirmasi Kehadiran</h3>
            <p class="text-sm opacity-60">Mohon isi form di bawah ini untuk RSVP & Ucapan</p>
        </div>

        @if (session()->has('message'))
            <div class="mb-8 p-4 rounded-xl bg-green-500/10 border border-green-500/20 text-green-700 flex items-center gap-3 animate-fade-in-up">
                <svg class="w-6 h-6 flex-shrink-0 text-green-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="font-medium">{{ session('message') }}</span>
            </div>
        @endif

        <form wire:submit="submit" class="space-y-6">
            <div class="grid md:grid-cols-2 gap-6">
                <!-- Nama -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest opacity-70 ml-1">Nama Lengkap</label>
                    <input type="text" wire:model="name" class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:border-stone-400 focus:ring-0 transition shadow-sm placeholder-gray-400" placeholder="Nama Anda">
                    @error('name') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
                </div>

                <!-- Kehadiran -->
                <div class="space-y-2">
                    <label class="text-xs font-bold uppercase tracking-widest opacity-70 ml-1">Konfirmasi</label>
                    <div class="relative">
                        <select wire:model.live="status" class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:border-stone-400 focus:ring-0 transition shadow-sm appearance-none">
                            <option value="confirmed">Hadir</option>
                            <option value="declined">Maaf, Tidak Bisa Hadir</option>
                        </select>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-500">
                             <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>
                </div>
            </div>



            <!-- Ucapan -->
            <div class="space-y-2">
                <label class="text-xs font-bold uppercase tracking-widest opacity-70 ml-1">Ucapan & Doa</label>
                <textarea wire:model="message" rows="4" class="w-full px-5 py-3 rounded-xl bg-white/50 border border-gray-200 focus:border-stone-400 focus:ring-0 transition shadow-sm placeholder-gray-400 resize-none" placeholder="Tuliskan doa restu Anda di sini..."></textarea>
                @error('message') <span class="text-red-500 text-xs ml-1">{{ $message }}</span> @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="w-full py-4 px-6 rounded-xl bg-stone-800 text-white font-bold tracking-widest uppercase transition duration-300 shadow-lg hover:shadow-xl hover:-translate-y-1 transform disabled:opacity-50 disabled:cursor-not-allowed group overflow-hidden relative">
                <span class="relative z-10 flex items-center justify-center gap-2">
                    <span wire:loading.remove>Kirim Konfirmasi</span>
                    <span wire:loading>Menyimpan Data...</span>
                     <svg wire:loading.remove class="w-5 h-5 group-hover:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                </span>
                <div class="absolute inset-0 h-full w-full bg-stone-700 transform scale-x-0 group-hover:scale-x-100 transition-transform origin-left duration-300"></div>
            </button>
        </form>
    </div>

    {{-- WISHES LIST SECTION --}}
    <div class="space-y-8">
        <h3 class="text-center text-2xl font-serif italic opacity-80">Doa & Ucapan Terbaru</h3>
        
        <div class="grid md:grid-cols-2 gap-6">
            @forelse($wishes as $wish)
                <div class="bg-white/60 backdrop-blur rounded-xl p-6 border border-white/40 shadow-sm hover:shadow-md transition duration-300 break-inside-avoid">
                    <div class="flex items-start gap-4 mb-4">
                        <div class="w-10 h-10 rounded-full bg-stone-200 flex items-center justify-center text-stone-600 font-bold text-lg font-serif shrink-0">
                            {{ substr($wish->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-800 text-sm md:text-base">{{ $wish->name }}</h4>
                            <p class="text-xs text-stone-400 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                {{ $wish->created_at->diffForHumans() }}
                            </p>
                        </div>
                    </div>
                    
                    {{-- Balon ucapan style --}}
                    <div class="relative bg-white/50 p-4 rounded-lg rounded-tl-none border border-stone-100 text-stone-600 text-sm leading-relaxed italic">
                        "{{ $wish->message }}"
                        <div class="absolute -left-2 top-0 w-3 h-3 bg-white/50 border-l border-b border-stone-100 transform rotate-45"></div>
                    </div>
                </div>
            @empty
                <div class="col-span-2 text-center py-10 opacity-50 border-2 border-dashed border-stone-300 rounded-xl">
                    <p>Belum ada ucapan. Jadilah yang pertama memberikan doa restu!</p>
                </div>
            @endforelse
        </div>
    </div>
</div>
