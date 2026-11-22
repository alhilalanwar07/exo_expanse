@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;500;600;700;800&family=Great+Vibes&family=Poppins:wght@300;400;500;600;700&family=Cormorant+Garamond:ital,wght@0,400;0,600;1,400&display=swap" rel="stylesheet">
@endpush

@push('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
:root {
    --bugis-bg: #4a0714;
    --bugis-bg-dark: #32030c;
    --bugis-gold: #dfb76c;
    --bugis-gold-light: #f5e4bc;
    --bugis-gold-dark: #b88d3e;
    --bugis-card-bg: rgba(50, 4, 13, 0.78);
    --bugis-card-border: rgba(223, 183, 108, 0.35);
    --font-heading: 'Cinzel', serif;
    --font-script: 'Great Vibes', cursive;
    --font-body: 'Poppins', sans-serif;
    --font-quote: 'Cormorant Garamond', serif;
}

html {
    scroll-behavior: smooth;
}
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
body {
    background-color: var(--bugis-bg-dark);
    color: #ffffff;
    font-family: var(--font-body);
    overflow-x: hidden;
    -webkit-font-smoothing: antialiased;
}

/* Background Wrapper */
.bugis-page-wrapper {
    width: 100%;
    min-height: 100dvh;
    background-color: var(--bugis-bg-dark);
    background-image: url('{{ asset('themes/3d-adat-bugis-02/assets/BACKGROUND-FIXED.jpg') }}');
    background-size: cover;
    background-position: center top;
    background-attachment: fixed;
    position: relative;
}

/* 480px Centered Mobile Frame */
.bugis-container {
    width: 100%;
    max-width: 480px;
    margin: 0 auto;
    position: relative;
    background-color: rgba(58, 5, 16, 0.92);
    box-shadow: 0 0 50px rgba(0, 0, 0, 0.8), 0 0 0 1px rgba(223, 183, 108, 0.2);
    overflow: hidden;
    min-height: 100dvh;
}

/* Typography Helpers */
.font-heading { font-family: var(--font-heading); }
.font-script { font-family: var(--font-script); }
.font-quote { font-family: var(--font-quote); }
.color-gold { color: var(--bugis-gold); }
.color-gold-light { color: var(--bugis-gold-light); }

.gold-gradient-text {
    background: linear-gradient(135deg, #fceecb 0%, #dfb76c 50%, #b88d3e 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
}

.gold-gradient-bg {
    background: linear-gradient(135deg, #dfb76c 0%, #c4994d 50%, #a0762f 100%);
    color: #2a030b;
}

/* Card Styling */
.bugis-card {
    background: var(--bugis-card-bg);
    border: 1px solid var(--bugis-card-border);
    border-radius: 18px;
    padding: 24px 20px;
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    box-shadow: 0 8px 32px rgba(0, 0, 0, 0.45);
    position: relative;
}

/* Button Styles */
.btn-bugis-gold {
    background: linear-gradient(135deg, #dfb76c 0%, #c99c4c 100%);
    color: #2b040c;
    border: none;
    border-radius: 50px;
    padding: 12px 28px;
    font-weight: 600;
    font-size: 13px;
    letter-spacing: 0.5px;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    text-decoration: none;
    box-shadow: 0 4px 15px rgba(223, 183, 108, 0.35);
}
.btn-bugis-gold:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(223, 183, 108, 0.5);
    color: #1a0207;
}

.btn-bugis-outline {
    background: rgba(223, 183, 108, 0.08);
    color: var(--bugis-gold);
    border: 1px solid var(--bugis-gold);
    border-radius: 50px;
    padding: 10px 24px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 6px;
    text-decoration: none;
}
.btn-bugis-outline:hover {
    background: var(--bugis-gold);
    color: #2b040c;
}

/* Floating Corner Ornaments with breathing animation */
.corner-ornament {
    position: absolute;
    pointer-events: none;
    z-index: 5;
    width: 110px;
    opacity: 0.75;
    animation: ornamentBreathe 6s ease-in-out infinite alternate;
}
.corner-tl { top: -10px; left: -10px; }
.corner-tr { top: -10px; right: -10px; transform: scaleX(-1); }
.corner-bl { bottom: -10px; left: -10px; transform: scaleY(-1); }
.corner-br { bottom: -10px; right: -10px; transform: scale(-1); }

@keyframes ornamentBreathe {
    0% { transform: scale(1) translateY(0); opacity: 0.7; }
    50% { transform: scale(1.05) translateY(-3px); opacity: 0.85; }
    100% { transform: scale(1) translateY(0); opacity: 0.7; }
}

/* Section Separator */
.bugis-divider {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 12px;
    margin: 20px auto;
}
.bugis-divider::before, .bugis-divider::after {
    content: '';
    height: 1px;
    width: 50px;
    background: linear-gradient(to right, transparent, var(--bugis-gold));
}
.bugis-divider::after {
    background: linear-gradient(to left, transparent, var(--bugis-gold));
}

/* 3D Motion Video Container */
.motion-video-container {
    width: 100%;
    position: relative;
    overflow: hidden;
    border-radius: 0 0 24px 24px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.6);
}
.motion-video-container video {
    width: 100%;
    height: 100%;
    min-height: 480px;
    object-fit: cover;
    display: block;
}

/* Floating Music Disc */
.music-toggle-btn {
    position: fixed;
    top: 20px;
    right: max(16px, calc((100vw - 480px) / 2 + 16px));
    z-index: 99;
    width: 44px;
    height: 44px;
    border-radius: 50%;
    background: rgba(45, 4, 12, 0.88);
    border: 1.5px solid var(--bugis-gold);
    display: flex;
    align-items: center;
    justify-content: center;
    color: var(--bugis-gold);
    box-shadow: 0 4px 18px rgba(0,0,0,0.6);
    cursor: pointer;
    backdrop-filter: blur(10px);
    -webkit-backdrop-filter: blur(10px);
    transition: transform 0.3s ease, background 0.3s ease;
}
.music-toggle-btn:hover { transform: scale(1.08); }
.music-disc-rotating {
    animation: discRotate 4s linear infinite;
}
@keyframes discRotate {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
@media (max-width: 480px) {
    .music-toggle-btn {
        top: max(16px, env(safe-area-inset-top, 16px));
        right: 16px;
        width: 40px;
        height: 40px;
    }
    .music-toggle-btn svg {
        width: 18px;
        height: 18px;
    }
}

/* ========================================= */
/* FULLY RESPONSIVE BOTTOM NAVIGATION BAR   */
/* ========================================= */
.bugis-nav-wrap {
    position: fixed;
    bottom: max(14px, env(safe-area-inset-bottom, 14px));
    left: 0;
    right: 0;
    z-index: 900;
    display: flex;
    justify-content: center;
    align-items: center;
    pointer-events: none;
    padding: 0 10px;
}

.bugis-nav {
    width: 100%;
    max-width: 440px;
    background: rgba(35, 3, 9, 0.94);
    border: 1px solid rgba(223, 183, 108, 0.45);
    border-radius: 50px;
    padding: 4px 6px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 2px;
    pointer-events: auto;
    box-shadow: 0 10px 35px rgba(0, 0, 0, 0.75), inset 0 0 12px rgba(223, 183, 108, 0.12);
    backdrop-filter: blur(16px);
    -webkit-backdrop-filter: blur(16px);
    overflow-x: auto;
    scrollbar-width: none;
    -ms-overflow-style: none;
    margin: 0 auto;
}
.bugis-nav::-webkit-scrollbar {
    display: none;
}

.bugis-nav-btn {
    flex: 1 1 0;
    min-width: 38px;
    height: 46px;
    padding: 3px 2px;
    border-radius: 20px;
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    gap: 2px;
    color: rgba(255, 255, 255, 0.65);
    background: transparent;
    border: none;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.22s cubic-bezier(0.4, 0, 0.2, 1);
    position: relative;
    user-select: none;
    -webkit-tap-highlight-color: transparent;
    font-family: inherit;
    flex-shrink: 0;
}

.bugis-nav-btn svg {
    width: 17px;
    height: 17px;
    flex-shrink: 0;
    transition: transform 0.22s ease, color 0.22s ease;
}

.bugis-nav-btn span {
    font-size: 8.5px;
    font-weight: 500;
    line-height: 1.1;
    letter-spacing: -0.2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 100%;
    display: block;
    transition: color 0.22s ease, font-weight 0.22s ease;
}

.bugis-nav-btn:hover {
    color: var(--bugis-gold-light);
}

.bugis-nav-btn.active {
    color: var(--bugis-gold);
    background: rgba(223, 183, 108, 0.16);
}

.bugis-nav-btn.active svg {
    color: var(--bugis-gold);
    transform: translateY(-1px) scale(1.1);
}

.bugis-nav-btn.active span {
    color: #ffffff;
    font-weight: 600;
}

.bugis-nav-btn.active::after {
    content: '';
    position: absolute;
    bottom: 2px;
    left: 50%;
    transform: translateX(-50%);
    width: 4px;
    height: 4px;
    border-radius: 50%;
    background: var(--bugis-gold);
    box-shadow: 0 0 6px var(--bugis-gold);
}

/* Tablet & Standard Mobile (361px - 420px) */
@media (max-width: 420px) {
    .bugis-nav-wrap {
        padding: 0 6px;
        bottom: max(10px, env(safe-area-inset-bottom, 10px));
    }
    .bugis-nav {
        padding: 3px 4px;
        gap: 1px;
    }
    .bugis-nav-btn {
        min-width: 35px;
        height: 42px;
        padding: 2px 1px;
    }
    .bugis-nav-btn svg {
        width: 15px;
        height: 15px;
    }
    .bugis-nav-btn span {
        font-size: 8px;
        letter-spacing: -0.3px;
    }
}

/* Ultra-compact Mobile (<= 360px, e.g. Galaxy Fold / iPhone SE) */
@media (max-width: 360px) {
    .bugis-nav-wrap {
        padding: 0 4px;
        bottom: max(8px, env(safe-area-inset-bottom, 8px));
    }
    .bugis-nav {
        padding: 2px 3px;
        border-radius: 35px;
    }
    .bugis-nav-btn {
        min-width: 32px;
        height: 38px;
        padding: 1px 1px;
    }
    .bugis-nav-btn svg {
        width: 14px;
        height: 14px;
    }
    .bugis-nav-btn span {
        font-size: 7.5px;
        letter-spacing: -0.4px;
    }
    .bugis-nav-btn.active::after {
        width: 3px;
        height: 3px;
        bottom: 1.5px;
    }
}

/* Input Form */
.bugis-input {
    width: 100%;
    background: rgba(255, 255, 255, 0.07);
    border: 1px solid rgba(223, 183, 108, 0.3);
    border-radius: 12px;
    padding: 12px 16px;
    color: #ffffff;
    font-size: 13px;
    font-family: var(--font-body);
    transition: all 0.3s;
    outline: none;
    margin-bottom: 12px;
}
.bugis-input:focus {
    border-color: var(--bugis-gold);
    background: rgba(255, 255, 255, 0.12);
    box-shadow: 0 0 10px rgba(223, 183, 108, 0.2);
}
.bugis-input::placeholder {
    color: rgba(255, 255, 255, 0.4);
}

/* Circular Photo Frames */
.couple-photo-frame {
    width: 140px;
    height: 140px;
    margin: 0 auto 16px;
    border-radius: 50%;
    border: 3px solid var(--bugis-gold);
    padding: 4px;
    position: relative;
    box-shadow: 0 0 25px rgba(223, 183, 108, 0.35);
}
.couple-photo-frame img {
    width: 100%;
    height: 100%;
    border-radius: 50%;
    object-fit: cover;
}

/* Countdown Box */
.timer-box {
    background: rgba(45, 4, 12, 0.7);
    border: 1px solid var(--bugis-gold);
    border-radius: 12px;
    padding: 10px 8px;
    text-align: center;
    min-width: 65px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.3);
}
.timer-num {
    font-family: var(--font-heading);
    font-size: 24px;
    font-weight: 700;
    color: var(--bugis-gold);
    line-height: 1.1;
}
.timer-label {
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 1px;
    opacity: 0.8;
    margin-top: 2px;
}

/* Bank Account Card */
.bank-card {
    background: linear-gradient(135deg, rgba(65, 8, 20, 0.9) 0%, rgba(35, 3, 9, 0.95) 100%);
    border: 1px solid rgba(223, 183, 108, 0.35);
    border-radius: 16px;
    padding: 20px;
    position: relative;
    overflow: hidden;
    margin-bottom: 16px;
    box-shadow: 0 8px 25px rgba(0,0,0,0.4);
}
.bank-card::after {
    content: '';
    position: absolute;
    top: -50%;
    right: -50%;
    width: 200px;
    height: 200px;
    background: radial-gradient(circle, rgba(223, 183, 108, 0.1) 0%, transparent 70%);
    pointer-events: none;
}
</style>
@endpush

<div class="bugis-page-wrapper" x-data="{
    opened: false,
    playing: false,
    audioEl: null,
    autoScrollInterval: null,
    isAutoScrolling: false,
    lightboxOpen: false,
    lightboxImg: '',
    activeSection: 'hero',
    observer: null,

    open() {
        this.opened = true;
        this.$nextTick(() => {
            this.audioEl = document.getElementById('bugisAudio');
            if (this.audioEl) {
                this.audioEl.play().then(() => { this.playing = true; }).catch(() => {});
            }
            const heroVid = document.getElementById('heroVideo');
            if (heroVid) {
                heroVid.play().catch(() => {});
            }
            this.initScrollSpy();
            this.scrollTo('hero');
        });
    },

    scrollTo(id) {
        this.activeSection = id;
        const target = document.getElementById('section-' + id);
        if (target) {
            target.scrollIntoView({ behavior: 'smooth' });
        }
    },

    initScrollSpy() {
        if (this.observer) this.observer.disconnect();
        const sectionIds = ['hero', 'couple', 'events', 'gallery', 'story', 'gift', 'rsvp'];
        this.observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.id.replace('section-', '');
                    this.activeSection = id;
                }
            });
        }, {
            root: null,
            rootMargin: '-15% 0px -55% 0px',
            threshold: 0
        });

        sectionIds.forEach(id => {
            const el = document.getElementById('section-' + id);
            if (el) this.observer.observe(el);
        });
    },

    toggleAudio() {
        if (!this.audioEl) this.audioEl = document.getElementById('bugisAudio');
        if (!this.audioEl) return;
        if (this.playing) {
            this.audioEl.pause();
            this.playing = false;
        } else {
            this.audioEl.play().then(() => { this.playing = true; }).catch(() => {});
        }
    },

    toggleAutoScroll() {
        if (this.isAutoScrolling) {
            this.stopAutoScroll();
        } else {
            this.isAutoScrolling = true;
            this.autoScrollInterval = setInterval(() => {
                window.scrollBy({ top: 1.5, behavior: 'auto' });
                if ((window.innerHeight + window.scrollY) >= (document.body.offsetHeight - 15)) {
                    this.stopAutoScroll();
                }
            }, 25);
        }
    },

    stopAutoScroll() {
        if (this.autoScrollInterval) {
            clearInterval(this.autoScrollInterval);
            this.autoScrollInterval = null;
        }
        this.isAutoScrolling = false;
    },

    openLightbox(src) {
        this.lightboxImg = src;
        this.lightboxOpen = true;
    }
}"
@wheel.passive="if (isAutoScrolling) stopAutoScroll()"
@touchstart.passive="if (isAutoScrolling) stopAutoScroll()">

    {{-- Background Audio --}}
    @php
        $audioSource = $invitation->background_music 
            ?? $invitation->music_url 
            ?? asset('themes/3d-adat-bugis-02/assets/759IcESZZqg.mp3');
    @endphp
    <audio id="bugisAudio" loop preload="auto">
        <source src="{{ $audioSource }}" type="audio/mpeg">
    </audio>

    {{-- Floating Music Control (Visible after opened) --}}
    <button x-show="opened" x-transition.opacity type="button" @click="toggleAudio()" class="music-toggle-btn" :title="playing ? 'Matikan Musik' : 'Putar Musik'">
        <svg :class="{'music-disc-rotating': playing}" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <circle cx="12" cy="12" r="10"/>
            <circle cx="12" cy="12" r="3"/>
            <path d="M12 2a10 10 0 0 1 10 10"/>
        </svg>
    </button>

    {{-- =================================================== --}}
    {{-- COVER SCREEN (Fixed Overlay until Opened)           --}}
    {{-- =================================================== --}}
    <div x-show="!opened" x-transition:leave="transition ease-in-out duration-700" x-transition:leave-start="opacity-100 transform translate-y-0" x-transition:leave-end="opacity-0 transform -translate-y-full" style="position:fixed;inset:0;z-index:9999;background:#32030c;display:flex;align-items:center;justify-content:center;">
        <div class="bugis-container" style="height:100dvh;display:flex;flex-direction:column;justify-content:space-between;padding:50px 24px;text-align:center;background-image:url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : asset('themes/3d-adat-bugis-02/assets/COVER-BUKA-UNDANGAN.jpg') }}');background-size:cover;background-position:center;position:relative;">
            
            {{-- Dark Overlay --}}
            <div style="position:absolute;inset:0;background:linear-gradient(to bottom, rgba(50,3,12,0.65) 0%, rgba(50,3,12,0.4) 40%, rgba(50,3,12,0.7) 70%, rgba(40,2,9,0.95) 100%);z-index:1;"></div>

            {{-- Corner Ornaments --}}
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-tl" alt="">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-tr" alt="">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-bl" alt="">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-br" alt="">

            {{-- Top Header --}}
            <div style="position:relative;z-index:10;padding-top:20px;" class="animate__animated animate__fadeInDown">
                <p style="font-size:12px;letter-spacing:4px;text-transform:uppercase;color:var(--bugis-gold);font-weight:600;">The Wedding Of</p>
                <h1 class="font-script color-gold-light" style="font-size:44px;line-height:1.2;margin:10px 0;text-shadow:0 2px 15px rgba(0,0,0,0.8);">
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                </h1>
                @if($invitation->akad_date || $invitation->resepsi_date)
                <p style="font-size:13px;letter-spacing:3px;color:rgba(255,255,255,0.85);font-weight:500;">
                    {{ ($invitation->akad_date ?? $invitation->resepsi_date)->translatedFormat('d . m . Y') }}
                </p>
                @endif
            </div>

            {{-- Bottom Guest Info & Open Button --}}
            <div style="position:relative;z-index:10;padding-bottom:20px;" class="animate__animated animate__fadeInUp">
                <div class="bugis-card" style="margin-bottom:24px;padding:18px 16px;background:rgba(40,2,9,0.75);">
                    <p style="font-size:11px;letter-spacing:1px;color:rgba(255,255,255,0.7);margin-bottom:4px;">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                    <h2 class="font-heading color-gold" style="font-size:18px;font-weight:700;margin-bottom:2px;">
                        {{ $guestName ?? request('to', request('kpd', 'Nama Tamu')) }}
                    </h2>
                    <p style="font-size:11px;color:rgba(255,255,255,0.5);font-style:italic;">*Mohon maaf bila ada kesalahan penulisan nama/gelar</p>
                </div>

                <button type="button" @click="open()" class="btn-bugis-gold" style="width:100%;max-width:240px;margin:0 auto;padding:14px 28px;font-size:14px;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Buka Undangan
                </button>
            </div>
        </div>
    </div>

    {{-- =================================================== --}}
    {{-- MAIN INVITATION CONTENT (Inside Centered Mobile Container) --}}
    {{-- =================================================== --}}
    <div class="bugis-container">

        {{-- =============================================== --}}
        {{-- SECTION 1: 3D ANIMATION HERO VIDEO             --}}
        {{-- =============================================== --}}
        <section id="section-hero" style="position:relative;min-height:540px;display:flex;flex-direction:column;justify-content:flex-end;">
            <div class="motion-video-container" style="position:relative;">
                <video id="heroVideo" autoplay loop muted playsinline poster="{{ asset('themes/3d-adat-bugis-02/assets/BUGISMAROONFALLBACK.jpg') }}">
                    <source src="{{ asset('themes/3d-adat-bugis-02/assets/BUGIS-MAROON.mp4') }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
                <div style="position:absolute;inset:0;background:linear-gradient(to top, #3a0510 0%, rgba(58,5,16,0.2) 50%, rgba(58,5,16,0.6) 100%);"></div>
            </div>

            {{-- Text Overlay --}}
            <div style="position:absolute;bottom:25px;left:0;right:0;text-align:center;padding:0 20px;z-index:10;">
                <p style="font-size:11px;letter-spacing:4px;text-transform:uppercase;color:var(--bugis-gold);font-weight:600;margin-bottom:6px;">The Wedding Of</p>
                <h1 class="font-script color-gold-light" style="font-size:46px;line-height:1.1;text-shadow:0 3px 20px rgba(0,0,0,0.9);margin-bottom:8px;">
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                </h1>
                @if($invitation->akad_date || $invitation->resepsi_date)
                <p style="font-size:14px;letter-spacing:3px;color:#ffffff;font-weight:600;text-shadow:0 2px 10px rgba(0,0,0,0.8);">
                    {{ ($invitation->akad_date ?? $invitation->resepsi_date)->translatedFormat('d . m . Y') }}
                </p>
                @endif
                <div style="margin-top:14px;">
                    <a href="#section-quote" style="display:inline-block;color:var(--bugis-gold);animation:bounce 2s infinite;">
                        <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                    </a>
                </div>
            </div>
        </section>

        {{-- =============================================== --}}
        {{-- SECTION 2: QUOTE / AYAT SUCI (QS. AR-RUM 21)   --}}
        {{-- =============================================== --}}
        <section id="section-quote" style="padding:60px 24px;text-align:center;position:relative;">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-tl" alt="" style="width:80px;">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-tr" alt="" style="width:80px;">

            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:12px;">WE FOUND LOVE</p>
            
            <div class="bugis-divider">
                <img src="{{ asset('themes/3d-adat-bugis-02/assets/icon-bunga-1.png') }}" style="width:24px;height:auto;" alt="*">
            </div>

            <div class="bugis-card" style="margin-top:20px;padding:30px 22px;">
                <p class="font-quote" style="font-size:17px;line-height:1.9;color:rgba(255,255,255,0.92);font-style:italic;margin-bottom:16px;">
                    "Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang. Sungguh, pada yang demikian itu benar-benar terdapat tanda-tanda (kebesaran Allah) bagi kaum yang berpikir."
                </p>
                <p class="font-heading color-gold" style="font-size:13px;font-weight:600;letter-spacing:1px;">
                    (QS. Ar-Rum: 21)
                </p>
            </div>
        </section>

        {{-- =============================================== --}}
        {{-- SECTION 3: MEMPELAI (BRIDE & GROOM PROFILES)   --}}
        {{-- =============================================== --}}
        <section id="section-couple" style="padding:40px 24px;text-align:center;position:relative;">
            <p style="font-size:12px;letter-spacing:2px;text-transform:uppercase;color:var(--bugis-gold);font-weight:600;margin-bottom:6px;">Assalamu'alaikum Wr. Wb.</p>
            <p style="font-size:13px;line-height:1.7;color:rgba(255,255,255,0.8);max-width:380px;margin:0 auto 36px;">
                Dengan memohon rahmat dan ridho Allah Subhanahu Wa Ta'ala, kami mengundang Bapak/Ibu/Saudara/i untuk menghadiri pernikahan putra-putri kami:
            </p>

            @php
                $order = $invitation->custom_styles['name_order'] ?? 'groom_first';
                $isGroomFirst = ($order === 'groom_first');
            @endphp

            {{-- Mempelai 1 --}}
            <div class="bugis-card" style="margin-bottom:24px;">
                <div class="couple-photo-frame">
                    <img src="{{ ($isGroomFirst ? $invitation->groom_photo : $invitation->bride_photo) ? img_url($isGroomFirst ? $invitation->groom_photo : $invitation->bride_photo) : asset('themes/3d-adat-bugis-02/assets/' . ($isGroomFirst ? 'DSCF7838-1.jpeg' : 'DSCF7840-1.jpeg')) }}" alt="Mempelai 1">
                </div>
                <h3 class="font-heading color-gold" style="font-size:20px;font-weight:700;margin-bottom:6px;">
                    {{ $isGroomFirst ? $invitation->groom_name : $invitation->bride_name }}
                </h3>
                <p style="font-size:12px;color:rgba(255,255,255,0.8);line-height:1.6;margin-bottom:12px;">
                    {{ $isGroomFirst ? 'Putra tercinta dari' : 'Putri tercinta dari' }}<br>
                    <strong>{{ $isGroomFirst ? $invitation->groom_father : $invitation->bride_father }}</strong> & <strong>{{ $isGroomFirst ? $invitation->groom_mother : $invitation->bride_mother }}</strong>
                </p>
                @php $ig1 = $isGroomFirst ? $invitation->instagram_groom : $invitation->instagram_bride; @endphp
                @if($ig1)
                <a href="https://instagram.com/{{ ltrim($ig1, '@') }}" target="_blank" class="btn-bugis-outline" style="font-size:11px;padding:6px 16px;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>{{ $ig1 }}</span>
                </a>
                @endif
            </div>

            {{-- Bugis '&' Calligraphy Divider --}}
            <div style="margin:16px 0;">
                <img src="{{ asset('themes/3d-adat-bugis-02/assets/dan-bugis-1.png') }}" style="max-width:70px;margin:0 auto;display:block;" alt="&">
            </div>

            {{-- Mempelai 2 --}}
            <div class="bugis-card" style="margin-top:24px;">
                <div class="couple-photo-frame">
                    <img src="{{ (!$isGroomFirst ? $invitation->groom_photo : $invitation->bride_photo) ? img_url(!$isGroomFirst ? $invitation->groom_photo : $invitation->bride_photo) : asset('themes/3d-adat-bugis-02/assets/' . (!$isGroomFirst ? 'DSCF7838-1.jpeg' : 'DSCF7840-1.jpeg')) }}" alt="Mempelai 2">
                </div>
                <h3 class="font-heading color-gold" style="font-size:20px;font-weight:700;margin-bottom:6px;">
                    {{ !$isGroomFirst ? $invitation->groom_name : $invitation->bride_name }}
                </h3>
                <p style="font-size:12px;color:rgba(255,255,255,0.8);line-height:1.6;margin-bottom:12px;">
                    {{ !$isGroomFirst ? 'Putra tercinta dari' : 'Putri tercinta dari' }}<br>
                    <strong>{{ !$isGroomFirst ? $invitation->groom_father : $invitation->bride_father }}</strong> & <strong>{{ !$isGroomFirst ? $invitation->groom_mother : $invitation->bride_mother }}</strong>
                </p>
                @php $ig2 = !$isGroomFirst ? $invitation->instagram_groom : $invitation->instagram_bride; @endphp
                @if($ig2)
                <a href="https://instagram.com/{{ ltrim($ig2, '@') }}" target="_blank" class="btn-bugis-outline" style="font-size:11px;padding:6px 16px;">
                    <svg width="14" height="14" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                    <span>{{ $ig2 }}</span>
                </a>
                @endif
            </div>
        </section>

        {{-- =============================================== --}}
        {{-- SECTION 4: RANGKAIAN ACARA (AKAD & RESEPSI)    --}}
        {{-- =============================================== --}}
        <section id="section-events" style="padding:40px 24px;text-align:center;">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:8px;">WAKTU & TEMPAT</p>
            <h2 class="font-script color-gold-light" style="font-size:38px;margin-bottom:20px;">Rangkaian Acara</h2>

            {{-- Akad Nikah --}}
            <div class="bugis-card" style="margin-bottom:24px;">
                <div style="width:48px;height:48px;border-radius:50%;background:rgba(223,183,108,0.15);border:1px solid var(--bugis-gold);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:var(--bugis-gold);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                </div>
                <h3 class="font-heading color-gold" style="font-size:22px;font-weight:700;margin-bottom:8px;">Akad Nikah</h3>
                <div class="bugis-divider" style="margin:10px auto 14px;"></div>
                <p style="font-size:14px;font-weight:600;margin-bottom:4px;">
                    {{ $invitation->akad_date?->translatedFormat('l, d F Y') ?? 'Senin, 10 Agustus 2026' }}
                </p>
                <p style="font-size:12px;color:var(--bugis-gold);margin-bottom:14px;">
                    {{ $invitation->akad_time ?? ($invitation->akad_date ? $invitation->akad_date->format('H:i') . ' WITA - Selesai' : '08:00 WITA - Selesai') }}
                </p>
                <p style="font-size:13px;font-weight:600;margin-bottom:4px;">{{ $invitation->akad_venue ?? 'Kediaman Mempelai' }}</p>
                <p style="font-size:12px;color:rgba(255,255,255,0.7);line-height:1.6;margin-bottom:18px;">{{ $invitation->akad_address ?? 'Jl. Suka Karya, Pekanbaru' }}</p>
                @if($invitation->akad_maps_link)
                <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn-bugis-gold" style="font-size:12px;padding:9px 20px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Buka Google Maps
                </a>
                @endif
            </div>

            {{-- Resepsi Pernikahan --}}
            <div class="bugis-card">
                <div style="width:48px;height:48px;border-radius:50%;background:rgba(223,183,108,0.15);border:1px solid var(--bugis-gold);display:flex;align-items:center;justify-content:center;margin:0 auto 14px;color:var(--bugis-gold);">
                    <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.701 2.701 0 00-1.5-.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M3 21h18M3 10h18M3 7l9-4 9 4v14H3V7z"/></svg>
                </div>
                <h3 class="font-heading color-gold" style="font-size:22px;font-weight:700;margin-bottom:8px;">Resepsi Pernikahan</h3>
                <div class="bugis-divider" style="margin:10px auto 14px;"></div>
                <p style="font-size:14px;font-weight:600;margin-bottom:4px;">
                    {{ $invitation->resepsi_date?->translatedFormat('l, d F Y') ?? 'Senin, 10 Agustus 2026' }}
                </p>
                <p style="font-size:12px;color:var(--bugis-gold);margin-bottom:14px;">
                    {{ $invitation->resepsi_time ?? ($invitation->resepsi_date ? $invitation->resepsi_date->format('H:i') . ' WITA - Selesai' : '11:00 WITA - Selesai') }}
                </p>
                <p style="font-size:13px;font-weight:600;margin-bottom:4px;">{{ $invitation->resepsi_venue ?? 'Gedung Serbaguna' }}</p>
                <p style="font-size:12px;color:rgba(255,255,255,0.7);line-height:1.6;margin-bottom:18px;">{{ $invitation->resepsi_address ?? 'Jl. Suka Karya, Pekanbaru' }}</p>
                @if($invitation->resepsi_maps_link)
                <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn-bugis-gold" style="font-size:12px;padding:9px 20px;">
                    <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    Buka Google Maps
                </a>
                @endif
            </div>
        </section>

        {{-- =============================================== --}}
        {{-- SECTION 5: COUNTDOWN TIMER & CALENDAR          --}}
        {{-- =============================================== --}}
        @php
            $targetDate = $invitation->akad_date ?? $invitation->resepsi_date ?? now()->addMonths(2);
            $targetIso = $targetDate->toIso8601String();
            $gCalUrl = "https://www.google.com/calendar/render?action=TEMPLATE&text=" 
                . urlencode("Wedding of {$invitation->groom_nickname} & {$invitation->bride_nickname}")
                . "&dates=" . $targetDate->format('Ymd\THis\Z') . "/" . $targetDate->copy()->addHours(4)->format('Ymd\THis\Z')
                . "&details=" . urlencode("Undangan Pernikahan {$invitation->groom_name} & {$invitation->bride_name}")
                . "&location=" . urlencode($invitation->akad_venue ?? $invitation->resepsi_venue ?? '');
        @endphp
        <section id="section-countdown" style="padding:40px 24px;text-align:center;" x-data="{
            target: new Date('{{ $targetIso }}').getTime(),
            days: 0, hours: 0, minutes: 0, seconds: 0,
            updateTimer() {
                const now = new Date().getTime();
                const diff = this.target - now;
                if (diff > 0) {
                    this.days = Math.floor(diff / (1000 * 60 * 60 * 24));
                    this.hours = Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                    this.minutes = Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60));
                    this.seconds = Math.floor((diff % (1000 * 60)) / 1000);
                }
            },
            init() {
                this.updateTimer();
                setInterval(() => this.updateTimer(), 1000);
            }
        }">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:6px;">MENUJU HARI BAHAGIA</p>
            <h2 class="font-script color-gold-light" style="font-size:36px;margin-bottom:20px;">Counting The Days</h2>

            <div style="display:flex;justify-content:center;gap:10px;margin-bottom:24px;">
                <div class="timer-box">
                    <div class="timer-num" x-text="days">00</div>
                    <div class="timer-label">Hari</div>
                </div>
                <div class="timer-box">
                    <div class="timer-num" x-text="hours">00</div>
                    <div class="timer-label">Jam</div>
                </div>
                <div class="timer-box">
                    <div class="timer-num" x-text="minutes">00</div>
                    <div class="timer-label">Menit</div>
                </div>
                <div class="timer-box">
                    <div class="timer-num" x-text="seconds">00</div>
                    <div class="timer-label">Detik</div>
                </div>
            </div>

            <a href="{{ $gCalUrl }}" target="_blank" class="btn-bugis-outline" style="font-size:12px;">
                <svg width="15" height="15" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                Simpan di Google Calendar
            </a>
        </section>

        {{-- =============================================== --}}
        {{-- SECTION 6: GALERI FOTO (OUR GALLERY)           --}}
        {{-- =============================================== --}}
        @if($invitation->enable_gallery)
        <section id="section-gallery" style="padding:40px 24px;text-align:center;">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:6px;">MOMEN BAHAGIA</p>
            <h2 class="font-script color-gold-light" style="font-size:38px;margin-bottom:8px;">Our Gallery</h2>
            <p style="font-size:12px;color:rgba(255,255,255,0.7);font-style:italic;margin-bottom:24px;">
                "Constantly, consistently, continually, You."
            </p>

            @php
                $galleryPhotos = $invitation->gallery_images ?? [];
                if (empty($galleryPhotos) && $invitation->photos()->exists()) {
                    $galleryPhotos = $invitation->photos()->pluck('file_path')->toArray();
                }
                if (empty($galleryPhotos)) {
                    $galleryPhotos = [
                        asset('themes/3d-adat-bugis-02/assets/DSCF7838-1.jpeg'),
                        asset('themes/3d-adat-bugis-02/assets/DSCF7840-1.jpeg'),
                        asset('themes/3d-adat-bugis-02/assets/DSCF7849-1.jpeg'),
                        asset('themes/3d-adat-bugis-02/assets/DSCF7876-1.jpeg'),
                        asset('themes/3d-adat-bugis-02/assets/DSCF7882-1.jpeg'),
                        asset('themes/3d-adat-bugis-02/assets/DSCF7920-1.jpeg'),
                    ];
                }
            @endphp

            <div style="display:grid;grid-template-columns:repeat(2, 1fr);gap:10px;">
                @foreach($galleryPhotos as $index => $photo)
                    @php
                        $photoUrl = str_starts_with($photo, 'http') || str_starts_with($photo, '/themes') 
                            ? $photo 
                            : img_url($photo);
                    @endphp
                    <div @click="openLightbox('{{ $photoUrl }}')" style="border-radius:12px;overflow:hidden;border:1px solid rgba(223,183,108,0.3);aspect-ratio:1/1;cursor:pointer;position:relative;" class="hover:opacity-90 transition">
                        <img src="{{ $photoUrl }}" style="width:100%;height:100%;object-fit:cover;" alt="Gallery {{ $index + 1 }}">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =============================================== --}}
        {{-- SECTION 7: CERITA CINTA (LOVE STORY)           --}}
        {{-- =============================================== --}}
        @if(!empty($invitation->love_story) && count($invitation->love_story) > 0)
        <section id="section-story" style="padding:40px 24px;text-align:center;">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:6px;">KISAH KAMI</p>
            <h2 class="font-script color-gold-light" style="font-size:38px;margin-bottom:8px;">Love Story</h2>
            <p style="font-size:12px;color:rgba(255,255,255,0.7);font-style:italic;margin-bottom:28px;">
                "Every love story is beautiful, but ours is my favorite."
            </p>

            <div style="position:relative;padding-left:20px;border-left:2px solid rgba(223,183,108,0.3);margin-left:14px;text-align:left;">
                @foreach($invitation->love_story as $story)
                <div style="margin-bottom:24px;position:relative;">
                    <div style="position:absolute;left:-27px;top:6px;width:12px;height:12px;border-radius:50%;background:var(--bugis-gold);border:2px solid #3a0510;box-shadow:0 0 10px rgba(223,183,108,0.5);"></div>
                    <div class="bugis-card" style="padding:16px;">
                        @if(!empty($story['year']) || !empty($story['date']))
                        <div class="color-gold" style="font-size:11px;font-weight:700;letter-spacing:1px;margin-bottom:4px;">
                            {{ $story['year'] ?? $story['date'] }}
                        </div>
                        @endif
                        <h4 class="font-heading" style="font-size:15px;color:#ffffff;margin-bottom:6px;">{{ $story['title'] ?? '' }}</h4>
                        <p style="font-size:12px;color:rgba(255,255,255,0.75);line-height:1.6;">{{ $story['description'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =============================================== --}}
        {{-- SECTION 8: WEDDING GIFT / AMPLOP DIGITAL       --}}
        {{-- =============================================== --}}
        @if($invitation->enable_gift)
        <section id="section-gift" style="padding:40px 24px;text-align:center;" x-data="{ showGift: true }">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:6px;">TANDA KASIH</p>
            <h2 class="font-script color-gold-light" style="font-size:38px;margin-bottom:12px;">Wedding Gift</h2>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);line-height:1.7;max-width:380px;margin:0 auto 24px;">
                Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Dan jika memberi adalah ungkapan tanda kasih Anda, Anda dapat memberi kado secara cashless.
            </p>

            <button type="button" @click="showGift = !showGift" class="btn-bugis-gold" style="margin-bottom:24px;">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span x-text="showGift ? 'Tutup Amplop Digital' : 'Kirim Hadiah / Amplop'"></span>
            </button>

            <div x-show="showGift" x-transition.duration.400ms style="text-align:left;">
                @php
                    $bankAccounts = $invitation->bank_accounts ?? [];
                    if (empty($bankAccounts) && !empty($invitation->bank_name)) {
                        $bankAccounts = [[
                            'bank' => $invitation->bank_name,
                            'account_number' => $invitation->bank_account,
                            'account_name' => $invitation->bank_holder,
                        ]];
                    }
                    if (empty($bankAccounts)) {
                        $bankAccounts = [
                            ['bank' => 'Bank Central Asia (BCA)', 'account_number' => '8210394821', 'account_name' => $invitation->groom_name],
                            ['bank' => 'Bank Negara Indonesia (BNI)', 'account_number' => '1394820194', 'account_name' => $invitation->bride_name],
                        ];
                    }
                @endphp

                @foreach($bankAccounts as $acc)
                <div class="bank-card" x-data="{ copied: false }">
                    <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
                        <span class="font-heading color-gold" style="font-size:14px;font-weight:700;">{{ $acc['bank'] ?? 'Rekening Bank' }}</span>
                        <svg width="22" height="22" fill="none" stroke="var(--bugis-gold)" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                    </div>
                    <div style="font-family:monospace;font-size:18px;font-weight:700;letter-spacing:2px;color:#ffffff;margin-bottom:4px;">
                        {{ $acc['account_number'] ?? '-' }}
                    </div>
                    <div style="font-size:12px;color:rgba(255,255,255,0.7);margin-bottom:14px;">
                        a.n {{ $acc['account_name'] ?? '-' }}
                    </div>
                    <button type="button" @click="navigator.clipboard.writeText('{{ $acc['account_number'] ?? '' }}'); copied = true; setTimeout(() => copied = false, 2500)" class="btn-bugis-outline" style="font-size:11px;padding:6px 14px;">
                        <svg width="13" height="13" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/></svg>
                        <span x-text="copied ? '✓ Berhasil Disalin' : 'Salin Nomor Rekening'"></span>
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- =============================================== --}}
        {{-- SECTION 9: RSVP & UCAPAN / DOA (BEST WISHES)   --}}
        {{-- =============================================== --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="section-rsvp" style="padding:40px 24px;text-align:center;">
            <p class="font-heading color-gold" style="font-size:12px;letter-spacing:3px;text-transform:uppercase;margin-bottom:6px;">BUKU TAMU</p>
            <h2 class="font-script color-gold-light" style="font-size:38px;margin-bottom:8px;">Best Wishes & RSVP</h2>
            <p style="font-size:12px;color:rgba(255,255,255,0.75);margin-bottom:24px;">
                Sampaikan doa dan konfirmasi kehadiran Anda untuk kedua mempelai
            </p>

            {{-- Flash Alert --}}
            @if (session()->has('rsvp_success'))
            <div style="background:rgba(34, 197, 94, 0.2);border:1px solid #22c55e;color:#bbf7d0;padding:12px 16px;border-radius:12px;font-size:13px;margin-bottom:16px;">
                {{ session('rsvp_success') }}
            </div>
            @endif

            {{-- RSVP Form --}}
            <div class="bugis-card" style="text-align:left;margin-bottom:28px;">
                <form wire:submit.prevent="submitRSVP">
                    <label style="font-size:12px;color:var(--bugis-gold);font-weight:600;display:block;margin-bottom:4px;">Nama Lengkap *</label>
                    <input type="text" wire:model="rsvpName" class="bugis-input" placeholder="Masukkan nama Anda">
                    @if(isset($errors) && $errors->has('rsvpName')) <span style="color:#f87171;font-size:11px;display:block;margin:-8px 0 8px;">{{ $errors->first('rsvpName') }}</span> @endif

                    @if($invitation->enable_rsvp)
                    <label style="font-size:12px;color:var(--bugis-gold);font-weight:600;display:block;margin-bottom:4px;">Konfirmasi Kehadiran *</label>
                    <select wire:model="rsvpStatus" class="bugis-input" style="background:#2a030b;">
                        <option value="confirmed">Hadir</option>
                        <option value="declined">Berhalangan Hadir</option>
                        <option value="tentative">Masih Ragu</option>
                    </select>

                    <label style="font-size:12px;color:var(--bugis-gold);font-weight:600;display:block;margin-bottom:4px;">Jumlah Tamu</label>
                    <select wire:model="rsvpGuests" class="bugis-input" style="background:#2a030b;">
                        <option value="1">1 Orang</option>
                        <option value="2">2 Orang</option>
                        <option value="3">3 Orang</option>
                        <option value="4">4 Orang</option>
                        <option value="5">5 Orang</option>
                    </select>
                    @endif

                    <label style="font-size:12px;color:var(--bugis-gold);font-weight:600;display:block;margin-bottom:4px;">Ucapan & Doa Restu *</label>
                    <textarea wire:model="rsvpMessage" rows="3" class="bugis-input" placeholder="Tuliskan ucapan dan doa terbaik Anda..."></textarea>
                    @if(isset($errors) && $errors->has('rsvpMessage')) <span style="color:#f87171;font-size:11px;display:block;margin:-8px 0 8px;">{{ $errors->first('rsvpMessage') }}</span> @endif

                    <button type="submit" wire:loading.attr="disabled" class="btn-bugis-gold" style="width:100%;margin-top:6px;">
                        <span wire:loading.remove>Kirim Ucapan & Konfirmasi</span>
                        <span wire:loading>Mengirim...</span>
                    </button>
                </form>
            </div>

            {{-- Feed Daftar Ucapan --}}
            <div style="text-align:left;">
                <h4 class="font-heading color-gold" style="font-size:14px;letter-spacing:1px;margin-bottom:12px;">
                    Ucapan & Doa ({{ count($wishes) }})
                </h4>
                <div style="max-height:380px;overflow-y:auto;display:flex;flex-direction:column;gap:10px;padding-right:4px;">
                    @forelse($wishes as $wish)
                    <div class="bugis-card" style="padding:14px;background:rgba(35,3,9,0.7);">
                        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;">
                            <span style="font-weight:600;font-size:13px;color:var(--bugis-gold);">{{ $wish->name }}</span>
                            @if($wish->attendance_status === 'confirmed')
                                <span style="font-size:9px;background:rgba(34,197,94,0.2);color:#4ade80;border:1px solid #22c55e;padding:2px 6px;border-radius:20px;">Hadir</span>
                            @elseif($wish->attendance_status === 'declined')
                                <span style="font-size:9px;background:rgba(239,68,68,0.2);color:#f87171;border:1px solid #ef4444;padding:2px 6px;border-radius:20px;">Berhalangan</span>
                            @endif
                        </div>
                        <p style="font-size:12px;color:rgba(255,255,255,0.85);line-height:1.5;">{{ $wish->message }}</p>
                        <span style="font-size:10px;color:rgba(255,255,255,0.4);display:block;margin-top:4px;">{{ $wish->created_at?->diffForHumans() }}</span>
                    </div>
                    @empty
                    <div style="text-align:center;padding:20px;font-size:12px;color:rgba(255,255,255,0.5);">
                        Belum ada ucapan. Jadilah yang pertama memberikan doa restu!
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
        @endif

        {{-- =============================================== --}}
        {{-- SECTION 10: PENUTUP (CLOSING)                  --}}
        {{-- =============================================== --}}
        <section id="section-closing" style="padding:60px 24px 100px;text-align:center;position:relative;">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-bl" alt="" style="width:80px;">
            <img src="{{ asset('themes/3d-adat-bugis-02/assets/bunga-bugis.png') }}" class="corner-ornament corner-br" alt="" style="width:80px;">

            <p style="font-size:13px;color:rgba(255,255,255,0.8);line-height:1.8;max-width:380px;margin:0 auto 24px;">
                Menjadi sebuah kebahagiaan bagi kami apabila Bapak/Ibu/Saudara/i berkenan hadir dalam hari bahagia ini. Terima kasih atas segala doa, ucapan dan perhatian yang diberikan.
            </p>

            <p style="font-size:12px;letter-spacing:1px;text-transform:uppercase;color:var(--bugis-gold);margin-bottom:8px;">Kami yang berbahagia,</p>
            <h2 class="font-script color-gold-light" style="font-size:42px;margin-bottom:16px;">
                {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
            </h2>
            <p style="font-size:11px;color:rgba(255,255,255,0.6);letter-spacing:0.5px;">Keluarga Besar Kedua Mempelai</p>

            <div class="bugis-divider" style="margin:24px auto;"></div>

            <p style="font-size:10px;color:rgba(255,255,255,0.4);letter-spacing:1px;">
                © {{ date('Y') }} {{ config('app.name', 'Exo Expanse') }} · All Rights Reserved
            </p>
        </section>

    </div>

    {{-- =================================================== --}}
    {{-- FLOATING BOTTOM NAV (Visible after opened)          --}}
    {{-- =================================================== --}}
    <div x-show="opened" 
         x-transition:enter="transition ease-out duration-300" 
         x-transition:enter-start="opacity-0 transform translate-y-8" 
         x-transition:enter-end="opacity-100 transform translate-y-0" 
         class="bugis-nav-wrap">
        <nav class="bugis-nav" aria-label="Navigasi Undangan">
            {{-- 1. Awal --}}
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'hero'}" 
                    @click="scrollTo('hero')" 
                    title="Awal">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Awal</span>
            </button>

            {{-- 2. Mempelai --}}
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'couple'}" 
                    @click="scrollTo('couple')" 
                    title="Mempelai">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                <span>Mempelai</span>
            </button>

            {{-- 3. Acara --}}
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'events'}" 
                    @click="scrollTo('events')" 
                    title="Acara">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Acara</span>
            </button>

            {{-- 4. Galeri --}}
            @if($invitation->enable_gallery)
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'gallery'}" 
                    @click="scrollTo('gallery')" 
                    title="Galeri">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Galeri</span>
            </button>
            @endif

            {{-- 5. Cerita Cinta (jika ada) --}}
            @if(!empty($invitation->love_story) && count($invitation->love_story) > 0)
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'story'}" 
                    @click="scrollTo('story')" 
                    title="Cerita">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Cerita</span>
            </button>
            @endif

            {{-- 6. Hadiah --}}
            @if($invitation->enable_gift)
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'gift'}" 
                    @click="scrollTo('gift')" 
                    title="Hadiah">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Hadiah</span>
            </button>
            @endif

            {{-- 7. Ucapan / RSVP --}}
            @if($invitation->enable_rsvp || $invitation->enable_wishes)
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': activeSection === 'rsvp'}" 
                    @click="scrollTo('rsvp')" 
                    title="Ucapan">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                <span>Ucapan</span>
            </button>
            @endif

            {{-- 8. Auto Scroll --}}
            <button type="button" 
                    class="bugis-nav-btn" 
                    :class="{'active': isAutoScrolling}" 
                    @click="toggleAutoScroll()" 
                    :title="isAutoScrolling ? 'Hentikan Scroll Otomatis' : 'Mulai Scroll Otomatis'">
                <template x-if="!isAutoScrolling">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 13l-7 7-7-7m14-8l-7 7-7-7"/></svg>
                </template>
                <template x-if="isAutoScrolling">
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </template>
                <span x-text="isAutoScrolling ? 'Stop' : 'Auto'">Auto</span>
            </button>
        </nav>
    </div>

    {{-- =================================================== --}}
    {{-- LIGHTBOX MODAL FOR GALLERY                         --}}
    {{-- =================================================== --}}
    <div x-show="lightboxOpen" x-transition.opacity style="position:fixed;inset:0;z-index:99999;background:rgba(0,0,0,0.92);display:flex;align-items:center;justify-content:center;padding:20px;" @click="lightboxOpen = false">
        <button type="button" @click="lightboxOpen = false" style="position:absolute;top:20px;right:20px;color:white;background:rgba(255,255,255,0.2);border:none;border-radius:50%;width:36px;height:36px;cursor:pointer;display:flex;align-items:center;justify-content:center;">✕</button>
        <img :src="lightboxImg" style="max-width:95vw;max-height:85vh;border-radius:8px;box-shadow:0 0 30px rgba(0,0,0,0.8);" @click.stop alt="Preview">
    </div>

</div>
