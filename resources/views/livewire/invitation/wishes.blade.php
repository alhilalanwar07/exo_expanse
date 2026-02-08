@php
$themeColors = [
    'gold' => ['primary' => '#C9A227', 'secondary' => '#8B6914', 'bg' => '#FDF8F0', 'card' => 'bg-white'],
    'rose' => ['primary' => '#D4A5A5', 'secondary' => '#B88B8B', 'bg' => '#FBF9F7', 'card' => 'bg-white'],
];
$colors = $themeColors[$theme] ?? $themeColors['gold'];
@endphp

<div class="space-y-6">
    {{-- Form --}}
    <div class="{{ $colors['card'] }} rounded-2xl p-6 shadow-sm border border-gray-100">
        @if($isSuccess)
            <div class="bg-green-100 border border-green-300 text-green-700 rounded-xl p-4 text-center mb-4">
                Terima kasih! Ucapan Anda telah terkirim.
            </div>
        @endif

        <form wire:submit="submit" class="space-y-4">
            <div>
                <input 
                    type="text" 
                    wire:model.blur="name"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition-all bg-white"
                    style="--tw-ring-color: {{ $colors['primary'] }}"
                    placeholder="Nama Anda"
                >
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <textarea 
                    wire:model.blur="message"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 transition-all resize-none bg-white"
                    style="--tw-ring-color: {{ $colors['primary'] }}"
                    placeholder="Tulis ucapan dan doa Anda..."
                ></textarea>
                @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <button 
                type="submit" 
                class="px-6 py-3 font-semibold rounded-xl transition-all w-full text-white flex justify-center items-center gap-2"
                style="background: linear-gradient(135deg, {{ $colors['primary'] }}, {{ $colors['secondary'] }})"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove>Kirim Ucapan</span>
                <span wire:loading>
                    <svg class="animate-spin h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </span>
            </button>
        </form>
    </div>

    {{-- Wishes List --}}
    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
        @forelse($wishes as $wish)
            <div wire:key="wish-{{ $wish->id }}" class="{{ $colors['card'] }} rounded-xl p-4 shadow-sm border border-gray-100">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-white text-lg"
                         style="background: linear-gradient(135deg, {{ $colors['primary'] }}, {{ $colors['secondary'] }})">
                        {{ strtoupper(substr($wish->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold text-gray-800">{{ $wish->name }}</p>
                        <p class="text-xs text-gray-400">{{ $wish->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="text-gray-600 leading-relaxed pl-13">{{ $wish->message }}</p>
            </div>
        @empty
            <div class="text-center py-8 text-gray-400">
                Belum ada ucapan. Jadilah yang pertama!
            </div>
        @endforelse
    </div>

    @if($wishes->count() < $totalWishes)
        <div class="text-center">
            <button 
                wire:click="loadMore"
                class="px-6 py-2 rounded-full border transition-opacity hover:opacity-80"
                style="border-color: {{ $colors['primary'] }}; color: {{ $colors['primary'] }}"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak</span>
                <span wire:loading wire:target="loadMore">Memuat...</span>
            </button>
        </div>
    @endif
</div>
