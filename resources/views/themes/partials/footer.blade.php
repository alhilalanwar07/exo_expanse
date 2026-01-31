{{-- Footer Partial --}}
@php
$themeStyles = [
    'rose' => ['bg' => 'bg-rose-900', 'text' => 'text-white'],
    'rustic' => ['bg' => 'bg-amber-900', 'text' => 'text-white'],
    'minimal' => ['bg' => 'bg-gray-900', 'text' => 'text-white'],
    'royal' => ['bg' => 'bg-black', 'text' => 'text-white'],
];
$style = $themeStyles[$theme] ?? $themeStyles['rose'];
$content = $invitation->content ?? [];
@endphp

<footer class="py-12 {{ $style['bg'] }} {{ $style['text'] }}">
    <div class="text-center">
        <p class="opacity-60 mb-4">Thank you for being part of our special day</p>
        <p class="text-2xl font-bold
            {{ $theme === 'royal' ? 'gold-gradient' : 
               ($theme === 'rose' ? 'text-rose-300' :
               ($theme === 'rustic' ? 'text-amber-300' : 'text-gray-300')) }}">
            {{ $content['groom_name'] ?? 'Groom' }} & {{ $content['bride_name'] ?? 'Bride' }}
        </p>
        <p class="opacity-40 text-sm mt-8">Powered by ExoInvite</p>
    </div>
</footer>
