@php
$themeStyles = [
    'rose' => ['card' => 'bg-white', 'text' => 'text-rose-dark', 'accent' => 'rose', 'btn' => 'bg-rose-500 hover:bg-rose-600'],
    'rustic' => ['card' => 'bg-amber-50', 'text' => 'text-rustic', 'accent' => 'amber', 'btn' => 'bg-amber-600 hover:bg-amber-700'],
    'minimal' => ['card' => 'bg-gray-50', 'text' => 'text-gray-900', 'accent' => 'gray', 'btn' => 'bg-gray-900 hover:bg-gray-800'],
    'royal' => ['card' => 'bg-white/5 border border-yellow-600/20', 'text' => 'text-white', 'accent' => 'yellow', 'btn' => 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-gray-900'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
@endphp

<div class="space-y-8">
    <!-- Form -->
    <div class="{{ $style['card'] }} rounded-2xl p-6 shadow-sm">
        @if($isSuccess)
            <div class="bg-green-100 border border-green-300 text-green-700 rounded-xl p-4 text-center animate-fade-in mb-4">
                Terima kasih! Ucapan Anda telah terkirim.
            </div>
        @endif

        <form wire:submit="submit" class="space-y-4">
            <div>
                <input 
                    type="text" 
                    wire:model.blur="name"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-{{ $style['accent'] }}-500 transition-all
                    {{ $theme === 'royal' ? 'bg-white/10 border-yellow-600/30 text-white placeholder-white/40' : 'bg-white border-gray-300' }}"
                    placeholder="Nama Anda"
                >
                @error('name') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>
            
            <div>
                <textarea 
                    wire:model.blur="message"
                    rows="3"
                    class="w-full px-4 py-3 border rounded-xl focus:outline-none focus:ring-2 focus:ring-{{ $style['accent'] }}-500 transition-all resize-none
                    {{ $theme === 'royal' ? 'bg-white/10 border-yellow-600/30 text-white placeholder-white/40' : 'bg-white border-gray-300' }}"
                    placeholder="Tulis ucapan dan doa Anda..."
                ></textarea>
                @error('message') <span class="text-red-500 text-sm mt-1">{{ $message }}</span> @enderror
            </div>

            <button 
                type="submit" 
                class="px-6 py-3 font-semibold rounded-xl transition-all w-full md:w-auto text-white {{ $style['btn'] }} flex justify-center items-center gap-2"
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

    <!-- List -->
    <div class="space-y-4">
        @forelse($wishes as $wish)
            <div wire:key="wish-{{ $wish->id }}" class="{{ $style['card'] }} rounded-xl p-4 shadow-sm animate-fade-in-up">
                <div class="flex items-center gap-3 mb-2">
                    <div class="w-10 h-10 rounded-full flex items-center justify-center font-semibold text-lg
                        {{ $theme === 'royal' ? 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-gray-900' : 
                           ($theme === 'rose' ? 'bg-rose-500 text-white' :
                           ($theme === 'rustic' ? 'bg-amber-600 text-white' : 'bg-gray-900 text-white')) }}">
                        {{ strtoupper(substr($wish->name, 0, 1)) }}
                    </div>
                    <div>
                        <p class="font-bold {{ $style['text'] }}">{{ $wish->name }}</p>
                        <p class="text-xs opacity-50 {{ $style['text'] }}">{{ $wish->created_at->diffForHumans() }}</p>
                    </div>
                </div>
                <p class="opacity-80 {{ $style['text'] }} pl-13 leading-relaxed">{{ $wish->message }}</p>
            </div>
        @empty
            <div class="text-center py-8 opacity-50 {{ $style['text'] }}">
                Belum ada ucapan. Jadilah yang pertama!
            </div>
        @endforelse
    </div>

    @if($wishes->count() < $totalWishes)
        <div class="text-center mt-6">
            <button 
                wire:click="loadMore"
                class="px-6 py-2 rounded-full border border-current opacity-60 hover:opacity-100 transition-opacity {{ $style['text'] }}"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="loadMore">Muat Lebih Banyak</span>
                <span wire:loading wire:target="loadMore">Memuat...</span>
            </button>
        </div>
    @endif
</div>
