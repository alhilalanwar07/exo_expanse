@section('title', 'The Royal Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link rel="preload" href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&display=swap" as="style" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&display=swap" rel="stylesheet"></noscript>
@endpush

@push('styles')
<style>
    :root {
        --java-gold: #D4AF37;
        --java-gold-dim: #AA8C2C;
        --java-brown: #2D1B0E;
        --java-cream: #FDFBF7;
        --java-batik: #3E2723;
        --overlay-dark: rgba(20, 10, 5, 0.9);
        
        --font-main: 'Lora', serif;
        --font-head: 'Cinzel Decorative', cursive;
        --font-script: 'Pinyon Script', cursive;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html, body {
        overflow-x: hidden;
        width: 100%;
        scroll-behavior: smooth;
        -webkit-tap-highlight-color: transparent;
    }

    body { 
        font-family: var(--font-main); 
        background-color: var(--java-cream); 
        color: var(--java-brown); 
        background-image: 
            radial-gradient(circle at 100% 150%, var(--java-gold) 24%, transparent 25%),
            radial-gradient(circle at 0% 150%, var(--java-gold) 24%, transparent 25%),
            radial-gradient(circle at 50% 100%, var(--java-gold) 10%, transparent 11%),
            radial-gradient(circle at 100% 50%, var(--java-gold) 5%, transparent 6%),
            radial-gradient(circle at 0% 50%, var(--java-gold) 5%, transparent 6%);
        background-size: 60px 60px;
        background-attachment: fixed;
        opacity: 0.98;
    }

    /* === UTILITIES === */
    .font-head { font-family: var(--font-head); letter-spacing: 1px; }
    .font-script { font-family: var(--font-script); }
    .text-gold { color: var(--java-gold); }
    .bg-brown { background-color: var(--java-brown); }

    /* === ANIMATIONS === */
    @keyframes wayangInLeft { 
        0% { opacity: 0; transform: translateX(-60px) rotate(-8deg) scale(0.85); filter: blur(4px); } 
        60% { opacity: 1; transform: translateX(5px) rotate(1deg) scale(1.02); filter: blur(0); }
        100% { opacity: 1; transform: translateX(0) rotate(0) scale(1); filter: blur(0); }
    }
    @keyframes wayangInRight { 
        0% { opacity: 0; transform: translateX(60px) rotate(8deg) scale(0.85); filter: blur(4px); } 
        60% { opacity: 1; transform: translateX(-5px) rotate(-1deg) scale(1.02); filter: blur(0); }
        100% { opacity: 1; transform: translateX(0) rotate(0) scale(1); filter: blur(0); }
    }
    
    @keyframes goldFlow { 
        0% { background-position: 0% 50%; } 
        50% { background-position: 100% 50%; } 
        100% { background-position: 0% 50%; } 
    }

    @keyframes gamelanPulse {
        0%, 100% { transform: scale(1); filter: brightness(1); }
        50% { transform: scale(1.05); filter: brightness(1.25); }
    }
    
    @media (prefers-reduced-motion: reduce) {
        * { animation-duration: 0.01ms !important; animation-iteration-count: 1 !important; transition-duration: 0.01ms !important; }
    }

    @keyframes coverGlow {
        0%, 100% { box-shadow: 0 0 15px rgba(212, 175, 55, 0.3), inset 0 0 30px rgba(212, 175, 55, 0.1); }
        50% { box-shadow: 0 0 35px rgba(212, 175, 55, 0.5), inset 0 0 60px rgba(212, 175, 55, 0.2); }
    }

    @keyframes floatUpDown {
        0%, 100% { transform: translateY(0px); }
        50% { transform: translateY(-12px); }
    }

    @keyframes shimmerGold {
        0% { background-position: -200% center; }
        100% { background-position: 200% center; }
    }

    @keyframes rotateGently {
        0%, 100% { transform: rotate(-2deg); }
        50% { transform: rotate(2deg); }
    }

    @keyframes fadeInUp {
        0% { opacity: 0; transform: translateY(30px) scale(0.97); filter: blur(6px); }
        100% { opacity: 1; transform: translateY(0) scale(1); filter: blur(0); }
    }

    @keyframes slideInLeft {
        0% { opacity: 0; transform: translateX(-30px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    @keyframes slideInRight {
        0% { opacity: 0; transform: translateX(30px); }
        100% { opacity: 1; transform: translateX(0); }
    }

    @keyframes floatParticle {
        0% { transform: translateY(0) translateX(0) rotate(0deg) scale(0.5); opacity: 0; }
        15% { opacity: 0.8; transform: translateY(-20px) translateX(10px) rotate(60deg) scale(1); }
        50% { opacity: 0.5; transform: translateY(-50px) translateX(-15px) rotate(180deg) scale(0.8); }
        85% { opacity: 0.3; }
        100% { transform: translateY(-100px) translateX(20px) rotate(360deg) scale(0.3); opacity: 0; }
    }

    @keyframes goldSparkle {
        0%, 100% { opacity: 0.2; transform: scale(0.8); }
        50% { opacity: 0.7; transform: scale(1.3); }
    }

    /* Scroll Reveal Classes */
    .reveal-element {
        opacity: 0;
        transform: translateY(40px) scale(0.97);
        transition: opacity 0.8s cubic-bezier(0.22, 1, 0.36, 1), transform 0.8s cubic-bezier(0.22, 1, 0.36, 1), filter 0.8s ease;
        filter: blur(4px);
    }
    .reveal-element.is-visible {
        opacity: 1;
        transform: translateY(0) scale(1);
        filter: blur(0);
    }
    .reveal-delay-1 { transition-delay: 0.15s; }
    .reveal-delay-2 { transition-delay: 0.3s; }
    .reveal-delay-3 { transition-delay: 0.45s; }

    .animate-wayang-left.is-visible { animation: wayangInLeft 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .animate-wayang-right.is-visible { animation: wayangInRight 1.2s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    /* === ORNAMENTS === */
    .gunungan-divider {
        width: 100%;
        height: 30px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 1200 120' preserveAspectRatio='none'%3E%3Cpath d='M600,0 C650,40 700,80 800,80 C900,80 1000,40 1200,120 L0,120 C200,40 300,80 400,80 C500,80 550,40 600,0 Z' fill='%23D4AF37' opacity='0.2'/%3E%3C/svg%3E");
        background-repeat: no-repeat;
        background-size: cover;
        transform: rotate(180deg);
        margin-bottom: -1px;
    }
    .gunungan-divider.bottom { transform: rotate(0deg); margin-top: -1px; margin-bottom: 0; }
    
    .batik-overlay {
        position: absolute; inset: 0; 
        background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23D4AF37' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
        pointer-events: none;
    }

    /* === COVER SCREEN === */
    .cover {
        position: fixed; inset: 0; z-index: 100;
        display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
        background: linear-gradient(135deg, var(--java-brown) 0%, #1a0f08 50%, var(--java-brown) 100%);
        overflow-y: auto; overflow-x: hidden;
        padding: 40px 0 30px;
    }
    .cover-bg {
        position: absolute; inset: 0;
        opacity: 0.4;
        background-size: cover; background-position: center;
        filter: sepia(0.6) contrast(1.1) brightness(0.8);
        transform: scale(1.15);
        animation: coverPulse 20s ease-in-out infinite alternate;
        will-change: transform;
    }
    
    @keyframes coverPulse {
        0%, 100% { transform: scale(1.15); }
        50% { transform: scale(1.18); }
    }
    .cover::before {
        content: ""; position: absolute; inset: 0;
        background: radial-gradient(ellipse at center, transparent 0%, rgba(0,0,0,0.5) 100%);
        z-index: 5;
    }

    .frame-jawa {
        position: absolute; inset: 15px;
        border: 2px solid var(--java-gold);
        mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
        animation: coverGlow 4s ease-in-out infinite;
        will-change: box-shadow;
    }
    .frame-jawa::before, .frame-jawa::after {
        content: ""; position: absolute; width: 30px; height: 30px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'%3E%3Cpath d='M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z'/%3E%3C/svg%3E");
        background-size: contain;
        animation: floatUpDown 3s ease-in-out infinite;
    }
    .frame-jawa::before { top: -15px; left: -15px; }
    .frame-jawa::after { top: -15px; right: -15px; }

    /* === HERO === */
    .hero-section {
        min-height: 100vh;
        position: relative;
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        text-align: center;
        padding: 60px 20px;
        background: var(--java-brown);
        color: var(--java-cream);
        overflow: hidden;
    }
    .hero-photo-frame {
        position: relative;
        width: 200px; height: 280px;
        margin: 0 auto 1.5rem;
        clip-path: ellipse(100% 100% at 50% 0%);
        border-radius: 100px 100px 20px 20px;
        padding: 4px;
        background: linear-gradient(180deg, var(--java-gold), transparent);
        box-shadow: 0 10px 30px rgba(0,0,0,0.4);
        animation: fadeInUp 1s ease-out, floatUpDown 4s ease-in-out 0.5s infinite;
    }
    @media (min-width: 768px) {
        .hero-photo-frame { width: 240px; height: 340px; border-radius: 120px 120px 20px 20px; padding: 6px; }
    }
    .hero-photo-img {
        width: 100%; height: 100%;
        border-radius: inherit;
        object-fit: cover;
        filter: sepia(0.3) contrast(1.1);
    }
    .hero-names {
        font-family: var(--font-head);
        font-size: clamp(2.5rem, 8vw, 5rem);
        color: var(--java-gold);
        text-shadow: 2px 2px 0px rgba(0,0,0,0.5), 0 0 15px rgba(212, 175, 55, 0.3);
        line-height: 1.1;
        margin-top: 1rem;
        animation: slideInLeft 1s ease-out 0.3s both;
    }

    /* === SECTIONS === */
    .section {
        padding: 60px 16px;
        position: relative;
        background: var(--java-cream);
        z-index: 10;
        border-top: 1px solid rgba(212,175,55,0.2);
    }
    @media (min-width: 768px) {
        .section { padding: 100px 20px; }
    }

    /* === BUTTONS === */
    .btn-royal {
        background: linear-gradient(45deg, #B8860B, #FFD700, #B8860B);
        background-size: 200% auto;
        color: #2D1B0E;
        padding: 14px 28px;
        border-radius: 50px;
        font-family: var(--font-head);
        font-weight: bold;
        font-size: 0.9rem;
        letter-spacing: 1px;
        border: 2px solid #FFE4B5;
        box-shadow: 0 5px 15px rgba(184, 134, 11, 0.3);
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
        z-index: 10;
        cursor: pointer;
        animation: goldFlow 3s infinite linear;
    }
    @media (min-width: 768px) {
        .btn-royal { padding: 16px 40px; font-size: 0.95rem; letter-spacing: 2px; }
    }
    .btn-royal:hover { transform: translateY(-3px); box-shadow: 0 10px 25px rgba(184, 134, 11, 0.5); }
    .btn-royal:active { transform: translateY(-1px); }

    /* === EVENT TICKET === */
    .event-card {
        background: linear-gradient(135deg, #fff 0%, #fafaf8 100%);
        border: 2px solid var(--java-gold);
        border-bottom-width: 4px;
        position: relative;
        overflow: hidden;
        border-radius: 12px;
        box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1);
        margin-bottom: 24px;
        transition: all 0.3s;
    }
    .event-card:hover { transform: translateY(-5px); box-shadow: 0 15px 40px -5px rgba(0,0,0,0.15); border-bottom-width: 6px; }
    .event-card::before {
        content: ""; position: absolute; top: 0; left: 0;
        width: 100px; height: 100px;
        background: linear-gradient(135deg, var(--java-gold) 50%, transparent 50%);
        opacity: 0.1;
        transform-origin: 0 0;
    }
    .event-time-box {
        background: linear-gradient(135deg, var(--java-brown) 0%, #1a0f08 100%);
        color: var(--java-gold);
        padding: 12px 20px;
        display: inline-block;
        font-family: var(--font-head);
        border-radius: 0 0 15px 15px;
        margin-bottom: 20px;
        border: 1px solid var(--java-gold);
        letter-spacing: 1px;
        font-size: 0.85rem;
    }

    /* === MUSIC CONTROL === */
    .music-gong {
        position: fixed; bottom: 90px; right: 16px;
        width: 50px; height: 50px;
        background: linear-gradient(135deg, #FFD700 0%, #B8860B 50%, #FFD700 100%);
        border-radius: 50%;
        border: 3px solid #5a3e18;
        z-index: 99;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.4);
        cursor: pointer;
        transition: all 0.3s;
    }
    @media (min-width: 768px) {
        .music-gong { width: 60px; height: 60px; bottom: 100px; right: 20px; border-width: 4px; }
    }
    .music-gong::after {
        content: ""; width: 14px; height: 14px;
        background: linear-gradient(135deg, #8B6F47, #5a3e18);
        border-radius: 50%;
    }
    .music-gong.playing { animation: gamelanPulse 2s infinite; will-change: transform; }
    .music-gong:focus-visible { outline: 2px solid var(--java-gold); outline-offset: 3px; }

    /* === PARTICLES === */
    .particle {
        position: absolute;
        width: 5px; height: 5px;
        background: radial-gradient(circle, var(--java-gold) 0%, transparent 70%);
        border-radius: 50%;
        pointer-events: none;
        will-change: transform, opacity;
        box-shadow: 0 0 6px rgba(212, 175, 55, 0.4);
    }
    .particle:nth-child(1) { animation: floatParticle 10s infinite ease-in-out, goldSparkle 3s infinite; left: 8%; top: 25%; animation-delay: 0s; width: 6px; height: 6px; }
    .particle:nth-child(2) { animation: floatParticle 14s infinite ease-in-out, goldSparkle 4s infinite; left: 22%; top: 55%; animation-delay: 1.5s; }
    .particle:nth-child(3) { animation: floatParticle 12s infinite ease-in-out, goldSparkle 3.5s infinite; left: 78%; top: 35%; animation-delay: 3s; width: 4px; height: 4px; }
    .particle:nth-child(4) { animation: floatParticle 16s infinite ease-in-out, goldSparkle 5s infinite; left: 65%; top: 65%; animation-delay: 5s; }
    .particle:nth-child(5) { animation: floatParticle 11s infinite ease-in-out, goldSparkle 2.8s infinite; left: 35%; top: 80%; animation-delay: 7s; width: 7px; height: 7px; }
    .particle:nth-child(6) { animation: floatParticle 13s infinite ease-in-out, goldSparkle 3.2s infinite; left: 50%; top: 15%; animation-delay: 2s; }
    .particle:nth-child(7) { animation: floatParticle 15s infinite ease-in-out, goldSparkle 4.5s infinite; left: 90%; top: 50%; animation-delay: 4s; width: 4px; height: 4px; }

    /* === SECTION TITLE === */
    .section-title h2 {
        font-family: var(--font-head);
        font-size: clamp(2rem, 5vw, 2.5rem);
        color: var(--java-brown);
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }
    .section-title h2::after {
        content: ""; display: block; width: 100%; height: 2px;
        background: linear-gradient(90deg, transparent, var(--java-gold), transparent);
        margin-top: 8px;
    }

    /* === NAVIGATION === */
    .royal-nav {
        position: fixed; bottom: 16px; left: 50%; transform: translateX(-50%);
        background: rgba(45, 27, 14, 0.95);
        border: 1px solid var(--java-gold);
        backdrop-filter: blur(10px);
        padding: 12px 20px;
        border-radius: 50px;
        display: flex; gap: 20px;
        z-index: 90;
        box-shadow: 0 10px 25px rgba(0,0,0,0.5);
        width: max-content;
        max-width: 95vw;
    }
    @media (min-width: 768px) {
        .royal-nav { padding: 14px 32px; gap: 35px; border-width: 2px; }
    }
    .royal-nav a {
        color: rgba(255,255,255,0.6);
        transition: all 0.3s ease;
        position: relative;
        display: flex; align-items: center; justify-content: center;
        cursor: pointer;
    }
    .royal-nav a:hover, .royal-nav a.active { color: var(--java-gold); transform: scale(1.15); }
    .royal-nav a.active::after {
        content: "•"; position: absolute; bottom: -14px; left: 50%; transform: translateX(-50%);
        color: var(--java-gold); font-size: 1.2rem;
    }

</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    activeSection: 'home',
    scrollObserver: null,
    revealObserver: null,
    isInitialized: false,
    prefersReduced: window.matchMedia('(prefers-reduced-motion: reduce)').matches,
    
    init() {
        document.body.style.overflow = 'hidden';
        this.$watch('opened', (value) => { if (value) this.onOpen(); });
    },
    open() {
        this.opened = true;
    },
    onOpen() {
        document.body.style.overflow = 'auto';
        this.$nextTick(() => {
            this.setupAudio();
            if (!this.isInitialized) {
                this.isInitialized = true;
                this.setupScrollSpy();
                this.setupRevealObserver();
            }
        });
    },
    setupAudio() {
        this.audioEl = document.getElementById('bgMusic');
        if (!this.audioEl) return;
        
        if (navigator.mediaSession) {
            navigator.mediaSession.metadata = new MediaMetadata({ 
                title: '{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}',
                artist: 'Wedding Celebration'
            });
        }
        
        this.audioEl.volume = 0.5;
        this.audioEl.play().then(() => { this.playing = true; }).catch(err => console.log('Audio play blocked'));
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        this.scrollObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) this.activeSection = entry.target.id;
            });
        }, { threshold: 0.3 });
        
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) this.scrollObserver.observe(el);
        });
    },
    setupRevealObserver() {
        const revealElements = document.querySelectorAll('[data-reveal]');
        revealElements.forEach(el => el.classList.add('reveal-element'));
        this.revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    if(entry.target.dataset.reveal === 'left') entry.target.classList.add('animate-wayang-left');
                    if(entry.target.dataset.reveal === 'right') entry.target.classList.add('animate-wayang-right');
                    this.revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1, rootMargin: '0px 0px -40px 0px' });
        revealElements.forEach(el => this.revealObserver.observe(el));
    },
    toggleAudio() {
        if (!this.audioEl) return;
        if (this.playing) { 
            this.audioEl.pause(); 
            this.playing = false;
        } else { 
            this.audioEl.play().then(() => { this.playing = true; }).catch(() => {});
        }
    },
    scrollTo(id) {
        this.activeSection = id;
        const el = document.getElementById(id);
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },
    destroy() {
        this.scrollObserver?.disconnect();
        this.revealObserver?.disconnect();
        this.audioEl?.pause();
    }
}" @destroy="destroy()">

    <!-- AUDIO -->
    @if($invitation->background_music)
    @php
        $musicSrc = str_starts_with($invitation->background_music, 'http') 
            ? $invitation->background_music 
            : asset('storage/' . $invitation->background_music);
    @endphp
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ $musicSrc }}" type="audio/mpeg">
    </audio>
    @endif

    <!-- COVER SCREEN (Pintu Gerbang) -->
    <div x-show="!opened"
         x-transition:leave="transition ease-in-out duration-[1000ms]"
         x-transition:leave-start="transform translateY(0) opacity-100"
         x-transition:leave-end="transform translateY(-100%) opacity-0"
         role="dialog"
         aria-modal="true"
         aria-label="Wedding invitation cover"
         class="cover">

        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>

        <div class="particle"></div><div class="particle"></div><div class="particle"></div>
        <div class="particle"></div><div class="particle"></div><div class="particle"></div><div class="particle"></div>

        <div class="frame-jawa"></div>

        {{-- Gunungan SVG Ornament --}}
        <div class="relative z-10 mb-auto mt-6" style="animation: floatUpDown 3s ease-in-out infinite;">
            <svg width="60" height="80" viewBox="0 0 60 80" fill="none" xmlns="http://www.w3.org/2000/svg" class="mx-auto drop-shadow-[0_0_12px_rgba(212,175,55,0.4)]">
                <path d="M30 0 L38 20 L50 30 L55 50 L50 65 L40 75 L30 80 L20 75 L10 65 L5 50 L10 30 L22 20 Z" fill="url(#gununganGrad)" stroke="#D4AF37" stroke-width="1.5" opacity="0.9"/>
                <path d="M30 8 L36 22 L44 30 L48 46 L44 58 L36 66 L30 70 L24 66 L16 58 L12 46 L16 30 L24 22 Z" fill="none" stroke="#D4AF37" stroke-width="0.8" opacity="0.5"/>
                <path d="M30 18 L33 28 L38 34 L40 44 L38 52 L34 56 L30 58 L26 56 L22 52 L20 44 L22 34 L27 28 Z" fill="#D4AF37" opacity="0.15"/>
                <line x1="30" y1="10" x2="30" y2="70" stroke="#D4AF37" stroke-width="0.5" opacity="0.4"/>
                <circle cx="30" cy="35" r="4" fill="#D4AF37" opacity="0.3"/>
                <circle cx="30" cy="35" r="2" fill="#D4AF37" opacity="0.5"/>
                <defs>
                    <linearGradient id="gununganGrad" x1="30" y1="0" x2="30" y2="80" gradientUnits="userSpaceOnUse">
                        <stop offset="0%" stop-color="#D4AF37" stop-opacity="0.6"/>
                        <stop offset="50%" stop-color="#AA8C2C" stop-opacity="0.3"/>
                        <stop offset="100%" stop-color="#D4AF37" stop-opacity="0.1"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>
        
        {{-- Cover Content --}}
        <div class="relative z-10 text-center px-5 w-full max-w-sm flex flex-col items-center">

            <p style="letter-spacing: 4px;" class="uppercase text-white/70 text-[10px] md:text-xs mb-3">Pernikahan Agung</p>

            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <div class="mb-8">
                <h1 class="font-head text-[2.5rem] md:text-5xl text-gold mb-1 drop-shadow-[0_2px_8px_rgba(212,175,55,0.3)]" style="animation: fadeInUp 1s ease-out 0.2s both;">
                    {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                </h1>
                <p class="font-script text-3xl md:text-4xl text-white/80 my-1">&</p>
                <h1 class="font-head text-[2.5rem] md:text-5xl text-gold drop-shadow-[0_2px_8px_rgba(212,175,55,0.3)]" style="animation: fadeInUp 1s ease-out 0.4s both;">
                    {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                </h1>
            </div>

            <div class="w-full bg-[rgba(45,27,14,0.85)] backdrop-blur-md p-6 md:p-8 rounded-2xl border border-[rgba(212,175,55,0.5)] shadow-[0_-10px_40px_rgba(0,0,0,0.3),0_0_20px_rgba(212,175,55,0.08)]" style="animation: fadeInUp 1s ease-out 0.6s both;">
                <p class="text-[10px] md:text-xs uppercase text-white/60 tracking-[0.2em] mb-1">Kepada Yth.</p>
                <p class="text-[10px] md:text-xs uppercase text-white/60 tracking-[0.15em] mb-3">Bapak/Ibu/Saudara/i</p>
                <h3 class="font-head text-xl md:text-2xl mb-5 text-gold" id="guestName">{{ $guestName }}</h3>
                <button @click="open()" type="button" class="btn-royal w-full py-3 md:py-4" aria-label="Open wedding invitation">
                    Buka Undangan
                </button>
            </div>

            {{-- Scroll Hint --}}
            <div class="mt-4 opacity-40">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="#D4AF37" stroke-width="2" class="mx-auto animate-bounce"><path d="M19 14l-7 7-7-7"/></svg>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main x-show="opened" class="relative z-0">
        
        <!-- HERO SECTION -->
        <section id="home" class="hero-section">
            <div class="absolute inset-0 bg-cover bg-center opacity-25" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>
            <div class="batik-overlay opacity-30"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--java-brown)] via-[rgba(62,39,35,0.4)] to-[var(--java-brown)]"></div>
            
            <div class="relative z-10 w-full max-w-lg mt-8">
                <div class="hero-photo-frame" data-reveal="up">
                    <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}" class="hero-photo-img" alt="{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}" loading="lazy" decoding="async">
                </div>

                <div data-reveal="up">
                    <div class="text-gold uppercase tracking-[0.3em] text-[10px] mb-2" style="animation-delay: 0.1s;">{{ $invitation->custom_styles['cover_subtitle'] ?? 'THE WEDDING OF' }}</div>
                    <h1 class="hero-names">
                        {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                        <span class="block font-script text-3xl md:text-4xl text-white opacity-80 my-1 md:my-2">&</span>
                        {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                    </h1>
                </div>

                <div class="mt-6" data-reveal="up" style="animation-delay: 0.2s;">
                    <div class="inline-block border-t border-b border-gold py-2 px-4 md:px-6">
                        <p class="font-head text-lg md:text-xl text-gold">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
            </div>

            <div class="absolute bottom-6 md:bottom-10 animate-bounce text-gold opacity-70">
                <svg width="24" height="24" fill="none" class="mx-auto" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M12 5v14M5 12l7 7 7-7"/></svg>
            </div>
        </section>

        <div class="gunungan-divider bg-white"></div>

        <!-- INTRO -->
        <section class="section text-center">
            <div class="max-w-xl mx-auto" data-reveal="up">
                <h2 class="font-script text-3xl md:text-4xl text-brown mb-4 md:mb-6">Mukadimah</h2>
                <div class="w-12 h-1 bg-gold mx-auto mb-6"></div>
                
                <p class="font-head text-xl md:text-2xl text-[var(--java-gold-dim)] mb-3">Assalamu'alaikum Wr. Wb.</p>
                <p class="leading-relaxed font-serif italic text-gray-700 text-base md:text-lg px-2">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                </p>
                <p class="mt-4 font-bold text-[10px] md:text-xs uppercase tracking-widest text-brown">(QS. Ar-Rum: 21)</p>
            </div>
        </section>

        <!-- COUPLE SECTION -->
        <section id="couple" class="section bg-[#F9F6F0]">
            <div class="text-center mb-10 md:mb-16" data-reveal="up">
                <p class="font-script text-2xl md:text-3xl text-gold">Pasangan Mempelai</p>
                <h2 class="font-head text-3xl md:text-4xl text-brown mt-1">Bride & Groom</h2>
            </div>

            <div class="max-w-5xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-12 md:gap-16 items-center px-2 relative overflow-hidden">
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[250px] h-[250px] md:w-[450px] md:h-[450px] bg-[var(--java-gold)] opacity-5 rounded-full blur-3xl pointer-events-none"></div>

                <!-- Person 1 -->
                <div class="text-center" data-reveal="left">
                    <div class="relative w-40 h-56 md:w-48 md:h-64 mx-auto mb-5">
                        <div class="absolute inset-0 border-[2px] md:border-[3px] border-gold rounded-[40px_40px_0_0] md:rounded-[50px_50px_0_0] rotate-3"></div>
                         <div class="absolute inset-0 border-[2px] md:border-[3px] border-brown rounded-[40px_40px_0_0] md:rounded-[50px_50px_0_0] -rotate-3 bg-white">
                             @if($order === 'bride_first')
                                <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-full h-full object-cover rounded-[36px_36px_0_0] md:rounded-[46px_46px_0_0] p-1" alt="{{ $invitation->bride_name }}" loading="lazy" decoding="async">
                            @else
                                <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-full h-full object-cover rounded-[36px_36px_0_0] md:rounded-[46px_46px_0_0] p-1" alt="{{ $invitation->groom_name }}" loading="lazy" decoding="async">
                            @endif
                         </div>
                    </div>
                    
                    <h3 class="font-head text-2xl md:text-3xl mb-1 text-brown">
                        {{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}
                    </h3>
                    <p class="text-gold font-bold text-sm mb-3">Putra/Putri Pertama</p>
                    <div class="text-xs md:text-sm font-serif text-gray-600 leading-relaxed px-4">
                        Putra/Putri dari Bpk. {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} <br> 
                        & Ibu {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}
                    </div>
                </div>

                <div class="hidden md:block absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                    <div class="w-14 h-14 bg-gold rounded-full flex items-center justify-center text-brown font-script text-3xl border-4 border-white shadow-xl">&</div>
                </div>
                <div class="block md:hidden text-center font-script text-4xl text-gold" data-reveal="up">&</div>

                <!-- Person 2 -->
                <div class="text-center" data-reveal="right">
                    <div class="relative w-40 h-56 md:w-48 md:h-64 mx-auto mb-5">
                        <div class="absolute inset-0 border-[2px] md:border-[3px] border-gold rounded-[40px_40px_0_0] md:rounded-[50px_50px_0_0] -rotate-3"></div>
                         <div class="absolute inset-0 border-[2px] md:border-[3px] border-brown rounded-[40px_40px_0_0] md:rounded-[50px_50px_0_0] rotate-3 bg-white">
                             @if($order === 'bride_first')
                                <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-full h-full object-cover rounded-[36px_36px_0_0] md:rounded-[46px_46px_0_0] p-1" alt="{{ $invitation->groom_name }}" loading="lazy" decoding="async">
                            @else
                                <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-full h-full object-cover rounded-[36px_36px_0_0] md:rounded-[46px_46px_0_0] p-1" alt="{{ $invitation->bride_name }}" loading="lazy" decoding="async">
                            @endif
                         </div>
                    </div>
                    
                    <h3 class="font-head text-2xl md:text-3xl mb-1 text-brown">
                        {{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}
                    </h3>
                    <p class="text-gold font-bold text-sm mb-3">Putra/Putri Kedua</p>
                    <div class="text-xs md:text-sm font-serif text-gray-600 leading-relaxed px-4">
                        Putra/Putri dari Bpk. {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} <br> 
                        & Ibu {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}
                    </div>
                </div>
            </div>
        </section>

        <div class="gunungan-divider bottom bg-[#F9F6F0]"></div>

        <!-- COUNTDOWN -->
        <section class="py-16 md:py-24 px-4 relative bg-fixed bg-cover bg-center" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');">
            <div class="absolute inset-0 bg-[var(--java-brown)] opacity-85"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto text-center" data-reveal="up">
                <p class="text-gold font-script text-3xl md:text-4xl mb-6 md:mb-8">Menghitung Waktu</p>
                
                <div x-data="{
                    days: 0, hours: 0, minutes: 0, seconds: 0,
                    target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
                    init() { this.update(); setInterval(() => this.update(), 1000); },
                    update() {
                        const diff = this.target - new Date();
                        if (diff > 0) {
                            this.days = Math.floor(diff / 86400000);
                            this.hours = Math.floor((diff % 86400000) / 3600000);
                            this.minutes = Math.floor((diff % 3600000) / 60000);
                            this.seconds = Math.floor((diff % 60000) / 1000);
                        }
                    },
                    saveToCalendar() {
                        const title = 'Wedding of ' + '{{ $invitation->groom_nickname }}' + ' & ' + '{{ $invitation->bride_nickname }}';
                        const start = '{{ $invitation->akad_date?->format('Ymd\THis') }}';
                        const end = '{{ $invitation->akad_date?->addHours(2)->format('Ymd\THis') }}';
                        const details = 'Celebrating the wedding of ' + '{{ $invitation->groom_nickname }}' + ' and ' + '{{ $invitation->bride_nickname }}';
                        const location = '{{ $invitation->akad_venue }}';
                        const url = `https://calendar.google.com/calendar/render?action=TEMPLATE&text=${encodeURIComponent(title)}&dates=${start}/${end}&details=${encodeURIComponent(details)}&location=${encodeURIComponent(location)}`;
                        window.open(url, '_blank');
                    }
                }">
                    <!-- Fixed Grid Structure for Mobile -->
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-8 md:mb-10">
                        <div class="bg-gradient-to-br from-[rgba(255,255,255,0.1)] to-[rgba(212,175,55,0.05)] border border-[rgba(212,175,55,0.4)] rounded-xl p-4 md:p-6 backdrop-blur-sm transition-transform hover:scale-105">
                            <div class="font-head text-3xl md:text-4xl text-gold" x-text="days">0</div>
                            <div class="text-[10px] md:text-xs text-white uppercase tracking-wider mt-2 font-semibold">Hari</div>
                        </div>
                        <div class="bg-gradient-to-br from-[rgba(255,255,255,0.1)] to-[rgba(212,175,55,0.05)] border border-[rgba(212,175,55,0.4)] rounded-xl p-4 md:p-6 backdrop-blur-sm transition-transform hover:scale-105">
                            <div class="font-head text-3xl md:text-4xl text-gold" x-text="hours">0</div>
                            <div class="text-[10px] md:text-xs text-white uppercase tracking-wider mt-2 font-semibold">Jam</div>
                        </div>
                        <div class="bg-gradient-to-br from-[rgba(255,255,255,0.1)] to-[rgba(212,175,55,0.05)] border border-[rgba(212,175,55,0.4)] rounded-xl p-4 md:p-6 backdrop-blur-sm transition-transform hover:scale-105">
                            <div class="font-head text-3xl md:text-4xl text-gold" x-text="minutes">0</div>
                            <div class="text-[10px] md:text-xs text-white uppercase tracking-wider mt-2 font-semibold">Menit</div>
                        </div>
                        <div class="bg-gradient-to-br from-[rgba(255,255,255,0.1)] to-[rgba(212,175,55,0.05)] border border-[rgba(212,175,55,0.4)] rounded-xl p-4 md:p-6 backdrop-blur-sm transition-transform hover:scale-105">
                            <div class="font-head text-3xl md:text-4xl text-gold" x-text="seconds">0</div>
                            <div class="text-[10px] md:text-xs text-white uppercase tracking-wider mt-2 font-semibold">Detik</div>
                        </div>
                    </div>
                    
                    <button @click="saveToCalendar()" class="btn-royal text-sm md:text-base px-6 py-3 md:px-8 md:py-4">
                        Simpan Tanggal
                    </button>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section id="events" class="section">
            <div class="section-title text-center mb-10 md:mb-16" data-reveal="up">
                <h2 class="font-head">Rangkaian Acara</h2>
            </div>

            <div class="max-w-4xl mx-auto grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 px-2 md:px-4">
                <!-- Akad Ticket -->
                <div class="event-card" data-reveal="left">
                    <div class="p-6 md:p-8 text-center">
                        <div class="event-time-box">AKAD NIKAH</div>
                        <div class="mb-4">
                            <p class="font-head text-4xl md:text-5xl text-brown mb-1">{{ $invitation->akad_date?->format('d') }}</p>
                            <p class="text-gold font-bold uppercase tracking-widest text-xs md:text-sm">{{ $invitation->akad_date?->translatedFormat('F Y') }}</p>
                        </div>
                        <div class="font-serif text-gray-600 mb-5 text-sm md:text-base">
                            <p class="text-lg md:text-xl italic">{{ $invitation->akad_date?->translatedFormat('l') }}</p>
                            <p>Pukul {{ $invitation->akad_date?->format('H:i') }} WIB</p>
                        </div>
                        <hr class="border-dashed border-gray-300 w-2/3 mx-auto mb-5">
                        <div class="mb-6">
                            <h4 class="font-bold text-brown mb-1 text-sm md:text-base">{{ $invitation->akad_venue }}</h4>
                            <p class="text-xs md:text-sm text-gray-500 px-2">{{ $invitation->akad_address }}</p>
                        </div>
                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="inline-block text-gold border-b border-gold pb-1 hover:text-brown transition text-[10px] md:text-xs font-bold uppercase tracking-widest">
                            Lihat Lokasi
                        </a>
                    </div>
                </div>

                <!-- Resepsi Ticket -->
                @if($invitation->resepsi_date)
                <div class="event-card" data-reveal="right">
                    <div class="p-6 md:p-8 text-center">
                        <div class="event-time-box">RESEPSI</div>
                        <div class="mb-4">
                            <p class="font-head text-4xl md:text-5xl text-brown mb-1">{{ $invitation->resepsi_date?->format('d') }}</p>
                            <p class="text-gold font-bold uppercase tracking-widest text-xs md:text-sm">{{ $invitation->resepsi_date?->translatedFormat('F Y') }}</p>
                        </div>
                        <div class="font-serif text-gray-600 mb-5 text-sm md:text-base">
                            <p class="text-lg md:text-xl italic">{{ $invitation->resepsi_date?->translatedFormat('l') }}</p>
                            <p>Pukul {{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</p>
                        </div>
                        <hr class="border-dashed border-gray-300 w-2/3 mx-auto mb-5">
                        <div class="mb-6">
                            <h4 class="font-bold text-brown mb-1 text-sm md:text-base">{{ $invitation->resepsi_venue }}</h4>
                            <p class="text-xs md:text-sm text-gray-500 px-2">{{ $invitation->resepsi_address }}</p>
                        </div>
                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="inline-block text-gold border-b border-gold pb-1 hover:text-brown transition text-[10px] md:text-xs font-bold uppercase tracking-widest">
                            Lihat Lokasi
                        </a>
                    </div>
                </div>
                @endif
            </div>
        </section>

        <!-- GALLERY -->
        @if($invitation->enable_gallery == 1 && $invitation->photos)
        <section class="section bg-[#F9F6F0]">
            <div class="section-title text-center mb-10 md:mb-12" data-reveal="up">
                <p class="font-script text-2xl md:text-3xl text-gold">Momen Indah</p>
                <h2 class="font-head">Galeri Foto</h2>
            </div>
            
            <div x-data="{ lightboxOpen: false, imgSrc: '', caption: '', open(url, cap) { this.imgSrc = url; this.caption = cap; this.lightboxOpen = true; }, close() { this.lightboxOpen = false; } }">
                <div class="columns-2 md:columns-3 gap-4 md:gap-6 space-y-4 md:space-y-6 px-2 md:px-4 max-w-6xl mx-auto">
                    @foreach($invitation->photos as $photo)
                    <div class="break-inside-avoid relative group cursor-pointer" data-reveal="up" role="button" tabindex="0" @click="open('{{ asset('storage/' . $photo) }}', '')" @keydown.enter="open('{{ asset('storage/' . $photo) }}', '')">
                        <div class="overflow-hidden rounded-lg p-2 md:p-3 bg-white shadow-md border border-gold hover:shadow-xl hover:scale-105 transition duration-300">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Wedding gallery moment" class="w-full h-auto object-cover rounded" loading="lazy" decoding="async">
                        </div>
                    </div>
                    @endforeach
                </div>

                <div x-show="lightboxOpen" class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4 backdrop-blur-sm" x-transition @click.self="close()" @keydown.escape="close()" role="dialog" aria-modal="true" aria-label="Image lightbox" style="display: none;">
                    <button @click="close()" aria-label="Close lightbox" class="absolute top-4 right-4 text-white hover:text-gold transition z-50 focus:outline-gold focus:outline-offset-2">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img :src="imgSrc" class="max-h-[85vh] max-w-full border-2 border-gold rounded-lg object-contain">
                </div>
            </div>
        </section>
        @endif

        <!-- LOVE STORY -->
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section class="section bg-[#F9F6F0]">
            <div class="section-title text-center mb-10 md:mb-16" data-reveal="up">
                <p class="font-script text-2xl md:text-3xl text-gold">Perjalanan Kami</p>
                <h2 class="font-head">Kisah Cinta</h2>
            </div>

            <div class="max-w-2xl mx-auto px-4" style="position: relative;">
                <div style="position: absolute; left: 31px; top: 0; bottom: 0; width: 2px; background: linear-gradient(180deg, transparent, var(--java-gold), var(--java-gold), transparent);"></div>

                @foreach($invitation->love_story as $index => $story)
                <div data-reveal="up" style="display: flex; align-items: flex-start; margin-bottom: {{ $loop->last ? '0' : '28px' }}; position: relative;">
                    <div style="flex-shrink: 0; width: 32px; display: flex; justify-content: center; position: relative; z-index: 2; padding-top: 20px;">
                        <div style="width: 14px; height: 14px; background: var(--java-cream); border: 3px solid var(--java-gold); border-radius: 50%; box-shadow: 0 0 0 4px rgba(212,175,55,0.15);"></div>
                    </div>
                    <div class="event-card" style="flex: 1; margin-left: 12px; margin-bottom: 0; border-left-width: 4px;">
                        <div style="padding: 20px;">
                            <div class="event-time-box" style="padding: 6px 16px; font-size: 11px; margin-bottom: 12px;">{{ $story['date'] ?? '' }}</div>
                            <h3 class="font-head" style="font-size: 1.15rem; color: var(--java-brown); margin-bottom: 6px; line-height: 1.3;">{{ $story['title'] ?? '' }}</h3>
                            <p class="font-serif text-gray-600" style="font-size: 13px; line-height: 1.75;">{{ $story['description'] ?? '' }}</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <!-- GIFT -->
        @if($invitation->enable_gift)
        <section id="gift" class="section">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-10 md:mb-12" data-reveal="up">
                    <h2 class="font-head text-2xl md:text-3xl mb-3 text-brown">Tanda Kasih</h2>
                    <p class="text-gray-600 font-serif italic text-sm md:text-base leading-relaxed px-4 md:px-6">
                        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 px-2 md:px-4">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="bg-gradient-to-br from-white to-[#fafaf8] p-6 md:p-8 rounded-xl border border-gold shadow-md relative overflow-hidden group" data-reveal="up" x-data="{ copied: false }">
                        <div class="absolute -right-6 -bottom-6 text-[120px] md:text-[150px] text-gray-100 opacity-40 font-head pointer-events-none select-none">Rp</div>
                        <div class="relative z-10">
                            <p class="font-head text-xl md:text-2xl text-brown mb-2">{{ $account['bank'] }}</p>
                            <div class="bg-[rgba(212,175,55,0.05)] p-3 md:p-4 rounded-lg border border-gold/50 mb-3 md:mb-4">
                                <p class="font-mono text-lg md:text-xl font-bold text-brown tracking-wider select-all">{{ $account['account_number'] }}</p>
                            </div>
                            <p class="text-xs md:text-sm text-gray-600 mb-5 uppercase tracking-wider font-semibold">a.n {{ $account['account_name'] }}</p>
                            <button @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    type="button" aria-label="Copy bank account number"
                                    class="w-full bg-[var(--java-brown)] text-gold px-4 py-2 md:py-3 rounded-full text-[10px] md:text-xs uppercase tracking-widest font-bold transition focus:outline-gold focus:outline-offset-2">
                                <span x-text="copied ? '✓ Tersalin' : 'Salin Nomor'"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP -->
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section bg-[#2D1B0E] text-[#F9F6F0]"
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
            <div class="section-title text-center mb-8 md:mb-10" data-reveal="up">
                <h2 class="font-head text-gold">Doa & Kehadiran</h2>
            </div>

            <div class="max-w-xl mx-auto bg-[rgba(255,255,255,0.03)] p-6 md:p-10 rounded-2xl border border-[rgba(212,175,55,0.3)]" data-reveal="up">
                {{-- Success --}}
                <div x-show="success" x-transition class="bg-gold text-brown p-3 rounded-lg text-center text-sm font-bold border border-gold mb-5">
                    ✓ Terima kasih! Ucapan dan konfirmasi Anda telah tersimpan.
                </div>
                {{-- Error --}}
                <div x-show="error" x-transition class="bg-red-900/30 text-red-300 p-3 rounded-lg text-center text-sm border border-red-700 mb-5" x-text="error"></div>

                <form @submit.prevent="submitForm" class="space-y-5 md:space-y-6 mb-10">
                    <div class="space-y-2">
                        <label class="text-[10px] md:text-xs uppercase tracking-widest text-gold font-bold">Nama Lengkap</label>
                        <input type="text" x-model="name" class="w-full bg-[rgba(255,255,255,0.05)] border-b border-gray-600 focus:border-gold outline-none py-2 md:py-3 text-white text-sm md:text-base px-3 rounded" placeholder="Nama Anda">
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] md:text-xs uppercase tracking-widest text-gold font-bold">Ucapan & Doa</label>
                        <textarea x-model="message" rows="3" class="w-full bg-[rgba(255,255,255,0.05)] border-b border-gray-600 focus:border-gold outline-none py-2 md:py-3 text-white text-sm md:text-base px-3 rounded resize-none" placeholder="Tuliskan doa..."></textarea>
                    </div>

                    <div class="space-y-2">
                        <label class="text-[10px] md:text-xs uppercase tracking-widest text-gold block font-bold">Konfirmasi Kehadiran</label>
                        <div class="flex gap-3">
                            <button type="button" @click="status = 'confirmed'"
                                    :class="status === 'confirmed' ? 'bg-gold text-brown border-gold font-bold' : 'text-gray-400 border-gray-600'"
                                    class="flex-1 py-3 text-sm border rounded transition flex items-center justify-center gap-2">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                Hadir
                            </button>
                            <button type="button" @click="status = 'declined'"
                                    :class="status === 'declined' ? 'bg-red-900 text-white border-red-700 font-bold' : 'text-gray-400 border-gray-600'"
                                    class="flex-1 py-3 text-sm border rounded transition flex items-center justify-center gap-2">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                Maaf
                            </button>
                        </div>
                    </div>

                    <div class="space-y-2" x-show="status === 'confirmed'" x-transition>
                        <label class="text-[10px] md:text-xs uppercase tracking-widest text-gold font-bold">Jumlah Tamu</label>
                        <select x-model="pax" class="w-full bg-[rgba(255,255,255,0.05)] border-b border-gray-600 focus:border-gold outline-none py-2 md:py-3 text-white rounded px-3 text-sm md:text-base">
                            <option value="1" class="text-black">1 Orang</option>
                            <option value="2" class="text-black">2 Orang</option>
                            <option value="3" class="text-black">3 Orang</option>
                            <option value="4" class="text-black">4 Orang</option>
                            <option value="5" class="text-black">5 Orang</option>
                        </select>
                    </div>

                    <button type="submit" :disabled="loading" class="btn-royal w-full mt-4 text-sm md:text-base">
                        <span x-show="!loading">Kirim</span>
                        <span x-show="loading">Mengirim...</span>
                    </button>
                </form>

                <div class="border-t border-[rgba(212,175,55,0.2)] pt-6 mt-6">
                    <p class="font-head text-center text-lg md:text-xl mb-4 text-gold">Ucapan Terindah</p>
                    <div class="max-h-64 md:max-h-80 overflow-y-auto space-y-3 pr-2">
                        <template x-for="wish in wishes" :key="wish.id">
                            <div class="bg-[rgba(255,255,255,0.02)] p-4 rounded border-l-2 border-gold text-sm md:text-base">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gold text-sm" x-text="wish.name"></h4>
                                    <span class="text-[10px] text-gray-500" x-text="wish.time"></span>
                                </div>
                                <p class="text-xs md:text-sm text-gray-300 italic" x-text="'- ' + wish.message + ' -'"></p>
                                <template x-if="wish.attendance_status">
                                    <span>
                                        <template x-if="wish.attendance_status === 'confirmed'">
                                            <span class="inline-flex items-center gap-1 mt-2 text-[10px] font-bold px-2 py-1 rounded-full" style="background: rgba(34,197,94,0.15); color: #22C55E;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Akan Hadir
                                            </span>
                                        </template>
                                        <template x-if="wish.attendance_status === 'declined'">
                                            <span class="inline-flex items-center gap-1 mt-2 text-[10px] font-bold px-2 py-1 rounded-full" style="background: rgba(239,68,68,0.15); color: #EF4444;">
                                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tidak Hadir
                                            </span>
                                        </template>
                                    </span>
                                </template>
                            </div>
                        </template>
                        <div x-show="wishes.length === 0" class="text-center text-gray-500 py-6 text-xs md:text-sm italic">Belum ada ucapan.</div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- FOOTER -->
        <footer class="bg-[#201309] text-[var(--java-cream)] py-10 md:py-12 text-center pb-28 md:pb-32 border-t border-[rgba(212,175,55,0.1)]">
            <h2 class="font-head text-2xl md:text-3xl mb-1 text-gold opacity-80">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p class="font-serif italic text-xs md:text-sm opacity-50 mb-6">Terima kasih atas doa & restu Anda</p>
            <div class="w-8 h-8 mx-auto border border-gold rounded-full flex items-center justify-center opacity-30 text-gold text-sm font-script">W</div>
        </footer>

    </main>

    <!-- MUSIC GONG -->
    <div x-show="opened" @click="toggleAudio()" @keydown.space.prevent="toggleAudio()" role="button" tabindex="0" aria-label="Toggle background music" class="music-gong" :class="{ 'playing': playing }">
        <svg x-show="playing" class="w-5 h-5 md:w-6 md:h-6 text-[#2D1B0E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-4z"/></svg>
        <svg x-show="!playing" class="w-5 h-5 md:w-6 md:h-6 text-[#2D1B0E]" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73 4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
    </div>

    <!-- FLOATING NAV -->
    <nav x-show="opened" class="royal-nav" aria-label="Main navigation"
         x-transition:enter="transition ease-out duration-500" 
         x-transition:enter-start="translate-y-20 opacity-0" 
         x-transition:enter-end="translate-y-0 opacity-100">
        
        <a @click.prevent="scrollTo('home')" :class="{ 'active': activeSection === 'home' }" role="button" tabindex="0" aria-label="Go to home" :aria-current="activeSection === 'home' ? 'page' : false">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </a>
        <a @click.prevent="scrollTo('couple')" :class="{ 'active': activeSection === 'couple' }" role="button" tabindex="0" aria-label="Go to couple">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </a>
        <a @click.prevent="scrollTo('events')" :class="{ 'active': activeSection === 'events' }" role="button" tabindex="0" aria-label="Go to events">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </a>
        @if($invitation->enable_gift)
        <a @click.prevent="scrollTo('gift')" :class="{ 'active': activeSection === 'gift' }" role="button" tabindex="0" aria-label="Go to gift">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </a>
        @endif
        @if($invitation->enable_rsvp)
        <a @click.prevent="scrollTo('rsvp')" :class="{ 'active': activeSection === 'rsvp' }" role="button" tabindex="0" aria-label="Go to RSVP">
            <svg class="w-5 h-5 md:w-6 md:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>
        @endif
    </nav>
</div>