
@section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
{{-- Fonts: Playfair Display (Judul Mewah), Dancing Script (Latin), Lato (Bacaan) --}}
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Dancing+Script:wght@400;600&family=Lato:wght@300;400;700&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    --sage-dark: #4A5D46;
    --sage-medium: #6F8B68;
    --sage-light: #E9F0E7;
    --dusty-pink: #D6C0B3;
    --sand: #F9F7F2;
    --text-main: #3D403D;
    --white: #FFFFFF;
}

* { margin: 0; padding: 0; box-sizing: border-box; }

html {
    scroll-behavior: smooth;
    -webkit-tap-highlight-color: transparent;
}

body { 
    font-family: 'Lato', sans-serif; 
    background-color: var(--sand);
    color: var(--text-main); 
    overflow-x: hidden;
    /* Subtle paper texture */
    background-image: url("data:image/svg+xml,%3Csvg width='100' height='100' viewBox='0 0 100 100' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M11 18c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm48 25c3.866 0 7-3.134 7-7s-3.134-7-7-7-7 3.134-7 7 3.134 7 7 7zm-43-7c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm63 31c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM34 90c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zm56-76c1.657 0 3-1.343 3-3s-1.343-3-3-3-3 1.343-3 3 1.343 3 3 3zM12 86c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm28-65c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm23-11c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-6 60c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 1.79 4 4 4zm29 22c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zM32 63c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm57-13c2.76 0 5-2.24 5-5s-2.24-5-5-5-5 2.24-5 5 2.24 5 5 5zm-9-21c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM60 91c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM35 41c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2zM12 60c1.105 0 2-.895 2-2s-.895-2-2-2-2 .895-2 2 .895 2 2 2z' fill='%236f8b68' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E");
}

.font-serif { font-family: 'Playfair Display', serif; }
.font-script { font-family: 'Dancing Script', cursive; }

/* === HELPERS === */
.arch-top { border-radius: 200px 200px 0 0; }
.arch-full { border-radius: 200px; }
.rounded-box { border-radius: 24px; }

/* === ANIMATIONS === */
@keyframes slideUp { from { transform: translateY(50px); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
@keyframes sway { 0%, 100% { transform: rotate(-5deg); } 50% { transform: rotate(5deg); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }

.animate-slide-up { animation: slideUp 1s ease forwards; }
.animate-sway { animation: sway 4s ease-in-out infinite; transform-origin: bottom center; }

/* === COVER === */
.cover { 
    position: fixed; inset: 0; z-index: 100; 
    background: var(--sand);
    display: flex; flex-direction: column; align-items: center; justify-content: center;
}
.cover-arch {
    width: 80%; max-width: 350px; height: 60vh;
    border-radius: 200px 200px 0 0;
    overflow: hidden;
    position: relative;
    border: 8px solid white;
    box-shadow: 0 20px 40px rgba(74, 93, 70, 0.15);
}
.cover-img { width: 100%; height: 100%; object-fit: cover; }
.cover-content {
    position: absolute; bottom: 40px; left: 0; right: 0;
    text-align: center;
    z-index: 101;
}

/* === BUTTONS === */
.btn {
    display: inline-block; padding: 14px 35px;
    background: var(--sage-medium); color: white;
    border-radius: 50px; border: none; cursor: pointer;
    font-family: 'Lato', sans-serif; font-weight: 700; letter-spacing: 1px;
    text-transform: uppercase; font-size: 12px;
    transition: all 0.3s;
    box-shadow: 0 4px 15px rgba(111, 139, 104, 0.3);
}
.btn:hover { background: var(--sage-dark); transform: translateY(-2px); }
.btn-outline {
    background: transparent; border: 1px solid var(--sage-medium); color: var(--sage-medium);
}

/* === HERO === */
.hero-section {
    min-height: 100vh;
    position: relative;
    padding-top: 80px; padding-bottom: 40px;
    text-align: center;
}
/* Leaf Decoration */
.leaf-decor {
    position: absolute; width: 150px; opacity: 0.2; pointer-events: none;
}
.leaf-tl { top: -20px; left: -30px; transform: rotate(180deg); }
.leaf-br { bottom: 0; right: -30px; }

.hero-names {
    font-family: 'Playfair Display', serif;
    font-size: 3rem; color: var(--sage-dark);
    line-height: 1.1; margin: 20px 0;
}
.hero-and {
    font-family: 'Dancing Script', cursive;
    color: var(--dusty-pink); font-size: 2.5rem;
}

/* === COUNTDOWN === */
.countdown-container {
    display: flex; justify-content: center; gap: 15px; margin-top: 30px;
}
.countdown-circle {
    width: 65px; height: 65px;
    background: white; border: 1px solid var(--sage-light);
    border-radius: 50%;
    display: flex; flex-direction: column; align-items: center; justify-content: center;
    box-shadow: 0 4px 10px rgba(0,0,0,0.05);
}
.countdown-num { font-weight: 700; color: var(--sage-dark); font-size: 1.2rem; }
.countdown-label { font-size: 0.6rem; text-transform: uppercase; color: #999; }

/* === SECTIONS === */
.section { padding: 60px 20px; position: relative; }
.section-title { text-align: center; margin-bottom: 40px; }
.section-title h2 {
    font-family: 'Dancing Script', cursive;
    font-size: 2.5rem; color: var(--sage-medium);
    margin-bottom: 10px;
}
.title-leaf {
    width: 40px; height: 2px; background: var(--sage-medium); margin: 0 auto;
    position: relative;
}
.title-leaf::after {
    content: '❀'; color: var(--sage-medium); position: absolute;
    top: -12px; left: 50%; transform: translateX(-50%); font-size: 14px;
}

/* === COUPLE === */
.couple-grid {
    display: flex; flex-direction: column; gap: 40px; max-width: 600px; margin: 0 auto;
}
.couple-card {
    background: white; border-radius: 20px; padding: 30px;
    text-align: center; position: relative;
    box-shadow: 0 10px 30px rgba(0,0,0,0.03);
}
.couple-img-frame {
    width: 150px; height: 200px;
    border-radius: 100px 100px 0 0; /* Arch Shape */
    overflow: hidden; margin: -60px auto 20px;
    border: 5px solid white;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
.couple-img { width: 100%; height: 100%; object-fit: cover; }
.couple-name-text {
    font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--sage-dark); margin-bottom: 5px;
}

/* === EVENTS (Timeline) === */
.timeline {
    position: relative; max-width: 500px; margin: 0 auto;
    border-left: 2px dashed var(--sage-medium); margin-left: 20px;
}
.event-item {
    margin-left: 30px; margin-bottom: 40px; position: relative;
    background: white; padding: 25px; border-radius: 0 20px 20px 20px;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
}
.event-dot {
    position: absolute; left: -41px; top: 20px;
    width: 20px; height: 20px; background: var(--sage-medium);
    border-radius: 50%; border: 4px solid var(--sand);
}
.event-name { font-family: 'Playfair Display', serif; font-size: 1.5rem; color: var(--sage-dark); }
.event-meta { display: flex; align-items: center; gap: 8px; color: #777; margin: 5px 0; font-size: 0.9rem; }

/* === GIFT CARD === */
.atm-card {
    background: linear-gradient(135deg, var(--sage-medium), var(--sage-dark));
    color: white; border-radius: 15px; padding: 25px;
    max-width: 400px; margin: 0 auto 20px;
    box-shadow: 0 10px 25px rgba(74, 93, 70, 0.3);
    position: relative; overflow: hidden;
}
.atm-card::before {
    content: ''; position: absolute; top: -50px; right: -50px;
    width: 150px; height: 150px; background: rgba(255,255,255,0.1);
    border-radius: 50%;
}
.copy-pill {
    background: rgba(255,255,255,0.2); padding: 5px 15px;
    border-radius: 20px; font-size: 0.8rem; cursor: pointer;
    display: inline-flex; align-items: center; gap: 5px;
    backdrop-filter: blur(5px); transition: 0.3s;
}
.copy-pill:hover { background: white; color: var(--sage-dark); }

/* === RSVP FORM === */
.rsvp-box {
    background: white; border-radius: 20px; padding: 30px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.05);
}
.input-field {
    width: 100%; padding: 15px; background: var(--sand); border: 1px solid transparent;
    border-radius: 10px; margin-bottom: 15px; font-family: 'Lato', sans-serif;
    transition: 0.3s;
}
.input-field:focus { outline: none; border-color: var(--sage-medium); background: white; }

/* === NAVBAR === */
.glass-nav {
    position: fixed; bottom: 20px; left: 50%; transform: translateX(-50%);
    background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);
    border-radius: 50px; padding: 10px 30px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    display: flex; gap: 25px; z-index: 999;
    border: 1px solid rgba(255,255,255,0.5);
}
.nav-icon {
    color: #999; font-size: 1.2rem; transition: 0.3s;
    position: relative; display: flex; flex-direction: column; align-items: center;
}
.nav-icon.active { color: var(--sage-dark); transform: translateY(-3px); }
.nav-icon.active::after {
    content: ''; width: 4px; height: 4px; background: var(--sage-dark);
    border-radius: 50%; margin-top: 3px;
}

/* === MUSIC === */
.music-trigger {
    position: fixed; top: 20px; right: 20px;
    width: 40px; height: 40px; background: white;
    border-radius: 50%; box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex; align-items: center; justify-content: center;
    color: var(--sage-dark); z-index: 90; cursor: pointer;
}
.music-trigger.playing { animation: spin 4s linear infinite; }
@keyframes spin { 100% { transform: rotate(360deg); } }

/* === LIGHTBOX === */
.lightbox-overlay {
    background: rgba(255,255,255,0.95); backdrop-filter: blur(5px);
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
        this.$watch('opened', (value) => { if(value) this.$nextTick(() => this.setupScrollSpy()); });
    },
    setupScrollSpy() {
        const sections = ['home', 'couple', 'events', 'gift', 'rsvp'];
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => { if(entry.isIntersecting) this.activeSection = entry.target.id; });
        }, { threshold: 0.3 });
        sections.forEach(id => {
            const el = document.getElementById(id);
            if(el) observer.observe(el);
        });
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

    {{-- COVER --}}
    <div x-show="!opened" x-transition:leave="transition duration-1000 transform" x-transition:leave-end="-translate-y-full" class="cover">
        <div class="cover-arch animate-slide-up">
            <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?w=500' }}" class="cover-img">
        </div>
        
        <div class="cover-content">
            <p style="text-transform: uppercase; letter-spacing: 3px; font-size: 0.8rem; margin-bottom: 10px;">The Wedding of</p>
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            <h1 style="font-family: 'Playfair Display', serif; font-size: 2.5rem; margin-bottom: 30px; color: var(--sage-dark);">
                @if($order === 'bride_first')
                    {{ $invitation->bride_nickname }} & {{ $invitation->groom_nickname }}
                @else
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                @endif
            </h1>
            
            <div style="background: white; padding: 15px 30px; border-radius: 15px; display: inline-block; box-shadow: 0 10px 20px rgba(0,0,0,0.05);">
                <p style="font-size: 0.8rem; color: #888;">Kepada Yth.</p>
                <p style="font-weight: 700; font-size: 1.1rem; margin-top: 5px;">{{ $guestName }}</p>
            </div>
            <br><br>
            <button @click="open()" class="btn">Buka Undangan</button>
        </div>
    </div>

    {{-- MAIN CONTENT --}}
    <main x-show="opened" x-transition:enter="transition ease-out duration-1000" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
        
        {{-- MUSIC BTN --}}
        <div class="music-trigger" :class="{'playing': playing}" @click="toggleAudio()">
            <svg x-show="playing" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M12 13c0 1.105-1.12 2-2.5 2S7 14.105 7 13s1.12-2 2.5-2 2.5.895 2.5 2z"/><path fill-rule="evenodd" d="M12 3v10h-1V3h1z"/><path d="M11 2.82a1 1 0 0 1 .804-.98l3-.6A1 1 0 0 1 16 2.22V4l-5 1V2.82z"/><path fill-rule="evenodd" d="M0 11.5a.5.5 0 0 1 .5-.5H4a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 7H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5zm0-4A.5.5 0 0 1 .5 3H8a.5.5 0 0 1 0 1H.5a.5.5 0 0 1-.5-.5z"/></svg>
            <svg x-show="!playing" width="16" height="16" fill="currentColor" viewBox="0 0 16 16"><path d="M5 6.25a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5zm3.5 0a1.25 1.25 0 1 1 2.5 0v3.5a1.25 1.25 0 1 1-2.5 0v-3.5z"/></svg>
        </div>

        {{-- HERO --}}
        <section id="home" class="hero-section">
            <p style="font-family: 'Dancing Script', cursive; font-size: 1.5rem; color: var(--sage-medium);">We Are Getting Married</p>
            
            <div class="hero-names">
                {{ $order === 'bride_first' ? $invitation->bride_nickname : $invitation->groom_nickname }}
                <div class="hero-and">&</div>
                {{ $order === 'bride_first' ? $invitation->groom_nickname : $invitation->bride_nickname }}
            </div>

            <p style="font-size: 1.1rem; letter-spacing: 2px; text-transform: uppercase;">{{ $invitation->akad_date?->translatedFormat('d . m . Y') }}</p>

            {{-- Countdown with SVG Circle --}}
            <div x-data="{
                days:0, hours:0, minutes:0, seconds:0,
                target: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}'),
                init() { setInterval(() => this.update(), 1000); },
                update() {
                    const diff = this.target - new Date();
                    if(diff>0){
                        this.days=Math.floor(diff/86400000);
                        this.hours=Math.floor((diff%86400000)/3600000);
                        this.minutes=Math.floor((diff%3600000)/60000);
                        this.seconds=Math.floor((diff%60000)/1000);
                    }
                }
            }" class="countdown-container">
                <div class="countdown-circle"><span class="countdown-num" x-text="days">0</span><span class="countdown-label">Hari</span></div>
                <div class="countdown-circle"><span class="countdown-num" x-text="hours">0</span><span class="countdown-label">Jam</span></div>
                <div class="countdown-circle"><span class="countdown-num" x-text="minutes">0</span><span class="countdown-label">Mnt</span></div>
            </div>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="section">
            <div class="section-title">
                <h2>Groom & Bride</h2>
                <div class="title-leaf"></div>
                <p style="margin-top: 15px; font-size: 0.9rem; color: #666;">"Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri..." (Ar-Rum: 21)</p>
            </div>

            <div class="couple-grid">
                {{-- First --}}
                <div class="couple-card">
                    <div class="couple-img-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo) ? asset('storage/' . ($order === 'bride_first' ? $invitation->bride_photo : $invitation->groom_photo)) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?w=400' }}" class="couple-img">
                    </div>
                    <h3 class="couple-name-text">{{ $order === 'bride_first' ? $invitation->bride_name : $invitation->groom_name }}</h3>
                    <p style="font-size: 0.9rem; color: #888;">Putra/Putri Bpk. {{ $order === 'bride_first' ? $invitation->bride_father : $invitation->groom_father }} & Ibu {{ $order === 'bride_first' ? $invitation->bride_mother : $invitation->groom_mother }}</p>
                </div>

                {{-- Second --}}
                <div class="couple-card">
                    <div class="couple-img-frame">
                        <img src="{{ ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo) ? asset('storage/' . ($order === 'bride_first' ? $invitation->groom_photo : $invitation->bride_photo)) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-img">
                    </div>
                    <h3 class="couple-name-text">{{ $order === 'bride_first' ? $invitation->groom_name : $invitation->bride_name }}</h3>
                    <p style="font-size: 0.9rem; color: #888;">Putra/Putri Bpk. {{ $order === 'bride_first' ? $invitation->groom_father : $invitation->bride_father }} & Ibu {{ $order === 'bride_first' ? $invitation->groom_mother : $invitation->bride_mother }}</p>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section" style="background: white;">
            <div class="section-title">
                <h2>Wedding Event</h2>
                <div class="title-leaf"></div>
            </div>

            <div class="timeline">
                {{-- Akad --}}
                <div class="event-item">
                    <div class="event-dot"></div>
                    <h3 class="event-name">Akad Nikah</h3>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                        {{ $invitation->akad_date?->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                        {{ $invitation->akad_date?->format('H:i') }} WIB
                    </div>
                    <p style="margin: 10px 0; font-size: 0.95rem;">{{ $invitation->akad_venue }}</p>
                    <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn btn-outline" style="padding: 8px 20px; font-size: 10px;">Google Maps</a>
                </div>

                {{-- Resepsi --}}
                @if($invitation->resepsi_date)
                <div class="event-item">
                    <div class="event-dot"></div>
                    <h3 class="event-name">Resepsi</h3>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
                        {{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}
                    </div>
                    <div class="event-meta">
                        <svg width="14" height="14" fill="currentColor" viewBox="0 0 16 16"><path d="M8 3.5a.5.5 0 0 0-1 0V9a.5.5 0 0 0 .252.434l3.5 2a.5.5 0 0 0 .496-.868L8 8.71V3.5z"/><path d="M8 16A8 8 0 1 0 8 0a8 8 0 0 0 0 16zm7-8A7 7 0 1 1 1 8a7 7 0 0 1 14 0z"/></svg>
                        {{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai
                    </div>
                    <p style="margin: 10px 0; font-size: 0.95rem;">{{ $invitation->resepsi_venue }}</p>
                    <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn btn-outline" style="padding: 8px 20px; font-size: 10px;">Google Maps</a>
                </div>
                @endif
            </div>
        </section>

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section">
            <div class="section-title">
                <h2>Wedding Gift</h2>
                <div class="title-leaf"></div>
            </div>
            
            <div style="text-align: center;">
                @if($invitation->bank_accounts)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="atm-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 1.2rem;">{{ $account['bank'] }}</span>
                            <svg width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/></svg>
                        </div>
                        <p style="font-family: monospace; font-size: 1.4rem; letter-spacing: 2px; margin-bottom: 20px;">{{ $account['account_number'] }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="text-align: left;">
                                <span style="font-size: 0.7rem; opacity: 0.8;">Account Name</span><br>
                                <span>{{ $account['account_name'] }}</span>
                            </div>
                            <div x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="copy-pill">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                    </div>
                    @endforeach
                @elseif($invitation->bank_name)
                    <div class="atm-card">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 30px;">
                            <span style="font-weight: 700; font-size: 1.2rem;">{{ $invitation->bank_name }}</span>
                            <svg width="30" height="30" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4zm2-1a1 1 0 0 0-1 1v1h14V4a1 1 0 0 0-1-1H2zm13 4H1v5a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V7z"/></svg>
                        </div>
                        <p style="font-family: monospace; font-size: 1.4rem; letter-spacing: 2px; margin-bottom: 20px;">{{ $invitation->bank_account }}</p>
                        <div style="display: flex; justify-content: space-between; align-items: flex-end;">
                            <div style="text-align: left;">
                                <span style="font-size: 0.7rem; opacity: 0.8;">Account Name</span><br>
                                <span>{{ $invitation->bank_holder }}</span>
                            </div>
                            <div x-data="{ copied: false }" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied=true; setTimeout(()=>copied=false, 2000)" class="copy-pill">
                                <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                <svg width="12" height="12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section" style="background: white;">
            <div class="section-title">
                <h2>RSVP & Wishes</h2>
                <div class="title-leaf"></div>
            </div>

            <div class="max-w-md mx-auto">
                {{-- RSVP Form --}}
                <div class="rsvp-box">
                    <form wire:submit="submitRSVP">
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Nama Lengkap</label>
                            <input type="text" wire:model="rsvpName" placeholder="Nama Lengkap" class="input-field">
                            @error('rsvpName') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Ucapan & Doa</label>
                            <textarea wire:model="rsvpMessage" rows="3" placeholder="Tuliskan ucapan & doa..." class="input-field"></textarea>
                            @error('rsvpMessage') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                        </div>
                        
                        <div style="margin-bottom: 15px;">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Konfirmasi Kehadiran</label>
                            <div style="display: flex; gap: 20px;">
                                <label style="display: flex; gap: 10px; align-items: center; cursor: pointer;">
                                    <input type="radio" value="Hadir" wire:model="rsvpStatus" style="accent-color: var(--sage-medium);"> Hadir
                                </label>
                                <label style="display: flex; gap: 10px; align-items: center; cursor: pointer;">
                                    <input type="radio" value="Maaf, Tidak Bisa Hadir" wire:model="rsvpStatus" style="accent-color: var(--sage-medium);"> Tidak Hadir
                                </label>
                            </div>
                            @error('rsvpStatus') <span style="color: red; font-size: 0.8rem;">{{ $message }}</span> @enderror
                        </div>

                        <div style="margin-bottom: 15px;" x-show="$wire.rsvpStatus === 'Hadir'">
                            <label style="display: block; margin-bottom: 5px; font-weight: bold; color: var(--sage-dark);">Jumlah Tamu</label>
                            <select wire:model="rsvpGuests" class="input-field">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                            </select>
                        </div>

                        <button type="submit" class="btn" style="width: 100%;">
                            <span wire:loading.remove>Kirim Konfirmasi</span>
                            <span wire:loading>Mengirim...</span>
                        </button>

                         @if (session()->has('message'))
                             <div style="margin-top: 15px; padding: 10px; background: rgba(40, 167, 69, 0.2); border: 1px solid #28a745; border-radius: 8px; text-align: center; color: #155724;">
                                 {{ session('message') }}
                             </div>
                         @endif
                    </form>
                </div>

                {{-- Wishes List --}}
                <div style="margin-top: 30px;">
                    <div style="text-align: center; margin-bottom: 20px;">
                        <h3 style="font-family: 'Dancing Script', cursive; font-size: 1.8rem; color: var(--sage-medium);">Doa Restu</h3>
                    </div>
                
                    <div style="max-height: 400px; overflow-y: auto; padding-right: 5px;">
                        @foreach($invitation->wishes()->latest()->get() as $wish)
                        <div style="background: white; border-radius: 10px; padding: 15px; margin-bottom: 10px; border-left: 3px solid var(--sage-medium); box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
                            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 5px;">
                                <div style="font-weight: 700; color: var(--sage-dark);">{{ $wish->name }}</div>
                                <span style="font-size: 0.7rem; color: #aaa;">{{ $wish->created_at->diffForHumans() }}</span>
                            </div>
                            <div style="margin-bottom: 5px;">
                                <span style="background: var(--sage-light); color: var(--sage-dark); font-size: 0.7rem; padding: 2px 8px; border-radius: 10px;">{{ $wish->status }}</span>
                            </div>
                            <p style="font-size: 0.9rem; color: #666; font-style: italic;">"{{ $wish->message }}"</p>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FOOTER --}}
        <footer style="text-align: center; padding: 50px 20px 100px; background: var(--sage-light); color: var(--sage-dark);">
            <h2 style="font-family: 'Dancing Script', cursive; font-size: 2rem;">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p style="font-size: 0.8rem; margin-top: 10px;">Terima kasih atas doa restunya</p>
        </footer>

    </main>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" class="glass-nav">
        <a @click.prevent="scrollTo('home')" class="nav-icon" :class="{'active': activeSection === 'home'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M8.707 1.5a1 1 0 0 0-1.414 0L.646 8.146a.5.5 0 0 0 .708.708L2 8.207V13.5A1.5 1.5 0 0 0 3.5 15h9a1.5 1.5 0 0 0 1.5-1.5V8.207l.646.647a.5.5 0 0 0 .708-.708L13 5.793V2.5a.5.5 0 0 0-.5-.5h-1a.5.5 0 0 0-.5.5v1.293L8.707 1.5Z"/></svg>
        </a>
        <a @click.prevent="scrollTo('couple')" class="nav-icon" :class="{'active': activeSection === 'couple'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path fill-rule="evenodd" d="M8 1.314C12.438-3.248 23.534 4.735 8 15-7.534 4.736 3.562-3.248 8 1.314z"/></svg>
        </a>
        <a @click.prevent="scrollTo('events')" class="nav-icon" :class="{'active': activeSection === 'events'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M3.5 0a.5.5 0 0 1 .5.5V1h8V.5a.5.5 0 0 1 1 0V1h1a2 2 0 0 1 2 2v11a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V3a2 2 0 0 1 2-2h1V.5a.5.5 0 0 1 .5-.5zM1 4v10a1 1 0 0 0 1 1h12a1 1 0 0 0 1-1V4H1z"/></svg>
        </a>
        <a @click.prevent="scrollTo('rsvp')" class="nav-icon" :class="{'active': activeSection === 'rsvp'}">
            <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16"><path d="M0 4a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v8a2 2 0 0 1-2 2H2a2 2 0 0 1-2-2V4Zm2-1a1 1 0 0 0-1 1v.217l7 4.2 7-4.2V4a1 1 0 0 0-1-1H2Zm13 2.383-4.708 2.825L15 11.105V5.383Zm-.034 6.876-5.64-3.471L8 9.583l-1.326-.795-5.64 3.47A1 1 0 0 0 2 13h12a1 1 0 0 0 .966-.741ZM1 11.105l4.708-2.897L1 5.383v5.722Z"/></svg>
        </a>
    </nav>
</div>
