
@section('title', 'Pawiwahan ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
{{-- Font Balinese Vibe: Cinzel (Ukiran), Pinyon Script (Elegan), Noto Serif (Tegas) --}}
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Noto+Serif+Display:wght@300;400;600&family=Pinyon+Script&display=swap" rel="stylesheet">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
:root {
    --bali-gold: #D4AF37; /* Emas Prada */
    --bali-gold-dim: #B8952E;
    --bali-brown: #3E2723; /* Kayu Gelap */
    --bali-red: #8D6E63; /* Terracotta Halus */
    --bali-cream: #FAF7F2;
    --bali-texture: #F4EFE6;
    --text-main: #2C241B;
    --border-radius: 4px; /* Sudut lebih tajam khas Bali */
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
    -webkit-tap-highlight-color: transparent;
}

body { 
    font-family: 'Noto Serif Display', serif; 
    background-color: var(--bali-cream);
    /* Pattern Halus seperti tekstur kertas/kain */
    background-image: radial-gradient(#D4AF37 0.5px, transparent 0.5px), radial-gradient(#D4AF37 0.5px, var(--bali-cream) 0.5px);
    background-size: 20px 20px;
    background-position: 0 0, 10px 10px;
    color: var(--text-main); 
    overflow-x: hidden;
}

.font-bali-title { font-family: 'Cinzel Decorative', cursive; }
.font-script { font-family: 'Pinyon Script', cursive; }

/* === ANIMATIONS === */
@keyframes fadeUp { 
    from { opacity: 0; transform: translateY(40px); } 
    to { opacity: 1; transform: translateY(0); } 
}
@keyframes pulse-gold { 
    0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
    70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
    100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
}
@keyframes float { 
    0%, 100% { transform: translateY(0); } 
    50% { transform: translateY(-8px); } 
}

.animate-fade-up { animation: fadeUp 1s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
.animate-float { animation: float 4s ease-in-out infinite; }

/* === REVEAL ON SCROLL === */
.reveal-on-scroll {
    opacity: 0; /* Hidden initially, Animate.css will take over */
    --animate-duration: 1s; /* slightly faster for more snappy scroll */
}

/* === ORNAMENTS === */
/* Border Khas Bali (Double Border) */
.bali-border {
    border: 1px solid var(--bali-gold);
    outline: 1px solid var(--bali-gold);
    outline-offset: 4px;
    position: relative;
    margin: 6px;
}

/* === COVER SCREEN === */
.cover { 
    position: fixed; 
    inset: 0; 
    z-index: 100; 
    display: flex; 
    flex-direction: column;
    align-items: center; 
    justify-content: flex-end;
    padding: 24px;
    padding-bottom: max(24px, env(safe-area-inset-bottom));
    overflow-y: auto;
    background: #1a1a1a;
}
.cover-bg { 
    position: absolute; 
    inset: 0; 
    background-size: cover; 
    background-position: center;
    opacity: 0.6;
    filter: sepia(30%); /* Efek Klasik */
}
.cover-frame {
    position: absolute;
    inset: 15px;
    border: 1px solid rgba(212, 175, 55, 0.5);
    z-index: 1;
}
.cover-frame::before {
    content: ''; position: absolute; inset: 4px;
    border: 2px solid rgba(212, 175, 55, 0.8);
}

/* === SECTIONS === */
.section { 
    padding: 70px 20px; 
    position: relative; 
}
.section-title { 
    text-align: center; 
    margin-bottom: 45px; 
    position: relative;
}
.section-title h2 { 
    font-family: 'Cinzel Decorative', cursive; 
    font-size: 2rem; 
    color: var(--bali-brown); 
    font-weight: 700;
    letter-spacing: 1px;
}
.bali-divider { 
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 10px;
    margin-top: 10px;
}
.bali-divider::before, .bali-divider::after {
    content: '';
    width: 40px; height: 1px;
    background: var(--bali-gold);
}
.bali-divider span {
    font-size: 1.2rem;
    color: var(--bali-gold);
}

/* === CARDS === */
.card { 
    background: #fff; 
    border-radius: 2px; /* Less rounded for Bali style */
    box-shadow: 0 4px 15px rgba(62, 39, 35, 0.08); 
    overflow: hidden; 
    border: 1px solid rgba(212, 175, 55, 0.2);
    position: relative;
}
/* Corner Ornament CSS Only */
.card::after {
    content: '';
    position: absolute; top: 5px; right: 5px;
    width: 10px; height: 10px;
    border-top: 2px solid var(--bali-gold);
    border-right: 2px solid var(--bali-gold);
}
.card::before {
    content: '';
    position: absolute; bottom: 5px; left: 5px;
    width: 10px; height: 10px;
    border-bottom: 2px solid var(--bali-gold);
    border-left: 2px solid var(--bali-gold);
}

/* === BUTTONS === */
.btn { 
    display: inline-flex; 
    align-items: center; 
    justify-content: center;
    gap: 10px; 
    padding: 12px 30px; 
    border-radius: 0; /* Boxy */
    font-family: 'Cinzel Decorative', cursive;
    font-weight: 700; 
    font-size: 13px;
    letter-spacing: 1px;
    transition: all 0.4s ease; 
    cursor: pointer; 
    border: 1px solid var(--bali-gold);
    text-decoration: none;
    text-transform: uppercase;
    position: relative;
    z-index: 1;
}
.btn-gold { 
    background: var(--bali-brown); 
    color: var(--bali-gold); 
}
.btn-gold:hover {
    background: var(--bali-gold);
    color: var(--bali-brown);
    border-color: var(--bali-brown);
}
.btn-outline { 
    background: transparent; 
    color: var(--bali-brown);
    border-color: var(--bali-brown);
}

/* === COUNTDOWN === */
.countdown-grid { 
    display: grid; 
    grid-template-columns: repeat(4, 1fr); 
    gap: 10px; 
    max-width: 100%; 
    margin: 0 auto;
}
.countdown-item { 
    background: var(--bali-brown); 
    padding: 12px 5px; 
    border: 1px solid var(--bali-gold);
    text-align: center; 
    color: var(--bali-gold);
}
.countdown-num { 
    font-family: 'Cinzel Decorative', serif; 
    font-size: 1.5rem; 
    font-weight: 700; 
    line-height: 1.2;
}
.countdown-label { 
    font-size: 0.6rem; 
    color: #fff; 
    text-transform: uppercase; 
    letter-spacing: 1px; 
    margin-top: 5px;
    font-family: sans-serif;
}

/* === EVENT CARDS === */
.event-card { 
    background: white; 
    border: 1px solid var(--bali-gold);
    padding: 0; 
    margin-bottom: 20px; 
    position: relative;
}
.event-card-inner {
    padding: 30px 24px;
    text-align: center;
    border: 4px solid white; /* Inner spacing */
    background: radial-gradient(circle at center, #fff 0%, #FAF7F2 100%);
}
.event-icon {
    width: 40px; height: 40px; margin: 0 auto 15px;
    color: var(--bali-gold);
}
.event-title {
    font-family: 'Cinzel Decorative', serif;
    font-size: 1.4rem;
    color: var(--bali-brown);
    margin-bottom: 8px;
    font-weight: 700;
}
.event-date {
    color: var(--bali-red);
    font-weight: 600;
    font-size: 15px;
    margin-bottom: 15px;
    font-style: italic;
    font-family: 'Noto Serif Display', serif;
}
.event-info-item {
    font-size: 14px;
    line-height: 1.6;
    color: #555;
    margin-bottom: 8px;
}
.event-link {
    display: inline-block;
    margin-top: 15px;
    color: var(--bali-brown);
    font-size: 12px;
    font-weight: 700;
    text-transform: uppercase;
    text-decoration: underline;
    text-decoration-color: var(--bali-gold);
    text-underline-offset: 4px;
}

/* === COUPLE SECTION === */
.couple-card { 
    text-align: center; 
    padding: 30px 20px;
    background: transparent;
    box-shadow: none;
    border: none;
}
.couple-card::after, .couple-card::before { display: none; }

.couple-photo-frame {
    width: 160px; height: 160px;
    margin: 0 auto 20px;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: center;
}
/* Bingkai ala Bali */
.couple-photo-frame::before {
    content: ''; position: absolute; inset: 0;
    border: 2px solid var(--bali-gold);
    transform: rotate(45deg);
}
.couple-photo { 
    width: 150px; height: 150px; 
    object-fit: cover; 
    border: 3px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    position: relative; z-index: 2;
    /* Shape Wajik (Diamond) jika didukung, kalau tidak circle biasa */
    clip-path: polygon(50% 0%, 100% 50%, 50% 100%, 0% 50%); 
}
.couple-name { 
    font-family: 'Cinzel Decorative', serif; 
    font-size: 1.6rem; 
    color: var(--bali-brown); 
    margin-bottom: 8px;
    font-weight: 700;
}

/* === GIFT SECTION === */
.gift-card { 
    background: #fff; 
    padding: 20px;
    border: 1px solid rgba(0,0,0,0.1);
    position: relative;
    border-left: 4px solid var(--bali-gold);
}
.copy-btn { 
    width: 100%;
    background: transparent; 
    border: 1px solid var(--bali-brown); 
    color: var(--bali-brown); 
    padding: 10px; 
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    text-transform: uppercase;
    transition: 0.3s;
}
.copy-btn:hover {
    background: var(--bali-brown);
    color: var(--bali-gold);
}
.copy-btn.copied {
    background: var(--bali-gold);
    color: white;
    border-color: var(--bali-gold);
}

/* === MUSIC BUTTON === */
.music-btn { 
    position: fixed; 
    bottom: 90px; /* Above nav */
    right: 20px; 
    width: 44px; height: 44px; 
    border-radius: 50%; 
    background: var(--bali-brown); 
    color: var(--bali-gold); 
    border: 2px solid var(--bali-gold);
    display: flex; align-items: center; justify-content: center; 
    z-index: 95; 
    animation: pulse-gold 2s infinite;
}

/* === FORM ELEMENTS === */
.form-input { 
    width: 100%; 
    padding: 12px; 
    border: 1px solid #ccc; 
    border-bottom: 2px solid var(--bali-gold); /* Underline style */
    background: rgba(255,255,255,0.8); 
    font-family: 'Noto Serif Display', serif;
    margin-bottom: 15px;
    border-radius: 2px;
}
.form-input:focus { 
    outline: none; 
    border-color: var(--bali-brown);
    background: #fff;
}

/* === BOTTOM NAV === */
.bottom-nav { 
    position: fixed; bottom: 0; left: 0; right: 0; 
    background: var(--bali-brown); 
    border-top: 3px solid var(--bali-gold);
    z-index: 90; 
    padding-bottom: env(safe-area-inset-bottom);
}
.nav-items { display: flex; justify-content: space-around; }
.nav-item { 
    padding: 12px; color: rgba(255,255,255,0.6); 
    text-decoration: none; display: flex; flex-direction: column; align-items: center; 
    font-size: 10px; text-transform: uppercase; letter-spacing: 1px;
}
.nav-item.active { color: var(--bali-gold); }
.nav-item svg { width: 20px; height: 20px; margin-bottom: 4px; }

/* === HERO SPECIFIC === */
.hero-section {
    min-height: 100vh;
    display: flex; flex-direction: column; 
    align-items: center; justify-content: center;
    position: relative;
    text-align: center;
    color: var(--bali-cream);
    padding: 40px 20px;
}
.hero-bg {
    position: absolute; inset: 0;
    background: #2C241B; /* Fallback */
    z-index: -2;
}
/* Overlay Pattern Batik/Ukiran */
.hero-bg::after {
    content: ''; position: absolute; inset: 0;
    background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
    z-index: -1;
}
.hero-frame {
    border: 2px solid var(--bali-gold);
    padding: 30px 20px;
    position: relative;
    max-width: 400px;
    width: 100%;
}
/* Sudut Frame */
.hero-frame::before, .hero-frame::after {
    content: '❖'; /* Simbol wajik */
    position: absolute; left: 50%; transform: translateX(-50%);
    color: var(--bali-gold); font-size: 24px;
    background: #2C241B; padding: 0 10px;
}
.hero-frame::before { top: -14px; }
.hero-frame::after { bottom: -14px; }

.hero-names-text {
    font-family: 'Cinzel Decorative', cursive;
    font-size: 2.5rem;
    color: var(--bali-gold);
    line-height: 1.3;
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}
.hero-and {
    font-family: 'Pinyon Script', cursive;
    font-size: 2rem; color: #fff; margin: 10px 0;
}

/* === TEDUNG ANIMATION === */
.tedung-wrapper {
    position: absolute;
    bottom: 0px; 
    z-index: 5;
    transform-origin: bottom center;
    pointer-events: none; /* Prevent blocking clicks */
}
.tedung-left {
    left: -20px;
    animation: swayTedung 5s ease-in-out infinite alternate;
}
.tedung-right {
    right: -20px;
    animation: swayTedung 5s ease-in-out infinite alternate-reverse;
}

@keyframes swayTedung {
    0% { transform: rotate(-3deg); }
    100% { transform: rotate(3deg); }
}

.tedung-svg {
    width: 140px;
    height: auto;
    filter: drop-shadow(4px 4px 10px rgba(0,0,0,0.6));
}
@media (min-width: 640px) {
    .tedung-svg { width: 180px; }
    .tedung-left { left: 20px; }
    .tedung-right { right: 20px; }
}

/* === BACKGROUND ANIMATIONS (JEPUN & FIREFLIES) === */
.bg-animation-container {
    position: absolute;
    inset: 0;
    overflow: hidden;
    pointer-events: none;
    z-index: 1;
}

/* Bunga Jepun (Frangipani) Falling */
.bunga-jepun {
    position: absolute;
    width: 24px; height: 24px;
    background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M50 15 C 65 25, 75 40, 85 45 C 70 55, 60 70, 50 85 C 40 70, 30 55, 15 45 C 25 40, 35 25, 50 15 Z' fill='%23FDFBF7' stroke='%23D4AF37' stroke-width='3' stroke-linejoin='round'/%3E%3Ccircle cx='50' cy='50' r='12' fill='%23D4AF37'/%3E%3C/svg%3E");
    background-size: contain;
    background-repeat: no-repeat;
    animation: fallJepun 15s linear infinite;
    opacity: 0;
}
@keyframes fallJepun {
    0% { transform: translate(0, -50px) rotate(0deg); opacity: 0; }
    10% { opacity: 0.9; }
    90% { opacity: 0.9; }
    100% { transform: translate(120px, 100vh) rotate(360deg); opacity: 0; }
}
.bunga-jepun:nth-child(1) { left: 10%; animation-delay: 0s; width: 28px; height: 28px; animation-duration: 16s; }
.bunga-jepun:nth-child(2) { left: 30%; animation-delay: -4s; width: 20px; height: 20px; animation-duration: 19s; }
.bunga-jepun:nth-child(3) { left: 55%; animation-delay: -7s; width: 32px; height: 32px; animation-duration: 22s; }
.bunga-jepun:nth-child(4) { left: 75%; animation-delay: -2s; width: 22px; height: 22px; animation-duration: 15s; }
.bunga-jepun:nth-child(5) { left: 90%; animation-delay: -9s; width: 26px; height: 26px; animation-duration: 18s; }

/* Glowing Fireflies / Gold Dust */
.firefly {
    position: absolute;
    width: 6px; height: 6px;
    background: #D4AF37;
    border-radius: 50%;
    box-shadow: 0 0 10px 2px rgba(212,175,55,0.8);
    animation: floatFirefly 8s ease-in-out infinite alternate;
    opacity: 0;
}
@keyframes floatFirefly {
    0% { transform: translateY(0) scale(1); opacity: 0; }
    50% { opacity: 0.8; transform: translateY(-30px) scale(1.5); }
    100% { transform: translateY(-60px) scale(0.8); opacity: 0; }
}
.firefly:nth-child(6) { left: 20%; top: 80%; animation-delay: 0s; animation-duration: 9s; }
.firefly:nth-child(7) { left: 40%; top: 60%; animation-delay: -2s; animation-duration: 7s; }
.firefly:nth-child(8) { left: 60%; top: 90%; animation-delay: -4s; animation-duration: 11s; }
.firefly:nth-child(9) { left: 80%; top: 70%; animation-delay: -1s; animation-duration: 8s; }
.firefly:nth-child(10) { left: 15%; top: 40%; animation-delay: -3s; animation-duration: 6s; }

</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    activeSection: 'home',
    
    init() {
        document.body.style.overflow = 'hidden';
        
        this.$watch('opened', (value) => {
            if (value) {
                this.$nextTick(() => this.setupScrollSpy());
            }
        });
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.activeSection = entry.target.id;
                }
            });
        }, { threshold: 0.3, rootMargin: '-20% 0px -60% 0px' });
        
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });

        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.remove('animate__fadeOutDown');
                    entry.target.classList.add('animate__animated', 'animate__fadeInUp');
                } else {
                    entry.target.classList.remove('animate__fadeInUp');
                    entry.target.classList.add('animate__animated', 'animate__fadeOutDown');
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -5% 0px' });
        
        document.querySelectorAll('.reveal-on-scroll').forEach(el => revealObserver.observe(el));
    },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto';
        
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if (this.audioEl) {
                this.audioEl.volume = 0.7;
                this.audioEl.play()
                    .then(() => { this.playing = true; })
                    .catch(e => { console.log('Play failed:', e); });
            }
        });
    },
    toggleAudio() {
        if (!this.audioEl) {
            this.audioEl = document.getElementById('bgMusic');
        }
        if (!this.audioEl) return;
        
        if (this.playing) { 
            this.audioEl.pause(); 
            this.playing = false;
        } else { 
            this.audioEl.play()
                .then(() => { this.playing = true; })
                .catch(() => { this.playing = false; });
        }
    },
    scrollTo(id) {
        this.activeSection = id;
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }
}">
    @if($invitation->background_music)
    @php
        $musicSrc = str_starts_with($invitation->background_music, 'http') 
            ? $invitation->background_music 
            : img_url($invitation->background_music);
    @endphp
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ $musicSrc }}" type="audio/mpeg">
    </audio>
    @endif

    @php
        $tedungSvg = '
        <svg viewBox="0 0 120 220" class="tedung-svg" xmlns="http://www.w3.org/2000/svg">
            <!-- Tiang/Pole -->
            <rect x="58" y="70" width="4" height="150" fill="#2C1A0F"/>
            <!-- Gold bands -->
            <rect x="56" y="100" width="8" height="6" fill="#D4AF37"/>
            <rect x="56" y="140" width="8" height="6" fill="#D4AF37"/>
            <rect x="56" y="180" width="8" height="6" fill="#D4AF37"/>
            <!-- Payung Bawah -->
            <path d="M 10 90 Q 60 -10 110 90 Z" fill="#FDFBF7"/>
            <path d="M 60 20 L 30 90 M 60 20 L 45 90 M 60 20 L 75 90 M 60 20 L 90 90" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M 10 90 L 110 90 L 105 105 L 15 105 Z" fill="#D4AF37" />
            <path d="M 15 105 Q 25 115 35 105 Q 45 115 55 105 Q 65 115 75 105 Q 85 115 95 105 Q 105 115 105 105" fill="none" stroke="#B8860B" stroke-width="2.5"/>
            <!-- Tassels -->
            <rect x="25" y="105" width="1.5" height="15" fill="#B8860B"/>
            <rect x="45" y="105" width="1.5" height="15" fill="#B8860B"/>
            <rect x="75" y="105" width="1.5" height="15" fill="#B8860B"/>
            <rect x="95" y="105" width="1.5" height="15" fill="#B8860B"/>
            <!-- Payung Atas -->
            <path d="M 25 55 Q 60 -10 95 55 Z" fill="#FDFBF7"/>
            <path d="M 60 5 L 40 55 M 60 5 L 50 55 M 60 5 L 70 55 M 60 5 L 80 55" stroke="#D4AF37" stroke-width="1.5" stroke-linecap="round"/>
            <path d="M 25 55 L 95 55 L 90 65 L 30 65 Z" fill="#D4AF37" />
            <path d="M 30 65 Q 40 75 50 65 Q 60 75 70 65 Q 80 75 90 65" fill="none" stroke="#B8860B" stroke-width="2"/>
            <!-- Finial -->
            <path d="M 55 20 L 60 0 L 65 20 Z" fill="#D4AF37"/>
            <circle cx="60" cy="0" r="5" fill="#D4AF37"/>
            <circle cx="60" cy="-6" r="3" fill="#B8860B"/>
            <rect x="59" y="-15" width="2" height="12" fill="#D4AF37"/>
        </svg>
        ';
    @endphp

    {{-- COVER (Gate Candi Bentar Style) --}}
    <div x-show="!opened" x-transition:leave="transition duration-1000" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="cover overflow-hidden">
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>
        <div class="cover-frame"></div> {{-- Decorative border --}}
        
        {{-- Background Animations (Jepun & Fireflies) --}}
        <div class="bg-animation-container">
            <div class="bunga-jepun"></div><div class="bunga-jepun"></div>
            <div class="bunga-jepun"></div><div class="bunga-jepun"></div><div class="bunga-jepun"></div>
            <div class="firefly"></div><div class="firefly"></div><div class="firefly"></div>
            <div class="firefly"></div><div class="firefly"></div>
        </div>

        {{-- Tedung Animation --}}
        <div class="tedung-wrapper tedung-left">{!! $tedungSvg !!}</div>
        <div class="tedung-wrapper tedung-right">{!! $tedungSvg !!}</div>
        
        {{-- Top Section: Names --}}
        <div class="relative z-10 text-center text-white w-full max-w-sm px-6 flex-1 flex flex-col items-center justify-center">
            <p style="font-size: 12px; letter-spacing: 3px; text-transform: uppercase; color: var(--bali-gold); margin-bottom: 20px;">Pawiwahan</p>
            
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <div class="font-bali-title" style="font-size: 2.2rem; line-height: 1.2; text-shadow: 0 2px 4px black;">
                @if($order === 'bride_first')
                    {{ $invitation->bride_nickname }} <br> <span class="font-script text-white text-3xl">&</span> <br> {{ $invitation->groom_nickname }}
                @else
                    {{ $invitation->groom_nickname }} <br> <span class="font-script text-white text-3xl">&</span> <br> {{ $invitation->bride_nickname }}
                @endif
            </div>
        </div>
        
        {{-- Bottom Section: Guest + Button --}}
        <div class="relative z-10 text-center text-white w-full max-w-sm px-6" style="flex-shrink: 0;">
            <div style="background: rgba(0,0,0,0.6); padding: 20px; border: 1px solid var(--bali-gold);">
                <p style="font-size: 11px; letter-spacing: 1px; opacity: 0.9; margin-bottom: 5px;">Kepada Yth.</p>
                <p class="font-serif" style="font-size: 1.3rem; color: var(--bali-gold); margin-bottom: 15px;">{{ $guestName }}</p>
                
                <button @click="open()" class="btn btn-gold animate-fade-up" style="width: 100%;">
                    Buka Undangan
                </button>
            </div>
        </div>
    </div>

    {{-- MAIN --}}
    <main x-show="opened" x-transition>
        
        {{-- HERO --}}
        <section id="home" class="hero-section overflow-hidden" x-data="{
            days: 0, hours: 0, minutes: 0, seconds: 0,
            isActive: true,
            target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
            init() { 
                this.update(); 
                setInterval(() => this.update(), 1000); 
            },
            update() {
                const diff = this.target - new Date();
                if (diff > 0) {
                    this.isActive = true;
                    this.days = Math.floor(diff / 86400000);
                    this.hours = Math.floor((diff % 86400000) / 3600000);
                    this.minutes = Math.floor((diff % 3600000) / 60000);
                    this.seconds = Math.floor((diff % 60000) / 1000);
                } else {
                    this.isActive = false;
                }
            },
            saveToCalendar() {
                const title = 'Wedding of {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}';
                const date = '{{ $invitation->akad_date?->format('Ymd\THis') }}';
                const location = '{{ $invitation->akad_venue }}';
                const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${date}/${date}&location=${encodeURIComponent(location)}`;
                window.open(url, '_blank');
            }
        }">
            {{-- Background with Overlay --}}
            <div class="hero-bg"></div>
            <div style="position: absolute; inset:0; background: linear-gradient(to top, #2C241B, transparent 80%); z-index: -1;"></div>
            
            {{-- Background Animations (Jepun & Fireflies) --}}
            <div class="bg-animation-container">
                <div class="bunga-jepun"></div><div class="bunga-jepun"></div>
                <div class="bunga-jepun"></div><div class="bunga-jepun"></div><div class="bunga-jepun"></div>
                <div class="firefly"></div><div class="firefly"></div><div class="firefly"></div>
                <div class="firefly"></div><div class="firefly"></div>
            </div>

            {{-- Tedung Animation --}}
            <div class="tedung-wrapper tedung-left">{!! $tedungSvg !!}</div>
            <div class="tedung-wrapper tedung-right">{!! $tedungSvg !!}</div>

            <div class="hero-frame">
                {{-- Names --}}
                <div class="hero-names-text">
                    {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                    <div class="hero-and">&</div>
                    {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                </div>
                
                {{-- Date --}}
                <div style="margin: 30px 0; border-top: 1px solid rgba(212,175,55,0.3); border-bottom: 1px solid rgba(212,175,55,0.3); padding: 10px 0;">
                    <p style="color: var(--bali-gold); font-size: 1.1rem; letter-spacing: 2px;">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                </div>

                {{-- Countdown --}}
                <div x-show="isActive" class="countdown-grid" style="gap: 5px;">
                    <div class="countdown-item"><div class="countdown-num" x-text="days">0</div><div class="countdown-label">Hari</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="hours">0</div><div class="countdown-label">Jam</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="minutes">0</div><div class="countdown-label">Menit</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="seconds">0</div><div class="countdown-label">Detik</div></div>
                </div>
                
                <button @click="saveToCalendar()" class="btn btn-outline" style="margin-top: 30px; border-color: var(--bali-gold); color: var(--bali-gold);">
                    Save The Date
                </button>
            </div>
        </section>

        {{-- INTRO --}}
        <section class="section">
            <div class="max-w-lg mx-auto text-center reveal-on-scroll">
                <p class="font-bali-title text-xl text-bali-brown mb-4">Om Swastiastu</p>
                <p class="text-sm leading-loose text-gray-700">
                    Atas Asung Kertha Wara Nugraha Ida Sang Hyang Widhi Wasa/Tuhan Yang Maha Esa, kami bermaksud mengundang Bapak/Ibu/Saudara/i pada Upacara Pawiwahan (Pernikahan) putra-putri kami.
                </p>
            </div>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="section" style="background: white;">
            <div class="section-title reveal-on-scroll">
                <h2>Mempelai</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>
            
            <div class="max-w-lg mx-auto">
                @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                
                {{-- First Person --}}
                <div class="couple-card reveal-on-scroll">
                    <div class="couple-photo-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo) ? img_url(($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo)) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="couple-photo" loading="lazy">
                    </div>
                    <h3 class="couple-name">{{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}</h3>
                    <p class="text-gray-600 text-sm italic">Putra/Putri dari: <br> {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} & {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}</p>
                </div>

                <div class="text-center my-4 font-script text-4xl text-bali-gold reveal-on-scroll">&</div>

                {{-- Second Person --}}
                <div class="couple-card reveal-on-scroll">
                    <div class="couple-photo-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo) ? img_url(($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo)) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-photo" loading="lazy">
                    </div>
                    <h3 class="couple-name">{{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}</h3>
                    <p class="text-gray-600 text-sm italic">Putra/Putri dari: <br> {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} & {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}</p>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section">
            <div class="section-title reveal-on-scroll">
                <h2>Ringkasan Acara</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>
            
            <div class="max-w-lg mx-auto">
                {{-- Akad/Pawiwahan --}}
                <div class="event-card reveal-on-scroll">
                    <div class="event-card-inner">
                        <h3 class="event-title">Pawiwahan</h3>
                        <p class="event-date">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                        
                        <div style="border-top: 1px dashed var(--bali-gold); border-bottom: 1px dashed var(--bali-gold); padding: 15px 0; margin: 15px 0;">
                            <div class="event-info-item">
                                <strong>Pukul</strong><br>
                                {{ $invitation->akad_date?->format('H:i') }} WITA - Selesai
                            </div>
                            <div class="event-info-item">
                                <strong>Bertempat di</strong><br>
                                {{ $invitation->akad_venue }}<br>
                                <span style="font-size: 12px; color: #777;">{{ $invitation->akad_address }}</span>
                            </div>
                        </div>

                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="event-link">Google Maps</a>
                    </div>
                </div>

                {{-- Resepsi --}}
                @if($invitation->resepsi_date)
                <div class="event-card reveal-on-scroll" style="transition-delay: 0.2s;">
                    <div class="event-card-inner">
                        <h3 class="event-title">Resepsi</h3>
                        <p class="event-date">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</p>
                        
                        <div style="border-top: 1px dashed var(--bali-gold); border-bottom: 1px dashed var(--bali-gold); padding: 15px 0; margin: 15px 0;">
                            <div class="event-info-item">
                                <strong>Pukul</strong><br>
                                {{ $invitation->resepsi_date?->format('H:i') }} WITA - Selesai
                            </div>
                            <div class="event-info-item">
                                <strong>Bertempat di</strong><br>
                                {{ $invitation->resepsi_venue }}
                            </div>
                        </div>

                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="event-link">Google Maps</a>
                    </div>
                </div>
                @endif
            </div>
        </section>

        {{-- GALLERY (Same Logic, Different Grid Style) --}}
        @if($invitation->enable_gallery == 1)
        <section id="gallery" class="section bg-white">
            <div class="section-title reveal-on-scroll">
                <h2>Galeri Foto</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>
            {{-- Logic Galeri Sama Persis --}}
            <div class="max-w-lg mx-auto reveal-on-scroll" style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 15px;">
                @foreach($invitation->photos as $photo)
                <div style="aspect-ratio: 1; border: 4px solid var(--bali-cream); box-shadow: 0 4px 10px rgba(0,0,0,0.1); cursor: pointer;" 
                     @click="openLightbox('{{ $photo->url }}', '{{ $photo->caption ?? '' }}')">
                    <img src="{{ $photo->url }}" style="width: 100%; height: 100%; object-fit: cover;">
                </div>
                @endforeach
            </div>
            {{-- Lightbox Code (Hidden) --}}
            <div x-show="lightboxOpen" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/95" style="display: none;" @click.self="closeLightbox()">
                <button @click="closeLightbox()" class="absolute top-5 right-5 text-white text-3xl">&times;</button>
                <img :src="lightboxImage" class="max-w-full max-h-[90vh] border-4 border-bali-gold">
            </div>
        </section>
        @endif

        {{-- LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section id="lovestory" class="section" style="background: white;">
            <div class="section-title reveal-on-scroll">
                <h2>Kisah Cinta</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>

            <div class="max-w-lg mx-auto" style="position: relative; padding: 0 16px;">
                <div style="position: absolute; left: 31px; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, transparent, var(--bali-gold), var(--bali-gold), transparent);"></div>

                @foreach($invitation->love_story as $index => $story)
                <div class="animate-fade-up" style="display: flex; align-items: flex-start; margin-bottom: {{ $loop->last ? '0' : '28px' }}; position: relative; opacity: 0; animation-delay: {{ $index * 0.15 }}s;">
                    <div style="flex-shrink: 0; width: 32px; display: flex; justify-content: center; position: relative; z-index: 2; padding-top: 20px;">
                        <div style="width: 14px; height: 14px; background: var(--bali-cream); border: 3px solid var(--bali-gold); border-radius: 50%; box-shadow: 0 0 0 4px rgba(212,175,55,0.15);"></div>
                    </div>
                    <div class="card" style="flex: 1; margin-left: 12px; padding: 20px; border-left: 3px solid var(--bali-gold);">
                        <div style="display: inline-block; padding: 4px 14px; background: var(--bali-brown); color: var(--bali-gold); border-radius: 2px; font-size: 11px; font-weight: 600; letter-spacing: 1px; margin-bottom: 10px; font-family: 'Cinzel Decorative', cursive;">{{ $story['date'] ?? '' }}</div>
                        <h3 style="font-family: 'Cinzel Decorative', cursive; font-size: 1.1rem; color: var(--bali-brown); font-weight: 700; margin-bottom: 6px; line-height: 1.3;">{{ $story['title'] ?? '' }}</h3>
                        <p style="font-size: 13px; line-height: 1.75; color: var(--text-main);">{{ $story['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section" style="background-color: var(--bali-brown); color: var(--bali-cream);">
            <div class="section-title reveal-on-scroll">
                <h2 style="color: var(--bali-gold);">Punia / Gift</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>
            <div class="max-w-md mx-auto text-center reveal-on-scroll">
                <p style="margin-bottom: 30px; opacity: 0.8; font-size: 14px;">Tanpa mengurangi rasa hormat, bagi keluarga dan sahabat yang ingin memberikan tanda kasih, dapat melalui:</p>
                
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="gift-card reveal-on-scroll" style="background: #2C241B; border-color: var(--bali-gold); text-align: left; margin-bottom: 15px;">
                        <div style="display: flex; align-items: center; gap: 15px; margin-bottom: 15px;">
                            <div style="font-size: 24px; color: var(--bali-gold);"><i class="fas fa-credit-card"></i></div> {{-- FontAwesome assumed or SVG --}}
                            <div>
                                <p style="font-weight: bold; color: var(--bali-gold); font-size: 1.1rem;">{{ $account['bank'] }}</p>
                                <p style="font-family: monospace; font-size: 1.2rem; letter-spacing: 1px;">{{ $account['account_number'] }}</p>
                                <p style="font-size: 0.9rem; opacity: 0.7;">a.n {{ $account['account_name'] }}</p>
                            </div>
                        </div>
                        <button class="copy-btn" x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)" :class="{'copied': copied}">
                            <span x-text="copied ? 'TERSALIN' : 'SALIN NO. REKENING'"></span>
                        </button>
                    </div>
                    @endforeach
                @elseif($invitation->bank_name)
                    {{-- Single Bank Logic Same as above but utilizing bank_name vars --}}
                    <div class="gift-card reveal-on-scroll" style="background: #2C241B; border-color: var(--bali-gold); text-align: left;">
                        <p style="font-weight: bold; color: var(--bali-gold);">{{ $invitation->bank_name }}</p>
                        <p style="font-family: monospace; font-size: 1.2rem;">{{ $invitation->bank_account }}</p>
                        <p style="opacity: 0.7; margin-bottom: 15px;">a.n {{ $invitation->bank_holder }}</p>
                        <button class="copy-btn" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}')">SALIN</button>
                    </div>
                @endif
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES (Logic tetap, Style form diubah) --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section"
            x-data="{
                invitationId: {{ $invitation->id }},
                name: '{{ request('kpd', '') }}',
                message: '',
                status: 'confirmed',
                pax: 1,
                loading: false,
                success: false,
                error: '',
                wishes: [],
                stats: { total_wishes: 0, total_confirmed: 0 },
                
                async submitForm() {
                    if (!this.name.trim() || !this.message.trim()) {
                        this.error = 'Mohon lengkapi nama dan ucapan Anda.';
                        return;
                    }
                    this.loading = true;
                    this.error = '';
                    try {
                        await fetch(`/api/invitations/${this.invitationId}/rsvp`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                            body: JSON.stringify({ name: this.name, status: this.status, pax: this.pax })
                        });
                        const wishRes = await fetch(`/api/invitations/${this.invitationId}/wishes`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                            body: JSON.stringify({ name: this.name, message: this.message })
                        });
                        if (wishRes.ok) {
                            const data = await wishRes.json();
                            this.wishes.unshift(data.wish);
                            this.stats.total_wishes++;
                            if (this.status === 'confirmed') this.stats.total_confirmed += parseInt(this.pax);
                            this.message = '';
                            this.success = true;
                            setTimeout(() => this.success = false, 5000);
                        }
                    } catch (e) { this.error = 'Gagal mengirim. Periksa koneksi internet.'; }
                    finally { this.loading = false; }
                },
                async loadWishes() {
                    try {
                        const res = await fetch(`/api/invitations/${this.invitationId}/wishes`);
                        const data = await res.json();
                        this.wishes = data.wishes || [];
                    } catch (e) {}
                },
                async loadStats() {
                    try {
                        const res = await fetch(`/api/invitations/${this.invitationId}/stats`);
                        this.stats = await res.json();
                    } catch (e) {}
                },
                init() { this.loadWishes(); this.loadStats(); }
            }">
            <div class="section-title reveal-on-scroll">
                <h2>Ucapan & Doa</h2>
                <div class="bali-divider"><span>❖</span></div>
            </div>
            
            <div class="max-w-md mx-auto">
                <div class="reveal-on-scroll" style="background: white; padding: 30px; border: 1px solid var(--bali-gold); box-shadow: 5px 5px 0 rgba(212,175,55,0.1);">
                    {{-- Success --}}
                    <div x-show="success" x-transition style="margin-bottom: 15px; padding: 10px; background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; border-radius: 8px; text-align: center; color: #155724;">
                        ✓ Terima kasih! Ucapan dan konfirmasi Anda telah tersimpan.
                    </div>
                    {{-- Error --}}
                    <div x-show="error" x-transition style="margin-bottom: 15px; padding: 10px; background: rgba(220, 53, 69, 0.2); border: 1px solid #dc3545; border-radius: 8px; text-align: center; color: #721c24;" x-text="error"></div>

                   <form @submit.prevent="submitForm" style="text-align: left;">
                       <div style="margin-bottom: 15px;">
                           <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Nama</label>
                           <input type="text" x-model="name" class="form-input" placeholder="Nama Anda" required>
                       </div>

                       <div style="margin-bottom: 15px;">
                           <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Konfirmasi Kehadiran</label>
                           <div class="flex gap-2">
                               <button type="button" @click="status = 'confirmed'"
                                   :style="status === 'confirmed' ? 'background: var(--bali-gold); color: var(--bali-brown); border-color: var(--bali-gold);' : 'background: white; color: var(--bali-brown); border-color: #ddd;'"
                                   class="flex-1 py-3 px-2 border-2 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center gap-2 text-[0.9rem] font-semibold">
                                   <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                   <span>Hadir</span>
                               </button>
                               <button type="button" @click="status = 'declined'"
                                   :style="status === 'declined' ? 'background: #dc3545; color: white; border-color: #dc3545;' : 'background: white; color: var(--bali-brown); border-color: #ddd;'"
                                   class="flex-1 py-3 px-2 border-2 rounded-lg cursor-pointer transition-all duration-300 flex flex-col items-center justify-center gap-2 text-[0.9rem] font-semibold leading-tight text-center">
                                   <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                   <span>Tidak Hadir</span>
                               </button>
                           </div>
                       </div>

                       <div style="margin-bottom: 15px;" x-show="status === 'confirmed'" x-transition>
                           <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Jumlah Tamu</label>
                           <select x-model="pax" class="form-input">
                               <option value="1">1 Orang</option>
                               <option value="2">2 Orang</option>
                               <option value="3">3 Orang</option>
                               <option value="4">4 Orang</option>
                               <option value="5">5 Orang</option>
                           </select>
                       </div>

                       <div style="margin-bottom: 20px;">
                           <label style="display: block; margin-bottom: 5px; font-size: 0.9rem;">Ucapan & Doa</label>
                           <textarea x-model="message" rows="3" class="form-input" placeholder="Tulis ucapan selamat..."></textarea>
                       </div>

                       <button type="submit" :disabled="loading" class="btn btn-gold" style="width: 100%;">
                           <span x-show="!loading">Kirim Ucapan</span>
                           <span x-show="loading">Mengirim...</span>
                       </button>
                   </form>
                </div>
                
                {{-- Wishes List --}}
                <div class="reveal-on-scroll" style="margin-top: 40px;">
                     <div class="text-center mb-4 font-bali-title text-bali-brown">Doa Restu</div>
                     
                     <div style="text-align: left; max-height: 400px; overflow-y: auto; padding-right: 5px;">
                         <template x-for="wish in wishes" :key="wish.id">
                             <div style="background: #fff; padding: 15px; border-bottom: 1px solid #eee; margin-bottom: 10px; border-left: 3px solid var(--bali-gold);">
                                 <div style="font-weight: bold; color: var(--bali-brown);">
                                     <span x-text="wish.name"></span>
                                     <span style="font-size: 0.7rem; color: #aaa; font-weight: normal; float: right;" x-text="wish.time"></span>
                                 </div>
                                 <p style="font-size: 13px; color: #555; margin: 8px 0;" x-text="'- ' + wish.message + ' -'"></p>
                                 <template x-if="wish.attendance_status">
                                     <span>
                                         <template x-if="wish.attendance_status === 'confirmed'">
                                             <span style="display: inline-flex; align-items: center; gap: 4px; background: #DEF7EC; color: #03543F; font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 10px;">
                                                 <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                 Akan Hadir
                                             </span>
                                         </template>
                                         <template x-if="wish.attendance_status === 'declined'">
                                             <span style="display: inline-flex; align-items: center; gap: 4px; background: #FDE8E8; color: #9B1C1C; font-size: 0.7rem; font-weight: 600; padding: 3px 10px; border-radius: 10px;">
                                                 <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                 Tidak Hadir
                                             </span>
                                         </template>
                                     </span>
                                 </template>
                             </div>
                         </template>
                         <div x-show="wishes.length === 0" style="text-align: center; padding: 30px;">
                             <p style="color: #aaa; font-size: 0.9rem;">Belum ada ucapan</p>
                             <p style="color: #ccc; font-size: 0.8rem; margin-top: 5px;">Jadilah yang pertama memberikan ucapan!</p>
                         </div>
                     </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FOOTER --}}
        <footer class="reveal-on-scroll" style="background: var(--bali-brown); color: var(--bali-cream); text-align: center; padding: 40px 20px 100px;">
            <p class="font-bali-title" style="font-size: 1.5rem; color: var(--bali-gold); margin-bottom: 10px;">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</p>
            <p style="font-size: 0.8rem;">Om Shanti Shanti Shanti Om</p>
        </footer>

    </main>

    {{-- MUSIC BUTTON --}}
    <button x-show="opened" @click="toggleAudio()" class="music-btn">
        <svg x-show="playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
        <svg x-show="!playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
    </button>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" class="bottom-nav">
        <div class="nav-items">
            <a @click.prevent="scrollTo('home')" class="nav-item" :class="{ 'active': activeSection === 'home' }">
                {{-- SVG Home --}}
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Beranda</span>
            </a>
            <a @click.prevent="scrollTo('couple')" class="nav-item" :class="{ 'active': activeSection === 'couple' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Mempelai</span>
            </a>
            <a @click.prevent="scrollTo('events')" class="nav-item" :class="{ 'active': activeSection === 'events' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Acara</span>
            </a>
            @if($invitation->enable_gift)
            <a @click.prevent="scrollTo('gift')" class="nav-item" :class="{ 'active': activeSection === 'gift' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Punia</span>
            </a>
            @endif
            <a @click.prevent="scrollTo('rsvp')" class="nav-item" :class="{ 'active': activeSection === 'rsvp' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>Ucapan</span>
            </a>
        </div>
    </nav>
</div>
