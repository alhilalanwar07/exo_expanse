{{-- Wishes Section Partial --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'bg-gradient-to-b from-rose-50 to-pink-50', 'card' => 'bg-white', 'text' => 'text-rose-dark', 'accent' => 'rose'],
    'rustic' => ['bg' => 'bg-white/80', 'card' => 'bg-amber-50', 'text' => 'text-rustic', 'accent' => 'amber'],
    'minimal' => ['bg' => 'bg-white', 'card' => 'bg-gray-50', 'text' => 'text-gray-900', 'accent' => 'gray'],
    'royal' => ['bg' => 'bg-gradient-to-b from-slate-950 to-slate-900', 'card' => 'bg-white/5 border border-yellow-600/20', 'text' => 'text-white', 'accent' => 'yellow'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
@endphp

<section class="py-20 {{ $style['bg'] }}">
    <div class="max-w-4xl mx-auto px-6">
        <h2 class="text-center text-3xl font-bold {{ $style['text'] }} mb-4">Ucapan & Doa</h2>
        <p class="text-center opacity-60 {{ $style['text'] }} mb-10">Kirimkan ucapan dan doa terbaik Anda</p>
        
        <livewire:invitation.wishes 
            :invitation="$invitation" 
            :theme="$theme" 
        />
    </div>
</section>
