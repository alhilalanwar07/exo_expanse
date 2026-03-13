@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Noto+Serif+Display:ital,wght@0,300..700;1,300..700&family=Great+Vibes&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --s03-bg: #f5efe6;
    --s03-text: #333333;
    --s03-accent: #8A4B33;
    --s03-accent2: #6b3a28;
    --s03-card: rgba(255,255,255,0.85);
    --s03-border: rgba(138,75,51,0.25);
    --s03-overlay: rgba(138,75,51,0.08);
    --font-serif: 'Noto Serif Display', serif;
    --font-script: 'Great Vibes', cursive;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body { background: var(--s03-bg); color: var(--s03-text); font-family: var(--font-serif); overflow: hidden; -webkit-font-smoothing: antialiased; }

/* Scroll container */
.s03-track { height: 100dvh; width: 100vw; overflow-y: auto; overflow-x: hidden; scroll-snap-type: y mandatory; scroll-behavior: smooth; position: relative; }
.s03-slide { min-height: 100dvh; width: 100vw; scroll-snap-align: start; position: relative; display: flex; justify-content: center; align-items: center; }
.s03-slide.auto-h { height: auto; min-height: 100dvh; scroll-snap-align: start; }

/* Mobile container */
.s03-mobile { width: 100%; max-width: 480px; min-height: 100dvh; position: relative; margin: 0 auto; overflow: hidden; background-image: url('{{ asset('themes/special-03/assets/eks-15-bg-01.jpg') }}'); background-size: cover; background-position: center; }
.s03-mobile.auto-h { height: auto; min-height: 100%; }

/* Edge decorations — all sides with breathing animation */
.s03-deco { position: absolute; inset: 0; pointer-events: none; z-index: 5; overflow: hidden; }
.s03-deco img { position: absolute; z-index: 5; opacity: 0; transition: opacity 1.2s ease, transform 1.2s ease; }
/* Corners */
.s03-deco .deco-tl { top: -10px; left: -10px; width: 140px; transform: translate(-20px, -20px); }
.s03-deco .deco-tr { top: -10px; right: -10px; width: 140px; transform: translate(20px, -20px) scaleX(-1); }
.s03-deco .deco-bl { bottom: -10px; left: -10px; width: 140px; transform: translate(-20px, 20px) scaleX(-1); }
.s03-deco .deco-br { bottom: -10px; right: -10px; width: 140px; transform: translate(20px, 20px); }
/* In-view: fade in and slide to position */
.s03-slide.in-view .s03-deco img { opacity: 0.75; }
.s03-slide.in-view .deco-tl { transform: translate(0,0); }
.s03-slide.in-view .deco-tr { transform: translate(0,0) scaleX(-1); }
.s03-slide.in-view .deco-bl { transform: translate(0,0) scaleX(-1); }
.s03-slide.in-view .deco-br { transform: translate(0,0); }
/* Breathing animation */
@keyframes s03-breathe { 0%,100% { transform: scale(1); } 50% { transform: scale(1.08); } }
@keyframes s03-breathe-flip { 0%,100% { transform: scaleX(-1) scale(1); } 50% { transform: scaleX(-1) scale(1.08); } }
.s03-slide.in-view .deco-tl { animation: s03-breathe 5s ease-in-out infinite; }
.s03-slide.in-view .deco-br { animation: s03-breathe 5s ease-in-out infinite 1.2s; }
.s03-slide.in-view .deco-tr { animation: s03-breathe-flip 5s ease-in-out infinite 0.6s; }
.s03-slide.in-view .deco-bl { animation: s03-breathe-flip 5s ease-in-out infinite 1.8s; }

/* Content wrapper */
.s03-content { position: relative; z-index: 10; width: 100%; display: flex; flex-direction: column; padding: 60px 28px; }
.s03-center { justify-content: center; align-items: center; min-height: 100dvh; }

/* Reveal animations */
.s03-reveal { opacity: 0; transform: translateY(30px); transition: opacity 0.8s ease, transform 0.8s ease; }
.s03-reveal.from-left { transform: translateX(-30px); }
.s03-reveal.from-right { transform: translateX(30px); }
.s03-reveal.from-scale { transform: scale(0.9); }
.s03-slide.in-view .s03-reveal { opacity: 1; transform: translate(0) scale(1); }
.s03-slide.in-view .s03-reveal.d1 { transition-delay: 0.15s; }
.s03-slide.in-view .s03-reveal.d2 { transition-delay: 0.3s; }
.s03-slide.in-view .s03-reveal.d3 { transition-delay: 0.45s; }
.s03-slide.in-view .s03-reveal.d4 { transition-delay: 0.6s; }
.s03-slide.in-view .s03-reveal.d5 { transition-delay: 0.75s; }

/* Typography */
.font-script { font-family: var(--font-script); }
.color-accent { color: var(--s03-accent); }

/* Cover overlay */
#s03-cover-overlay { position: fixed; inset: 0; z-index: 100; background: var(--s03-bg); display: flex; justify-content: center; align-items: center; }

/* Photo frames */
.s03-photo { width: 130px; height: 130px; margin: 0 auto; border: 2px solid var(--s03-accent); border-radius: 50%; overflow: hidden; box-shadow: 0 4px 20px rgba(138,75,51,0.15); }
.s03-photo img { width: 100%; height: 100%; object-fit: cover; }

/* Cards */
.s03-card { background: var(--s03-card); border: 1px solid var(--s03-border); border-radius: 16px; padding: 28px 24px; backdrop-filter: blur(8px); }

/* Buttons */
.s03-btn { background: var(--s03-accent); color: #fff; border: none; padding: 12px 32px; border-radius: 50px; font-family: var(--font-serif); font-size: 13px; font-weight: 600; letter-spacing: 0.5px; cursor: pointer; transition: all 0.3s ease; display: inline-flex; align-items: center; gap: 8px; }
.s03-btn:hover { background: var(--s03-accent2); box-shadow: 0 4px 15px rgba(138,75,51,0.3); transform: translateY(-1px); }
.s03-btn-outline { background: transparent; color: var(--s03-accent); border: 1.5px solid var(--s03-accent); padding: 10px 24px; border-radius: 50px; font-family: var(--font-serif); font-size: 12px; font-weight: 600; cursor: pointer; transition: all 0.3s; display: inline-flex; align-items: center; gap: 6px; text-decoration: none; }
.s03-btn-outline:hover { background: var(--s03-accent); color: #fff; }

/* Form */
.s03-input { width: 100%; padding: 12px 16px; border-radius: 10px; background: rgba(138,75,51,0.06); border: 1px solid var(--s03-border); color: var(--s03-text); font-family: var(--font-serif); font-size: 14px; transition: border-color 0.3s; outline: none; margin-bottom: 12px; }
.s03-input:focus { border-color: var(--s03-accent); background: rgba(138,75,51,0.1); }
.s03-input::placeholder { color: rgba(51,51,51,0.4); }

/* Bottom Nav */
.s03-nav-wrap { position: fixed; bottom: 20px; left: 0; right: 0; z-index: 50; display: flex; justify-content: center; pointer-events: none; }
.s03-nav { width: 260px; background: rgba(138,75,51,0.95); backdrop-filter: blur(12px); padding: 8px 0; border-radius: 50px; pointer-events: auto; box-shadow: 0 4px 20px rgba(0,0,0,0.15); overflow: hidden; }
.s03-nav-inner { display: flex; transition: transform 0.35s cubic-bezier(0.4,0,0.2,1); }
.s03-nav-item { color: rgba(255,255,255,0.5); text-align: center; font-size: 9px; cursor: pointer; transition: all 0.3s; display: flex; flex-direction: column; align-items: center; gap: 2px; flex: 0 0 65px; width: 65px; height: 42px; justify-content: center; position: relative; }
.s03-nav-item.active { color: #fff; opacity: 1; }
.s03-nav-item.active::after { content: ''; position: absolute; bottom: 0; left: 50%; transform: translateX(-50%); width: 5px; height: 5px; background: #fff; border-radius: 50%; }
.s03-nav-item svg { width: 16px; height: 16px; }

/* Separator */
.s03-sep { width: 60px; height: 1px; background: var(--s03-accent); opacity: 0.4; margin: 12px auto; }

/* Scrollbar hide */
.s03-track::-webkit-scrollbar { display: none; }
.s03-track { -ms-overflow-style: none; scrollbar-width: none; }
.s03-content::-webkit-scrollbar { display: none; }
.s03-content { -ms-overflow-style: none; scrollbar-width: none; }
a { color: inherit; text-decoration: none; }

/* Ornament divider */
.s03-ornament { color: var(--s03-accent); opacity: 0.5; margin: 8px auto; }

/* Quote section */
.s03-quote-bg { background: var(--s03-accent); color: #fff; }

/* ===== BUTTERFLY ANIMATION ===== */
.s03-butterfly { position: absolute; z-index: 12; pointer-events: none; width: 28px; height: 28px; opacity: 0; }
.s03-butterfly svg { width: 100%; height: 100%; fill: var(--s03-accent); opacity: 0.5; }
.s03-butterfly .wing-l, .s03-butterfly .wing-r { transform-origin: center; }
.s03-butterfly .wing-l { animation: s03-wingL 0.3s ease-in-out infinite; }
.s03-butterfly .wing-r { animation: s03-wingR 0.3s ease-in-out infinite; }

@keyframes s03-wingL { 0%,100% { transform: rotateY(0deg); } 50% { transform: rotateY(60deg); } }
@keyframes s03-wingR { 0%,100% { transform: rotateY(0deg); } 50% { transform: rotateY(-60deg); } }

/* Butterfly flight paths */
.s03-bf-1 { animation: s03-fly1 12s ease-in-out infinite 1s; }
.s03-bf-2 { animation: s03-fly2 15s ease-in-out infinite 3s; width: 22px; height: 22px; }
.s03-bf-3 { animation: s03-fly3 18s ease-in-out infinite 5s; width: 18px; height: 18px; }

@keyframes s03-fly1 {
    0% { top: 70%; left: -10%; opacity: 0; transform: rotate(-15deg) scale(0.8); }
    10% { opacity: 0.7; }
    25% { top: 40%; left: 30%; transform: rotate(10deg) scale(1); }
    50% { top: 20%; left: 65%; transform: rotate(-8deg) scale(0.9); }
    75% { top: 35%; left: 85%; transform: rotate(15deg) scale(1.1); }
    90% { opacity: 0.6; }
    100% { top: 10%; left: 110%; opacity: 0; transform: rotate(-5deg) scale(0.8); }
}
@keyframes s03-fly2 {
    0% { top: 15%; right: -10%; left: auto; opacity: 0; transform: rotate(10deg) scale(0.7); }
    10% { opacity: 0.5; }
    30% { top: 45%; right: 20%; transform: rotate(-12deg) scale(1); }
    60% { top: 65%; right: 55%; transform: rotate(8deg) scale(0.85); }
    85% { opacity: 0.4; }
    100% { top: 80%; right: 110%; opacity: 0; transform: rotate(-10deg) scale(0.7); }
}
@keyframes s03-fly3 {
    0% { bottom: 20%; left: -8%; opacity: 0; transform: rotate(5deg) scale(0.6); }
    15% { opacity: 0.45; }
    35% { bottom: 50%; left: 45%; transform: rotate(-10deg) scale(0.9); }
    65% { bottom: 25%; left: 70%; transform: rotate(12deg) scale(0.75); }
    90% { opacity: 0.3; }
    100% { bottom: 5%; left: 110%; opacity: 0; transform: rotate(-8deg) scale(0.6); }
}

/* ===== COVER/OPENING DECO ANIMATIONS ===== */
.s03-cover-deco .deco-tl { animation: s03-coverDecoTL 6s ease-in-out infinite !important; opacity: 0.8 !important; }
.s03-cover-deco .deco-tr { animation: s03-coverDecoTR 6s ease-in-out infinite 0.8s !important; opacity: 0.8 !important; }
.s03-cover-deco .deco-bl { animation: s03-coverDecoBL 7s ease-in-out infinite 1.5s !important; opacity: 0.8 !important; }
.s03-cover-deco .deco-br { animation: s03-coverDecoBR 7s ease-in-out infinite 2s !important; opacity: 0.8 !important; }

@keyframes s03-coverDecoTL {
    0%,100% { transform: translate(0,0) rotate(0deg) scale(1); }
    30% { transform: translate(3px, 4px) rotate(2deg) scale(1.06); }
    60% { transform: translate(-2px, 2px) rotate(-1.5deg) scale(1.1); }
}
@keyframes s03-coverDecoTR {
    0%,100% { transform: translate(0,0) scaleX(-1) rotate(0deg) scale(1); }
    35% { transform: translate(-3px, 3px) scaleX(-1) rotate(-2deg) scale(1.08); }
    65% { transform: translate(2px, 5px) scaleX(-1) rotate(1.5deg) scale(1.05); }
}
@keyframes s03-coverDecoBL {
    0%,100% { transform: translate(0,0) scaleX(-1) rotate(0deg) scale(1); }
    40% { transform: translate(4px, -3px) scaleX(-1) rotate(2deg) scale(1.07); }
    70% { transform: translate(-2px, -5px) scaleX(-1) rotate(-1deg) scale(1.1); }
}
@keyframes s03-coverDecoBR {
    0%,100% { transform: translate(0,0) rotate(0deg) scale(1); }
    25% { transform: translate(-4px, -4px) rotate(-2deg) scale(1.08); }
    55% { transform: translate(3px, -2px) rotate(1.5deg) scale(1.05); }
}
</style>
@endpush

<div style="background: var(--s03-bg); height: 100dvh;" x-data="{
    opened: false, activeSlide: 0, playing: false, audioEl: null,
    open() {
        this.opened = true;
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if(this.audioEl) this.audioEl.play().then(() => this.playing = true).catch(() => {});
            this.scrollToSlide(1);
            this.initObserver();
        });
    },
    toggleAudio() {
        if(!this.audioEl) this.audioEl = document.getElementById('bgMusic');
        if(!this.audioEl) return;
        if(this.playing) { this.audioEl.pause(); this.playing = false; }
        else { this.audioEl.play().then(()=>this.playing=true).catch(()=>{}); }
    },
    scrollToSlide(idx) {
        const t = document.getElementById('slide-'+idx);
        if(t) { t.scrollIntoView({behavior:'smooth'}); this.activeSlide = idx; }
    },
    initObserver() {
        new IntersectionObserver((entries) => {
            entries.forEach(e => {
                if(e.isIntersecting) {
                    e.target.classList.add('in-view');
                    this.activeSlide = parseInt(e.target.dataset.index);
                } else {
                    e.target.classList.remove('in-view');
                }
            });
        }, {threshold: 0.3}).observe(document.querySelectorAll('.s03-slide').forEach(s => {
            new IntersectionObserver((entries) => {
                entries.forEach(e => {
                    if(e.isIntersecting) { e.target.classList.add('in-view'); this.activeSlide = parseInt(e.target.dataset.index); }
                });
            }, {threshold: 0.3}).observe(s);
        }) || document.body);
    }
}">
    @if($invitation->music_url)
    <audio id="bgMusic" loop><source src="{{ img_url($invitation->music_url) }}" type="audio/mpeg"></audio>
    @endif

    {{-- COVER OVERLAY --}}
    <div x-show="!opened" x-transition.opacity.duration.800ms id="s03-cover-overlay">
        <div class="s03-mobile" style="background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : asset('themes/special-03/assets/cover.jpg') }}'); background-size: cover; background-position: center; display: flex;">
            <div class="s03-deco s03-cover-deco">
                <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="" style="opacity:.75;transform:translate(0,0);">
                <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="" style="opacity:.75;transform:translate(0,0) scaleX(-1);">
                <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="" style="opacity:.75;transform:translate(0,0) scaleX(-1);">
                <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="" style="opacity:.75;transform:translate(0,0);">
            </div>
            {{-- Butterflies --}}
            <div class="s03-butterfly s03-bf-1"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
            <div class="s03-butterfly s03-bf-2"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
            <div class="s03-butterfly s03-bf-3"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
            <div style="position:absolute;inset:0;background:linear-gradient(to bottom, rgba(245,239,230,0.65) 0%, rgba(245,239,230,0.35) 30%, rgba(245,239,230,0.5) 50%, rgba(245,239,230,0.75) 70%, rgba(245,239,230,0.95) 100%);z-index:1;"></div>
            <div class="s03-content" style="justify-content:flex-end;align-items:center;padding-bottom:60px;">
                <div class="text-center" style="margin-bottom:auto;padding-top:80px;">
                    <div style="letter-spacing:4px;text-transform:uppercase;font-size:11px;font-weight:600;color:var(--s03-accent);text-shadow:0 1px 8px rgba(245,239,230,0.9);">The Wedding Of</div>
                    <div class="font-script color-accent" style="font-size:42px;line-height:1.2;margin-top:8px;text-shadow:0 2px 12px rgba(245,239,230,0.95), 0 0 30px rgba(245,239,230,0.8);">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</div>
                    @if($invitation->akad_date)
                    <div style="font-size:13px;margin-top:12px;letter-spacing:2px;color:var(--s03-text);font-weight:500;text-shadow:0 1px 8px rgba(245,239,230,0.9);">{{ $invitation->akad_date->format('d . m . Y') }}</div>
                    @endif
                </div>
                <div class="text-center" style="margin-top:auto;">
                    <div style="font-size:12px;opacity:0.6;margin-bottom:4px;">Kepada Yth. Bapak/Ibu/Saudara/i</div>
                    <div style="font-size:18px;font-weight:600;margin-bottom:20px;">{{ request('kpd', 'Tamu Undangan') }}</div>
                    <button type="button" @click="open()" class="s03-btn">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                        Buka Undangan
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="s03-track">

        {{-- Slide 1: OPENING --}}
        <div id="slide-1" data-index="1" class="s03-slide">
            <div class="s03-mobile" style="background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : asset('themes/special-03/assets/cover.jpg') }}'); background-size:cover; background-position:center; display:flex;">
                <div class="s03-deco s03-cover-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                </div>
                {{-- Butterflies --}}
                <div class="s03-butterfly s03-bf-1"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
                <div class="s03-butterfly s03-bf-2"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
                <div class="s03-butterfly s03-bf-3"><svg viewBox="0 0 24 24"><path class="wing-l" d="M12 12C9 4 2 3 2 8s4 7 10 4"/><path class="wing-r" d="M12 12C15 4 22 3 22 8s-4 7-10 4"/><path d="M12 12c-1 4-2 8-2 10M12 12c1 4 2 8 2 10" stroke="currentColor" stroke-width="0.5" fill="none"/></svg></div>
                <div style="position:absolute;inset:0;background:linear-gradient(to bottom, rgba(245,239,230,0.65) 0%, rgba(245,239,230,0.4) 30%, rgba(245,239,230,0.55) 50%, rgba(245,239,230,0.85) 100%);z-index:1;"></div>
                <div class="s03-content s03-center text-center">
                    <div class="s03-reveal" style="letter-spacing:4px;text-transform:uppercase;font-size:11px;font-weight:600;color:var(--s03-accent);text-shadow:0 1px 8px rgba(245,239,230,0.9);">The Wedding Of</div>
                    <div class="s03-reveal from-scale d1 font-script color-accent" style="font-size:48px;line-height:1.1;margin:12px 0;text-shadow:0 2px 12px rgba(245,239,230,0.95), 0 0 30px rgba(245,239,230,0.8);">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</div>
                    @if($invitation->akad_date)
                    <div class="s03-reveal d2" style="font-size:14px;letter-spacing:3px;color:var(--s03-text);font-weight:500;text-shadow:0 1px 8px rgba(245,239,230,0.9);">{{ $invitation->akad_date->format('d . m . Y') }}</div>
                    @endif
                    <div class="s03-sep s03-reveal d3"></div>
                </div>
            </div>
        </div>

        {{-- Slide 2: MEMPELAI --}}
        <div id="slide-2" data-index="2" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content s03-center text-center">
                    @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                    @php
                        $p1 = $order === 'groom_first'
                            ? ['role'=>'Putra','name'=>$invitation->groom_name,'father'=>$invitation->groom_father,'mother'=>$invitation->groom_mother,'photo'=>$invitation->groom_photo]
                            : ['role'=>'Putri','name'=>$invitation->bride_name,'father'=>$invitation->bride_father,'mother'=>$invitation->bride_mother,'photo'=>$invitation->bride_photo];
                        $p2 = $order === 'groom_first'
                            ? ['role'=>'Putri','name'=>$invitation->bride_name,'father'=>$invitation->bride_father,'mother'=>$invitation->bride_mother,'photo'=>$invitation->bride_photo]
                            : ['role'=>'Putra','name'=>$invitation->groom_name,'father'=>$invitation->groom_father,'mother'=>$invitation->groom_mother,'photo'=>$invitation->groom_photo];
                    @endphp
                    <div class="s03-reveal" style="font-size:11px;letter-spacing:3px;text-transform:uppercase;opacity:0.5;margin-bottom:16px;">Assalamu'alaikum Wr. Wb.</div>
                    <div class="s03-reveal d1 s03-photo"><img src="{{ $p1['photo'] ? img_url($p1['photo']) : asset('themes/s03-modern/assets/placeholder-groom.jpg') }}" alt=""></div>
                    <div class="s03-reveal d1 font-script color-accent" style="font-size:28px;margin:8px 0 4px;">{{ $p1['name'] }}</div>
                    <div class="s03-reveal d2" style="font-size:13px;opacity:0.7;">{{ $p1['role'] }} dari {{ $p1['father'] }} & {{ $p1['mother'] }}</div>

                    <div class="s03-reveal d2 color-accent font-script" style="font-size:36px;margin:12px 0;">&</div>

                    <div class="s03-reveal d3 s03-photo"><img src="{{ $p2['photo'] ? img_url($p2['photo']) : asset('themes/s03-modern/assets/placeholder-bride.jpg') }}" alt=""></div>
                    <div class="s03-reveal d3 font-script color-accent" style="font-size:28px;margin:8px 0 4px;">{{ $p2['name'] }}</div>
                    <div class="s03-reveal d4" style="font-size:13px;opacity:0.7;">{{ $p2['role'] }} dari {{ $p2['father'] }} & {{ $p2['mother'] }}</div>
                </div>
            </div>
        </div>

        {{-- Slide 3: QUOTE --}}
        <div id="slide-3" data-index="3" class="s03-slide">
            <div class="s03-mobile s03-quote-bg" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="" style="opacity:0.3;">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="" style="opacity:0.3;">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="" style="opacity:0.3;">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="" style="opacity:0.3;">

                </div>
                <div class="s03-content s03-center text-center" style="padding:60px 32px;">
                    <div class="s03-reveal font-script" style="font-size:32px;color:rgba(255,255,255,0.9);margin-bottom:20px;">QS. Ar-Rum : 21</div>
                    <div class="s03-reveal d1" style="font-size:14px;line-height:1.9;color:rgba(255,255,255,0.85);font-style:italic;">
                        "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 4: LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <div id="slide-4" data-index="4" class="s03-slide auto-h">
            <div class="s03-mobile auto-h" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content" style="padding:80px 28px;">
                    <div class="text-center s03-reveal" style="margin-bottom:28px;">
                        <div class="font-script color-accent" style="font-size:36px;">Love Story</div>
                        <div class="s03-sep"></div>
                    </div>
                    <div style="position:relative;padding-left:20px;border-left:2px solid rgba(138,75,51,0.2);margin-left:12px;">
                        @foreach($invitation->love_story as $i => $story)
                        <div class="s03-reveal" style="margin-bottom:24px;position:relative;transition-delay:{{ $i * 0.15 }}s;">
                            <div style="position:absolute;left:-27px;top:6px;width:12px;height:12px;border-radius:50%;background:var(--s03-accent);border:3px solid var(--s03-bg);"></div>
                            <div class="s03-card">
                                @if(!empty($story['date']))<div class="color-accent" style="font-size:12px;font-weight:600;margin-bottom:4px;">{{ $story['date'] }}</div>@endif
                                <div class="color-accent" style="font-size:16px;font-weight:600;margin-bottom:6px;">{{ $story['title'] ?? '' }}</div>
                                <div style="font-size:13px;opacity:0.7;line-height:1.7;">{{ $story['description'] ?? '' }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 5: ACARA --}}
        <div id="slide-5" data-index="5" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content s03-center text-center" style="gap:16px;">
                    <div class="s03-card s03-reveal" style="width:100%;">
                        <div class="font-script color-accent" style="font-size:28px;margin-bottom:8px;">Akad Nikah</div>
                        <div style="font-size:14px;margin-bottom:4px;">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</div>
                        <div style="font-size:13px;opacity:0.6;">Pukul {{ $invitation->akad_date?->format('H:i') }} WITA</div>
                        <div class="s03-sep"></div>
                        <div style="font-size:13px;"><strong>{{ $invitation->akad_venue }}</strong></div>
                        <div style="font-size:12px;opacity:0.6;">{{ $invitation->akad_address }}</div>
                    </div>
                    @if($invitation->resepsi_date)
                    <div class="s03-card s03-reveal d2" style="width:100%;">
                        <div class="font-script color-accent" style="font-size:28px;margin-bottom:8px;">Resepsi</div>
                        <div style="font-size:14px;margin-bottom:4px;">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</div>
                        <div style="font-size:13px;opacity:0.6;">Pukul {{ $invitation->resepsi_date?->format('H:i') }} WITA - Selesai</div>
                        <div class="s03-sep"></div>
                        <div style="font-size:13px;"><strong>{{ $invitation->resepsi_venue }}</strong></div>
                        <div style="font-size:12px;opacity:0.6;">{{ $invitation->resepsi_address }}</div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Slide 6: MAPS --}}
        <div id="slide-6" data-index="6" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content s03-center">
                    @if($invitation->akad_maps_link)
                    @php $mapsEmbed = 'https://maps.google.com/maps?q=' . urlencode($invitation->akad_address ?? $invitation->akad_venue ?? '') . '&z=15&output=embed'; @endphp
                    <div class="s03-reveal" style="width:100%;border-radius:14px;overflow:hidden;margin-bottom:20px;">
                        <iframe src="{{ $mapsEmbed }}" width="100%" height="260" style="border:0;" allowfullscreen loading="lazy"></iframe>
                    </div>
                    @endif
                    <div class="text-center s03-reveal d1">
                        <div style="font-size:14px;opacity:0.8;margin-bottom:4px;"><strong>{{ $invitation->akad_venue }}</strong></div>
                        <div style="font-size:12px;opacity:0.6;margin-bottom:16px;">{{ $invitation->akad_address }}</div>
                        <a href="{{ $invitation->akad_maps_link }}" class="s03-btn-outline" target="_blank">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            Petunjuk Lokasi
                        </a>
                    </div>
                </div>
            </div>
        </div>

        {{-- Slide 7: GALLERY --}}
        @if($invitation->enable_gallery)
        <div id="slide-7" data-index="7" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content" style="padding-top:60px;overflow-y:auto;">
                    <div class="text-center s03-reveal" style="margin-bottom:16px;"><div class="font-script color-accent" style="font-size:32px;">Our Moments</div></div>
                    @php
                        $photos = $invitation->photos && count($invitation->photos) > 0 ? $invitation->photos : [
                            (object)['url' => asset('themes/s03-modern/assets/placeholder-gallery-1.jpg')],
                            (object)['url' => asset('themes/s03-modern/assets/placeholder-gallery-2.jpg')],
                            (object)['url' => asset('themes/s03-modern/assets/placeholder-gallery-3.jpg')],
                            (object)['url' => asset('themes/s03-modern/assets/placeholder-gallery-1.jpg')]
                        ];
                    @endphp
                    <div class="grid grid-cols-2 gap-3 pb-20">
                        @foreach($photos as $i => $photo)
                        <div class="s03-reveal" style="transition-delay:{{ $i*0.1 }}s;border-radius:12px;overflow:hidden;height:{{ $i%2==0?'150px':'120px' }};">
                            <img src="{{ $photo->url }}" alt="" style="width:100%;height:100%;object-fit:cover;">
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 8: GIFT --}}
        @if($invitation->enable_gift)
        <div id="slide-8" data-index="8" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content s03-center text-center">
                    <div class="font-script color-accent s03-reveal" style="font-size:32px;margin-bottom:8px;">Wedding Gift</div>
                    <div class="s03-reveal d1" style="font-size:13px;opacity:0.6;margin-bottom:20px;max-width:280px;margin-left:auto;margin-right:auto;">Terima kasih telah menambah kegembiraan pernikahan kami dengan kehadiran dan hadiah Anda.</div>
                    <div style="display:flex;flex-direction:column;gap:12px;width:100%;max-width:340px;">
                        @foreach($invitation->bank_accounts ?? [] as $acc)
                        <div class="s03-reveal s03-card" style="text-align:left;transition-delay:{{ $loop->index*0.15 }}s;">
                            <div style="font-weight:700;color:var(--s03-accent);margin-bottom:6px;">{{ $acc['bank'] ?? 'Bank' }}</div>
                            <div style="font-size:18px;font-weight:700;font-family:monospace;letter-spacing:2px;margin-bottom:4px;">{{ $acc['account_number'] }}</div>
                            <div style="font-size:13px;opacity:0.6;margin-bottom:10px;">a.n {{ $acc['account_name'] }}</div>
                            <button x-data="{copied:false}" @click="navigator.clipboard.writeText('{{ $acc['account_number'] }}');copied=true;setTimeout(()=>copied=false,2000)" class="s03-btn-outline" style="font-size:11px;padding:6px 14px;">
                                <span x-text="copied?'Tersalin ✓':'Salin Rekening'"></span>
                            </button>
                        </div>
                        @endforeach
                        @if($invitation->bank_name && empty($invitation->bank_accounts))
                        <div class="s03-reveal s03-card" style="text-align:left;">
                            <div style="font-weight:700;color:var(--s03-accent);margin-bottom:6px;">{{ $invitation->bank_name }}</div>
                            <div style="font-size:18px;font-weight:700;font-family:monospace;letter-spacing:2px;margin-bottom:4px;">{{ $invitation->bank_account }}</div>
                            <div style="font-size:13px;opacity:0.6;margin-bottom:10px;">a.n {{ $invitation->bank_holder }}</div>
                            <button x-data="{copied:false}" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}');copied=true;setTimeout(()=>copied=false,2000)" class="s03-btn-outline" style="font-size:11px;padding:6px 14px;">
                                <span x-text="copied?'Tersalin ✓':'Salin Rekening'"></span>
                            </button>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 9: RSVP --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <div id="slide-9" data-index="9" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content text-center" style="padding-top:50px;overflow-y:auto;" x-data="{
                    invitationId: {{ $invitation->id }}, name: '{{ request('kpd', '') }}', message: '', status: 'confirmed', pax: 1,
                    loading: false, submitted: false, error: '', wishes: [],
                    async submitForm() {
                        if(!this.name.trim()||!this.message.trim()){this.error='Mohon lengkapi nama dan ucapan.';return;}
                        this.loading=true;this.error='';
                        try {
                            const csrf=document.querySelector('meta[name=csrf-token]')?.content||'';
                            await fetch(`/api/invitations/${this.invitationId}/rsvp`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({name:this.name,status:this.status,pax:this.pax})});
                            const r=await fetch(`/api/invitations/${this.invitationId}/wishes`,{method:'POST',headers:{'Content-Type':'application/json','X-CSRF-TOKEN':csrf},body:JSON.stringify({name:this.name,message:this.message})});
                            if(r.ok){const d=await r.json();if(d.wish){d.wish.attendance_status=this.status;this.wishes.unshift(d.wish);}this.message='';this.submitted=true;}
                        } catch(e){this.error='Gagal mengirim.';}finally{this.loading=false;}
                    },
                    async loadWishes(){try{const r=await fetch(`/api/invitations/${this.invitationId}/wishes`);const d=await r.json();this.wishes=d.wishes||[];}catch(e){}},
                    init(){this.loadWishes();}
                }">
                    <div class="font-script color-accent s03-reveal" style="font-size:28px;margin-bottom:12px;">Do'a & Ucapan</div>

                    {{-- Countdown --}}
                    <div class="s03-reveal d1" style="display:flex;justify-content:center;gap:8px;margin-bottom:20px;" x-data="{
                        d:0,h:0,m:0,s:0,
                        t:new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') ?? now()->addDays(30)->format('Y-m-d H:i:s') }}'),
                        init(){setInterval(()=>{const df=this.t-new Date();if(df>0){this.d=Math.floor(df/864e5);this.h=Math.floor((df%864e5)/36e5);this.m=Math.floor((df%36e5)/6e4);this.s=Math.floor((df%6e4)/1e3);}},1000);}
                    }">
                        <template x-for="[v,l] in [[d,'Hari'],[h,'Jam'],[m,'Menit'],[s,'Detik']]">
                            <div style="border:1px solid var(--s03-border);border-radius:8px;padding:8px;width:56px;text-align:center;">
                                <div class="color-accent" style="font-size:18px;font-weight:700;" x-text="v">0</div>
                                <div style="font-size:9px;opacity:0.5;" x-text="l"></div>
                            </div>
                        </template>
                    </div>

                    <div x-show="submitted" x-transition class="s03-card s03-reveal text-center" style="margin-bottom:16px;">
                        <div class="color-accent" style="font-size:24px;">✓</div>
                        <div class="color-accent" style="font-weight:600;font-size:14px;">Terima kasih!</div>
                        <div style="font-size:12px;opacity:0.6;">Ucapan Anda telah tersimpan.</div>
                    </div>

                    <div x-show="!submitted" x-transition class="s03-card s03-reveal d2" style="text-align:left;margin-bottom:16px;">
                        <div x-show="error" x-transition style="margin-bottom:10px;padding:8px;border-radius:8px;background:rgba(220,38,38,0.08);border:1px solid rgba(220,38,38,0.2);text-align:center;">
                            <span style="color:#dc2626;font-size:12px;" x-text="error"></span>
                        </div>
                        <form @submit.prevent="submitForm">
                            <label style="font-size:11px;color:var(--s03-accent);font-weight:600;">Nama</label>
                            <input type="text" x-model="name" class="s03-input" placeholder="Nama Anda">
                            <label style="font-size:11px;color:var(--s03-accent);font-weight:600;">Kehadiran</label>
                            <div style="display:flex;gap:8px;margin-bottom:12px;">
                                <button type="button" @click="status='confirmed'" class="s03-btn-outline" style="flex:1;justify-content:center;font-size:12px;padding:8px;" :style="status==='confirmed'?'background:var(--s03-accent);color:#fff;':''">✓ Hadir</button>
                                <button type="button" @click="status='declined'" class="s03-btn-outline" style="flex:1;justify-content:center;font-size:12px;padding:8px;" :style="status==='declined'?'background:#dc2626;color:#fff;border-color:#dc2626;':''">✗ Tidak</button>
                            </div>
                            <div x-show="status==='confirmed'" x-transition style="margin-bottom:12px;">
                                <label style="font-size:11px;color:var(--s03-accent);font-weight:600;">Jumlah Tamu</label>
                                <select x-model="pax" class="s03-input"><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select>
                            </div>
                            <label style="font-size:11px;color:var(--s03-accent);font-weight:600;">Ucapan & Doa</label>
                            <textarea x-model="message" class="s03-input" rows="3" placeholder="Tulis ucapan terbaik Anda..."></textarea>
                            <button type="submit" :disabled="loading" class="s03-btn" style="width:100%;justify-content:center;">
                                <span x-text="loading?'Mengirim...':'Kirim Ucapan'"></span>
                            </button>
                        </form>
                    </div>

                    <div class="text-left" style="max-height:30vh;overflow-y:auto;padding-bottom:80px;">
                        <template x-for="w in wishes" :key="w.id">
                            <div style="background:rgba(138,75,51,0.05);border-left:2px solid var(--s03-accent);padding:10px;margin-bottom:8px;border-radius:0 8px 8px 0;">
                                <div style="font-size:13px;font-weight:700;color:var(--s03-accent);" x-text="w.name"></div>
                                <div style="font-size:12px;opacity:0.8;margin:4px 0;" x-text="w.message"></div>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </div>
        @endif

        {{-- Slide 10: CLOSING --}}
        <div id="slide-10" data-index="10" class="s03-slide">
            <div class="s03-mobile" style="display:flex;">
                <div class="s03-deco">
                    <img class="deco-tl" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-tr" src="{{ asset('themes/special-03/assets/eks-15-asset-01.png') }}" alt="">
                    <img class="deco-bl" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">
                    <img class="deco-br" src="{{ asset('themes/special-03/assets/eks-15-asset-03.png') }}" alt="">

                </div>
                <div class="s03-content s03-center text-center" style="padding:60px 32px;">
                    <div class="s03-reveal" style="font-size:14px;opacity:0.7;font-style:italic;line-height:1.8;margin-bottom:24px;">Merupakan suatu kebahagiaan dan kehormatan bagi kami, apabila Bapak/Ibu/Saudara/i berkenan hadir dan memberikan do'a restu.</div>
                    <div class="s03-reveal d1" style="font-size:13px;opacity:0.6;font-style:italic;">Hormat Kami</div>
                    <div class="s03-reveal d2 font-script color-accent" style="font-size:40px;margin:8px 0 24px;">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</div>
                    <div class="s03-reveal d3" style="opacity:0.3;font-size:9px;text-transform:uppercase;letter-spacing:2px;">Powered by Exo Expanse</div>
                </div>
            </div>
        </div>
    </div>

    {{-- NAV --}}
    <div x-show="opened" class="s03-nav-wrap" x-transition>
        <div class="s03-nav">
            <div class="s03-nav-inner" x-ref="navInner" x-effect="
                let items=[...($refs.navInner?.children||[])];let idx=items.findIndex(el=>Number(el.dataset.slide)===activeSlide);
                if(idx<0)idx=0;let mx=Math.max(0,items.length-4);let off=Math.max(0,Math.min(idx-1,mx));
                if($refs.navInner)$refs.navInner.style.transform='translateX(-'+(off*65)+'px)';
            ">
                <div class="s03-nav-item" data-slide="1" :class="{'active':activeSlide===1}" @click="scrollToSlide(1)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span>Cover</span></div>
                <div class="s03-nav-item" data-slide="2" :class="{'active':activeSlide===2}" @click="scrollToSlide(2)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.35a4 4 0 110 5.3M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.2M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg><span>Mempelai</span></div>
                <div class="s03-nav-item" data-slide="3" :class="{'active':activeSlide===3}" @click="scrollToSlide(3)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg><span>Ayat</span></div>
                @if($invitation->love_story && count($invitation->love_story) > 0)
                <div class="s03-nav-item" data-slide="4" :class="{'active':activeSlide===4}" @click="scrollToSlide(4)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg><span>Cerita</span></div>
                @endif
                <div class="s03-nav-item" data-slide="5" :class="{'active':activeSlide===5}" @click="scrollToSlide(5)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Acara</span></div>
                <div class="s03-nav-item" data-slide="6" :class="{'active':activeSlide===6}" @click="scrollToSlide(6)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg><span>Lokasi</span></div>
                @if($invitation->enable_gallery)
                <div class="s03-nav-item" data-slide="7" :class="{'active':activeSlide===7}" @click="scrollToSlide(7)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Galeri</span></div>
                @endif
                @if($invitation->enable_gift)
                <div class="s03-nav-item" data-slide="8" :class="{'active':activeSlide===8}" @click="scrollToSlide(8)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg><span>Hadiah</span></div>
                @endif
                @if($invitation->enable_rsvp || $invitation->enable_wishes)
                <div class="s03-nav-item" data-slide="9" :class="{'active':activeSlide===9}" @click="scrollToSlide(9)"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><span>RSVP</span></div>
                @endif
                @if($invitation->music_url)
                <div class="s03-nav-item" data-slide="0" @click="toggleAudio()" :class="{'active':playing}">
                    <svg x-show="playing" viewBox="0 0 24 24" fill="currentColor"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
                    <svg x-show="!playing" viewBox="0 0 24 24" fill="currentColor"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
                    <span x-text="playing?'Musik':'Mute'"></span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
