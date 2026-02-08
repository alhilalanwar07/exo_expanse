@section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,500;0,600;0,700;1,400&family=Italiana&family=Lora:ital,wght@0,400;0,500;1,400&family=Pinyon+Script&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
:root {
    /* Mapped from Dynamic Theme Config */
    --rose: var(--color-primary, #E8919B);
    --rose-dark: var(--color-secondary, #D4727E); /* Using secondary as darker shade */
    --rose-light: color-mix(in srgb, var(--color-primary), white 85%);
    
    --sage: var(--color-accent, #9CAF88);
    --cream: var(--color-background, #FFFBFC);
    --dark: var(--color-heading, #3D3D3D);
    --text: var(--color-text, #5A5A5A);
}

* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: var(--font-body, 'Lora', serif); background: var(--cream); color: var(--text); overflow-x: hidden; }
.font-display { font-family: var(--font-heading, 'Playfair Display', serif); }
.font-script { font-family: var(--font-accent, 'Pinyon Script', cursive); }
.font-elegant { font-family: var(--font-heading, 'Italiana', serif); }

@keyframes fadeUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
@keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
@keyframes bloom { 0%, 100% { transform: scale(1); } 50% { transform: scale(1.02); } }
.animate-fade-up { animation: fadeUp 0.8s ease forwards; }
.animate-float { animation: float 3s ease-in-out infinite; }

.cover { position: fixed; inset: 0; z-index: 100; display: flex; align-items: center; justify-content: center; }
.cover-bg { position: absolute; inset: 0; background-size: cover; background-position: center; }
.cover-overlay { position: absolute; inset: 0; background: linear-gradient(180deg, rgba(232,145,155,0.2) 0%, rgba(0,0,0,0.6) 100%); }

.section { padding: 80px 20px; position: relative; }
.section-title { text-align: center; margin-bottom: 50px; }
.section-title h2 { font-family: 'Playfair Display', serif; font-size: 2.5rem; color: var(--dark); font-weight: 500; }
.section-title .subtitle { font-family: 'Pinyon Script', cursive; font-size: 1.5rem; color: var(--rose-dark); margin-bottom: 10px; }
.section-title .divider { width: 100px; height: 1px; background: linear-gradient(90deg, transparent, var(--rose), transparent); margin: 20px auto; }

.card { background: white; border-radius: 25px; box-shadow: 0 15px 50px rgba(232,145,155,0.12); overflow: hidden; }

.btn { display: inline-flex; align-items: center; gap: 8px; padding: 14px 32px; border-radius: 50px; font-weight: 500; transition: all 0.3s; cursor: pointer; border: none; text-decoration: none; font-family: 'Lora', serif; }
.btn-rose { background: linear-gradient(135deg, var(--rose), var(--rose-dark)); color: white; }
.btn-rose:hover { transform: translateY(-2px); box-shadow: 0 10px 30px rgba(232,145,155,0.4); }
.btn-outline { background: transparent; border: 2px solid var(--rose); color: var(--rose-dark); }
.btn-outline:hover { background: var(--rose); color: white; }

.countdown-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; max-width: 450px; margin: 0 auto; }
.countdown-item { background: white; padding: 20px 10px; border-radius: 20px; text-align: center; box-shadow: 0 10px 30px rgba(232,145,155,0.12); border: 1px solid var(--rose-light); }
.countdown-num { font-family: 'Playfair Display', serif; font-size: 2.5rem; font-weight: 600; color: var(--rose-dark); }
.countdown-label { font-size: 0.7rem; color: var(--text); text-transform: uppercase; letter-spacing: 2px; margin-top: 5px; }

.couple-card { text-align: center; padding: 40px 25px; position: relative; }
.couple-photo { width: 200px; height: 200px; border-radius: 50%; object-fit: cover; border: 8px solid white; box-shadow: 0 15px 40px rgba(232,145,155,0.25); margin: 0 auto 25px; }
.couple-name { font-family: 'Playfair Display', serif; font-size: 1.8rem; color: var(--dark); margin-bottom: 5px; font-weight: 500; }

.event-card { background: white; border-radius: 25px; padding: 35px; margin-bottom: 25px; box-shadow: 0 15px 50px rgba(232,145,155,0.1); position: relative; overflow: hidden; }
.event-card::before { content: ''; position: absolute; top: 0; left: 0; width: 5px; height: 100%; background: linear-gradient(180deg, var(--rose), var(--sage)); }
.event-icon { width: 60px; height: 60px; border-radius: 50%; background: linear-gradient(135deg, var(--rose-light), white); display: flex; align-items: center; justify-content: center; color: var(--rose-dark); margin-bottom: 20px; }

.form-input { width: 100%; padding: 15px 20px; border: 2px solid #FFE4E8; border-radius: 15px; font-size: 15px; transition: all 0.3s; background: white; font-family: 'Lora', serif; }
.form-input:focus { outline: none; border-color: var(--rose); box-shadow: 0 0 0 4px rgba(232,145,155,0.15); }
.form-select { appearance: none; background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 24 24' stroke='%23E8919B'%3E%3Cpath stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M19 9l-7 7-7-7'/%3E%3C/svg%3E"); background-repeat: no-repeat; background-position: right 12px center; background-size: 20px; }

.gallery-grid { display: grid; grid-template-columns: repeat(3, 1fr); gap: 12px; }
.gallery-item { aspect-ratio: 1; overflow: hidden; border-radius: 15px; cursor: pointer; }
.gallery-item img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
.gallery-item:hover img { transform: scale(1.1); }

.gift-card { background: linear-gradient(135deg, var(--rose), var(--rose-dark)); color: white; border-radius: 20px; padding: 25px; margin-bottom: 15px; }
.copy-btn { background: rgba(255,255,255,0.2); border: none; color: white; padding: 10px 20px; border-radius: 10px; cursor: pointer; font-size: 13px; transition: all 0.3s; }
.copy-btn:hover { background: rgba(255,255,255,0.3); }

.wish-item { background: var(--rose-light); border-radius: 20px; padding: 20px; margin-bottom: 15px; }
.wish-avatar { width: 45px; height: 45px; border-radius: 50%; background: linear-gradient(135deg, var(--rose), var(--rose-dark)); color: white; display: flex; align-items: center; justify-content: center; font-weight: 600; flex-shrink: 0; }

.stats-box { display: flex; gap: 20px; justify-content: center; margin-bottom: 30px; }
.stat-item { display: flex; align-items: center; gap: 8px; font-size: 14px; }
.stat-dot { width: 10px; height: 10px; border-radius: 50%; }

.bottom-nav { position: fixed; bottom: 0; left: 0; right: 0; background: white; box-shadow: 0 -5px 30px rgba(232,145,155,0.15); z-index: 90; padding: 12px 0; }
.nav-items { display: flex; justify-content: space-around; max-width: 450px; margin: 0 auto; }
.nav-item { display: flex; flex-direction: column; align-items: center; gap: 4px; color: var(--text); text-decoration: none; font-size: 9px; padding: 5px 10px; transition: color 0.3s; text-transform: uppercase; letter-spacing: 1px; }
.nav-item.active, .nav-item:hover { color: var(--rose-dark); }
.nav-item svg { width: 20px; height: 20px; }

.music-btn { position: fixed; bottom: 90px; right: 20px; width: 50px; height: 50px; border-radius: 50%; background: var(--rose); color: white; display: flex; align-items: center; justify-content: center; cursor: pointer; z-index: 95; box-shadow: 0 5px 25px rgba(232,145,155,0.4); transition: all 0.3s; border: none; }
.music-btn:hover { transform: scale(1.1); }
.music-btn.playing { animation: bloom 2s infinite; }

.quote-section { background: linear-gradient(135deg, var(--rose-light) 0%, white 100%); padding: 60px 20px; text-align: center; }
.quote-text { font-family: 'Playfair Display', serif; font-size: 1.3rem; font-style: italic; color: var(--dark); max-width: 600px; margin: 0 auto; line-height: 1.8; }

@media (max-width: 640px) {
    .countdown-num { font-size: 1.8rem; }
    .section { padding: 60px 15px; }
    .couple-photo { width: 160px; height: 160px; }
    .gallery-grid { grid-template-columns: repeat(2, 1fr); }
    .quote-text { font-size: 1.1rem; }
}
</style>
@endpush

<div x-data="{
    opened: false,
    playing: false,
    
    init() { document.body.style.overflow = 'hidden'; },
    open() {
        this.opened = true;
        document.body.style.overflow = 'auto';
        this.playAudio();
    },
    playAudio() {
        if (this.$refs.audio) {
            this.$refs.audio.play().then(() => this.playing = true).catch(() => {});
        }
    },
    toggleAudio() {
        if (!this.$refs.audio) return;
        if (this.playing) { this.$refs.audio.pause(); } 
        else { this.$refs.audio.play(); }
        this.playing = !this.playing;
    },
    scrollTo(id) {
        document.getElementById(id)?.scrollIntoView({ behavior: 'smooth', block: 'start' });
    },
    copyText(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor rekening disalin!');
    }
}">
    @if($invitation->music_url)
    <audio x-ref="audio" loop src="{{ asset('storage/' . $invitation->music_url) }}"></audio>
    @endif

    {{-- COVER --}}
    <div x-show="!opened" x-transition:leave="transition duration-700" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="cover">
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=1200' }}');"></div>
        <div class="cover-overlay"></div>
        
        <div class="relative z-10 text-center text-white px-6 max-w-md">
            <p class="font-elegant text-lg tracking-[0.4em] uppercase mb-8 opacity-90">The Wedding Of</p>
            <h1 class="font-script text-7xl mb-2">{{ $invitation->groom_nickname }}</h1>
            <p class="font-script text-5xl opacity-80 my-4">&</p>
            <h1 class="font-script text-7xl mb-10">{{ $invitation->bride_nickname }}</h1>
            
            <div class="w-24 h-px bg-gradient-to-r from-transparent via-white/50 to-transparent mx-auto mb-10"></div>
            
            <p class="font-elegant text-sm tracking-[0.3em] uppercase opacity-70 mb-2">Kepada Yth.</p>
            <p class="font-display text-2xl font-medium mb-10">{{ $guestName }}</p>
            
            <button @click="open()" class="btn btn-rose animate-fade-up">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
                Buka Undangan
            </button>
        </div>
    </div>

    {{-- MAIN --}}
    <main class="pb-20" x-show="opened" x-transition>
        
        {{-- HERO --}}
        <section id="home" class="relative min-h-screen flex items-center justify-center text-center text-white">
            <div class="absolute inset-0">
                <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1522673607200-164d1b6ce486?w=1200' }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-gradient-to-b from-[#E8919B]/20 via-black/40 to-[#FFFBFC]"></div>
            </div>
            <div class="relative z-10 px-6 py-20">
                <p class="font-elegant text-sm tracking-[0.5em] uppercase mb-8 opacity-90">We Are Getting Married</p>
                <h1 class="font-script text-8xl md:text-9xl mb-4">{{ $invitation->groom_nickname }}</h1>
                <p class="font-script text-6xl opacity-70 my-6">&</p>
                <h1 class="font-script text-8xl md:text-9xl mb-10">{{ $invitation->bride_nickname }}</h1>
                <p class="font-display text-xl tracking-wide">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
                <div class="mt-16 animate-float">
                    <svg class="w-8 h-8 mx-auto opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </div>
            </div>
        </section>

        {{-- QUOTE --}}
        <div class="quote-section">
            <svg class="w-10 h-10 mx-auto mb-6 text-[#E8919B]" fill="currentColor" viewBox="0 0 24 24"><path d="M6 17h3l2-4V7H5v6h3zm8 0h3l2-4V7h-6v6h3z"/></svg>
            <p class="quote-text">"Dan di antara tanda-tanda kekuasaan-Nya ialah Dia menciptakan untukmu istri-istri dari jenismu sendiri, supaya kamu cenderung dan merasa tenteram kepadanya."</p>
            <p class="text-[#E8919B] mt-6 text-sm">— QS. Ar-Rum: 21</p>
        </div>

        {{-- COUPLE --}}
        <section id="couple" class="section bg-[#FFFBFC]">
            <div class="section-title">
                <p class="subtitle">Bride & Groom</p>
                <h2>Mempelai</h2>
                <div class="divider"></div>
            </div>
            
            <div class="max-w-4xl mx-auto grid md:grid-cols-2 gap-8">
                <div class="card couple-card">
                    <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="couple-photo" alt="Groom">
                    <h3 class="couple-name">{{ $invitation->groom_name }}</h3>
                    <p class="text-gray-500 mb-4">Putra dari<br>Bapak {{ $invitation->groom_father }}<br>& Ibu {{ $invitation->groom_mother }}</p>
                    @if($invitation->groom_instagram)
                    <a href="https://instagram.com/{{ $invitation->groom_instagram }}" target="_blank" class="inline-flex items-center gap-2 text-[#E8919B] hover:text-[#D4727E] transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->groom_instagram }}
                    </a>
                    @endif
                </div>

                <div class="card couple-card">
                    <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="couple-photo" alt="Bride">
                    <h3 class="couple-name">{{ $invitation->bride_name }}</h3>
                    <p class="text-gray-500 mb-4">Putri dari<br>Bapak {{ $invitation->bride_father }}<br>& Ibu {{ $invitation->bride_mother }}</p>
                    @if($invitation->bride_instagram)
                    <a href="https://instagram.com/{{ $invitation->bride_instagram }}" target="_blank" class="inline-flex items-center gap-2 text-[#E8919B] hover:text-[#D4727E] transition-colors">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/></svg>
                        @{{ $invitation->bride_instagram }}
                    </a>
                    @endif
                </div>
            </div>
        </section>

        {{-- COUNTDOWN --}}
        <section class="section bg-white">
            <div class="max-w-xl mx-auto text-center">
                <p class="font-script text-4xl text-[#E8919B] mb-2">Save The Date</p>
                <p class="text-sm tracking-widest uppercase opacity-60 mb-10">Menuju Hari Bahagia</p>
                
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
                    }
                }" class="countdown-grid">
                    <div class="countdown-item"><div class="countdown-num" x-text="days">0</div><div class="countdown-label">Hari</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="hours">0</div><div class="countdown-label">Jam</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="minutes">0</div><div class="countdown-label">Menit</div></div>
                    <div class="countdown-item"><div class="countdown-num" x-text="seconds">0</div><div class="countdown-label">Detik</div></div>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section bg-[#FFFBFC]">
            <div class="section-title">
                <p class="subtitle">Wedding Events</p>
                <h2>Waktu & Tempat</h2>
                <div class="divider"></div>
            </div>
            
            <div class="max-w-2xl mx-auto">
                <div class="event-card">
                    <div class="event-icon">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-[#3D3D3D] mb-2">Akad Nikah</h3>
                    <p class="text-[#E8919B] font-medium mb-4">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                    <div class="space-y-2 text-[#5A5A5A] text-sm mb-5">
                        <p class="flex items-center gap-2"><svg class="w-4 h-4 text-[#E8919B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg> {{ $invitation->akad_date?->format('H:i') }} WIB</p>
                        <p class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 text-[#E8919B]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> {{ $invitation->akad_venue }}, {{ $invitation->akad_address }}</p>
                    </div>
                    <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn btn-outline text-sm">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Lihat Lokasi
                    </a>
                </div>

                <div class="event-card">
                    <div class="event-icon" style="background: linear-gradient(135deg, #E8F5E1, white);">
                        <svg class="w-7 h-7 text-[#9CAF88]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 15.546c-.523 0-1.046.151-1.5.454a2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.704 2.704 0 01-3 0 2.704 2.704 0 00-3 0 2.701 2.701 0 01-1.5.454M9 6v2m3-2v2m3-2v2M9 3h.01M12 3h.01M15 3h.01M21 21v-7a2 2 0 00-2-2H5a2 2 0 00-2 2v7h18zm-3-9v-2a2 2 0 00-2-2H8a2 2 0 00-2 2v2h12z"/></svg>
                    </div>
                    <h3 class="font-display text-2xl font-semibold text-[#3D3D3D] mb-2">Resepsi</h3>
                    <p class="text-[#9CAF88] font-medium mb-4">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</p>
                    <div class="space-y-2 text-[#5A5A5A] text-sm mb-5">
                        <p class="flex items-center gap-2"><svg class="w-4 h-4 text-[#9CAF88]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C6.5 2 2 6.5 2 12s4.5 10 10 10 10-4.5 10-10S17.5 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm.5-13H11v6l5.2 3.2.8-1.3-4.5-2.7V7z"/></svg> {{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</p>
                        <p class="flex items-start gap-2"><svg class="w-4 h-4 mt-0.5 text-[#9CAF88]" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.13 2 5 5.13 5 9c0 5.25 7 13 7 13s7-7.75 7-13c0-3.87-3.13-7-7-7zm0 9.5c-1.38 0-2.5-1.12-2.5-2.5s1.12-2.5 2.5-2.5 2.5 1.12 2.5 2.5-1.12 2.5-2.5 2.5z"/></svg> {{ $invitation->resepsi_venue }}, {{ $invitation->resepsi_address }}</p>
                    </div>
                    <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn btn-outline text-sm" style="border-color: #9CAF88; color: #9CAF88;">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/></svg>
                        Lihat Lokasi
                    </a>
                </div>
            </div>
        </section>

        {{-- GALLERY --}}
        @if($metadata && isset($metadata['gallery']) && count($metadata['gallery']) > 0)
        <section id="gallery" class="section bg-white">
            <div class="section-title">
                <p class="subtitle">Our Moments</p>
                <h2>Galeri</h2>
                <div class="divider"></div>
            </div>
            <div class="max-w-4xl mx-auto gallery-grid">
                @foreach($metadata['gallery'] as $img)
                <div class="gallery-item"><img src="{{ asset('storage/' . $img) }}" alt="Gallery"></div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- GIFT --}}
        <section id="gift" class="section bg-[#FFFBFC]">
            <div class="section-title">
                <p class="subtitle">Wedding Gift</p>
                <h2>Amplop Digital</h2>
                <div class="divider"></div>
            </div>
            <div class="max-w-md mx-auto text-center">
                <p class="text-[#5A5A5A] mb-8">Doa restu Anda merupakan karunia yang sangat berarti. Jika memberi adalah ungkapan tanda kasih, Anda dapat memberi secara cashless.</p>
                
                @if($invitation->bank_name)
                <div class="gift-card">
                    <p class="font-semibold text-sm mb-1">{{ $invitation->bank_name }}</p>
                    <p class="font-mono text-xl tracking-wide mb-1">{{ $invitation->bank_account }}</p>
                    <p class="text-sm opacity-80 mb-4">a.n {{ $invitation->bank_holder }}</p>
                    <button @click="copyText('{{ $invitation->bank_account }}')" class="copy-btn">📋 Salin Nomor</button>
                </div>
                @endif
            </div>
        </section>

        {{-- RSVP & WISHES - Using Reusable Component --}}
        <x-invitation.rsvp-wishes :invitation="$invitation" theme="rose" />

        {{-- FOOTER --}}
        <footer class="py-16 bg-gradient-to-br from-[#E8919B] to-[#D4727E] text-white text-center">
            <p class="font-script text-6xl mb-4">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</p>
            <p class="font-display text-xl mb-6">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
            <div class="w-20 h-px bg-white/30 mx-auto mb-6"></div>
            <p class="text-sm opacity-80">Terima kasih atas kehadiran dan doa restu Anda</p>
        </footer>
    </main>

    {{-- MUSIC --}}
    <button x-show="opened" @click="toggleAudio()" class="music-btn" :class="{ 'playing': playing }">
        <svg x-show="playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M3 9v6h4l5 5V4L7 9H3zm13.5 3c0-1.77-1.02-3.29-2.5-4.03v8.05c1.48-.73 2.5-2.25 2.5-4.02z"/></svg>
        <svg x-show="!playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M16.5 12c0-1.77-1.02-3.29-2.5-4.03v2.21l2.45 2.45c.03-.2.05-.41.05-.63zm2.5 0c0 .94-.2 1.82-.54 2.64l1.51 1.51C20.63 14.91 21 13.5 21 12c0-4.28-2.99-7.86-7-8.77v2.06c2.89.86 5 3.54 5 6.71zM4.27 3L3 4.27 7.73 9H3v6h4l5 5v-6.73l4.25 4.25c-.67.52-1.42.93-2.25 1.18v2.06c1.38-.31 2.63-.95 3.69-1.81L19.73 21 21 19.73l-9-9L4.27 3zM12 4L9.91 6.09 12 8.18V4z"/></svg>
    </button>

    {{-- NAV --}}
    <nav x-show="opened" class="bottom-nav">
        <div class="nav-items">
            <a @click.prevent="scrollTo('home')" class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg><span>Home</span></a>
            <a @click.prevent="scrollTo('couple')" class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg><span>Couple</span></a>
            <a @click.prevent="scrollTo('events')" class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg><span>Event</span></a>
            <a @click.prevent="scrollTo('gift')" class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg><span>Gift</span></a>
            <a @click.prevent="scrollTo('rsvp')" class="nav-item"><svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg><span>RSVP</span></a>
        </div>
    </nav>
</div>