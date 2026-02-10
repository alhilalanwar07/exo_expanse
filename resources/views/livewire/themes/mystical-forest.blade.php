@section('title', 'The Mystical Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
{{-- Cinzel (Judul Magis), Fauna One (Teks Bacaan Unik), Pinyon Script (Aksen) --}}
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;900&family=Fauna+One&family=Pinyon+Script&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --mystic-dark: #071A18;    /* Deepest Forest Green */
    --mystic-teal: #134640;    /* Mid-tone Teal */
    --mystic-gold: #D4AF37;    /* Magic Gold Accent */
    --mystic-cream: #E8E3D1;   /* Readable Text Color */
    --mystic-overlay: rgba(7, 26, 24, 0.85);
    --glass-bg: rgba(19, 70, 64, 0.3);
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
    -webkit-tap-highlight-color: transparent;
    overflow-x: hidden;
}

body { 
    font-family: 'Fauna One', serif;
    background-color: var(--mystic-dark);
    color: var(--mystic-cream);
    line-height: 1.6;
    overflow-x: hidden;
}

h1, h2, h3 { font-family: 'Cinzel', serif; letter-spacing: 2px; }
.font-script { font-family: 'Pinyon Script', cursive; color: var(--mystic-gold); }

/* === COMPLEX ANIMATIONS KEYFRAMES === */
/* 1. Firefly Twinkle & Move */
@keyframes firefly-move {
    0%, 100% { transform: translate(0, 0) scale(1); opacity: 0.6; }
    25% { transform: translate(30px, -50px) scale(1.2); opacity: 1; }
    50% { transform: translate(-20px, 20px) scale(0.8); opacity: 0.4; }
    75% { transform: translate(10px, 60px) scale(1.1); opacity: 0.8; }
}

/* 2. Organic Sway (Goyangan Halus) */
@keyframes organic-sway {
    0%, 100% { transform: rotate(-2deg) translateY(0); }
    50% { transform: rotate(2deg) translateY(-10px); }
}

/* 3. Reveal: Unfurl & Grow (Mekar saat discroll) */
@keyframes reveal-unfurl {
    from { 
        opacity: 0; 
        transform: translateY(60px) scale(0.9) rotateX(20deg); 
        filter: blur(10px);
    }
    to { 
        opacity: 1; 
        transform: translateY(0) scale(1) rotateX(0); 
        filter: blur(0);
    }
}

/* 4. Magic Pulse Glow */
@keyframes magic-pulse {
    0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.4); }
    70% { box-shadow: 0 0 0 15px rgba(212, 175, 55, 0); }
    100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
}

/* Utility Classes for Scroll Reveal */
.reveal-element {
    opacity: 0; /* Hidden by default */
}
.reveal-element.is-visible {
    animation: reveal-unfurl 1s cubic-bezier(0.25, 0.46, 0.45, 0.94) forwards;
}
.delay-1 { animation-delay: 0.2s; }
.delay-2 { animation-delay: 0.4s; }
.delay-3 { animation-delay: 0.6s; }


/* === FIREFLY PARTICLES CONTAINER === */
.fireflies-container {
    position: fixed; inset: 0; pointer-events: none; z-index: 1;
    overflow: hidden;
}
.firefly {
    position: absolute; width: 4px; height: 4px;
    background: var(--mystic-gold); border-radius: 50%;
    box-shadow: 0 0 10px var(--mystic-gold), 0 0 20px var(--mystic-gold);
    animation: firefly-move ease-in-out infinite alternate;
}

/* === GLOBAL STYLES === */
.section { 
    padding: 100px 20px; position: relative; z-index: 2;
    /* Parallax background attachment for depth */
    background-attachment: fixed;
    background-position: center;
    background-size: cover;
    background-blend-mode: multiply;
}
.section-title { 
    text-align: center; margin-bottom: 60px; position: relative;
}
.section-title h2 { 
    font-size: 2.5rem; color: var(--mystic-gold); 
    text-transform: uppercase;
    text-shadow: 0 5px 15px rgba(0,0,0,0.5);
}
/* Ornamen Mistis di bawah judul */
.mystic-divider {
    height: 30px; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 80 20' fill='none' stroke='%23D4AF37'%3E%3Cpath d='M0 10 C20 0, 20 20, 40 10 C60 0, 60 20, 80 10' stroke-width='1'/%3E%3C/svg%3E");
    background-size: 80px 20px; opacity: 0.5; margin: 10px auto 0; width: 60%;
}

/* === COMPONENTS === */
.btn-magic {
    padding: 15px 40px; background: linear-gradient(45deg, var(--mystic-teal), var(--mystic-dark));
    border: 2px solid var(--mystic-gold); color: var(--mystic-gold);
    text-transform: uppercase; letter-spacing: 2px; font-weight: bold;
    position: relative; overflow: hidden; transition: 0.5s; cursor: pointer;
    animation: magic-pulse 2s infinite;
}
.btn-magic:hover { background: var(--mystic-gold); color: var(--mystic-dark); box-shadow: 0 0 30px var(--mystic-gold); }

/* Glass Card Effect */
.glass-card {
    background: var(--glass-bg);
    backdrop-filter: blur(10px); -webkit-backdrop-filter: blur(10px);
    border: 1px solid rgba(212, 175, 55, 0.2);
    border-radius: 16px; padding: 30px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.3);
}

/* === COVER === */
.cover { 
    position: fixed; inset: 0; z-index: 100; 
    display: flex; align-items: center; justify-content: center; overflow: hidden;
}
.cover-bg {
    position: absolute; inset: 0; background-size: cover; background-position: center;
    animation: organic-sway 20s ease-in-out infinite alternate; /* Background moves slowly */
    transform: scale(1.1);
}
.cover-overlay { position: absolute; inset: 0; background: linear-gradient(to bottom, rgba(7,26,24,0.5), var(--mystic-dark)); }
.cover-content {
    position: relative; z-index: 10; text-align: center; padding: 30px;
    border: 2px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(5px);
}

/* === HERO === */
.hero-section {
    min-height: 110vh; display: flex; align-items: center; justify-content: center;
    text-align: center; position: relative; overflow: hidden;
    /* Layered Parallax Backgrounds */
    background-image: url('https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=1200'); /* Hutan Gelap */
}
.hero-content-box {
    background: rgba(7, 26, 24, 0.6);
    padding: 50px; border: 3px double var(--mystic-gold);
    transform: translateZ(0); /* Fix for complex layers */
}
.hero-couple-img {
    width: 200px; height: 200px; border-radius: 50%; border: 4px solid var(--mystic-gold);
    margin: 0 auto 30px; object-fit: cover;
    box-shadow: 0 0 40px rgba(212, 175, 55, 0.3);
}

/* === COUPLE SECTION === */
.couple-card-mystic {
    text-align: center; position: relative;
}
.couple-img-frame-mystic {
    width: 220px; height: 300px; margin: 0 auto 30px;
    /* Bentuk Arch Gothic */
    clip-path: polygon(0% 15%, 50% 0%, 100% 15%, 100% 100%, 0% 100%);
    position: relative; z-index: 2;
}
.couple-img-frame-mystic::before {
    content: ''; position: absolute; inset: -5px; background: var(--mystic-gold);
    clip-path: polygon(0% 15%, 50% 0%, 100% 15%, 100% 100%, 0% 100%); z-index: -1;
    opacity: 0.5;
}
.couple-img-mystic { width: 100%; height: 100%; object-fit: cover; }

/* === EVENTS TIMELINE (Pohon Kehidupan) === */
.timeline-mystic {
    position: relative; max-width: 700px; margin: 0 auto;
}
/* Garis tengah seperti batang pohon bercahaya */
.timeline-mystic::before {
    content: ''; position: absolute; left: 50%; top: 0; bottom: 0; width: 4px;
    background: linear-gradient(to bottom, transparent, var(--mystic-gold), transparent);
    transform: translateX(-50%); box-shadow: 0 0 15px var(--mystic-gold);
}
.event-node {
    display: flex; justify-content: center; align-items: center; margin-bottom: 60px; position: relative;
}
.event-node:nth-child(even) { flex-direction: row-reverse; }
.event-content-mystic {
    width: 45%; background: var(--glass-bg); padding: 30px; border: 1px solid var(--mystic-gold);
    position: relative; backdrop-filter: blur(5px);
}
/* Konektor ke garis tengah */
.event-content-mystic::after {
    content: ''; position: absolute; top: 50%; width: 5%; height: 2px; background: var(--mystic-gold);
}
.event-node:nth-child(odd) .event-content-mystic::after { right: -5%; }
.event-node:nth-child(even) .event-content-mystic::after { left: -5%; }

.event-marker {
    position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%);
    width: 30px; height: 30px; background: var(--mystic-dark); border: 4px solid var(--mystic-gold);
    border-radius: 50%; z-index: 5; box-shadow: 0 0 20px var(--mystic-gold);
}

/* === RSVP & GIFT === */
.form-input-mystic {
    width: 100%; padding: 15px; background: rgba(0,0,0,0.3); border: 1px solid var(--mystic-teal);
    color: var(--mystic-cream); margin-bottom: 15px; font-family: inherit;
    transition: 0.3s;
}
.form-input-mystic:focus { outline: none; border-color: var(--mystic-gold); box-shadow: 0 0 15px rgba(212,175,55,0.3); }

/* === BOTTOM NAV (Magic Runes Vibe) === */
.nav-mystic {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    background: rgba(7, 26, 24, 0.9); border: 2px solid var(--mystic-gold);
    padding: 15px 30px; border-radius: 50px; display: flex; gap: 30px; z-index: 99;
    box-shadow: 0 10px 30px rgba(0,0,0,0.5); backdrop-filter: blur(10px);
}
.nav-link-mystic { color: var(--mystic-teal); transition: 0.3s; position: relative; }
.nav-link-mystic.active { color: var(--mystic-gold); transform: scale(1.2); }
.nav-link-mystic.active::after {
    content: '✦'; position: absolute; top: -15px; left: 50%; transform: translateX(-50%); font-size: 10px; color: var(--mystic-gold);
}

/* === MUSIC BTN (Floating Orb) === */
.music-orb {
    position: fixed; bottom: 100px; right: 20px; width: 50px; height: 50px;
    background: radial-gradient(circle, var(--mystic-gold), var(--mystic-teal));
    border-radius: 50%; display: flex; align-items: center; justify-content: center;
    color: var(--mystic-dark); z-index: 90; cursor: pointer; border: 2px solid var(--mystic-gold);
    box-shadow: 0 0 20px var(--mystic-gold);
}
.music-orb.playing { animation: organic-sway 3s infinite alternate, magic-pulse 2s infinite; }
</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    activeSection: 'home',
    
    init() {
        document.body.style.overflow = 'hidden';
        // Setup Scroll Observer for Complex Reveals
        this.$watch('opened', (value) => { 
            if(value) {
                this.$nextTick(() => {
                    this.setupScrollSpy();
                    this.setupRevealObserver();
                });
            }
        });
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) this.activeSection = entry.target.id; });
        }, { threshold: 0.3 });
        sections.forEach(id => { const el = document.getElementById(id); if(el) observer.observe(el); });
    },
    // New: Observer for complex animations
    setupRevealObserver() {
        const revealElements = document.querySelectorAll('.reveal-element');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    revealObserver.unobserve(entry.target); // Animate once
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -50px 0px' });
        revealElements.forEach(el => revealObserver.observe(el));
    },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto';
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if(this.audioEl) {
                this.audioEl.volume = 0.6;
                this.audioEl.play().then(() => this.playing = true).catch(() => {});
            }
        });
    },
    toggleAudio() {
        if(!this.audioEl) return;
        if(this.playing) { this.audioEl.pause(); this.playing = false; }
        else { this.audioEl.play(); this.playing = true; }
    },
    scrollTo(id) {
        this.activeSection = id;
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    }
}">
    @if($invitation->background_music)
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ str_starts_with($invitation->background_music, 'http') ? $invitation->background_music : asset('storage/' . $invitation->background_music) }}" type="audio/mpeg">
    </audio>
    @endif

    {{-- FIREFLY PARTICLES (BACKGROUND) --}}
    <div class="fireflies-container">
        {{-- Generate random fireflies using foreach for robust Blade loop context --}}
        @foreach(range(0, 19) as $i)
            <div class="firefly" style="top: {{ rand(0, 100) }}%; left: {{ rand(0, 100) }}%; animation-delay: {{ rand(0, 5) }}s; animation-duration: {{ rand(10, 20) }}s;"></div>
        @endforeach
    </div>

    {{-- COVER --}}
    <div x-show="!opened" x-transition:leave="transition duration-[1.5s] ease-in-out" x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-110" class="cover">
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1518531933037-91b2f5f229cc?w=1200' }}');"></div>
        <div class="cover-overlay"></div>
        
        <div class="cover-content reveal-element is-visible">
            <p style="letter-spacing: 3px; text-transform: uppercase; margin-bottom: 20px; color: var(--mystic-gold);">A Mystical Union</p>
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <h1 style="font-size: 3rem; margin-bottom: 30px; text-shadow: 0 0 20px var(--mystic-gold);">
                @if($order === 'bride_first')
                    {{ $invitation->bride_nickname }} <span class="font-script">&</span> {{ $invitation->groom_nickname }}
                @else
                    {{ $invitation->groom_nickname }} <span class="font-script">&</span> {{ $invitation->bride_nickname }}
                @endif
            </h1>
            
            <div style="margin-bottom: 40px; border-top: 1px solid var(--mystic-gold); border-bottom: 1px solid var(--mystic-gold); padding: 20px;">
                <p>Dear,</p>
                <h3 style="font-size: 1.5rem; margin: 10px 0; color: var(--mystic-gold);">{{ $guestName }}</h3>
                <p>You are invited to our enchanted evening.</p>
            </div>
            <button @click="open()" class="btn-magic">Enter The Realm</button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main x-show="opened">
        
        {{-- HERO --}}
        <section id="home" class="hero-section" style="background-image: url('https://images.unsplash.com/photo-1448375240586-882707db888b?w=1200');"> <div style="position: absolute; inset:0; background: var(--mystic-overlay);"></div>
            
            <div class="hero-content-box reveal-element">
                <img src="{{ ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo) ? asset('storage/' . ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo)) : 'https://images.unsplash.com/photo-1589159637956-2a8729122449?w=400' }}" class="hero-couple-img">
                
                <h2 style="font-size: 2.5rem; color: var(--mystic-gold); margin-bottom: 10px;">
                    {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                    <span class="font-script" style="font-size: 3rem; display: block; margin: 10px 0;">&</span>
                    {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                </h2>
                
                <p style="letter-spacing: 3px; font-size: 1.2rem; margin-top: 30px;">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
                
                {{-- Simple Countdown --}}
                <div x-data="{
                    days:0, hours:0, target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
                    init() { setInterval(() => this.update(), 1000); },
                    update() { const diff = this.target - new Date(); if(diff>0){ this.days=Math.floor(diff/86400000); this.hours=Math.floor((diff%86400000)/3600000); }}
                }" style="display: flex; justify-content: center; gap: 20px; margin-top: 30px; color: var(--mystic-gold);">
                    <div><span style="font-size: 2rem; font-weight: bold;" x-text="days"></span><br>Days</div>
                    <div><span style="font-size: 2rem; font-weight: bold;" x-text="hours"></span><br>Hours to go</div>
                </div>
            </div>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="section" style="background-image: url('https://images.unsplash.com/photo-1505933719637-55364bb14d28?w=1200');">
             <div style="position: absolute; inset:0; background: rgba(7, 26, 24, 0.9);"></div>
             
            <div class="section-title reveal-element">
                <h2>The Enchanted Couple</h2>
                <div class="mystic-divider"></div>
            </div>

            <div style="display: flex; flex-direction: column; gap: 60px; max-width: 800px; margin: 0 auto;">
                {{-- Couple 1 --}}
                <div class="couple-card-mystic reveal-element delay-1">
                    <div class="couple-img-frame-mystic">
                        <img src="{{ ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo) ? asset('storage/' . ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo)) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400' }}" class="couple-img-mystic">
                    </div>
                    <h3 style="color: var(--mystic-gold); font-size: 2rem;">{{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}</h3>
                    <p style="opacity: 0.8;">Child of Mr. {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} & Mrs. {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}</p>
                </div>
                
                <div class="font-script text-center text-6xl text-mystic-gold reveal-element delay-2">&</div>

                {{-- Couple 2 --}}
                <div class="couple-card-mystic reveal-element delay-3">
                    <div class="couple-img-frame-mystic">
                        <img src="{{ ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo) ? asset('storage/' . ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo)) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-img-mystic">
                    </div>
                    <h3 style="color: var(--mystic-gold); font-size: 2rem;">{{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}</h3>
                    <p style="opacity: 0.8;">Child of Mr. {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} & Mrs. {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}</p>
                </div>
            </div>
        </section>

        {{-- EVENTS (Tree of Life Timeline) --}}
        <section id="events" class="section" style="background-image: url('https://images.unsplash.com/photo-1549524979-8375d53635d0?w=1200');"> <div style="position: absolute; inset:0; background: rgba(7, 26, 24, 0.85);"></div>

            <div class="section-title reveal-element">
                <h2>The Journey</h2>
                <div class="mystic-divider"></div>
            </div>

            <div class="timeline-mystic">
                {{-- Akad --}}
                <div class="event-node reveal-element delay-1" style="transform-origin: left center;">
                    <div class="event-marker"></div>
                    <div class="event-content-mystic">
                        <h3 style="color: var(--mystic-gold);">Sacred Union (Akad)</h3>
                        <p style="font-weight: bold; margin: 10px 0;">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }} <br> {{ $invitation->akad_date?->format('H:i') }} WIB</p>
                        <p style="opacity: 0.8; font-size: 0.9rem;">{{ $invitation->akad_venue }}</p>
                         <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn-magic" style="padding: 10px 20px; font-size: 0.8rem; margin-top: 15px; display: inline-block;">Open Map</a>
                    </div>
                </div>

                {{-- Resepsi --}}
                @if($invitation->resepsi_date)
                <div class="event-node reveal-element delay-2" style="transform-origin: right center;">
                    <div class="event-marker"></div>
                    <div class="event-content-mystic">
                         <h3 style="color: var(--mystic-gold);">The Royal Feast (Resepsi)</h3>
                         <p style="font-weight: bold; margin: 10px 0;">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }} <br> {{ $invitation->resepsi_date?->format('H:i') }} WIB</p>
                        <p style="opacity: 0.8; font-size: 0.9rem;">{{ $invitation->resepsi_venue }}</p>
                         <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn-magic" style="padding: 10px 20px; font-size: 0.8rem; margin-top: 15px; display: inline-block;">Open Map</a>
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section" style="background: var(--mystic-dark);">
            <div class="section-title reveal-element">
                <h2>Wedding Offerings</h2>
                <div class="mystic-divider"></div>
                <p style="margin-top: 20px; opacity: 0.8;">Your blessing is our greatest gift. If you wish to contribute, you may do so here.</p>
            </div>

            <div style="max-w-md mx-auto">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    {{-- Complex Reveal: Alternating slight rotations using loop iteration --}}
                    <div class="glass-card reveal-element delay-{{ $loop->iteration }}" style="margin-bottom: 20px; transform: rotate({{ $loop->odd ? '-2deg' : '2deg' }}); transition: transform 0.5s;" onmouseover="this.style.transform='rotate(0deg) scale(1.02)'" onmouseout="this.style.transform='rotate({{ $loop->odd ? '-2deg' : '2deg' }}) scale(1)'">
                        <div style="display: flex; align-items: center; gap: 20px; margin-bottom: 20px;">
                            <div style="font-size: 2rem; color: var(--mystic-gold);"><i class="fas fa-coins"></i></div>
                            <div>
                                <h3 style="color: var(--mystic-gold); margin-bottom: 0;">{{ $account['bank'] }}</h3>
                                <p style="font-family: 'Cinzel', serif; font-size: 1.3rem; letter-spacing: 2px;">{{ $account['account_number'] }}</p>
                                <p style="opacity: 0.7; font-size: 0.9rem;">a.n {{ $account['account_name'] }}</p>
                            </div>
                        </div>
                        <button class="btn-magic" style="width: 100%; padding: 10px;" x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)">
                            <span x-text="copied ? 'COPIED!' : 'COPY NUMBER'"></span>
                        </button>
                    </div>
                    @endforeach
                @endif
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section" style="background-image: url('https://images.unsplash.com/photo-1494913389576-9a34771f384a?w=1200');">
            <div style="position: absolute; inset:0; background: rgba(7, 26, 24, 0.9);"></div>

            <div class="section-title reveal-element">
                <h2>RSVP & Wishes</h2>
                <div class="mystic-divider"></div>
            </div>

            <div class="glass-card reveal-element delay-1" style="max-w-md mx-auto;">
                <form wire:submit="submitRSVP">
                    <input type="text" wire:model="rsvpName" placeholder="Your Full Name" class="form-input-mystic">
                    @error('rsvpName') <span style="color: red; font-size: 0.8rem; display: block; margin-bottom: 10px;">{{ $message }}</span> @enderror
                    
                    <textarea wire:model="rsvpMessage" rows="3" placeholder="Write a magical wish..." class="form-input-mystic"></textarea>
                    @error('rsvpMessage') <span style="color: red; font-size: 0.8rem; display: block; margin-bottom: 10px;">{{ $message }}</span> @enderror
                    
                    <div style="display: flex; gap: 20px; margin-bottom: 20px; justify-content: center;">
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" value="confirmed" wire:model="rsvpStatus" style="accent-color: var(--mystic-gold);"> Will Attend
                        </label>
                        <label style="display: flex; align-items: center; gap: 10px; cursor: pointer;">
                            <input type="radio" value="declined" wire:model="rsvpStatus" style="accent-color: var(--mystic-gold);"> Unable to Attend
                        </label>
                    </div>
                    @error('rsvpStatus') <span style="color: red; font-size: 0.8rem; display: block; margin-bottom: 10px; text-align: center;">{{ $message }}</span> @enderror

                    <div style="margin-bottom: 20px; text-align: center;" x-show="$wire.rsvpStatus === 'confirmed'">
                         <label style="display: block; margin-bottom: 5px;">Number of Guests</label>
                         <select wire:model="rsvpGuests" class="form-input-mystic" style="width: 50%; display: inline-block;">
                             <option value="1">1 Person</option>
                             <option value="2">2 People</option>
                         </select>
                    </div>

                    <button type="submit" class="btn-magic" style="width: 100%;">
                        <span wire:loading.remove>Send Confirmation</span>
                        <span wire:loading>Sending Magic...</span>
                    </button>

                    @if (session()->has('message'))
                        <div style="margin-top: 15px; padding: 10px; background: rgba(212, 175, 55, 0.2); border: 1px solid var(--mystic-gold); border-radius: 8px; text-align: center; color: var(--mystic-gold);">
                            {{ session('message') }}
                        </div>
                    @endif
                </form>
                
                {{-- Wishes List --}}
                <div style="margin-top: 40px; max-height: 300px; overflow-y: auto;">
                    <h3 style="text-align: center; color: var(--mystic-gold); margin-bottom: 20px;">Latest Wishes</h3>
                     @foreach($invitation->wishes()->latest()->get() as $wish)
                        <div style="background: rgba(0,0,0,0.2); padding: 15px; border-left: 3px solid var(--mystic-gold); margin-bottom: 10px; color: var(--mystic-cream);" class="reveal-element">
                            <div style="font-weight: bold; color: var(--mystic-gold);">
                                {{ $wish->name }} 
                                <span style="font-size: 0.7rem; opacity: 0.7; font-weight: normal; float: right;">{{ $wish->created_at->diffForHumans() }}</span>
                            </div>
                            <p style="font-size: 0.9rem; margin-top: 5px; font-style: italic;">"{{ $wish->message }}"</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <footer style="text-align: center; padding: 60px 20px 120px; background: var(--mystic-dark); color: var(--mystic-gold);">
            <h2 style="font-size: 2rem;">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p class="font-script" style="font-size: 1.5rem; margin-top: 10px;">Thank you for making our day magical.</p>
        </footer>

    </main>

    {{-- MUSIC ORB --}}
    <div class="music-orb" :class="{'playing': playing}" @click="toggleAudio()">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-4z"/></svg>
    </div>

    {{-- MYSTIC NAV --}}
    <nav x-show="opened" class="nav-mystic animate-fade-up">
        <a @click.prevent="scrollTo('home')" class="nav-link-mystic" :class="{'active': activeSection === 'home'}">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M10 20v-6h4v6h5v-8h3L12 3 2 12h3v8z"/></svg>
        </a>
        <a @click.prevent="scrollTo('couple')" class="nav-link-mystic" :class="{'active': activeSection === 'couple'}">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg>
        </a>
        <a @click.prevent="scrollTo('events')" class="nav-link-mystic" :class="{'active': activeSection === 'events'}">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M17 12h-5v5h5v-5zM16 1v2H8V1H6v2H5c-1.11 0-1.99.9-1.99 2L3 19c0 1.1.89 2 2 2h14c1.1 0 2-.9 2-2V5c0-1.1-.9-2-2-2h-1V1h-2zm3 18H5V8h14v11z"/></svg>
        </a>
        @if($invitation->enable_gift)
        <a @click.prevent="scrollTo('gift')" class="nav-link-mystic" :class="{'active': activeSection === 'gift'}">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 6h-2.18c.11-.31.18-.65.18-1 0-1.66-1.34-3-3-3-1.05 0-1.96.54-2.5 1.35l-.5.67-.5-.68C10.96 2.54 10.05 2 9 2 7.34 2 6 3.34 6 5c0 .35.07.69.18 1H4c-1.11 0-1.99.89-1.99 2L2 19c0 1.11.89 2 2 2h16c1.11 0 2-.89 2-2V8c0-1.11-.89-2-2-2zm-5-2c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zM9 4c.55 0 1 .45 1 1s-.45 1-1 1-1-.45-1-1 .45-1 1-1zm11 15H4v-2h16v2zm0-5H4V8h5.08L7 10.83 8.62 12 11 8.76l1-1.36 1 1.36L15.38 12 17 10.83 14.92 8H20v6z"/></svg>
        </a>
        @endif
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <a @click.prevent="scrollTo('rsvp')" class="nav-link-mystic" :class="{'active': activeSection === 'rsvp'}">
            <svg width="24" height="24" fill="currentColor" viewBox="0 0 24 24"><path d="M20 2H4c-1.1 0-1.99.9-1.99 2L2 22l4-4h14c1.1 0 2-.9 2-2V4c0-1.1-.9-2-2-2zm-2 12H6v-2h12v2zm0-3H6V9h12v2zm0-3H6V6h12v2z"/></svg>
        </a>
        @endif
    </nav>
</div>
