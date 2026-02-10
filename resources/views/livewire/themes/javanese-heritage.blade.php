@section('title', 'The Royal Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cinzel+Decorative:wght@400;700;900&family=Lora:ital,wght@0,400;0,500;0,600;1,400&family=Pinyon+Script&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
    :root {
        /* Premium Javanese Palette (Keraton) */
        --java-gold: #D4AF37; /* Emas Murni */
        --java-gold-dim: #AA8C2C;
        --java-brown: #2D1B0E; /* Coklat Kayu Jati Tua */
        --java-cream: #FDFBF7; /* Putih Gading */
        --java-batik: #3E2723;
        --overlay-dark: rgba(20, 10, 5, 0.9);
        
        --font-main: 'Lora', serif;
        --font-head: 'Cinzel Decorative', cursive;
        --font-script: 'Pinyon Script', cursive;
    }

    * { margin: 0; padding: 0; box-sizing: border-box; }

    html {
        scroll-behavior: smooth;
        -webkit-tap-highlight-color: transparent;
    }

    body { 
        font-family: var(--font-main); 
        background-color: var(--java-cream); 
        color: var(--java-brown); 
        overflow-x: hidden;
        /* Pattern Batik Kawung Halus */
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

    /* === ANIMATIONS (Wayang Style) === */
    /* Muncul dari samping seperti wayang masuk kelir */
    @keyframes wayangInLeft { 
        0% { opacity: 0; transform: translateX(-100px) rotate(-10deg) scale(0.8); } 
        60% { opacity: 1; transform: translateX(10px) rotate(2deg) scale(1.05); }
        100% { opacity: 1; transform: translateX(0) rotate(0) scale(1); }
    }
    @keyframes wayangInRight { 
        0% { opacity: 0; transform: translateX(100px) rotate(10deg) scale(0.8); } 
        60% { opacity: 1; transform: translateX(-10px) rotate(-2deg) scale(1.05); }
        100% { opacity: 1; transform: translateX(0) rotate(0) scale(1); }
    }
    
    /* Gunungan opening effect */
    @keyframes gununganOpen {
        0% { transform: scale(0.5) translateY(100px); opacity: 0; }
        100% { transform: scale(1) translateY(0); opacity: 1; }
    }

    @keyframes goldFlow { 
        0% { background-position: 0% 50%; } 
        50% { background-position: 100% 50%; } 
        100% { background-position: 0% 50%; } 
    }

    @keyframes gamelanPulse {
        0%, 100% { transform: scale(1); filter: brightness(1); }
        50% { transform: scale(1.02); filter: brightness(1.2); }
    }

    /* Scroll Reveal Classes */
    .reveal-element {
        opacity: 0;
        transform: translateY(50px);
        transition: all 1.2s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .reveal-element.is-visible {
        opacity: 1;
        transform: translateY(0);
    }
    
    .animate-wayang-left.is-visible { animation: wayangInLeft 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }
    .animate-wayang-right.is-visible { animation: wayangInRight 1.5s cubic-bezier(0.34, 1.56, 0.64, 1) forwards; }

    /* === ORNAMENTS === */
    .gunungan-divider {
        width: 100%;
        height: 40px;
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
        display: flex; flex-direction: column; align-items: center; justify-content: center;
        background: var(--java-brown);
        overflow: hidden;
    }
    .cover-bg { 
        position: absolute; inset: 0; 
        opacity: 0.3; 
        background-size: cover; background-position: center; 
        filter: sepia(0.6) contrast(1.1);
        transform: scale(1.1);
        animation: gamelanPulse 20s infinite alternate;
    }
    /* Frame Ornamen Jawa */
    .frame-jawa {
        position: absolute; inset: 15px;
        border: 2px solid var(--java-gold);
        mask-image: linear-gradient(to bottom, black 80%, transparent 100%);
    }
    .frame-jawa::before, .frame-jawa::after {
        content: ""; position: absolute; width: 40px; height: 40px;
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23D4AF37'%3E%3Cpath d='M12 2l2.4 7.2h7.6l-6 4.8 2.4 7.2-6-4.8-6 4.8 2.4-7.2-6-4.8h7.6z'/%3E%3C/svg%3E");
        background-size: contain;
    }
    .frame-jawa::before { top: -20px; left: -20px; }
    .frame-jawa::after { top: -20px; right: -20px; }

    /* === HERO === */
    .hero-section {
        min-height: 100vh;
        position: relative;
        display: flex; flex-direction: column;
        justify-content: center; align-items: center;
        text-align: center;
        padding: 40px 20px;
        background: var(--java-brown);
        color: var(--java-cream);
        overflow: hidden;
    }
    /* Exclusive Hero Photo Frame */
    .hero-photo-frame {
        position: relative;
        width: 240px; height: 340px;
        margin: 0 auto 2rem;
        /* Bentuk kubah / gunungan */
        clip-path: ellipse(100% 100% at 50% 0%); /* Initial clip */
        border-radius: 120px 120px 20px 20px;
        padding: 6px;
        background: linear-gradient(180deg, var(--java-gold), transparent);
        box-shadow: 0 0 50px rgba(212, 175, 55, 0.3);
    }
    .hero-photo-img {
        width: 100%; height: 100%;
        border-radius: 115px 115px 15px 15px;
        object-fit: cover;
        filter: sepia(0.3) contrast(1.1);
    }
    .hero-names {
        font-family: var(--font-head);
        font-size: clamp(3rem, 10vw, 5rem);
        color: var(--java-gold);
        text-shadow: 2px 2px 0px rgba(0,0,0,0.5);
        line-height: 1;
        margin-top: 1rem;
    }

    /* === SECTIONS === */
    .section { 
        padding: 100px 20px; 
        position: relative; 
        background: var(--java-cream);
        z-index: 10;
        border-top: 1px solid rgba(212,175,55,0.2);
    }
    .section-title h2 {
        font-family: var(--font-head);
        font-size: 2.5rem;
        color: var(--java-brown);
        font-weight: 700;
        margin-bottom: 0.5rem;
        position: relative;
        display: inline-block;
    }
    /* Garis bawah judul dengan ornamen */
    .section-title h2::after {
        content: ""; display: block; width: 100%; height: 3px;
        background: linear-gradient(90deg, transparent, var(--java-gold), transparent);
        margin-top: 10px;
    }

    /* === BUTTONS === */
    .btn-royal {
        background: linear-gradient(45deg, #B8860B, #FFD700, #B8860B);
        background-size: 200% auto;
        color: #2D1B0E;
        padding: 16px 40px;
        border-radius: 50px;
        font-family: var(--font-head);
        font-weight: bold;
        font-size: 0.95rem;
        letter-spacing: 2px;
        border: 1px solid #FFE4B5;
        box-shadow: 0 10px 30px rgba(184, 134, 11, 0.4);
        position: relative;
        overflow: hidden;
        transition: all 0.5s ease;
        z-index: 10;
        cursor: pointer;
        animation: goldFlow 3s infinite linear;
    }
    .btn-royal:hover { transform: translateY(-3px); box-shadow: 0 15px 40px rgba(184, 134, 11, 0.6); }

    /* === EVENT TICKET (Kartu Mewah) === */
    .event-card {
        background: #fff;
        border: 1px solid #e0e0e0;
        position: relative;
        overflow: hidden;
        border-radius: 4px;
        box-shadow: 0 20px 40px -10px rgba(0,0,0,0.1);
        margin-bottom: 40px;
    }
    /* Sudut Emas */
    .event-card::before {
        content: ""; position: absolute; top: 0; left: 0;
        width: 100px; height: 100px;
        background: linear-gradient(135deg, var(--java-gold) 50%, transparent 50%);
        opacity: 0.2;
    }
    .event-time-box {
        background: var(--java-brown);
        color: var(--java-gold);
        padding: 15px;
        display: inline-block;
        font-family: var(--font-head);
        border-radius: 0 0 20px 20px;
        margin-bottom: 20px;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
    }

    /* === MUSIC CONTROL (Gong Style) === */
    .music-gong {
        position: fixed; bottom: 100px; right: 20px;
        width: 50px; height: 50px;
        background: radial-gradient(circle at 30% 30%, #FFD700, #B8860B);
        border-radius: 50%;
        border: 4px solid #5a3e18;
        z-index: 99;
        display: flex; align-items: center; justify-content: center;
        box-shadow: 0 10px 20px rgba(0,0,0,0.4);
        cursor: pointer;
        transition: transform 0.3s;
    }
    .music-gong::after {
        content: ""; width: 15px; height: 15px;
        background: #5a3e18; border-radius: 50%;
        box-shadow: inset 0 2px 5px rgba(0,0,0,0.5);
    }
    .music-gong.playing { animation: pulseSoft 2s infinite; }

    /* === NAVIGASI BAWAH === */
    .royal-nav {
        position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
        background: rgba(45, 27, 14, 0.95); /* #2D1B0E */
        border: 1px solid var(--java-gold);
        backdrop-filter: blur(10px);
        padding: 12px 30px;
        border-radius: 100px;
        display: flex; gap: 30px;
        z-index: 90;
        box-shadow: 0 10px 30px rgba(0,0,0,0.5);
    }
    .royal-nav a { color: rgba(255,255,255,0.5); transition: 0.3s; }
    .royal-nav a.active { color: var(--java-gold); transform: scale(1.2); }
    /* Titik kecil di bawah icon aktif */
    .royal-nav a.active::after {
        content: "•"; position: absolute; bottom: -12px; left: 50%; transform: translateX(-50%); color: var(--java-gold);
    }

</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    activeSection: 'home',
    
    init() {
        document.body.style.overflow = 'hidden';
        
        // Setup observer saat dibuka
        this.$watch('opened', (value) => {
            if (value) {
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
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    this.activeSection = entry.target.id;
                }
            });
        }, { threshold: 0.3 });
        
        sections.forEach(id => {
            const el = document.getElementById(id);
            if (el) observer.observe(el);
        });
    },
    setupRevealObserver() {
        const revealElements = document.querySelectorAll('.reveal-element');
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('is-visible');
                    // Tambahkan class khusus untuk animasi wayang jika ada
                    if(entry.target.classList.contains('wayang-left')) entry.target.classList.add('animate-wayang-left');
                    if(entry.target.classList.contains('wayang-right')) entry.target.classList.add('animate-wayang-right');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15 });

        revealElements.forEach(el => revealObserver.observe(el));
    },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto'; // Enable scroll
        
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bgMusic');
            if (this.audioEl) {
                this.audioEl.volume = 0.5;
                this.audioEl.play().then(() => { this.playing = true; }).catch(() => {});
            }
        });
    },
    toggleAudio() {
        if (!this.audioEl) return;
        if (this.playing) { 
            this.audioEl.pause(); 
            this.playing = false;
        } else { 
            this.audioEl.play().then(() => { this.playing = true; });
        }
    },
    scrollTo(id) {
        this.activeSection = id;
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
    }
}">

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
         x-transition:leave="transition ease-in-out duration-[1500ms]" 
         x-transition:leave-start="transform translateY(0) opacity-100" 
         x-transition:leave-end="transform translateY(-100%) opacity-50" 
         class="cover">
        
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>
        <div class="frame-jawa"></div>
        
        <div class="relative z-10 text-center px-6 w-full max-w-md">
            <!-- Wayang Gunungan Icon -->
            <div class="mb-4">
               <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/2/2c/Gunungan_wayang_vector.svg/1200px-Gunungan_wayang_vector.svg.png" 
                    class="w-24 mx-auto drop-shadow-lg filter brightness-150 contrast-125 sepia-100 hue-rotate-5 saturate-200" 
                    style="animation: gamelanPulse 3s infinite alternate;">
            </div>

            <p style="letter-spacing: 4px;" class="uppercase text-white text-xs mb-2">Pernikahan Agung</p>

            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <div class="mb-8">
                <h1 class="font-head text-5xl md:text-6xl text-gold mb-2" style="text-shadow: 0 4px 10px rgba(0,0,0,0.8);">
                    {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                </h1>
                <p class="font-script text-4xl text-white opacity-90">&</p>
                <h1 class="font-head text-5xl md:text-6xl text-gold" style="text-shadow: 0 4px 10px rgba(0,0,0,0.8);">
                    {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                </h1>
            </div>

            <div class="bg-[rgba(45,27,14,0.8)] backdrop-blur-sm p-8 rounded-2xl border border-[var(--java-gold)] shadow-2xl transform transition-all duration-500 hover:scale-105">
                <p class="text-xs uppercase text-[var(--java-cream)] tracking-widest mb-3 opacity-90">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <h3 class="font-head text-2xl mb-6 text-gold">{{ $guestName }}</h3>
                <button @click="open()" class="btn-royal w-full">
                    Buka Undangan
                </button>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <main x-show="opened" class="relative z-0">
        
        <!-- HERO SECTION -->
        <section id="home" class="hero-section">
            <div class="hero-bg absolute inset-0 bg-cover bg-center" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}'); opacity: 0.25;"></div>
            <div class="batik-overlay opacity-30"></div>
            <!-- Gradient Overlay -->
            <div class="absolute inset-0 bg-gradient-to-t from-[var(--java-brown)] via-[rgba(62,39,35,0.5)] to-[var(--java-brown)]"></div>
            
            <div class="relative z-10 w-full max-w-lg mt-10">
                <div class="hero-photo-frame reveal-element">
                    <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}" class="hero-photo-img" alt="Couple">
                </div>

                <div class="reveal-element" style="transition-delay: 0.2s;">
                    <div class="text-gold uppercase tracking-[0.4em] text-[10px] mb-2">The Wedding Of</div>
                    <h1 class="hero-names">
                        {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                        <span class="block font-script text-4xl text-white opacity-80 my-2">&</span>
                        {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
                    </h1>
                </div>

                <div class="mt-8 reveal-element" style="transition-delay: 0.4s;">
                    <div class="inline-block border-t border-b border-gold py-2 px-6">
                        <p class="font-head text-xl text-gold">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                    </div>
                </div>
            </div>

            <!-- Scroll Down Animation -->
            <div class="absolute bottom-10 animate-bounce text-gold opacity-70">
                <svg width="30" height="30" fill="none" class="mx-auto" viewBox="0 0 24 24"><path stroke="currentColor" stroke-width="2" d="M12 5v14M5 12l7 7 7-7"/></svg>
            </div>
        </section>

        <!-- Divider Gunungan Atas -->
        <div class="gunungan-divider bg-white"></div>

        <!-- INTRO -->
        <section class="section text-center">
            <div class="max-w-xl mx-auto reveal-element">
                <h2 class="font-script text-4xl text-brown mb-6">Mukadimah</h2>
                <div class="w-16 h-1 bg-gold mx-auto mb-8"></div>
                
                <p class="font-head text-2xl text-[var(--java-gold-dim)] mb-4">Assalamu'alaikum Wr. Wb.</p>
                <p class="leading-relaxed font-serif italic text-gray-700 text-lg px-4">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                </p>
                <p class="mt-4 font-bold text-xs uppercase tracking-widest text-brown">(QS. Ar-Rum: 21)</p>
            </div>
        </section>

        <!-- COUPLE SECTION -->
        <section id="couple" class="section bg-[#F9F6F0]">
            <div class="text-center mb-16 reveal-element">
                <p class="font-script text-3xl text-gold">Pasangan Mempelai</p>
                <h2 class="font-head text-4xl text-brown mt-2">Bride & Groom</h2>
            </div>

            <div class="max-w-5xl mx-auto grid md:grid-cols-2 gap-16 items-center px-4 relative">
                <!-- Batik Background Circle -->
                <div class="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 w-[500px] h-[500px] bg-[var(--java-gold)] opacity-5 rounded-full blur-3xl pointer-events-none"></div>

                @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
                
                <!-- Person 1 (Wayang Left Animation) -->
                <div class="text-center reveal-element wayang-left">
                    <div class="relative w-48 h-64 mx-auto mb-6">
                        <div class="absolute inset-0 border-[3px] border-gold rounded-[50px_50px_0_0] rotate-3"></div>
                         <div class="absolute inset-0 border-[3px] border-brown rounded-[50px_50px_0_0] -rotate-3 bg-white">
                             @if($order === 'bride_first')
                                <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-full h-full object-cover rounded-[46px_46px_0_0] p-1">
                            @else
                                <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-full h-full object-cover rounded-[46px_46px_0_0] p-1">
                            @endif
                         </div>
                    </div>
                    
                    <h3 class="font-head text-3xl mb-1 text-brown">
                        {{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}
                    </h3>
                    <p class="text-gold font-bold mb-4">Putra/Putri Pertama</p>
                    <div class="text-sm font-serif text-gray-600 leading-relaxed">
                        Putra/Putri dari Bpk. {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} <br> 
                        & Ibu {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}
                    </div>
                </div>

                <!-- Simbol & di tengah (Desktop only) -->
                <div class="hidden md:block absolute left-1/2 top-1/2 transform -translate-x-1/2 -translate-y-1/2 z-20">
                    <div class="w-16 h-16 bg-gold rounded-full flex items-center justify-center text-brown font-script text-4xl border-4 border-white shadow-xl">&</div>
                </div>

                <!-- Person 2 (Wayang Right Animation) -->
                <div class="text-center reveal-element wayang-right" style="transition-delay: 0.3s;">
                    <div class="relative w-48 h-64 mx-auto mb-6">
                        <div class="absolute inset-0 border-[3px] border-gold rounded-[50px_50px_0_0] -rotate-3"></div>
                         <div class="absolute inset-0 border-[3px] border-brown rounded-[50px_50px_0_0] rotate-3 bg-white">
                             @if($order === 'bride_first')
                                <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-full h-full object-cover rounded-[46px_46px_0_0] p-1">
                            @else
                                <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-full h-full object-cover rounded-[46px_46px_0_0] p-1">
                            @endif
                         </div>
                    </div>
                    
                    <h3 class="font-head text-3xl mb-1 text-brown">
                        {{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}
                    </h3>
                    <p class="text-gold font-bold mb-4">Putra/Putri Kedua</p>
                    <div class="text-sm font-serif text-gray-600 leading-relaxed">
                        Putra/Putri dari Bpk. {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} <br> 
                        & Ibu {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}
                    </div>
                </div>
            </div>
        </section>

        <!-- Divider Gunungan Bawah -->
        <div class="gunungan-divider bottom bg-[#F9F6F0]"></div>

        <!-- COUNTDOWN -->
        <section class="py-24 px-4 relative bg-fixed bg-cover bg-center" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');">
            <div class="absolute inset-0 bg-[var(--java-brown)] opacity-80"></div>
            
            <div class="relative z-10 max-w-3xl mx-auto text-center reveal-element">
                <p class="text-gold font-script text-4xl mb-8">Menghitung Waktu</p>
                
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
                    <div class="grid grid-cols-4 gap-4 md:gap-8 mb-10">
                        <!-- Countdown Item -->
                        <div class="bg-[rgba(255,255,255,0.1)] border border-[rgba(212,175,55,0.3)] rounded-lg p-4 backdrop-blur-sm">
                            <div class="font-head text-3xl md:text-5xl text-gold" x-text="days">0</div>
                            <div class="text-xs text-white uppercase tracking-wider mt-2">Hari</div>
                        </div>
                        <div class="bg-[rgba(255,255,255,0.1)] border border-[rgba(212,175,55,0.3)] rounded-lg p-4 backdrop-blur-sm">
                            <div class="font-head text-3xl md:text-5xl text-gold" x-text="hours">0</div>
                            <div class="text-xs text-white uppercase tracking-wider mt-2">Jam</div>
                        </div>
                        <div class="bg-[rgba(255,255,255,0.1)] border border-[rgba(212,175,55,0.3)] rounded-lg p-4 backdrop-blur-sm">
                            <div class="font-head text-3xl md:text-5xl text-gold" x-text="minutes">0</div>
                            <div class="text-xs text-white uppercase tracking-wider mt-2">Menit</div>
                        </div>
                        <div class="bg-[rgba(255,255,255,0.1)] border border-[rgba(212,175,55,0.3)] rounded-lg p-4 backdrop-blur-sm">
                            <div class="font-head text-3xl md:text-5xl text-gold" x-text="seconds">0</div>
                            <div class="text-xs text-white uppercase tracking-wider mt-2">Detik</div>
                        </div>
                    </div>
                    
                    <button @click="saveToCalendar()" class="btn-royal">
                        Simpan Tanggal
                    </button>
                </div>
            </div>
        </section>

        <!-- EVENTS -->
        <section id="events" class="section">
            <div class="section-title text-center mb-16 reveal-element">
                <h2 class="font-head">Rangkaian Acara</h2>
            </div>

            <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8 px-4">
                <!-- Akad Ticket -->
                <div class="event-card reveal-element">
                    <div class="p-8 text-center">
                        <div class="event-time-box">
                            AKAD NIKAH
                        </div>
                        
                        <div class="mb-4">
                            <p class="font-head text-4xl text-brown mb-1">{{ $invitation->akad_date?->format('d') }}</p>
                            <p class="text-gold font-bold uppercase tracking-widest">{{ $invitation->akad_date?->translatedFormat('F Y') }}</p>
                        </div>
                        
                        <div class="font-serif text-gray-600 mb-6">
                            <p class="text-xl italic">{{ $invitation->akad_date?->translatedFormat('l') }}</p>
                            <p>Pukul {{ $invitation->akad_date?->format('H:i') }} WIB</p>
                        </div>
                        
                        <hr class="border-dashed border-gray-300 w-1/2 mx-auto mb-6">
                        
                        <div class="mb-6">
                            <h4 class="font-bold text-brown mb-2">{{ $invitation->akad_venue }}</h4>
                            <p class="text-sm text-gray-500 px-4">{{ $invitation->akad_address }}</p>
                        </div>

                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="inline-block text-gold border-b border-gold pb-1 hover:text-brown transition text-sm font-bold uppercase tracking-wider">
                            Lihat Lokasi
                        </a>
                    </div>
                </div>

                <!-- Resepsi Ticket -->
                @if($invitation->resepsi_date)
                <div class="event-card reveal-element" style="transition-delay: 0.2s;">
                    <div class="p-8 text-center">
                        <div class="event-time-box">
                            RESEPSI
                        </div>
                        
                        <div class="mb-4">
                            <p class="font-head text-4xl text-brown mb-1">{{ $invitation->resepsi_date?->format('d') }}</p>
                            <p class="text-gold font-bold uppercase tracking-widest">{{ $invitation->resepsi_date?->translatedFormat('F Y') }}</p>
                        </div>
                        
                        <div class="font-serif text-gray-600 mb-6">
                            <p class="text-xl italic">{{ $invitation->resepsi_date?->translatedFormat('l') }}</p>
                            <p>Pukul {{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</p>
                        </div>
                        
                        <hr class="border-dashed border-gray-300 w-1/2 mx-auto mb-6">
                        
                        <div class="mb-6">
                            <h4 class="font-bold text-brown mb-2">{{ $invitation->resepsi_venue }}</h4>
                            <p class="text-sm text-gray-500 px-4">{{ $invitation->resepsi_address }}</p>
                        </div>

                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="inline-block text-gold border-b border-gold pb-1 hover:text-brown transition text-sm font-bold uppercase tracking-wider">
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
            <div class="section-title text-center mb-12 reveal-element">
                <p class="font-script text-2xl text-gold">Momen Indah</p>
                <h2 class="font-head">Galeri Foto</h2>
            </div>
            
            <div x-data="{ 
                lightboxOpen: false, 
                imgSrc: '', 
                caption: '',
                open(url, cap) { this.imgSrc = url; this.caption = cap; this.lightboxOpen = true; },
                close() { this.lightboxOpen = false; }
            }">
                <div class="columns-2 md:columns-3 gap-4 space-y-4 px-4 max-w-6xl mx-auto">
                    @foreach($invitation->photos as $photo)
                    <div class="break-inside-avoid relative group cursor-pointer reveal-element"
                         @click="open('{{ asset('storage/' . $photo) }}', '')">
                        <!-- Frame Foto -->
                        <div class="p-2 bg-white shadow-md border border-[var(--java-gold)] transform transition duration-500 group-hover:rotate-1">
                            <img src="{{ asset('storage/' . $photo) }}" alt="Gallery" class="w-full h-auto object-cover grayscale group-hover:grayscale-0 transition duration-700">
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Lightbox -->
                <div x-show="lightboxOpen" class="fixed inset-0 z-[100] bg-black/95 flex items-center justify-center p-4"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0"
                     x-transition:enter-end="opacity-100"
                     @click.self="close()">
                    <button @click="close()" class="absolute top-4 right-4 text-white hover:text-gold">
                        <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <img :src="imgSrc" class="max-h-[90vh] max-w-full border-4 border-gold shadow-2xl">
                </div>
            </div>
        </section>
        @endif

        <!-- GIFT -->
        @if($invitation->enable_gift)
        <section id="gift" class="section">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-12 reveal-element">
                    <h2 class="font-head text-3xl mb-4 text-brown">Tanda Kasih</h2>
                    <p class="text-gray-600 font-serif italic leading-relaxed px-6">
                        Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika memberi adalah ungkapan tanda kasih, Anda dapat memberi kado secara cashless.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-6 px-4">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="bg-white p-6 rounded-lg border border-gold shadow-lg reveal-element hover:shadow-xl transition relative overflow-hidden" x-data="{ copied: false }">
                        <!-- Watermark -->
                        <div class="absolute -right-4 -bottom-4 text-9xl text-gray-50 opacity-50 font-head pointer-events-none">Rp</div>
                        
                        <div class="relative z-10">
                            <p class="font-head text-xl text-brown mb-2">{{ $account['bank'] }}</p>
                            <p class="font-mono text-xl font-bold text-gray-800 tracking-wider mb-2">{{ $account['account_number'] }}</p>
                            <p class="text-sm text-gray-500 mb-6 uppercase tracking-wide">a.n {{ $account['account_name'] }}</p>
                            
                            <button @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied = true; setTimeout(() => copied = false, 2000)" 
                                    class="bg-brown text-gold px-6 py-2 rounded-full text-xs uppercase tracking-widest hover:bg-gold hover:text-brown transition">
                                <span x-text="copied ? 'Berhasil Disalin' : 'Salin Nomor'"></span>
                            </button>
                        </div>
                    </div>
                    @endforeach
                @endif
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP & WISHES -->
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section bg-[#2D1B0E] text-[#F9F6F0]">
            <div class="section-title text-center mb-10 reveal-element">
                <h2 class="font-head text-gold">Doa & Kehadiran</h2>
            </div>

            <div class="max-w-xl mx-auto bg-[rgba(255,255,255,0.05)] backdrop-blur-md p-8 rounded-xl border border-[rgba(212,175,55,0.3)] reveal-element">
                <!-- Livewire Form -->
                <form wire:submit="submitRSVP" class="space-y-6 mb-12">
                    @if (session()->has('message'))
                        <div class="bg-gold text-brown p-4 rounded text-center font-bold animate-pulse">
                            {{ session('message') }}
                        </div>
                    @endif

                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gold">Nama Lengkap</label>
                        <input type="text" wire:model="rsvpName" class="w-full bg-transparent border-b border-gray-500 focus:border-gold outline-none py-2 text-white transition text-lg placeholder-gray-600" placeholder="Masukkan nama Anda">
                        @error('rsvpName') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gold">Ucapan & Doa</label>
                        <textarea wire:model="rsvpMessage" rows="3" class="w-full bg-transparent border-b border-gray-500 focus:border-gold outline-none py-2 text-white transition text-lg placeholder-gray-600" placeholder="Tuliskan doa restu..."></textarea>
                        @error('rsvpMessage') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>

                    <div class="space-y-2">
                        <label class="text-xs uppercase tracking-widest text-gold mb-2 block">Konfirmasi</label>
                        <div class="flex gap-4">
                            <button type="button" wire:click="$set('rsvpStatus', 'confirmed')" 
                                    class="flex-1 py-3 border rounded transition duration-300 font-bold {{ $rsvpStatus === 'confirmed' ? 'bg-gold text-brown border-gold shadow-[0_0_15px_#D4AF37]' : 'bg-transparent text-gray-400 border-gray-600' }}">
                                Hadir
                            </button>
                            <button type="button" wire:click="$set('rsvpStatus', 'declined')" 
                                    class="flex-1 py-3 border rounded transition duration-300 font-bold {{ $rsvpStatus === 'declined' ? 'bg-red-800 text-white border-red-800' : 'bg-transparent text-gray-400 border-gray-600' }}">
                                Maaf
                            </button>
                        </div>
                    </div>

                    @if($rsvpStatus === 'confirmed')
                    <div class="space-y-2" x-transition>
                        <label class="text-xs uppercase tracking-widest text-gold">Jumlah Tamu</label>
                        <select wire:model="rsvpGuests" class="w-full bg-transparent border-b border-gray-500 focus:border-gold outline-none py-2 text-white">
                            <option value="1" class="text-black">1 Orang</option>
                            <option value="2" class="text-black">2 Orang</option>
                            <option value="3" class="text-black">3 Orang</option>
                            <option value="4" class="text-black">4 Orang</option>
                            <option value="5" class="text-black">5 Orang</option>
                        </select>
                    </div>
                    @endif

                    <button type="submit" class="btn-royal w-full mt-4">
                        <span wire:loading.remove>Kirim Ucapan</span>
                        <span wire:loading>Sedang Mengirim...</span>
                    </button>
                </form>

                <!-- Wishes Feed -->
                <div class="border-t border-[rgba(255,255,255,0.1)] pt-8">
                    <p class="font-head text-center text-xl mb-6 text-gold">Latest Wishes</p>
                    <div class="max-h-80 overflow-y-auto space-y-4 pr-2 custom-scroll">
                        @forelse($invitation->wishes()->latest()->get() as $wish)
                            <div class="bg-[rgba(255,255,255,0.05)] p-4 rounded border-l-2 border-gold">
                                <div class="flex justify-between items-start mb-2">
                                    <h4 class="font-bold text-gold">{{ $wish->name }}</h4>
                                    <span class="text-[10px] text-gray-400">{{ $wish->created_at->diffForHumans() }}</span>
                                </div>
                                <p class="text-sm text-gray-300 italic">"{{ $wish->message }}"</p>
                                <div class="mt-2 text-right">
                                    <span class="text-[10px] px-2 py-1 rounded border border-gray-600 text-gray-400 uppercase tracking-wider">
                                        {{ $wish->status }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <div class="text-center text-gray-500 py-4 text-sm italic">
                                Belum ada ucapan. Jadilah yang pertama.
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- FOOTER -->
        <footer class="bg-[#201309] text-[var(--java-cream)] py-12 text-center pb-32 border-t border-[rgba(212,175,55,0.1)]">
            <h2 class="font-head text-3xl mb-2 text-gold opacity-80">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p class="font-serif italic text-sm opacity-50 mb-8">Terima kasih atas doa & restu Anda</p>
            <div class="w-10 h-10 mx-auto border border-gold rounded-full flex items-center justify-center opacity-30 text-gold">
                <span class="font-script text-lg">W</span>
            </div>
        </footer>

    </main>

    <!-- MUSIC GONG -->
    <div x-show="opened" @click="toggleAudio()" class="music-gong" :class="{ 'playing': playing }">
        <!-- Icon Speaker -->
        <svg x-show="playing" class="w-6 h-6 text-[#2D1B0E]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 3v10.55c-.59-.34-1.27-.55-2-.55-2.21 0-4 1.79-4 4s1.79 4 4 4 4-1.79 4-4V7h4V3h-4z"/></svg>
        <svg x-show="!playing" class="w-6 h-6 text-[#2D1B0E]" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73 4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
    </div>

    <!-- FLOATING NAV -->
    <nav x-show="opened" class="royal-nav" 
         x-transition:enter="transition ease-out duration-700" 
         x-transition:enter-start="translate-y-20 opacity-0" 
         x-transition:enter-end="translate-y-0 opacity-100">
        
        <a @click.prevent="scrollTo('home')" :class="{ 'active': activeSection === 'home' }">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        </a>
        <a @click.prevent="scrollTo('couple')" :class="{ 'active': activeSection === 'couple' }">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        </a>
        <a @click.prevent="scrollTo('events')" :class="{ 'active': activeSection === 'events' }">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
        </a>
        @if($invitation->enable_gift)
        <a @click.prevent="scrollTo('gift')" :class="{ 'active': activeSection === 'gift' }">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
        </a>
        @endif
        @if($invitation->enable_rsvp)
        <a @click.prevent="scrollTo('rsvp')" :class="{ 'active': activeSection === 'rsvp' }">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
        </a>
        @endif
    </nav>
</div>