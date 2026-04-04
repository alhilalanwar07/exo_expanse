<div>
    <div class="max-w-2xl mx-auto px-6">
        <h2 class="font-display text-4xl font-bold text-center mb-4">Ucapan & Doa</h2>
        <p class="text-white/60 text-center mb-12">Kirimkan ucapan dan doa retu Anda</p>

        @if(session('wish_success'))
            <div class="bg-green-500/20 border border-green-500/30 rounded-xl p-6 text-center mb-8 animate-fade-in">
                <p class="text-green-400 font-medium">{{ session('wish_success') }}</p>
            </div>
        @endif

        <form wire:submit="save" class="space-y-6 mb-16">
            <div>
                <label class="block text-sm font-medium text-white/80 mb-2">Nama Pengirim</label>
                <input 
                    type="text" 
                    wire:model="name"
                    required
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all"
                    placeholder="Nama Anda"
                >
                @error('name') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-white/80 mb-2">Ucapan & Doa</label>
                <textarea 
                    wire:model="message"
                    required
                    rows="4"
                    class="w-full px-4 py-3 bg-white/10 border border-white/20 rounded-xl text-white placeholder-white/40 focus:outline-none focus:ring-2 focus:ring-rose-500 transition-all resize-none"
                    placeholder="Tuliskan ucapan dan doa restu Anda..."
                ></textarea>
                @error('message') <span class="text-red-400 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full py-4 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all flex items-center justify-center gap-2" wire:loading.attr="disabled">
                <span wire:loading.remove>Kirim Ucapan</span>
                <span wire:loading>Processing...</span>
            </button>
        </form>

        <div class="space-y-4">
            @forelse($wishes as $wish)
                <div class="bg-white/5 p-6 rounded-xl border border-white/10" wire:key="{{ $wish->id }}">
                    <div class="flex items-center justify-between mb-4">
                        <h4 class="font-bold text-white">{{ $wish->name }}</h4>
                        <span class="text-xs text-white/40">{{ $wish->created_at->diffForHumans() }}</span>
                    </div>
                    <p class="text-white/80 leading-relaxed">{{ $wish->message }}</p>
                </div>
            @empty
                <div class="text-center py-12 bg-white/5 rounded-xl border-2 border-dashed border-white/10">
                    <p class="text-white/40">Belum ada ucapan. Jadilah yang pertama mengirimkan ucapan!</p>
                </div>
            @endforelse

            <div class="pt-4">
                {{ $wishes->links(data: ['scrollTo' => false]) }}
            </div>
        </div>
    </div>
</div>
