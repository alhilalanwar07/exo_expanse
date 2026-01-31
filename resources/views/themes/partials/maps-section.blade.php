{{-- Maps Section Partial --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'bg-white', 'text' => 'text-rose-dark', 'btn' => 'bg-rose-500 hover:bg-rose-600'],
    'rustic' => ['bg' => 'rustic-gradient', 'text' => 'text-rustic', 'btn' => 'bg-amber-700 hover:bg-amber-800'],
    'minimal' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-900', 'btn' => 'bg-gray-900 hover:bg-gray-800'],
    'royal' => ['bg' => 'bg-slate-950', 'text' => 'text-white', 'btn' => 'bg-gradient-to-r from-yellow-600 to-yellow-500 text-gray-900'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
$content = $invitation->content ?? [];
$mapsUrl = $content['maps_url'] ?? null;
@endphp

@if($mapsUrl)
<section class="py-16 {{ $style['bg'] }}">
    <div class="max-w-2xl mx-auto px-6 text-center">
        <h2 class="text-2xl font-bold {{ $style['text'] }} mb-4">📍 Lokasi</h2>
        <p class="opacity-60 {{ $style['text'] }} mb-6">Buka di Google Maps untuk petunjuk arah</p>
        
        <a 
            href="{{ $mapsUrl }}" 
            target="_blank" 
            rel="noopener noreferrer"
            class="inline-flex items-center gap-2 px-8 py-4 {{ $style['btn'] }} text-white font-semibold rounded-xl transition-all"
        >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
            </svg>
            Buka Google Maps
        </a>
    </div>
</section>
@endif
