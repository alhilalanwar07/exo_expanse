@php
    $bgColor = $themeConfig['background_color'] ?? '#5d071f';
    $baseColor = $themeConfig['text_color'] ?? '#ffffff';
    $accentColor = $themeConfig['accent_color'] ?? '#d4b051';
    $primaryColor = $themeConfig['primary_color'] ?? '#d4b051';
    $headingFont = $themeConfig['heading_font'] ?? 'Fraunces';
    $accentFont = $themeConfig['accent_font'] ?? 'Great Vibes';
    $bodyFont = $themeConfig['body_font'] ?? 'Parisienne';
    $menuBg = $navConfig['bg_color'] ?? 'rgba(118, 19, 50, 0.95)';
    $menuActive = $navConfig['active_color'] ?? '#d4b051';
    $menuInactive = $navConfig['inactive_color'] ?? '#ffffff';
    $btnColor = $themeConfig['secondary_color'] ?? '#3d0d19';

    $fonts = collect([$headingFont, $accentFont, $bodyFont])
        ->unique()
        ->map(fn($f) => str_replace(' ', '+', $f))
        ->implode('&family=');
    $fontsUrl = "https://fonts.googleapis.com/css2?family={$fonts}&display=swap";

    // Build slide names for Alpine
    $slideNames = collect($enabledSections)->pluck('id')->toArray();
    $slidesJson = json_encode($slideNames);

    // Assign slide indices
    $slideIndex = 0;
@endphp

@push('fonts')
<link href="{{ $fontsUrl }}" rel="stylesheet">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
:root {
    --inv-bg: {{ $bgColor }};
    --inv-base: {{ $baseColor }};
    --inv-accent: {{ $accentColor }};
    --inv-border: {{ $primaryColor }};
    --font-base: '{{ $headingFont }}', serif;
    --font-accent: '{{ $accentFont }}', cursive;
    --font-latin: '{{ $bodyFont }}', cursive;
    --menu-bg: {{ $menuBg }};
    --menu-inactive: {{ $menuInactive }};
    --menu-active: {{ $menuActive }};
    --btn-color: {{ $btnColor }};
}

*, *::before, *::after { box-sizing: border-box; }

body {
    background-color: var(--inv-bg);
    color: var(--inv-base);
    font-family: var(--font-base);
    overflow: hidden;
    -webkit-font-smoothing: antialiased;
    margin: 0; padding: 0;
}

.animate__slower { animation-duration: 2s; }
.animate__slow { animation-duration: 1.5s; }

.satumomen_track {
    height: 100dvh; width: 100vw;
    overflow-y: auto; overflow-x: hidden;
    scroll-snap-type: y mandatory; scroll-behavior: smooth;
    position: relative; background-color: var(--inv-bg);
    -webkit-overflow-scrolling: touch;
}
.satumomen_slide {
    height: 100dvh; width: 100vw;
    scroll-snap-align: start; position: relative;
    display: flex; justify-content: center; align-items: center;
}
.container-mobile {
    width: 100%; max-width: 480px; height: 100%;
    position: relative; margin: 0 auto;
    background-size: cover; background-position: center;
    background-color: var(--inv-bg); overflow: hidden;
    box-shadow: 0 0 30px rgba(0,0,0,0.6);
}
.frame { position: absolute; inset: 0; pointer-events: none; z-index: 10; }
.frame img { position: absolute; z-index: 10; }
.frame-tl { top: 0; left: 0; max-width: 40%; max-height: 30%; }
.frame-tr { top: 0; right: 0; max-width: 40%; max-height: 30%; }
.frame-bl { bottom: 0; left: 0; max-width: 40%; max-height: 30%; }
.frame-br { bottom: 0; right: 0; max-width: 40%; max-height: 30%; }
.frame-tc { top: 0; left: 50%; transform: translateX(-50%); width: 100%; max-height: 15%; }
.frame-bc { bottom: 0; left: 50%; transform: translateX(-50%); width: 100%; max-height: 15%; }
.frame-lc { left: 0; top: 0; height: 100%; width: auto; max-width: 12%; }
.frame-rc { right: 0; top: 0; height: 100%; width: auto; max-width: 12%; }

.satumomen_nav_wrap {
    position: fixed; bottom: 20px; left: 0; right: 0; z-index: 50;
    display: flex; justify-content: center; pointer-events: none;
}
.satumomen_menu {
    width: 260px; backdrop-filter: blur(12px); -webkit-backdrop-filter: blur(12px);
    padding: 8px 0; overflow: hidden;
    box-shadow: 0 4px 30px rgba(0,0,0,0.45), inset 0 0 0 1px rgba(212,176,81,0.2);
    border-radius: 50px; pointer-events: auto;
}
.satumomen_menu_inner {
    display: flex; flex-direction: row;
    transition: transform 0.35s cubic-bezier(0.4, 0, 0.2, 1);
}
.satumomen_menu_item {
    color: var(--menu-inactive); text-align: center; font-size: 9px; cursor: pointer;
    transition: color 0.3s, opacity 0.3s;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    gap: 2px; opacity: 0.45; flex: 0 0 65px; width: 65px; height: 42px;
    white-space: nowrap; position: relative;
}
.satumomen_menu_item.active { color: var(--menu-active); opacity: 1; }
.satumomen_menu_item.active::after {
    content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%);
    width: 5px; height: 5px; background: var(--inv-accent); border-radius: 50%;
}
.satumomen_menu_item svg { width: 16px; height: 16px; }

.font-accent { font-family: var(--font-accent); }
.font-latin { font-family: var(--font-latin); }
.color-accent { color: var(--inv-accent); }

.form-input {
    width: 100%; padding: 12px 15px; border-radius: 8px;
    background: rgba(255,255,255,0.08); border: 1px solid rgba(212,176,81,0.4);
    color: white; margin-bottom: 12px; font-family: var(--font-base);
    font-size: 14px; transition: border-color 0.3s ease; outline: none;
}
.form-input:focus { border-color: var(--inv-accent); background: rgba(255,255,255,0.12); }
.form-input::placeholder { color: rgba(255,255,255,0.5); }

.btn-primary {
    background-color: var(--btn-color); color: var(--inv-accent);
    border: 1px solid var(--inv-accent); padding: 12px 28px; border-radius: 50px;
    font-family: sans-serif; font-weight: 600; font-size: 13px;
    letter-spacing: 0.5px; cursor: pointer; transition: all 0.3s ease;
    display: inline-block; width: 100%; text-align: center;
}
.btn-primary:hover {
    background-color: var(--inv-accent); color: var(--btn-color);
    box-shadow: 0 4px 15px rgba(212,176,81,0.3);
}

.slide-content {
    position: relative; z-index: 20; width: 100%; height: 100%;
    display: flex; flex-direction: column; padding: 60px 28px; overflow-y: auto;
}
.slide-center { justify-content: center; align-items: center; }

#cover-overlay { position: fixed; inset: 0; z-index: 100; background-color: var(--inv-bg); }

.photo-frame {
    width: 120px; height: 120px; margin: 0 auto;
    border: 2px solid var(--inv-accent); border-radius: 50%; overflow: hidden;
    box-shadow: 0 4px 20px rgba(212,176,81,0.2);
}
.photo-frame img { width: 100%; height: 100%; object-fit: cover; }
a { color: inherit; text-decoration: none; }

.satumomen_track::-webkit-scrollbar { display: none; }
.satumomen_track { -ms-overflow-style: none; scrollbar-width: none; }
.slide-content::-webkit-scrollbar { display: none; }
.slide-content { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endpush

<div style="background-color: var(--inv-bg); height: 100dvh;" x-data="{
    opened: false,
    activeSlide: 0,
    playing: false,
    audioEl: null,
    slides: {{ $slidesJson }},
    init() { this.setupIntersectionObserver(); },
    open() {
        this.opened = true;
        this.$nextTick(() => { this.scrollToSlide(1); });
    },
    toggleAudio() {},
    scrollToSlide(index) {
        if(index < 0 || index >= this.slides.length) return;
        const target = document.getElementById('slide-' + index);
        if(target) { target.scrollIntoView({ behavior: 'smooth' }); this.activeSlide = index; }
    },
    setupIntersectionObserver() {
        let observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    this.activeSlide = parseInt(entry.target.dataset.index);
                    const animatedEls = entry.target.querySelectorAll('.animate__animated');
                    animatedEls.forEach(el => { el.style.animation = 'none'; el.offsetHeight; el.style.animation = null; });
                }
            });
        }, { threshold: 0.5 });
        this.$nextTick(() => {
            document.querySelectorAll('.satumomen_slide').forEach((slide) => { observer.observe(slide); });
        });
    }
}">

    {{-- Cover --}}
    @php $hasCover = false; @endphp
    @foreach($enabledSections as $section)
        @if($section['id'] === 'cover')
            @php $hasCover = true; @endphp
            @include('livewire.themes.sections._cover', [
                'invitation' => $invitation,
                'sc' => $section['config'] ?? [],
                'frame' => $frameConfig,
            ])
        @endif
    @endforeach

    {{-- Main Slider --}}
    <div class="satumomen_track">
        @foreach($enabledSections as $section)
            @if($section['id'] !== 'cover')
                @php $slideIndex++; @endphp
                @include('livewire.themes.sections._' . $section['id'], [
                    'invitation' => $invitation,
                    'sc' => $section['config'] ?? [],
                    'frame' => $frameConfig,
                    'slideIndex' => $slideIndex,
                ])
            @endif
        @endforeach
    </div>

    {{-- Bottom Nav --}}
    @php
        $navSections = [];
        $navIdx = 0;
        foreach ($enabledSections as $section) {
            if ($section['id'] === 'cover') continue;
            $navIdx++;
            $navSections[] = array_merge($section, ['_slideIndex' => $navIdx]);
        }
    @endphp
    @include('livewire.themes.sections._nav', [
        'enabledSections' => $navSections,
        'nav' => $navConfig,
        'invitation' => $invitation,
    ])

</div>
