@props([
    'variant' => 'full',
    'class' => '',
    'width' => null,
    'height' => null,
    'white' => false,
])

@if($variant === 'full')
    {{-- Full logo: icon + text --}}
    <a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-flex items-center gap-2 ' . $class]) }}>
        {{-- Icon --}}
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="{{ $width ?? 'w-9 h-9' }}">
            <defs>
                <linearGradient id="logoIconGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="{{ $white ? '#ffffff' : '#f43f5e' }}"/>
                    <stop offset="100%" stop-color="{{ $white ? '#ffffff' : '#f59e0b' }}"/>
                </linearGradient>
            </defs>
            <rect x="2" y="12" width="44" height="30" rx="4" fill="url(#logoIconGrad)" opacity="0.12"/>
            <rect x="2" y="12" width="44" height="30" rx="4" stroke="url(#logoIconGrad)" stroke-width="2.5" fill="none"/>
            <path d="M3 13 L24 30 L45 13" stroke="url(#logoIconGrad)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M24 20 C24 16.5, 18 14.5, 18 18 C18 21, 24 25.5, 24 25.5 C24 25.5, 30 21, 30 18 C30 14.5, 24 16.5, 24 20Z" fill="url(#logoIconGrad)"/>
        </svg>
        {{-- Text --}}
        <span class="text-2xl font-bold {{ $white ? 'text-white' : 'bg-gradient-to-r from-rose-500 to-amber-500 bg-clip-text text-transparent' }}">
            Exo<span class="font-normal">Invite</span>
        </span>
    </a>
@elseif($variant === 'icon')
    {{-- Icon only --}}
    <a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-flex items-center ' . $class]) }}>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 48 48" fill="none" class="{{ $width ?? 'w-9 h-9' }}">
            <defs>
                <linearGradient id="logoIconOnlyGrad" x1="0%" y1="0%" x2="100%" y2="100%">
                    <stop offset="0%" stop-color="{{ $white ? '#ffffff' : '#f43f5e' }}"/>
                    <stop offset="100%" stop-color="{{ $white ? '#ffffff' : '#f59e0b' }}"/>
                </linearGradient>
            </defs>
            <rect x="2" y="12" width="44" height="30" rx="4" fill="url(#logoIconOnlyGrad)" opacity="0.12"/>
            <rect x="2" y="12" width="44" height="30" rx="4" stroke="url(#logoIconOnlyGrad)" stroke-width="2.5" fill="none"/>
            <path d="M3 13 L24 30 L45 13" stroke="url(#logoIconOnlyGrad)" stroke-width="2.5" fill="none" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M24 20 C24 16.5, 18 14.5, 18 18 C18 21, 24 25.5, 24 25.5 C24 25.5, 30 21, 30 18 C30 14.5, 24 16.5, 24 20Z" fill="url(#logoIconOnlyGrad)"/>
        </svg>
    </a>
@elseif($variant === 'text')
    {{-- Text only --}}
    <a href="{{ url('/') }}" {{ $attributes->merge(['class' => 'inline-flex items-center ' . $class]) }}>
        <span class="text-2xl font-bold {{ $white ? 'text-white' : 'bg-gradient-to-r from-rose-500 to-amber-500 bg-clip-text text-transparent' }}">
            Exo<span class="font-normal">Invite</span>
        </span>
    </a>
@endif
