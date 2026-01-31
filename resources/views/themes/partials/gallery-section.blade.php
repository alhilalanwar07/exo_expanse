{{-- Gallery Section Partial --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'rose-gradient', 'text' => 'text-rose-dark', 'accent' => 'rose'],
    'rustic' => ['bg' => 'bg-amber-50', 'text' => 'text-rustic', 'accent' => 'amber'],
    'minimal' => ['bg' => 'bg-white', 'text' => 'text-gray-900', 'accent' => 'gray'],
    'royal' => ['bg' => 'royal-gradient', 'text' => 'text-white', 'accent' => 'yellow'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
$photos = $invitation->photos ?? collect();
@endphp

@if($photos->count() > 0)
<section class="py-20 {{ $style['bg'] }}">
    <div class="max-w-6xl mx-auto px-6">
        <h2 class="text-center text-3xl font-bold {{ $style['text'] }} mb-4">Galeri</h2>
        <p class="text-center opacity-60 {{ $style['text'] }} mb-10">Momen berharga kami</p>
        
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
            @foreach($photos as $photo)
                <div class="aspect-square rounded-xl overflow-hidden cursor-pointer group relative"
                    x-data
                    @click="$dispatch('open-lightbox', { src: '{{ $photo->url }}', caption: '{{ $photo->caption ?? '' }}' })">
                    <img 
                        src="{{ $photo->url }}" 
                        alt="{{ $photo->caption ?? 'Gallery photo' }}"
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300"
                    >
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/20 transition-colors"></div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Lightbox -->
<div x-data="{ show: false, src: '', caption: '' }"
    x-show="show"
    x-cloak
    @open-lightbox.window="show = true; src = $event.detail.src; caption = $event.detail.caption"
    @keydown.escape.window="show = false"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/90"
    x-transition>
    <button @click="show = false" class="absolute top-4 right-4 text-white/80 hover:text-white">
        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
    </button>
    <div class="max-w-4xl max-h-[90vh] px-4">
        <img :src="src" alt="" class="max-w-full max-h-[85vh] object-contain">
        <p x-show="caption" x-text="caption" class="text-white text-center mt-4"></p>
    </div>
</div>
@endif
