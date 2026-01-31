{{-- Event Details Section Partial (Wedding) --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'rose-gradient', 'text' => 'text-rose-dark', 'card' => 'bg-white/80'],
    'rustic' => ['bg' => 'rustic-gradient', 'text' => 'text-rustic', 'card' => 'bg-white/90'],
    'minimal' => ['bg' => 'bg-gray-50', 'text' => 'text-gray-900', 'card' => 'bg-white'],
    'royal' => ['bg' => 'bg-slate-900', 'text' => 'text-white', 'card' => 'bg-slate-800/80'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
$content = $invitation->content ?? [];
$eventType = $content['event_type'] ?? 'both';
$showAkad = in_array($eventType, ['akad_only', 'both']);
$showResepsi = in_array($eventType, ['resepsi_only', 'both']);

// Format date helper
$formatDate = function($date) use ($invitation) {
    if (!empty($date)) {
        try {
            return \Carbon\Carbon::parse($date)->translatedFormat('l, d F Y');
        } catch (\Exception $e) {
            return $date;
        }
    }
    return $invitation->event_date?->translatedFormat('l, d F Y') ?? '';
};
@endphp

<section class="py-20 {{ $style['bg'] }}">
    <div class="max-w-4xl mx-auto px-6 text-center">
        <h2 class="font-display text-4xl font-bold {{ $style['text'] }} mb-12">Waktu & Tempat</h2>
        
        <div class="grid md:grid-cols-{{ ($showAkad && $showResepsi) ? '2' : '1 max-w-xl mx-auto' }} gap-8">
            @if($showAkad)
            <!-- Akad Nikah -->
            <div class="{{ $style['card'] }} backdrop-blur rounded-2xl p-8 shadow-lg">
                <div class="text-4xl mb-4">💒</div>
                <h3 class="font-display text-2xl font-semibold {{ $style['text'] }} mb-4">Akad Nikah</h3>
                <div class="space-y-2">
                    <p class="text-lg {{ $style['text'] }}">
                        {{ $formatDate($content['akad_date'] ?? '') }}
                    </p>
                    @if(!empty($content['akad_time']))
                    <p class="{{ $style['text'] }} opacity-70">Pukul {{ $content['akad_time'] }} WIB - Selesai</p>
                    @endif
                    @if(!empty($content['akad_venue']))
                    <p class="mt-4 font-medium {{ $style['text'] }}">{{ $content['akad_venue'] }}</p>
                    @endif
                    @if(!empty($content['akad_address']))
                    <p class="{{ $style['text'] }} opacity-70 text-sm">{{ $content['akad_address'] }}</p>
                    @endif
                </div>
            </div>
            @endif

            @if($showResepsi)
            <!-- Resepsi -->
            <div class="{{ $style['card'] }} backdrop-blur rounded-2xl p-8 shadow-lg">
                <div class="text-4xl mb-4">🍽️</div>
                <h3 class="font-display text-2xl font-semibold {{ $style['text'] }} mb-4">Resepsi</h3>
                <div class="space-y-2">
                    <p class="text-lg {{ $style['text'] }}">
                        {{ $formatDate($content['resepsi_date'] ?? '') }}
                    </p>
                    @if(!empty($content['resepsi_time']))
                    <p class="{{ $style['text'] }} opacity-70">Pukul {{ $content['resepsi_time'] }} WIB - Selesai</p>
                    @endif
                    @if(!empty($content['resepsi_venue']))
                    <p class="mt-4 font-medium {{ $style['text'] }}">{{ $content['resepsi_venue'] }}</p>
                    @endif
                    @if(!empty($content['resepsi_address']))
                    <p class="{{ $style['text'] }} opacity-70 text-sm">{{ $content['resepsi_address'] }}</p>
                    @endif
                </div>
            </div>
            @endif
        </div>
    </div>
</section>
