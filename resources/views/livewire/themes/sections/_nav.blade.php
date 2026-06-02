{{-- Section: Bottom Navigation Menu --}}
{{-- Variables: $enabledSections (array), $nav (nav config), $invitation --}}
@php
    $navBg = $nav['bg_color'] ?? 'rgba(118, 19, 50, 0.95)';
    $navActive = $nav['active_color'] ?? '#d4b051';
    $navInactive = $nav['inactive_color'] ?? '#ffffff';

    $sectionMeta = [
        'cover' => ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Cover'],
        'opening' => ['icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'label' => 'Cover'],
        'couple' => ['icon' => 'M12 4.35a4 4 0 110 5.3M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.2M13 7a4 4 0 11-8 0 4 4 0 018 0z', 'label' => 'Mempelai'],
        'quote' => ['icon' => 'M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253', 'label' => 'Ayat'],
        'lovestory' => ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Cerita'],
        'events' => ['icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Acara'],
        'maps' => ['icon' => 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0zM15 11a3 3 0 11-6 0 3 3 0 016 0z', 'label' => 'Lokasi'],
        'gallery' => ['icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'label' => 'Galeri'],
        'gift' => ['icon' => 'M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7', 'label' => 'Hadiah'],
        'rsvp' => ['icon' => 'M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z', 'label' => 'RSVP'],
        'closing' => ['icon' => 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z', 'label' => 'Penutup'],
    ];

    // Build nav items from enabled sections (skip cover since it's the overlay)
    $navItems = [];
    foreach ($enabledSections as $section) {
        if ($section['id'] === 'cover') continue;
        $meta = $sectionMeta[$section['id']] ?? null;
        if ($meta) {
            $navItems[] = ['slide' => $section['_slideIndex'] ?? 0, 'icon' => $meta['icon'], 'label' => $meta['label'], 'id' => $section['id']];
        }
    }
@endphp

<div x-show="opened" class="satumomen_nav_wrap animate__animated animate__fadeInUp animate__faster">
<div class="satumomen_menu" style="background: {{ $navBg }}; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);">
    <div class="satumomen_menu_inner" x-ref="navInner"
         x-effect="
             let items = [...($refs.navInner?.children || [])];
             let total = items.length;
             let idx = items.findIndex(el => Number(el.dataset.slide) === activeSlide);
             if (idx < 0) idx = 0;
             let maxOff = Math.max(0, total - 4);
             let off = Math.max(0, Math.min(idx - 1, maxOff));
             if ($refs.navInner) $refs.navInner.style.transform = 'translateX(-' + (off * 65) + 'px)';
         ">
        @foreach($navItems as $item)
        <div class="satumomen_menu_item" data-slide="{{ $item['slide'] }}" :class="{'active': activeSlide === {{ $item['slide'] }}}" @click="scrollToSlide({{ $item['slide'] }})" style="--menu-active: {{ $navActive }}; --menu-inactive: {{ $navInactive }};">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $item['icon'] }}"/></svg>
            <span>{{ $item['label'] }}</span>
        </div>
        @endforeach

        @if($invitation->music_url)
        <div class="satumomen_menu_item" data-slide="0" @click="toggleAudio()" :class="{'active': playing}">
            <svg x-show="playing" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
            <svg x-show="!playing" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
            <span x-text="playing ? 'Musik' : 'Mute'"></span>
        </div>
        @endif
    </div>
</div>
</div>
