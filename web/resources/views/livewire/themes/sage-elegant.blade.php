<div x-data="{
    opened: false,
    audioPlaying: false,
    audioElement: null,
    activeSection: 'home',
    guestName: '{{ $guestName ?? 'Tamu Undangan' }}',
    days: '00', hours: '00', minutes: '00', seconds: '00',

    init() {
        // Audio Setup
        this.audioElement = document.getElementById('bgMusic');

        // AOS Init
        setTimeout(() => {
            if (window.AOS) AOS.init({ duration: 1000, once: true });
        }, 100);

        // Countdown Logic
        const targetDate = new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}').getTime();
        setInterval(() => {
            const now = new Date().getTime();
            const distance = targetDate - now;
            if (distance > 0) {
                this.days = String(Math.floor(distance / (1000 * 60 * 60 * 24))).padStart(2, '0');
                this.hours = String(Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                this.minutes = String(Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                this.seconds = String(Math.floor((distance % (1000 * 60)) / 1000)).padStart(2, '0');
            }
        }, 1000);

        // Scroll Spy Logic
        window.addEventListener('scroll', () => {
            const sections = ['home', 'couple', 'event', 'gallery', 'rsvp'];
            const scrollPos = window.scrollY + 100;
            for (const section of sections) {
                const el = document.getElementById(section);
                if (el && el.offsetTop <= scrollPos && (el.offsetTop + el.offsetHeight) > scrollPos) {
                    this.activeSection = section;
                }
            }
        });
    },

    openInvitation() {
        this.opened = true;
        document.body.style.overflowY = 'auto';
        if (this.audioElement) {
            this.audioElement.play().then(() => { 
                this.audioPlaying = true; 
            }).catch(e => console.log('Auto-play prevented', e));
        }
        setTimeout(() => { if (window.AOS) AOS.refresh(); }, 500);
    },

    toggleMusic() {
        if (!this.audioElement) return;
        if (this.audioPlaying) {
            this.audioElement.pause();
            this.audioPlaying = false;
        } else {
            this.audioElement.play();
            this.audioPlaying = true;
        }
    },

    copyText(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor Rekening berhasil disalin');
    }
}" x-init="init()">

    @section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

    @push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;600&family=Great+Vibes&family=Nunito+Sans:wght@300;400;700&display=swap" rel="stylesheet">

    <style>
        :root {
            /* TEMA BIRU / UNGU (Royal Blue & Lavender) */
            --primary-color: #4834d4; /* Royal Purple/Blue */
            --primary-light: #686de0; /* Soft Blue */
            --secondary-color: #f3f4f6; /* Light Gray/Blueish */
            --accent-color: #f0932b; /* Gold Accent */
            --text-color: #2c3e50;
        }

        body {
            font-family: 'Nunito Sans', sans-serif;
            color: var(--text-color);
            background-color: var(--secondary-color);
            overflow-x: hidden;
        }

        /* Typography */
        .font-script { font-family: 'Great Vibes', cursive; }
        .font-serif { font-family: 'Cinzel', serif; }

        /* Custom Colors & Utilities */
        .text-primary-custom { color: var(--primary-color) !important; }
        .bg-primary-custom { background-color: var(--primary-color) !important; color: white; }
        .bg-light-custom { background-color: #e2e1fa !important; } /* Lavender tint */

        /* Buttons */
        .btn-custom {
            background: linear-gradient(135deg, var(--primary-color), var(--primary-light));
            color: white;
            border-radius: 50px;
            padding: 12px 35px;
            border: none;
            box-shadow: 0 4px 15px rgba(72, 52, 212, 0.3);
            transition: all 0.3s;
        }
        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(72, 52, 212, 0.4);
            color: white;
        }

        /* Cover Screen Overlay */
        #cover-screen {
            position: fixed;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #fff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: transform 1s cubic-bezier(0.77, 0, 0.175, 1);
            background-image: url('https://www.transparenttextures.com/patterns/diamond-upholstery.png');
        }

        /* ORNAMENTS */
        .flower-ornament {
            position: absolute;
            width: 150px;
            pointer-events: none;
            z-index: 1;
        }
        .ornament-tl { top: 0; left: 0; }
        .ornament-tr { top: 0; right: 0; transform: scaleX(-1); }
        .ornament-bl { bottom: 0; left: 0; } 
        .ornament-br { bottom: 0; right: 0; transform: scaleX(-1); }

        /* ANIMASI CAPUNG */
        .dragonfly {
            position: absolute;
            width: 65px;
            top: 30px;
            right: 30px;
            z-index: 10;
            opacity: 0;
            /* Terbang selama 3 detik, setelah itu tetap di tempat */
            animation: fly-in 3s cubic-bezier(0.25, 1, 0.5, 1) forwards;
            transform-origin: center;
        }

        @keyframes fly-in {
            0% { transform: translate(-80vw, 80vh) rotate(-60deg) scale(0.5); opacity: 0; }
            20% { opacity: 1; }
            60% { transform: translate(-60px, 40px) rotate(20deg) scale(1.2); }
            100% { transform: translate(0, 0) rotate(-35deg) scale(1); opacity: 1; }
        }

        /* Animasi sayap mengepak */
        .wing-l {
            transform-origin: right center;
            animation: flap-l 0.05s infinite alternate;
        }
        .wing-r {
            transform-origin: left center;
            animation: flap-r 0.05s infinite alternate;
        }

        @keyframes flap-l {
            0% { transform: rotateY(0deg) skewY(0deg); }
            100% { transform: rotateY(40deg) skewY(-10deg); }
        }
        @keyframes flap-r {
            0% { transform: rotateY(0deg) skewY(0deg); }
            100% { transform: rotateY(-40deg) skewY(10deg); }
        }

        /* Profile Image */
        .couple-frame {
            padding: 5px;
            border: 2px dashed var(--primary-light);
            border-radius: 50%;
            display: inline-block;
        }
        .couple-img {
            width: 180px;
            height: 180px;
            object-fit: cover;
            border-radius: 50%;
            border: 5px solid white;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
        }

        /* Cards */
        .card-custom {
            border: none;
            border-radius: 20px;
            background: white;
            box-shadow: 0 10px 30px rgba(0,0,0,0.05);
            overflow: hidden;
            border-top: 5px solid var(--primary-color);
        }

        /* Countdown Box */
        .countdown-box {
            background: var(--primary-color);
            color: white;
            padding: 15px 10px;
            border-radius: 10px;
            min-width: 80px;
            box-shadow: 0 5px 15px rgba(72, 52, 212, 0.2);
        }

        /* Floating Controls */
        .floating-btn {
            position: fixed;
            bottom: 90px;
            right: 20px;
            width: 50px;
            height: 50px;
            background: white;
            color: var(--primary-color);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 15px rgba(0,0,0,0.15);
            z-index: 1040;
            border: 2px solid var(--primary-light);
            cursor: pointer;
            transition: all 0.3s;
        }
        .spin { animation: spin 4s linear infinite; }
        @keyframes spin { 100% { transform: rotate(360deg); } }

        /* Bottom Navbar */
        .navbar-bottom {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(10px);
            border-top: 1px solid #ddd;
            padding-bottom: env(safe-area-inset-bottom);
            z-index: 1030;
        }
        .nav-link {
            color: #999;
            font-size: 0.7rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            transition: color 0.3s;
            text-decoration: none;
        }
        .nav-link.active, .nav-link:hover { color: var(--primary-color); }
        .nav-link i { font-size: 1.2rem; margin-bottom: 3px; }

        /* Gallery */
        .gallery-item {
            overflow: hidden;
            border-radius: 10px;
            cursor: pointer;
        }
        .gallery-item img {
            width: 100%;
            height: 200px;
            object-fit: cover;
            transition: transform 0.5s;
        }
        .gallery-item:hover img { transform: scale(1.1); }

        /* ============================== */
        /* ANIMATED BACKGROUNDS           */
        /* ============================== */

        /* Floating Orbs */
        .bg-orb {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
            filter: blur(60px);
        }
        @keyframes orb-float {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(20px, -30px) scale(1.1); }
            50% { transform: translate(-15px, -10px) scale(0.95); }
            75% { transform: translate(10px, 20px) scale(1.05); }
        }
        @keyframes orb-float-reverse {
            0%, 100% { transform: translate(0, 0) scale(1); }
            25% { transform: translate(-20px, 20px) scale(1.05); }
            50% { transform: translate(15px, -15px) scale(0.9); }
            75% { transform: translate(-10px, -25px) scale(1.1); }
        }

        /* Floating Particles */
        .bg-particle {
            position: absolute;
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }
        @keyframes particle-drift {
            0%, 100% { transform: translateY(0) translateX(0); opacity: 0.4; }
            25% { transform: translateY(-20px) translateX(8px); opacity: 0.8; }
            50% { transform: translateY(-10px) translateX(-5px); opacity: 0.6; }
            75% { transform: translateY(-25px) translateX(12px); opacity: 1; }
        }

        /* Sparkle dots */
        @keyframes sparkle-blink {
            0%, 100% { opacity: 0; transform: scale(0); }
            50% { opacity: 1; transform: scale(1); }
        }
        .bg-sparkle {
            position: absolute;
            width: 4px; height: 4px;
            background: var(--primary-light);
            border-radius: 50%;
            pointer-events: none;
            z-index: 0;
        }

        /* Wave Separator */
        .wave-separator {
            position: relative;
            width: 100%;
            overflow: hidden;
            line-height: 0;
            margin-top: -1px;
        }
        .wave-separator svg {
            display: block;
            width: 100%;
            height: 50px;
        }
        .wave-separator.flip {
            transform: rotate(180deg);
            margin-bottom: -1px;
            margin-top: 0;
        }

        /* Section relative positioning */
        .section-animated {
            position: relative;
            overflow: hidden;
        }

        /* Heartbeat for heart icon */
        @keyframes heartbeat {
            0%, 100% { transform: scale(1); }
            15% { transform: scale(1.2); }
            30% { transform: scale(1); }
            45% { transform: scale(1.15); }
            60% { transform: scale(1); }
        }
        .animate-heartbeat {
            animation: heartbeat 2s ease-in-out infinite;
        }

        /* Gradient shimmer for dividers */
        @keyframes shimmer-line {
            0% { background-position: -200% 0; }
            100% { background-position: 200% 0; }
        }
        .shimmer-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--primary-light), var(--primary-color), var(--primary-light), transparent);
            background-size: 200% 100%;
            animation: shimmer-line 3s linear infinite;
        }

        /* Responsive text */
        @media (max-width: 576px) {
            h1.font-script { font-size: 3rem; }
            .flower-ornament { width: 100px; }
            .bg-orb { filter: blur(40px); }
        }
    </style>
    @endpush

    @if($invitation->background_music)
    <audio id="bgMusic" loop preload="auto">
        <source src="{{ str_starts_with($invitation->background_music, 'http') ? $invitation->background_music : img_url($invitation->background_music) }}" type="audio/mpeg">
    </audio>
    @endif

    <section id="cover-screen" x-show="!opened" x-transition:leave="transition ease-in duration-1000" x-transition:leave-start="transform translate-y-0" x-transition:leave-end="transform -translate-y-full" style="background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://www.transparenttextures.com/patterns/diamond-upholstery.png' }}'); background-size: cover; background-position: center;">
        
        <!-- Dark overlay to enhance text readability while keeping the photo clear -->
        <div style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(0, 0, 0, 0.5); z-index: 0;"></div>

        <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" class="flower-ornament ornament-tl z-1" alt="flower" style="opacity: 0.9;">
        <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" class="flower-ornament ornament-tr z-1" alt="flower" style="opacity: 0.9;">
        
        <svg class="dragonfly z-1" viewBox="0 0 100 100" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M48,15 C48,15 50,10 52,15 C55,40 53,85 50,85 C47,85 45,40 48,15 Z" fill="#2c3e50"/>
            <circle cx="48.5" cy="16" r="2.5" fill="#f0932b"/>
            <circle cx="51.5" cy="16" r="2.5" fill="#f0932b"/>
            <path class="wing-l" d="M49,25 C30,15 10,20 15,35 C20,45 47,35 49,25 Z" fill="rgba(104, 109, 224, 0.7)"/>
            <path class="wing-l" d="M48,35 C25,35 5,45 10,55 C15,65 46,50 48,35 Z" fill="rgba(104, 109, 224, 0.5)"/>
            <path class="wing-r" d="M51,25 C70,15 90,20 85,35 C80,45 53,35 51,25 Z" fill="rgba(104, 109, 224, 0.7)"/>
            <path class="wing-r" d="M52,35 C75,35 95,45 90,55 C85,65 54,50 52,35 Z" fill="rgba(104, 109, 224, 0.5)"/>
        </svg>

        <div class="text-center px-4 position-relative z-2" data-aos="zoom-in" style="width: 100%;">
            <div class="mb-4 text-white ls-2 text-uppercase fw-bold small" style="text-shadow: 1px 1px 3px rgba(0,0,0,0.8);">{{ $invitation->custom_styles['cover_subtitle'] ?? 'THE WEDDING OF' }}</div>
            
            <div class="position-relative d-flex flex-column justify-content-center align-items-center mb-4" style="width: 280px; height: 280px; margin: 0 auto;">
                <div class="position-absolute top-0 start-0 w-100 h-100 border border-2 border-white rounded-circle" style="z-index: -1; opacity: 0.6; box-shadow: 0 0 15px rgba(255,255,255,0.2);"></div>
                <h1 class="font-script text-white mb-0 lh-1" style="font-size: 3.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.8);">{{ $invitation->groom_nickname }}</h1>
                <h1 class="font-script display-5 text-white my-2 lh-1" style="text-shadow: 2px 2px 5px rgba(0,0,0,0.8);">&</h1>
                <h1 class="font-script text-white mb-0 lh-1" style="font-size: 3.5rem; text-shadow: 2px 2px 5px rgba(0,0,0,0.8);">{{ $invitation->bride_nickname }}</h1>
            </div>

            <div class="mt-2 p-4 rounded-4 shadow-lg border border-light" style="max-width: 320px; margin: 0 auto; background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(10px);">
                <p class="mb-1 text-muted small fw-bold">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                <div class="divider my-3 bg-primary opacity-25" style="height: 1px;"></div>
                <h4 class="font-serif fw-bold text-dark mb-3" x-text="guestName">Tamu Undangan</h4>
                <button @click="openInvitation()" class="btn btn-custom w-100 font-serif fw-bold" style="letter-spacing: 1px;">
                    <i class="fas fa-envelope-open-text me-2"></i> Buka Undangan
                </button>
            </div>
        </div>

        <img src="{{ asset('assets/themes/bunga_ungu_putih_pinggir_bawah.png') }}" class="flower-ornament ornament-bl z-1" alt="flower" style="opacity: 0.9;">
        <img src="{{ asset('assets/themes/bunga_ungu_putih_pinggir_bawah.png') }}" class="flower-ornament ornament-br z-1" alt="flower" style="opacity: 0.9;">
    </section>

    <div id="main-content" x-show="opened" style="display:none;">
        
        <!-- 2. HERO SECTION -->
        <section id="home" class="min-vh-100 d-flex align-items-center justify-content-center position-relative pt-5 bg-light-custom section-animated">
            <!-- Animated Background Orbs -->
            <div class="bg-orb" style="width: 300px; height: 300px; background: rgba(72,52,212,0.08); top: -80px; right: -80px; animation: orb-float 12s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 250px; height: 250px; background: rgba(104,109,224,0.06); bottom: -60px; left: -60px; animation: orb-float-reverse 15s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 180px; height: 180px; background: rgba(240,147,43,0.05); top: 40%; left: 50%; animation: orb-float 10s ease-in-out infinite 2s;"></div>

            <!-- Floating Particles -->
            <div class="bg-particle" style="width: 6px; height: 6px; background: rgba(72,52,212,0.3); top: 15%; left: 12%; animation: particle-drift 7s ease-in-out infinite;"></div>
            <div class="bg-particle" style="width: 4px; height: 4px; background: rgba(104,109,224,0.25); top: 25%; right: 18%; animation: particle-drift 9s ease-in-out infinite 1s;"></div>
            <div class="bg-particle" style="width: 5px; height: 5px; background: rgba(240,147,43,0.2); top: 60%; left: 80%; animation: particle-drift 8s ease-in-out infinite 2s;"></div>
            <div class="bg-particle" style="width: 3px; height: 3px; background: rgba(72,52,212,0.2); top: 75%; left: 25%; animation: particle-drift 6s ease-in-out infinite 3s;"></div>

            <!-- Sparkle dots -->
            <div class="bg-sparkle" style="top: 20%; left: 30%; animation: sparkle-blink 4s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="top: 50%; right: 15%; animation: sparkle-blink 4s ease-in-out infinite 1.5s;"></div>
            <div class="bg-sparkle" style="top: 70%; left: 65%; animation: sparkle-blink 4s ease-in-out infinite 3s;"></div>

            <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" class="flower-ornament ornament-tl" alt="flower">
            <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" class="flower-ornament ornament-tr" alt="flower">

            <div class="container text-center position-relative z-1" data-aos="fade-up">
                <p class="text-uppercase text-muted small fw-bold mb-2">We Are Getting Married</p>
                <h1 class="font-script text-primary-custom display-1 mb-2">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h1>
                <p class="font-serif fs-4 text-dark mb-4">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                
                <div class="mt-4">
                    <div class="couple-frame p-2 bg-white shadow">
                        <img src="{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1621621667797-e06afc217fb0?w=600' }}" class="couple-img object-fit-cover" alt="Couple">
                    </div>
                </div>

                <div class="row justify-content-center mt-5">
                    <div class="col-auto d-flex gap-2">
                        <div class="countdown-box">
                            <h2 class="mb-0 fw-bold" x-text="days">00</h2>
                            <small class="text-uppercase" style="font-size: 0.6rem;">Hari</small>
                        </div>
                        <div class="countdown-box">
                            <h2 class="mb-0 fw-bold" x-text="hours">00</h2>
                            <small class="text-uppercase" style="font-size: 0.6rem;">Jam</small>
                        </div>
                        <div class="countdown-box">
                            <h2 class="mb-0 fw-bold" x-text="minutes">00</h2>
                            <small class="text-uppercase" style="font-size: 0.6rem;">Menit</small>
                        </div>
                        <div class="countdown-box">
                            <h2 class="mb-0 fw-bold" x-text="seconds">00</h2>
                            <small class="text-uppercase" style="font-size: 0.6rem;">Detik</small>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wave: Hero → Quote -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,50 720,0 1080,25 C1260,37.5 1350,37.5 1440,25 L1440,50 L0,50 Z" fill="white"></path>
            </svg>
        </div>

        <!-- 3. QUOTE SECTION -->
        <section class="py-5 bg-white section-animated">
            <!-- Subtle orbs -->
            <div class="bg-orb" style="width: 200px; height: 200px; background: rgba(72,52,212,0.04); top: -50px; left: -50px; animation: orb-float 14s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 150px; height: 150px; background: rgba(240,147,43,0.04); bottom: -30px; right: -30px; animation: orb-float-reverse 11s ease-in-out infinite;"></div>

            <div class="container text-center py-5 position-relative z-1">
                <div class="row justify-content-center">
                    <div class="col-lg-8" data-aos="fade-up">
                        <div class="mb-4 text-primary-custom fs-1"><i class="fas fa-heart animate-heartbeat"></i></div>
                        <div class="shimmer-divider mx-auto mb-4" style="width: 80px;"></div>
                        <p class="font-serif fs-5 text-muted fst-italic lh-lg">
                            "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang."
                        </p>
                        <p class="fw-bold text-primary-custom mt-3 ls-1">QS. Ar-Rum: 21</p>
                        <div class="shimmer-divider mx-auto mt-4" style="width: 80px;"></div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wave: Quote → Couple -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,50 720,0 1080,25 C1260,37.5 1350,37.5 1440,25 L1440,50 L0,50 Z" fill="#e2e1fa"></path>
            </svg>
        </div>

        <!-- 4. COUPLE SECTION -->
        <section id="couple" class="py-5 bg-light-custom position-relative section-animated">
            <!-- Animated background -->
            <div class="position-absolute top-0 start-0 w-100 h-100" style="background-image: url('https://www.transparenttextures.com/patterns/cubes.png'); opacity: 0.3;"></div>
            <div class="bg-orb" style="width: 280px; height: 280px; background: rgba(72,52,212,0.06); top: 10%; right: -60px; animation: orb-float 13s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 220px; height: 220px; background: rgba(104,109,224,0.05); bottom: 5%; left: -40px; animation: orb-float-reverse 16s ease-in-out infinite;"></div>

            <!-- Particles -->
            <div class="bg-particle" style="width: 5px; height: 5px; background: rgba(72,52,212,0.2); top: 20%; left: 8%; animation: particle-drift 8s ease-in-out infinite;"></div>
            <div class="bg-particle" style="width: 4px; height: 4px; background: rgba(240,147,43,0.2); top: 50%; right: 10%; animation: particle-drift 7s ease-in-out infinite 2s;"></div>
            <div class="bg-particle" style="width: 3px; height: 3px; background: rgba(104,109,224,0.15); bottom: 20%; left: 30%; animation: particle-drift 9s ease-in-out infinite 1s;"></div>

            <div class="container py-4 position-relative z-1">
                <div class="text-center mb-5" data-aos="fade-down">
                    <h2 class="font-script text-primary-custom display-4">Mempelai</h2>
                    <div class="shimmer-divider mx-auto my-3" style="width: 60px;"></div>
                    <p class="text-muted">Dengan memohon Ridho Allah SWT, kami bermaksud menyelenggarakan pernikahan kami:</p>
                </div>

                <div class="row g-5 justify-content-center align-items-center">
                    <div class="col-md-5 text-center" data-aos="fade-right">
                        <div class="couple-frame mb-3 bg-white">
                            <img src="{{ $invitation->groom_photo ? img_url($invitation->groom_photo) : 'https://placehold.co/400x400' }}" class="couple-img" alt="Groom">
                        </div>
                        <h2 class="font-script text-primary-custom">{{ $invitation->groom_name }}</h2>
                        <p class="text-muted mb-0">Putra dari Bpk. {{ $invitation->groom_father }}</p>
                        <p class="text-muted mb-3">& Ibu {{ $invitation->groom_mother }}</p>
                        @if($invitation->groom_instagram)
                        <a href="https://instagram.com/{{ $invitation->groom_instagram }}" class="btn btn-sm btn-custom text-white rounded-pill"><i class="fab fa-instagram"></i> @ {{ $invitation->groom_instagram }}</a>
                        @endif
                    </div>

                    <div class="col-md-1 text-center d-none d-md-block">
                        <div class="font-script display-3 text-secondary">&</div>
                    </div>

                    <div class="col-md-5 text-center" data-aos="fade-left">
                        <div class="couple-frame mb-3 bg-white">
                            <img src="{{ $invitation->bride_photo ? img_url($invitation->bride_photo) : 'https://placehold.co/400x400' }}" class="couple-img" alt="Bride">
                        </div>
                        <h2 class="font-script text-primary-custom">{{ $invitation->bride_name }}</h2>
                        <p class="text-muted mb-0">Putri dari Bpk. {{ $invitation->bride_father }}</p>
                        <p class="text-muted mb-3">& Ibu {{ $invitation->bride_mother }}</p>
                        @if($invitation->bride_instagram)
                        <a href="https://instagram.com/{{ $invitation->bride_instagram }}" class="btn btn-sm btn-custom text-white rounded-pill"><i class="fab fa-instagram"></i> @ {{ $invitation->bride_instagram }}</a>
                        @endif
                    </div>
                </div>
            </div>
        </section>

        <!-- Wave: Couple → Event -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,0 720,50 1080,25 C1260,12.5 1350,12.5 1440,25 L1440,50 L0,50 Z" fill="white"></path>
            </svg>
        </div>

        <!-- 5. EVENTS SECTION -->
        <section id="event" class="py-5 bg-white section-animated">
            <!-- Background decoration -->
            <div class="bg-orb" style="width: 250px; height: 250px; background: rgba(72,52,212,0.04); top: -40px; left: -60px; animation: orb-float 11s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 200px; height: 200px; background: rgba(240,147,43,0.04); bottom: -40px; right: -50px; animation: orb-float-reverse 14s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="top: 30%; right: 10%; animation: sparkle-blink 5s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="bottom: 25%; left: 15%; animation: sparkle-blink 5s ease-in-out infinite 2s;"></div>

            <div class="container py-4 position-relative z-1">
                <div class="text-center mb-5" data-aos="fade-up">
                    <span class="badge bg-primary-custom rounded-pill px-3 py-2 mb-2">SAVE THE DATE</span>
                    <h2 class="font-serif fw-bold display-6">Rangkaian Acara</h2>
                    <div class="shimmer-divider mx-auto mt-3" style="width: 60px;"></div>
                </div>

                <div class="row justify-content-center g-4">
                    <div class="col-md-6 col-lg-5" data-aos="flip-left">
                        <div class="card card-custom text-center p-4 h-100">
                            <div class="card-body">
                                <div class="icon-box text-primary-custom fs-1 mb-3"><i class="fas fa-hand-holding-heart"></i></div>
                                <h3 class="font-script fs-1 mb-2">Akad Nikah</h3>
                                <p class="fw-bold text-muted mb-4">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                                
                                <div class="bg-light-custom p-3 rounded mb-3">
                                    <p class="mb-0 fw-bold text-primary-custom"><i class="far fa-clock me-2"></i>{{ $invitation->akad_date?->format('H:i') }} WIB - Selesai</p>
                                </div>
                                
                                <p class="mb-0 fw-bold">{{ $invitation->akad_venue }}</p>
                                <p class="text-muted small">Venue Akad</p>
                                
                                <a href="{{ $invitation->akad_maps_link }}" class="btn btn-outline-primary btn-sm rounded-pill mt-3 w-100">
                                    <i class="fas fa-map-marked-alt me-2"></i> Google Maps
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-5" data-aos="flip-right">
                        <div class="card card-custom text-center p-4 h-100">
                            <div class="card-body">
                                <div class="icon-box text-primary-custom fs-1 mb-3"><i class="fas fa-glass-cheers"></i></div>
                                <h3 class="font-script fs-1 mb-2">Resepsi</h3>
                                <p class="fw-bold text-muted mb-4">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</p>
                                
                                <div class="bg-light-custom p-3 rounded mb-3">
                                    <p class="mb-0 fw-bold text-primary-custom"><i class="far fa-clock me-2"></i>{{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</p>
                                </div>
                                
                                <p class="mb-0 fw-bold">{{ $invitation->resepsi_venue }}</p>
                                <p class="text-muted small">Venue Resepsi</p>
                                
                                <a href="{{ $invitation->resepsi_maps_link }}" class="btn btn-outline-primary btn-sm rounded-pill mt-3 w-100">
                                    <i class="fas fa-map-marked-alt me-2"></i> Google Maps
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Wave: Event → Gallery -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,50 720,0 1080,25 C1260,37.5 1350,37.5 1440,25 L1440,50 L0,50 Z" fill="#e2e1fa"></path>
            </svg>
        </div>

        <!-- 6. GALLERY -->
        @if($invitation->enable_gallery)
        <section id="gallery" class="py-5 bg-light-custom position-relative section-animated">
            <!-- Animated bg -->
            <div class="bg-orb" style="width: 220px; height: 220px; background: rgba(72,52,212,0.05); top: -50px; right: -40px; animation: orb-float 12s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 180px; height: 180px; background: rgba(240,147,43,0.04); bottom: -30px; left: -30px; animation: orb-float-reverse 10s ease-in-out infinite;"></div>
            <div class="bg-particle" style="width: 5px; height: 5px; background: rgba(72,52,212,0.2); top: 20%; right: 15%; animation: particle-drift 8s ease-in-out infinite;"></div>
            <div class="bg-particle" style="width: 4px; height: 4px; background: rgba(104,109,224,0.15); bottom: 30%; left: 10%; animation: particle-drift 7s ease-in-out infinite 1.5s;"></div>

            <div class="container py-4 position-relative z-1">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="font-serif fw-bold display-6">Momen Bahagia</h2>
                    <div class="shimmer-divider mx-auto mt-3" style="width: 60px;"></div>
                </div>

                <div class="row g-3">
                    @foreach($invitation->photos as $photo)
                    <div class="col-6 col-md-3" data-aos="zoom-in">
                        <div class="gallery-item shadow">
                            <img src="{{ $photo->url }}" alt="Galeri" class="img-fluid">
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Love Story Section -->
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section class="py-5 bg-light-custom section-animated">
            <!-- bg decorations -->
            <div class="bg-orb" style="width: 200px; height: 200px; background: rgba(72,52,212,0.05); top: 10%; left: -40px; animation: orb-float 14s ease-in-out infinite;"></div>
            <div class="bg-orb" style="width: 160px; height: 160px; background: rgba(240,147,43,0.04); bottom: 10%; right: -30px; animation: orb-float-reverse 12s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="top: 25%; right: 20%; animation: sparkle-blink 4s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="bottom: 35%; left: 25%; animation: sparkle-blink 4s ease-in-out infinite 2s;"></div>

            <div class="container py-4 position-relative z-1">
                <div class="text-center mb-5" data-aos="fade-up">
                    <h2 class="font-script text-primary-custom display-4 mb-2">Our Journey</h2>
                    <div class="shimmer-divider mx-auto my-3" style="width: 60px;"></div>
                    <p class="text-muted">Kisah Cinta Kami</p>
                </div>

                <div class="row justify-content-center">
                    <div class="col-lg-8">
                        <div style="position: relative; padding-left: 40px;">
                            <div style="position: absolute; left: 15px; top: 0; bottom: 0; width: 3px; background: linear-gradient(180deg, transparent, var(--primary-color), var(--primary-light), transparent); border-radius: 2px;"></div>

                            @foreach($invitation->love_story as $index => $story)
                            <div data-aos="fade-up" data-aos-delay="{{ $index * 100 }}" style="position: relative; margin-bottom: {{ $loop->last ? '0' : '32px' }};">
                                <div style="position: absolute; left: -33px; top: 20px; width: 16px; height: 16px; background: white; border: 3px solid var(--primary-color); border-radius: 50%; box-shadow: 0 0 0 4px rgba(72,52,212,0.15); z-index: 2;"></div>
                                <div class="card card-custom p-4">
                                    <div class="card-body">
                                        <span class="badge bg-primary-custom rounded-pill px-3 py-1 mb-3" style="font-size: 11px; letter-spacing: 1px;">{{ $story['date'] ?? '' }}</span>
                                        <h5 class="font-serif fw-bold mb-2">{{ $story['title'] ?? '' }}</h5>
                                        <p class="text-muted small mb-0 lh-lg">{{ $story['description'] ?? '' }}</p>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Wave: to Gift -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,0 720,50 1080,25 C1260,12.5 1350,12.5 1440,25 L1440,50 L0,50 Z" fill="white"></path>
            </svg>
        </div>

        <!-- 7. GIFT -->
        @if($invitation->enable_gift)
        <section id="gift" class="py-5 bg-white section-animated">
            <!-- bg -->
            <div class="bg-orb" style="width: 200px; height: 200px; background: rgba(72,52,212,0.04); top: -40px; right: -40px; animation: orb-float 12s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="top: 30%; left: 20%; animation: sparkle-blink 5s ease-in-out infinite;"></div>
            <div class="bg-sparkle" style="bottom: 20%; right: 25%; animation: sparkle-blink 5s ease-in-out infinite 2.5s;"></div>

            <div class="container py-4 text-center position-relative z-1">
                <div class="row justify-content-center">
                    <div class="col-lg-6" data-aos="fade-up">
                        <h2 class="font-script text-primary-custom display-4 mb-2">Wedding Gift</h2>
                        <div class="shimmer-divider mx-auto my-3" style="width: 60px;"></div>
                        <p class="text-muted small mb-4">Kehadiran dan doa restu Anda adalah hadiah terindah bagi kami. Namun jika Anda ingin memberikan tanda kasih, dapat melalui:</p>
                        
                        @if($invitation->bank_accounts)
                            @foreach($invitation->bank_accounts as $acc)
                            <div class="card border-0 shadow-lg text-white text-start p-4 rounded-4 position-relative overflow-hidden mb-3" 
                                 style="background: linear-gradient(45deg, #4834d4, #686de0);">
                                <div class="position-absolute top-0 end-0 opacity-25 p-3">
                                    <i class="fas fa-university fa-5x"></i>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-center mb-4 position-relative z-1">
                                    <span class="fw-bold fs-5">{{ $acc['bank'] }}</span>
                                    <i class="fas fa-chip fa-2x opacity-50"></i>
                                </div>
                                
                                <div class="mb-3 position-relative z-1">
                                    <p class="small mb-0 opacity-75">No. Rekening</p>
                                    <h3 class="font-monospace fw-bold mb-0 tracking-wider">{{ $acc['account_number'] }}</h3>
                                </div>
                                
                                <div class="d-flex justify-content-between align-items-end position-relative z-1">
                                    <div>
                                        <p class="small mb-0 opacity-75">Atas Nama</p>
                                        <h5 class="fw-bold mb-0">{{ $acc['account_name'] }}</h5>
                                    </div>
                                    <button class="btn btn-light btn-sm rounded-pill px-3 fw-bold text-primary-custom" @click="copyText('{{ $acc['account_number'] }}')">
                                        <i class="far fa-copy me-1"></i> Salin
                                    </button>
                                </div>
                            </div>
                            @endforeach
                        @endif
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- Wave: Gift → RSVP -->
        <div class="wave-separator">
            <svg viewBox="0 0 1440 50" preserveAspectRatio="none">
                <path d="M0,25 C360,50 720,0 1080,25 C1260,37.5 1350,37.5 1440,25 L1440,50 L0,50 Z" fill="#e2e1fa"></path>
            </svg>
        </div>

        <!-- 8. RSVP -->
        @if($invitation->enable_rsvp)
        <section id="rsvp" class="py-5 bg-light-custom section-animated"
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
            <div class="container py-4">
                <div class="row justify-content-center">
                    <div class="col-lg-6">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden" data-aos="fade-up">
                            <div class="card-header bg-primary-custom text-white text-center py-4">
                                <h3 class="font-serif fw-bold mb-0">RSVP</h3>
                                <p class="small mb-0 opacity-75">Konfirmasi Kehadiran</p>
                            </div>
                            <div class="card-body p-4">
                                {{-- Success --}}
                                <div x-show="success" x-transition class="alert alert-success small py-2 text-center">
                                    ✓ Terima kasih! Ucapan dan konfirmasi Anda telah tersimpan.
                                </div>
                                {{-- Error --}}
                                <div x-show="error" x-transition class="alert alert-danger small py-2 text-center" x-text="error"></div>

                                <form @submit.prevent="submitForm">
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-primary-custom">Nama Lengkap</label>
                                        <input type="text" x-model="name" class="form-control bg-light" placeholder="Masukkan nama Anda" required>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-primary-custom">Ucapan & Doa</label>
                                        <textarea x-model="message" class="form-control bg-light" rows="3" placeholder="Tuliskan doa restu..." required></textarea>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label small fw-bold text-primary-custom">Konfirmasi</label>
                                        <div class="d-flex gap-2">
                                            <button type="button" @click="status = 'confirmed'"
                                                :class="status === 'confirmed' ? 'btn btn-custom' : 'btn btn-outline-secondary'"
                                                class="flex-fill d-flex align-items-center justify-content-center gap-1" style="font-size: 0.9rem;">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                Hadir
                                            </button>
                                            <button type="button" @click="status = 'declined'"
                                                :class="status === 'declined' ? 'btn btn-danger' : 'btn btn-outline-secondary'"
                                                class="flex-fill d-flex align-items-center justify-content-center gap-1" style="font-size: 0.9rem;">
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                                                Tidak Hadir
                                            </button>
                                        </div>
                                    </div>
                                    <div class="mb-3" x-show="status === 'confirmed'" x-transition>
                                        <label class="form-label small fw-bold text-primary-custom">Jumlah Tamu</label>
                                        <select x-model="pax" class="form-select bg-light">
                                            <option value="1">1 Orang</option>
                                            <option value="2">2 Orang</option>
                                            <option value="3">3 Orang</option>
                                            <option value="4">4 Orang</option>
                                            <option value="5">5 Orang</option>
                                        </select>
                                    </div>
                                    <button type="submit" :disabled="loading" class="btn btn-custom w-100">
                                        <span x-show="!loading">Kirim Konfirmasi</span>
                                        <span x-show="loading">Mengirim...</span>
                                    </button>
                                </form>

                                <div class="mt-4 pt-4 border-top" style="max-height: 300px; overflow-y: auto;">
                                    <h6 class="fw-bold mb-3 small text-muted">Ucapan Terbaru</h6>
                                    <template x-for="wish in wishes" :key="wish.id">
                                        <div class="d-flex mb-3">
                                            <div class="flex-shrink-0">
                                                <div class="bg-primary-custom text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 40px; height: 40px;" x-text="wish.initial"></div>
                                            </div>
                                            <div class="flex-grow-1 ms-3 bg-white p-2 rounded shadow-sm border">
                                                <h6 class="mt-0 mb-1 fw-bold small">
                                                    <span x-text="wish.name"></span>
                                                    <template x-if="wish.attendance_status === 'confirmed'">
                                                        <span class="badge bg-success ms-1" style="font-size:8px">Akan Hadir</span>
                                                    </template>
                                                    <template x-if="wish.attendance_status === 'declined'">
                                                        <span class="badge bg-danger ms-1" style="font-size:8px">Tidak Hadir</span>
                                                    </template>
                                                </h6>
                                                <p class="small text-muted mb-0" x-text="wish.message"></p>
                                                <small class="text-muted" style="font-size: 10px;" x-text="wish.time"></small>
                                            </div>
                                        </div>
                                    </template>
                                    <div x-show="wishes.length === 0" class="text-center py-3">
                                        <p class="small text-muted mb-0">Belum ada ucapan. Jadilah yang pertama!</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        @endif

        <!-- FOOTER -->
        <footer class="bg-dark text-white text-center py-5 pb-5 mb-5 position-relative overflow-hidden">
            <!-- Footer bg animation -->
            <div class="bg-orb" style="width: 200px; height: 200px; background: rgba(72,52,212,0.1); top: -50px; left: -50px; animation: orb-float 10s ease-in-out infinite; filter: blur(40px);"></div>
            <div class="bg-orb" style="width: 150px; height: 150px; background: rgba(104,109,224,0.08); bottom: -30px; right: -30px; animation: orb-float-reverse 12s ease-in-out infinite; filter: blur(40px);"></div>
            <div class="position-relative z-1">
                <div class="mb-3"><i class="fas fa-heart animate-heartbeat" style="color: var(--accent-color); font-size: 1.2rem;"></i></div>
                <h2 class="font-script mb-2">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
                <div class="shimmer-divider mx-auto my-3" style="width: 60px;"></div>
                <p class="small opacity-50 mb-0">Terima kasih atas kehadiran dan doa restu Anda.</p>
            </div>
        </footer>
    </div>

    <div class="floating-btn shadow" @click="toggleMusic()" x-show="audioElement" style="display:none;" x-transition>
        <i class="fas fa-compact-disc fs-4" :class="audioPlaying ? 'spin' : ''"></i>
    </div>

    <nav class="navbar fixed-bottom navbar-bottom" x-show="opened" style="display:none;" x-transition>
        <div class="container px-2">
            <div class="w-100 d-flex justify-content-between px-3">
                <a class="nav-link" href="#home" :class="activeSection === 'home' ? 'active' : ''"><i class="fas fa-home"></i>Home</a>
                <a class="nav-link" href="#couple" :class="activeSection === 'couple' ? 'active' : ''"><i class="fas fa-heart"></i>Mempelai</a>
                <a class="nav-link" href="#event" :class="activeSection === 'event' ? 'active' : ''"><i class="fas fa-calendar-day"></i>Acara</a>
                <a class="nav-link" href="#gallery" :class="activeSection === 'gallery' ? 'active' : ''"><i class="fas fa-images"></i>Galeri</a>
                <a class="nav-link" href="#rsvp" :class="activeSection === 'rsvp' ? 'active' : ''"><i class="fas fa-envelope"></i>Ucapan</a>
            </div>
        </div>
    </nav>
    
    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @endpush
</div>