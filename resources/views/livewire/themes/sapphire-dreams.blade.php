@section('title', 'The Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
<link href="https://fonts.googleapis.com/css2?family=Italiana&family=Montserrat:wght@300;400;500;600&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
/* SAPPHIRE DREAMS THEME CSS */
:root {
    --navy: #0A192F;
    --navy-light: #172A45;
    --silver: #E2E8F0;
    --blue: #3B82F6;
    --cyan: #64FFDA;
    --text-main: #CCD6F6;
    --text-muted: #8892B0;
    
    --bottom-nav-height: 70px;
}

body {
    background-color: var(--navy);
    color: var(--text-main);
    font-family: 'Montserrat', sans-serif;
    -webkit-font-smoothing: antialiased;
}

.font-serif { font-family: 'Italiana', serif; }
.font-sans { font-family: 'Montserrat', sans-serif; }

.section {
    padding: 80px 20px;
    position: relative;
    overflow: hidden;
}

.bg-dark { background-color: var(--navy); }
.bg-darker { background-color: var(--navy-light); }

.text-silver { color: var(--silver); }
.text-cyan { color: var(--cyan); }
.text-blue { color: var(--blue); }

.card {
    background: rgba(23, 42, 69, 0.7);
    border: 1px solid rgba(100, 255, 218, 0.1);
    border-radius: 16px;
    padding: 24px;
    backdrop-filter: blur(10px);
}

/* Typography components */
.section-subtitle {
    font-size: 11px;
    letter-spacing: 4px;
    text-transform: uppercase;
    color: var(--cyan);
    margin-bottom: 12px;
    font-weight: 500;
}

.section-title {
    font-family: 'Italiana', serif;
    font-size: 2.5rem;
    color: var(--silver);
    margin-bottom: 24px;
}

.divider {
    width: 40px;
    height: 2px;
    background: var(--cyan);
    margin: 0 auto 32px;
}

/* Cover & Hero Specific */
.cover-overlay {
    position: absolute;
    inset: 0;
    background: linear-gradient(180deg, rgba(10,25,47,0.3) 0%, rgba(10,25,47,0.8) 70%, var(--navy) 100%);
    z-index: 1;
}
.cover-bg {
    position: absolute;
    inset: 0;
    background-size: cover;
    background-position: center;
    z-index: 0;
    filter: saturate(0.8) contrast(1.1);
}

.hero-names {
    font-family: 'Italiana', serif;
    font-size: 3rem;
    line-height: 1.1;
    color: var(--silver);
    text-shadow: 0 4px 12px rgba(0,0,0,0.5);
}

.btn-cyan {
    background: transparent;
    border: 1px solid var(--cyan);
    color: var(--cyan);
    padding: 12px 32px;
    border-radius: 30px;
    font-weight: 500;
    letter-spacing: 1px;
    transition: all 0.3s ease;
    text-transform: uppercase;
    font-size: 13px;
    cursor: pointer;
}
.btn-cyan:hover {
    background: rgba(100, 255, 218, 0.1);
}

/* Nav */
.bottom-nav {
    position: fixed;
    bottom: 20px;
    left: 50%;
    transform: translateX(-50%);
    width: calc(100% - 40px);
    max-width: 400px;
    background: rgba(23, 42, 69, 0.85);
    backdrop-filter: blur(16px);
    border: 1px solid rgba(255,255,255,0.05);
    border-radius: 20px;
    padding: 8px 16px;
    z-index: 50;
    box-shadow: 0 10px 30px -10px rgba(0,0,0,0.5);
}
.nav-items {
    display: flex;
    justify-content: space-around;
    align-items: center;
}
.nav-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 4px;
    color: var(--text-muted);
    font-size: 10px;
    padding: 8px;
    border-radius: 12px;
    transition: all 0.3s ease;
    text-decoration: none;
    cursor: pointer;
}
.nav-item svg { width: 22px; height: 22px; transition: all 0.3s ease; }
.nav-item.active { color: var(--cyan); }
.nav-item.active svg { transform: translateY(-2px); }

/* RSVP form specific */
.form-input {
    width: 100%;
    background: rgba(10, 25, 47, 0.5);
    border: 1px solid rgba(136, 146, 176, 0.3);
    color: var(--silver);
    padding: 12px 16px;
    border-radius: 8px;
    font-family: inherit;
    font-size: 14px;
    transition: all 0.3s ease;
}
.form-input:focus {
    outline: none;
    border-color: var(--cyan);
    box-shadow: 0 0 0 2px rgba(100, 255, 218, 0.1);
}

.music-btn {
    position: fixed;
    right: 20px;
    bottom: calc(var(--bottom-nav-height) + 40px + env(safe-area-inset-bottom));
    width: 44px;
    height: 44px;
    background: rgba(23, 42, 69, 0.8);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(100, 255, 218, 0.2);
    border-radius: 50%;
    color: var(--cyan);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 40;
    cursor: pointer;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
}

.anime-element {
    opacity: 0; /* Hidden initially, Anime.js will show them */
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/animejs/3.2.1/anime.min.js"></script>
@endpush

<div class="desktop-wrapper" x-data="sapphireTheme()" x-init="initTheme()">
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

    {{-- COVER --}}
    <div x-show="!opened" class="fixed inset-0 z-[100] flex flex-col justify-between" style="background-color: var(--navy)">
        <div class="cover-bg" style="background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=1200' }}');"></div>
        <div class="cover-overlay"></div>
        
        <div class="relative z-10 w-full flex-1 flex flex-col items-center justify-center p-6 text-center anime-cover">
            <p class="section-subtitle">The Wedding Of</p>
            @php $order = $invitation->custom_styles['name_order'] ?? 'groom_first'; @endphp
            @if($order === 'bride_first')
                <h1 class="hero-names">{{ $invitation->bride_nickname }}</h1>
                <p class="font-serif text-3xl text-cyan my-4">&</p>
                <h1 class="hero-names">{{ $invitation->groom_nickname }}</h1>
            @else
                <h1 class="hero-names">{{ $invitation->groom_nickname }}</h1>
                <p class="font-serif text-3xl text-cyan my-4">&</p>
                <h1 class="hero-names">{{ $invitation->bride_nickname }}</h1>
            @endif
        </div>

        <div class="relative z-10 w-full p-8 text-center pb-12 anime-cover-bottom">
            <p class="text-xs text-text-muted mb-2 tracking-widest uppercase">Kepada Yth.</p>
            <p class="font-serif text-2xl text-silver mb-8">{{ $guestName }}</p>
            <button @click="openInvitation()" class="btn-cyan flex items-center justify-center mx-auto gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
                Buka Undangan
            </button>
        </div>
    </div>

    {{-- MAIN --}}
    <main x-show="opened" x-transition.opacity.duration.1000ms class="pb-24">
        
        {{-- HERO --}}
        <section id="home" class="hero-section min-h-screen flex flex-col items-center justify-center relative bg-dark">
            <div class="cover-bg" style="opacity: 0.15; background-image: url('{{ $invitation->cover_image ? img_url($invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?w=600' }}');"></div>
            
            <div class="z-10 text-center px-4 anime-stagger">
                <p class="section-subtitle">We Are Getting Married</p>
                <h1 class="font-serif text-5xl md:text-6xl text-silver mb-2">
                    {{ $order === 'bride_first' ? $invitation->bride_nickname . ' & ' . $invitation->groom_nickname : $invitation->groom_nickname . ' & ' . $invitation->bride_nickname }}
                </h1>
                <p class="text-cyan font-medium mt-6">{{ $invitation->akad_date?->translatedFormat('d F Y') }}</p>
            </div>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="section bg-darker text-center scroll-spyable">
            <p class="section-subtitle anime-element">Bride & Groom</p>
            <h2 class="section-title anime-element">Mempelai</h2>
            <div class="divider anime-element"></div>
            
            <div class="max-w-md mx-auto space-y-12">
                @if($order === 'bride_first')
                    <div class="card anime-element">
                        <img src="{{ $invitation->bride_photo ? img_url($invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-32 h-32 rounded-full mx-auto object-cover mb-4 border-2 border-cyan" alt="Bride">
                        <h3 class="font-serif text-2xl text-silver mb-1">{{ $invitation->bride_name }}</h3>
                        <p class="text-sm text-text-muted mb-4">Putri Bapak {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>
                        @if($invitation->bride_instagram)
                        <a href="https://instagram.com/{{ $invitation->bride_instagram }}" target="_blank" class="text-xs text-cyan hover:underline">@{{ $invitation->bride_instagram }}</a>
                        @endif
                    </div>
                    <div class="text-center font-serif text-4xl text-cyan anime-element">&</div>
                    <div class="card anime-element">
                        <img src="{{ $invitation->groom_photo ? img_url($invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-32 h-32 rounded-full mx-auto object-cover mb-4 border-2 border-cyan" alt="Groom">
                        <h3 class="font-serif text-2xl text-silver mb-1">{{ $invitation->groom_name }}</h3>
                        <p class="text-sm text-text-muted mb-4">Putra Bapak {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                        @if($invitation->groom_instagram)
                        <a href="https://instagram.com/{{ $invitation->groom_instagram }}" target="_blank" class="text-xs text-cyan hover:underline">@{{ $invitation->groom_instagram }}</a>
                        @endif
                    </div>
                @else
                    <div class="card anime-element">
                        <img src="{{ $invitation->groom_photo ? img_url($invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}" class="w-32 h-32 rounded-full mx-auto object-cover mb-4 border-2 border-cyan" alt="Groom">
                        <h3 class="font-serif text-2xl text-silver mb-1">{{ $invitation->groom_name }}</h3>
                        <p class="text-sm text-text-muted mb-4">Putra Bapak {{ $invitation->groom_father }} & Ibu {{ $invitation->groom_mother }}</p>
                        @if($invitation->groom_instagram)
                        <a href="https://instagram.com/{{ $invitation->groom_instagram }}" target="_blank" class="text-xs text-cyan hover:underline">@{{ $invitation->groom_instagram }}</a>
                        @endif
                    </div>
                    <div class="text-center font-serif text-4xl text-cyan anime-element">&</div>
                    <div class="card anime-element">
                        <img src="{{ $invitation->bride_photo ? img_url($invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}" class="w-32 h-32 rounded-full mx-auto object-cover mb-4 border-2 border-cyan" alt="Bride">
                        <h3 class="font-serif text-2xl text-silver mb-1">{{ $invitation->bride_name }}</h3>
                        <p class="text-sm text-text-muted mb-4">Putri Bapak {{ $invitation->bride_father }} & Ibu {{ $invitation->bride_mother }}</p>
                        @if($invitation->bride_instagram)
                        <a href="https://instagram.com/{{ $invitation->bride_instagram }}" target="_blank" class="text-xs text-cyan hover:underline">@{{ $invitation->bride_instagram }}</a>
                        @endif
                    </div>
                @endif
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="section bg-dark text-center scroll-spyable">
            <p class="section-subtitle anime-element">Wedding Events</p>
            <h2 class="section-title anime-element">Waktu & Tempat</h2>
            <div class="divider anime-element"></div>
            
            <div class="max-w-md mx-auto space-y-6">
                {{-- Akad --}}
                <div class="card anime-element relative overflow-hidden">
                    <div class="absolute -top-10 -right-10 w-24 h-24 bg-cyan/10 rounded-full blur-xl"></div>
                    <h3 class="font-serif text-xl text-cyan mb-2">Akad Nikah</h3>
                    <p class="font-bold text-silver mb-2">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-text-muted mb-2">Pukul {{ $invitation->akad_date?->format('H:i') }} WIB</p>
                    <p class="text-sm text-text-main mb-6">{{ $invitation->akad_venue }}<br><span class="text-xs text-text-muted">{{ $invitation->akad_address }}</span></p>
                    <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="btn-cyan w-full text-center inline-block">Lokasi Akad</a>
                </div>

                {{-- Resepsi --}}
                <div class="card anime-element relative overflow-hidden">
                    <div class="absolute -bottom-10 -left-10 w-24 h-24 bg-blue/20 rounded-full blur-xl"></div>
                    <h3 class="font-serif text-xl text-blue mb-2">Resepsi</h3>
                    <p class="font-bold text-silver mb-2">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</p>
                    <p class="text-sm text-text-muted mb-2">Pukul {{ $invitation->resepsi_date?->format('H:i') }} WIB</p>
                    <p class="text-sm text-text-main mb-6">{{ $invitation->resepsi_venue }}<br><span class="text-xs text-text-muted">{{ $invitation->resepsi_address }}</span></p>
                    <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="btn-cyan w-full text-center inline-block" style="border-color:var(--blue); color:var(--blue);">Lokasi Resepsi</a>
                </div>
            </div>
        </section>
        
        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="section bg-darker text-center scroll-spyable">
            <p class="section-subtitle anime-element">Share the Love</p>
            <h2 class="section-title anime-element">Wedding Gift</h2>
            <div class="divider anime-element"></div>
            
            <div class="max-w-md mx-auto">
                <p class="text-sm text-text-muted mb-8 anime-element">Doa restu Anda sangat berarti. Jika ada yang ingin memberikan hadiah, silakan melalui rekening berikut:</p>
                
                <div class="space-y-4">
                @if($invitation->bank_accounts && count($invitation->bank_accounts) > 0)
                    @foreach($invitation->bank_accounts as $account)
                    <div class="card flex flex-col items-center anime-element gift-card">
                        <p class="text-cyan font-bold uppercase tracking-widest mb-2 text-sm">{{ $account['bank'] }}</p>
                        <p class="font-mono text-silver text-xl mb-1 tracking-widest">{{ $account['account_number'] }}</p>
                        <p class="text-sm text-text-muted mb-4">a.n {{ $account['account_name'] }}</p>
                        <button class="btn-cyan text-xs py-2 px-6" @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); alert('Tersalin!')">Salin Rekening</button>
                    </div>
                    @endforeach
                @elseif($invitation->bank_name)
                    <div class="card flex flex-col items-center anime-element gift-card">
                        <p class="text-cyan font-bold uppercase tracking-widest mb-2 text-sm">{{ $invitation->bank_name }}</p>
                        <p class="font-mono text-silver text-xl mb-1 tracking-widest">{{ $invitation->bank_account }}</p>
                        <p class="text-sm text-text-muted mb-4">a.n {{ $invitation->bank_holder }}</p>
                        <button class="btn-cyan text-xs py-2 px-6" @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); alert('Tersalin!')">Salin Rekening</button>
                    </div>
                @endif
                </div>
            </div>
        </section>
        @endif

        {{-- RSVP --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="section bg-dark scroll-spyable">
            <div class="section-title text-center">
                <p class="section-subtitle anime-element">Will you join us?</p>
                <h2 class="anime-element">RSVP</h2>
                <div class="divider anime-element"></div>
            </div>
            
            <div class="max-w-md mx-auto px-4" x-data="{
                invitationId: {{ $invitation->id }},
                name: '{{ request('kpd', '') }}',
                message: '',
                status: 'confirmed',
                pax: 1,
                loading: false,
                submitted: false,
                error: '',
                wishes: [],

                async submitForm() {
                    if (!this.name.trim() || !this.message.trim()) {
                        this.error = 'Lengkapi formulir.';
                        return;
                    }
                    this.loading = true;
                    this.error = '';
                    try {
                        const csrfToken = document.querySelector('meta[name=csrf-token]')?.content || '';
                        await fetch(`/api/invitations/${this.invitationId}/rsvp`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ name: this.name, status: this.status, pax: this.pax })
                        });
                        const wishRes = await fetch(`/api/invitations/${this.invitationId}/wishes`, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                            body: JSON.stringify({ name: this.name, message: this.message })
                        });
                        if (wishRes.ok) {
                            const data = await wishRes.json();
                            if (data.wish) {
                                data.wish.attendance_status = this.status;
                                this.wishes.unshift(data.wish);
                            }
                            this.message = '';
                            this.submitted = true;
                        }
                    } catch (e) { this.error = 'Gagal menyimpan data.'; }
                    finally { this.loading = false; }
                },
                async loadWishes() {
                    try {
                        const res = await fetch(`/api/invitations/${this.invitationId}/wishes`);
                        const data = await res.json();
                        this.wishes = data.wishes || [];
                    } catch (e) {}
                },
                init() { this.loadWishes(); }
            }">
                
                {{-- Thanks --}}
                <div x-show="submitted" x-transition class="card text-center border-cyan bg-cyan/10 anime-element">
                    <p class="text-cyan font-medium text-lg mb-2">Terima kasih!</p>
                    <p class="text-sm text-silver">Ucapan Anda telah disampaikan.</p>
                </div>
                
                {{-- Form --}}
                <div x-show="!submitted" x-transition class="card anime-element p-6">
                    <form @submit.prevent="submitForm">
                        <div x-show="error" class="mb-4 text-xs text-red-400 bg-red-400/10 p-2 rounded" x-text="error"></div>
                        
                        <div class="mb-4">
                            <label class="block text-xs text-text-muted mb-2 uppercase tracking-wide">Nama</label>
                            <input type="text" x-model="name" class="form-input" placeholder="Nama Anda">
                        </div>
                        <div class="mb-4">
                            <label class="block text-xs text-text-muted mb-2 uppercase tracking-wide">Kehadiran</label>
                            <div class="flex gap-2">
                                <button type="button" @click="status = 'confirmed'" :class="status === 'confirmed' ? 'bg-cyan/20 border-cyan text-cyan' : 'border-slate-600 text-text-muted'" class="flex-1 py-3 text-sm font-medium border rounded-lg transition">Hadir</button>
                                <button type="button" @click="status = 'declined'" :class="status === 'declined' ? 'bg-red-400/20 border-red-400 text-red-400' : 'border-slate-600 text-text-muted'" class="flex-1 py-3 text-sm font-medium border rounded-lg transition">Tidak Hadir</button>
                            </div>
                        </div>
                        <div class="mb-4" x-show="status === 'confirmed'">
                            <label class="block text-xs text-text-muted mb-2 uppercase tracking-wide">Tamu</label>
                            <select x-model="pax" class="form-input text-white bg-navy appearance-none">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                            </select>
                        </div>
                        <div class="mb-6">
                            <label class="block text-xs text-text-muted mb-2 uppercase tracking-wide">Ucapan</label>
                            <textarea x-model="message" class="form-input" rows="3" placeholder="Doa terbaik untuk mempelai..."></textarea>
                        </div>
                        <button type="submit" :disabled="loading" class="btn-cyan w-full text-center bg-cyan/10">
                            <span x-show="!loading">Kirim RSVP</span>
                            <span x-show="loading">Memproses...</span>
                        </button>
                    </form>
                </div>

                {{-- Wish list --}}
                <div class="mt-12 anime-element">
                    <h3 class="text-xs text-text-muted uppercase tracking-widest mb-4">Ucapan & Doa</h3>
                    <div class="space-y-4 max-h-[400px] overflow-y-auto pr-2 custom-scroll">
                        <template x-for="wish in wishes" :key="wish.id">
                            <div class="p-4 rounded-xl bg-navy-light/50 border border-slate-700">
                                <div class="flex justify-between items-center mb-2">
                                    <h4 class="font-medium text-silver text-sm" x-text="wish.name"></h4>
                                    <span class="text-[10px] text-text-muted" x-text="wish.time || ''"></span>
                                </div>
                                <p class="text-sm text-text-muted mb-3" x-text="wish.message"></p>
                                <template x-if="wish.attendance_status">
                                    <span class="text-[10px] py-1 px-2 rounded font-medium" :class="wish.attendance_status === 'confirmed' ? 'bg-cyan/20 text-cyan' : 'bg-red-400/20 text-red-400'" x-text="wish.attendance_status === 'confirmed' ? 'Hadir' : 'Tidak Hadir'"></span>
                                </template>
                            </div>
                        </template>
                    </div>
                </div>
            </div>
        </section>
        @endif
        
    </main>

    {{-- AUDIO BUTTON --}}
    <button x-show="opened" @click="toggleAudio()" class="music-btn" :class="{'opacity-50': !playing}">
        <svg x-show="playing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5 19h4l5-5V5L9 10H5v9z"/></svg>
        <svg x-show="!playing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.586 15H4a1 1 0 01-1-1v-4a1 1 0 011-1h1.586l4.707-4.707C10.923 3.663 12 4.109 12 5v14c0 .891-1.077 1.337-1.707.707L5.586 15z" clip-rule="evenodd" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2" /></svg>
    </button>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" class="bottom-nav">
        <div class="nav-items">
            <a @click="scrollTo('home')" class="nav-item" :class="{ 'active': activeSection === 'home' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span>Home</span>
            </a>
            <a @click="scrollTo('couple')" class="nav-item" :class="{ 'active': activeSection === 'couple' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
                <span>Couple</span>
            </a>
            <a @click="scrollTo('events')" class="nav-item" :class="{ 'active': activeSection === 'events' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                <span>Event</span>
            </a>
            @if($invitation->enable_gift)
            <a @click="scrollTo('gift')" class="nav-item" :class="{ 'active': activeSection === 'gift' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v13m0-13V6a2 2 0 112 2h-2zm0 0V5.5A2.5 2.5 0 109.5 8H12zm-7 4h14M5 12a2 2 0 110-4h14a2 2 0 110 4M5 12v7a2 2 0 002 2h10a2 2 0 002-2v-7"/></svg>
                <span>Gift</span>
            </a>
            @endif
            @if($invitation->enable_rsvp || $invitation->enable_wishes)
            <a @click="scrollTo('rsvp')" class="nav-item" :class="{ 'active': activeSection === 'rsvp' }">
                <svg fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/></svg>
                <span>RSVP</span>
            </a>
            @endif
        </div>
    </nav>
</div>

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('sapphireTheme', () => ({
        opened: false,
        playing: false,
        activeSection: 'home',
        audioEl: null,
        
        initTheme() {
            // Setup intersection observer for active menu
            setTimeout(() => {
                const sections = document.querySelectorAll('.scroll-spyable, .hero-section');
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            this.activeSection = entry.target.id;
                            
                            // Trigger Anime.js for elements inside this section
                            const targets = entry.target.querySelectorAll('.anime-element');
                            if (targets.length > 0) {
                                anime({
                                    targets: targets,
                                    translateY: [40, 0],
                                    opacity: [0, 1],
                                    duration: 1000,
                                    delay: anime.stagger(150),
                                    easing: 'easeOutExpo'
                                });
                                // Remove class so it doesn't re-animate every single scroll (optional)
                                targets.forEach(el => el.classList.remove('anime-element'));
                            }
                        }
                    });
                }, { threshold: 0.2 });

                sections.forEach(s => observer.observe(s));
            }, 100);

            // Cover animations initial
            anime({
                targets: '.anime-cover',
                translateY: [-50, 0],
                opacity: [0, 1],
                duration: 2000,
                easing: 'easeOutExpo',
                delay: 300
            });
            anime({
                targets: '.anime-cover-bottom',
                translateY: [50, 0],
                opacity: [0, 1],
                duration: 2000,
                easing: 'easeOutExpo',
                delay: 600
            });
        },
        
        openInvitation() {
            this.opened = true;
            this.audioEl = document.getElementById('bgMusic');
            if (this.audioEl) {
                this.audioEl.play().then(() => this.playing = true).catch(() => {});
            }
            
            // Hero animation trigger
            setTimeout(() => {
                anime({
                    targets: '.hero-section .anime-stagger > *',
                    translateY: [30, 0],
                    opacity: [0, 1],
                    duration: 1200,
                    delay: anime.stagger(200),
                    easing: 'easeOutQuint'
                });
            }, 100);
        },
        
        toggleAudio() {
            if(!this.audioEl) return;
            if(this.playing) {
                this.audioEl.pause();
                this.playing = false;
            } else {
                this.audioEl.play().then(() => this.playing = true).catch(() => {});
            }
        },

        scrollTo(id) {
            this.activeSection = id;
            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
        }
    }));
});
</script>
@endpush
