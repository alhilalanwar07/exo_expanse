{{-- Couple Section Partial (Wedding) --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'bg-white', 'text' => 'text-rose-dark', 'accent' => 'text-rose-500', 'btn' => 'bg-rose-100 text-rose-600 hover:bg-rose-200'],
    'rustic' => ['bg' => 'bg-amber-50', 'text' => 'text-rustic', 'accent' => 'text-amber-700', 'btn' => 'bg-amber-100 text-amber-700 hover:bg-amber-200'],
    'minimal' => ['bg' => 'bg-white', 'text' => 'text-gray-900', 'accent' => 'text-gray-600', 'btn' => 'bg-gray-100 text-gray-900 hover:bg-gray-200'],
    'royal' => ['bg' => 'bg-slate-950', 'text' => 'text-white', 'accent' => 'text-yellow-400', 'btn' => 'bg-slate-800 text-yellow-400 hover:bg-slate-700'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
$content = $invitation->content ?? [];
$nameOrder = $content['name_order'] ?? 'groom_first';

// Determine first and second person based on name_order
if ($nameOrder === 'bride_first') {
    $first = [
        'name' => $content['bride_name'] ?? 'Mempelai Wanita',
        'fullname' => $content['bride_fullname'] ?? '',
        'parents' => $content['bride_parents'] ?? '',
        'instagram' => $content['bride_instagram'] ?? '',
        'icon' => '👰',
        'label' => 'Putri dari',
        'bg' => 'bg-pink-100 dark:bg-pink-900/30'
    ];
    $second = [
        'name' => $content['groom_name'] ?? 'Mempelai Pria',
        'fullname' => $content['groom_fullname'] ?? '',
        'parents' => $content['groom_parents'] ?? '',
        'instagram' => $content['groom_instagram'] ?? '',
        'icon' => '🤵',
        'label' => 'Putra dari',
        'bg' => 'bg-blue-100 dark:bg-blue-900/30'
    ];
} else {
    $first = [
        'name' => $content['groom_name'] ?? 'Mempelai Pria',
        'fullname' => $content['groom_fullname'] ?? '',
        'parents' => $content['groom_parents'] ?? '',
        'instagram' => $content['groom_instagram'] ?? '',
        'icon' => '🤵',
        'label' => 'Putra dari',
        'bg' => 'bg-blue-100 dark:bg-blue-900/30'
    ];
    $second = [
        'name' => $content['bride_name'] ?? 'Mempelai Wanita',
        'fullname' => $content['bride_fullname'] ?? '',
        'parents' => $content['bride_parents'] ?? '',
        'instagram' => $content['bride_instagram'] ?? '',
        'icon' => '👰',
        'label' => 'Putri dari',
        'bg' => 'bg-pink-100 dark:bg-pink-900/30'
    ];
}
@endphp

<link href="https://fonts.bunny.net/css?family=amiri:400,700&display=swap" rel="stylesheet" />

<section class="py-20 {{ $style['bg'] }}">
    <div class="max-w-4xl mx-auto px-6">
        {{-- Quran Verse / Opening --}}
        <div class="text-center mb-16 animate-on-scroll">
            <p class="font-display text-2xl font-bold {{ $style['text'] }} mb-4 opacity-80">
                 بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم
            </p>
            <p class="font-['Amiri'] text-2xl {{ $style['text'] }} leading-loose mb-6 dir-rtl" style="direction: rtl;">
                وَمِنْ اٰيٰتِهٖٓ اَنْ خَلَقَ لَكُمْ مِّنْ اَنْفُسِكُمْ اَزْوَاجًا لِّتَسْكُنُوْٓا اِلَيْهَا وَجَعَلَ بَيْنَكُمْ مَّوَدَّةً وَّرَحْمَةًۗ اِنَّ فِيْ ذٰلِكَ لَاٰيٰتٍ لِّقَوْمٍ يَّتَفَكَّرُوْنَ
            </p>
            @if(!empty($content['quran_verse']))
            <p class="italic opacity-70 {{ $style['text'] }} max-w-2xl mx-auto text-lg leading-relaxed">
                "{{ $content['quran_verse'] }}"
            </p>
            @else
            <p class="italic opacity-70 {{ $style['text'] }} max-w-2xl mx-auto text-base leading-relaxed">
                "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir." 
                <br><span class="font-semibold block mt-2">(QS. Ar-Rum: 21)</span>
            </p>
            @endif
        </div>

        <h2 class="font-display text-4xl font-bold {{ $style['text'] }} text-center mb-16">Mempelai</h2>

        <div class="grid md:grid-cols-2 gap-12 items-center relative">
            <!-- First Person -->
            <div class="text-center group">
                <div class="relative w-40 h-40 mx-auto mb-6">
                    <div class="absolute inset-0 rounded-full {{ $first['bg'] }} animate-pulse opacity-50"></div>
                    <div class="relative w-full h-full rounded-full {{ $first['bg'] }} flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-xl overflow-hidden transform group-hover:scale-105 transition-transform duration-500">
                         <span class="text-6xl">{{ $first['icon'] }}</span>
                    </div>
                </div>
                
                <h3 class="font-display text-3xl font-bold {{ $style['text'] }} mb-2">
                    {{ $first['name'] }}
                </h3>
                @if(!empty($first['fullname']))
                <p class="{{ $style['accent'] }} font-medium mb-4 text-lg">{{ $first['fullname'] }}</p>
                @endif
                @if(!empty($first['parents']))
                <p class="{{ $style['text'] }} opacity-70 text-sm mb-4">{{ $first['label'] }} {{ $first['parents'] }}</p>
                @endif

                @if(!empty($first['instagram']))
                <a href="https://instagram.com/{{ str_replace('@', '', $first['instagram']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $style['btn'] }} transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>@ {{ str_replace('@', '', $first['instagram']) }}</span>
                </a>
                @endif
            </div>

            <!-- Ampersand (Desktop) -->
            <div class="hidden md:flex items-center justify-center absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-10 w-full pointer-events-none">
                <span class="text-6xl {{ $style['accent'] }} font-display bg-white/50 dark:bg-slate-900/50 backdrop-blur px-4 rounded-full shadow-sm">&</span>
            </div>

            <!-- Second Person -->
            <div class="text-center group">
                <div class="relative w-40 h-40 mx-auto mb-6">
                    <div class="absolute inset-0 rounded-full {{ $second['bg'] }} animate-pulse opacity-50"></div>
                    <div class="relative w-full h-full rounded-full {{ $second['bg'] }} flex items-center justify-center border-4 border-white dark:border-slate-800 shadow-xl overflow-hidden transform group-hover:scale-105 transition-transform duration-500">
                        <span class="text-6xl">{{ $second['icon'] }}</span>
                    </div>
                </div>

                <h3 class="font-display text-3xl font-bold {{ $style['text'] }} mb-2">
                    {{ $second['name'] }}
                </h3>
                @if(!empty($second['fullname']))
                <p class="{{ $style['accent'] }} font-medium mb-4 text-lg">{{ $second['fullname'] }}</p>
                @endif
                @if(!empty($second['parents']))
                <p class="{{ $style['text'] }} opacity-70 text-sm mb-4">{{ $second['label'] }} {{ $second['parents'] }}</p>
                @endif

                @if(!empty($second['instagram']))
                <a href="https://instagram.com/{{ str_replace('@', '', $second['instagram']) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2 rounded-full {{ $style['btn'] }} transition-colors text-sm font-medium">
                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>@ {{ str_replace('@', '', $second['instagram']) }}</span>
                </a>
                @endif
            </div>
        </div>

        <!-- Ampersand for mobile -->
        <div class="md:hidden text-center mt-8 mb-6">
            <span class="text-4xl {{ $style['accent'] }} font-display bg-white/50 dark:bg-slate-900/50 backdrop-blur px-4 py-1 rounded-full shadow-sm">&</span>
        </div>
    </div>
</section>
