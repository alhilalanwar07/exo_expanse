<div>
    <form wire:submit="submit" class="mb-10 p-6 rounded-2xl bg-white shadow-sm border border-stone-100">
        @if (session()->has('message'))
            <div class="mb-4 p-3 rounded bg-green-50 text-green-600 text-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                {{ session('message') }}
            </div>
        @endif

        <div class="mb-5">
            <label class="block mb-2 text-sm font-medium text-stone-600 uppercase tracking-wider">Nama Anda</label>
            <input type="text" 
                   wire:model="name" 
                   class="w-full px-4 py-2 rounded-lg border border-stone-200 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 bg-stone-50 transition placeholder-stone-400"
                   placeholder="Tulis nama anda">
            @error('name') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="mb-5">
            <label class="block mb-2 text-sm font-medium text-stone-600 uppercase tracking-wider">Ucapan & Doa</label>
            <textarea wire:model="message" 
                      rows="3"
                      class="w-full px-4 py-2 rounded-lg border border-stone-200 focus:border-amber-400 focus:ring-1 focus:ring-amber-400 bg-stone-50 transition placeholder-stone-400 resize-none"
                      placeholder="Tuliskan ucapan selamat..."></textarea>
            @error('message') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
        </div>

        <div class="text-right">
            <button type="submit" 
                    class="px-6 py-2 rounded-lg bg-stone-800 text-white hover:bg-black transition duration-300 text-sm font-medium tracking-wide">
                <span wire:loading.remove>Kirim Ucapan</span>
                <span wire:loading>Sending...</span>
            </button>
        </div>
    </form>

    <div class="space-y-6 max-h-[600px] overflow-y-auto pr-2 custom-scrollbar">
        @forelse($wishes as $wish)
            <div class="p-6 rounded-xl bg-white border border-stone-100 shadow-sm hover:shadow-md transition duration-300">
                <div class="flex items-center justify-between mb-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-amber-100 flex items-center justify-center text-amber-600 font-bold text-lg font-serif">
                            {{ substr($wish->name, 0, 1) }}
                        </div>
                        <div>
                            <h4 class="font-bold text-stone-800">{{ $wish->name }}</h4>
                            <p class="text-xs text-stone-400">{{ $wish->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </div>
                <p class="text-stone-600 leading-relaxed italic">"{{ $wish->message }}"</p>
            </div>
        @empty
            <div class="text-center py-10 opacity-50">
                <p>Belum ada ucapan. Jadilah yang pertama!</p>
            </div>
        @endforelse
    </div>
</div>
