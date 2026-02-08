@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('fonts')
    <link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300;0,400;0,600;1,400&family=Great+Vibes&family=Montserrat:wght@200;300;400;500&display=swap" rel="stylesheet">
@endpush

@push('styles')
<style>
    :root {
        --color-gold: #D4AF37;
        --color-gold-light: #F3E5AB;
        --color-cream: #F9F7F2;
        --color-dark: #1A1A1A;
        --color-gray: #4A4A4A;
        
        --font-heading: 'Cormorant Garamond', serif;
        --font-script: 'Great Vibes', cursive;
        --font-body: 'Montserrat', sans-serif;
    }

    body {
        background-color: var(--color-cream);
        color: var(--color-dark);
        font-family: var(--font-body);
        overflow-x: hidden; /* Prevent horizontal scroll */
    }

    .font-heading { font-family: var(--font-heading); }
    .font-script { font-family: var(--font-script); }
    .font-body { font-family: var(--font-body); }

    /* Smooth Reveal Animation */
    .reveal-up { opacity: 0; transform: translateY(40px); transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal-zoom { opacity: 0; transform: scale(0.95); transition: all 1.2s cubic-bezier(0.16, 1, 0.3, 1); }
    
    .active { opacity: 1 !important; transform: translateY(0) scale(1) !important; }

    /* Ken Burns Effect */
    .ken-burns {
        animation: kenBurns 20s ease-out infinite alternate;
    }
    @keyframes kenBurns {
        0% { transform: scale(1); }
        100% { transform: scale(1.15); }
    }

    /* Glass Effect */
    .glass-dark {
        background: rgba(26, 26, 26, 0.4);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        border: 1px solid rgba(255, 255, 255, 0.1);
    }
    
    /* Audio Pulse */
    .pulse-ring {
        position: relative;
    }
    .pulse-ring::before {
        content: '';
        position: absolute;
        inset: -4px;
        border-radius: 50%;
        border: 1px solid var(--color-gold);
        opacity: 0;
        animation: pulseWave 2s cubic-bezier(0.25, 0.8, 0.25, 1) infinite;
    }
    @keyframes pulseWave {
        0% { transform: scale(1); opacity: 0.8; }
        100% { transform: scale(1.6); opacity: 0; }
    }
    
    [x-cloak] { display: none !important; }
</style>
@endpush

<div x-data="{
    showOverlay: true,
    isPlaying: false,
    audio: null,
    showGalleryModal: false,
    modalImage: '',
    
    init() {
        this.audio = this.$refs.audioPlayer;
        
        // Setup Intersection Observer for animations
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if(entry.isIntersecting) {
                    entry.target.classList.add('active');
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        
        // Wait specifically for DOM updates if needed, or just select
        setTimeout(() => {
            document.querySelectorAll('.reveal-up, .reveal-zoom').forEach(el => observer.observe(el));
        }, 100);
    },
    
    openInvitation() {
        this.showOverlay = false;
        // Optional: unlock body scroll if it was locked.
        // document.body.style.overflow = 'auto'; 

        if (this.audio) {
            this.audio.play().then(() => {
                this.isPlaying = true;
            }).catch(e => console.log('Autoplay blocked', e));
        }
    },
    
    toggleAudio() {
        if (!this.audio) return;
        
        if (this.audio.paused) {
            this.audio.play();
            this.isPlaying = true;
        } else {
            this.audio.pause();
            this.isPlaying = false;
        }
    },
    
    openGallery(src) {
        this.modalImage = src;
        this.showGalleryModal = true;
    },
    
    copyToClipboard(text, event) {
        if (!text) return;
        navigator.clipboard.writeText(text);
        
        // Visual feedback
        const originalText = event.target.innerText;
        event.target.innerText = 'Copied!';
        setTimeout(() => {
            event.target.innerText = originalText;
        }, 2000);
    }
}" class="antialiased min-h-screen">

    {{-- Audio Element --}}
    @if($invitation->music_url)
        <audio x-ref="audioPlayer" src="{{ asset('storage/' . $invitation->music_url) }}" loop preload="auto"></audio>
    @endif

    {{-- 1. COVER OVERLAY --}}
    <div class="fixed inset-0 z-[9999] bg-stone-900 transition-transform duration-1000 cubic-bezier(0.77, 0, 0.175, 1) flex flex-col justify-between"
         :class="{ '-translate-y-full': !showOverlay }">
         
        {{-- Background Image with Ken Burns --}}
        <div class="absolute inset-0 overflow-hidden">
            <div class="w-full h-full bg-cover bg-center ken-burns opacity-60"
                 style="background-image: url('{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1515934751635-c81c6bc9a2d8?q=80&w=2070&auto=format&fit=crop' }}')">
            </div>
            <div class="absolute inset-0 bg-gradient-to-b from-black/30 via-transparent to-black/70"></div>
        </div>

        {{-- Content --}}
        <div class="relative z-10 h-full flex flex-col items-center justify-between py-20 px-6 text-white text-center">
            
            <div class="space-y-4 opacity-0 animate-[fadeInDown_1s_ease-out_1s_forwards]">
                <p class="font-body text-[10px] tracking-[0.4em] uppercase text-gold-light border-y border-white/20 py-2 inline-block">
                    The Wedding Of
                </p>
            </div>

            <div class="flex flex-col items-center justify-center space-y-6">
                <h1 class="font-heading text-6xl md:text-8xl leading-tight opacity-0 animate-[fadeInUp_1.2s_ease-out_0.5s_forwards]">
                    <span class="block">{{ $invitation->groom_nickname }}</span>
                    <span class="font-script text-5xl md:text-7xl text-gold-light block my-2">&</span>
                    <span class="block">{{ $invitation->bride_nickname }}</span>
                </h1>
            </div>

            <div class="w-full max-w-sm space-y-8 opacity-0 animate-[fadeInUp_1s_ease-out_1.5s_forwards]">
                {{-- Guest Card --}}
                <div class="glass-dark p-6 rounded-xl border border-white/10 backdrop-blur-md">
                    <p class="font-body text-xs text-gray-300 tracking-widest uppercase mb-3">Kepada Yth. Bapak/Ibu/Saudara/i</p>
                    <h3 class="font-heading text-2xl md:text-3xl text-white italic tracking-wide">
                        {{ $guestName }}
                    </h3>
                </div>

                {{-- Open Button --}}
                <button @click="openInvitation()" class="group relative px-12 py-4 bg-white/10 overflow-hidden rounded-full border border-white/30 transition-all hover:bg-white hover:text-stone-900 hover:border-white w-full max-w-xs mx-auto">
                    <span class="relative z-10 font-body text-xs font-bold uppercase tracking-[0.2em] group-hover:tracking-[0.3em] transition-all duration-300">
                        💌 Buka Undangan
                    </span>
                    <div class="absolute inset-0 bg-white/10 translate-y-full group-hover:translate-y-0 transition-transform duration-300"></div>
                </button>
            </div>
        </div>
    </div>

    {{-- 2. MAIN SCROLL CONTAINER --}}
    <div id="main-content" 
         :class="{'opacity-0': showOverlay}" 
         class="relative bg-cream shadow-2xl max-w-lg mx-auto min-h-screen transition-opacity duration-1000 delay-500">
        
        {{-- HERO SECTION --}}
        <section id="home" class="relative min-h-screen flex flex-col items-center justify-center text-center p-8 overflow-hidden">
            {{-- Decorative Elements --}}
            <div class="absolute top-0 left-0 w-full h-32 bg-gradient-to-b from-white/80 to-transparent z-10"></div>
            <div class="border-[1px] border-gold/20 absolute inset-6 pointer-events-none"></div>
            <div class="border-[1px] border-gold/40 absolute inset-7 pointer-events-none"></div>

            <div class="reveal-up space-y-6 relative z-20 mt-16">
                <p class="font-body text-xs md:text-sm tracking-[0.4em] uppercase text-gray-500">We Are Getting Married</p>
                
                <div class="py-10">
                    <h2 class="font-heading text-7xl md:text-8xl text-dark leading-none">
                        {{ $invitation->groom_nickname }}
                        <span class="block font-script text-5xl text-gold my-4">and</span>
                        {{ $invitation->bride_nickname }}
                    </h2>
                </div>

                <div class="flex items-center justify-center gap-4 text-sm font-body text-gray-600 tracking-widest border-t border-b border-gold/20 py-4 max-w-xs mx-auto">
                    <span>{{ $invitation->akad_date?->format('d') }}</span>
                    <span class="text-xs text-gold">•</span>
                    <span>{{ $invitation->akad_date?->format('F') }}</span>
                    <span class="text-xs text-gold">•</span>
                    <span>{{ $invitation->akad_date?->format('Y') }}</span>
                </div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce opacity-40">
                <span class="text-3xl text-dark">↓</span>
            </div>
        </section>

        {{-- QUOTE SECTION --}}
        <section class="py-24 px-8 bg-white text-center relative">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-48 h-48 bg-gold/5 rounded-full blur-3xl"></div>
            <div class="reveal-zoom relative z-10 max-w-xs mx-auto">
               <img src="https://indoinvite.com/nikah/template/elegan-nature/images/bismillah.png" class="h-10 mx-auto opacity-60 mb-8" alt="Bismillah">
               <p class="font-heading text-xl md:text-2xl italic leading-relaxed text-gray-700 mb-6">
                   "And of His signs is that He created for you from yourselves mates that you may find tranquility in them; and He placed between you affection and mercy."
               </p>
               <div class="w-12 h-px bg-gold mx-auto mb-4"></div>
               <span class="font-body text-[10px] tracking-widest uppercase text-gray-400">Ar-Rum: 21</span>
            </div>
        </section>

        {{-- COUPLE SECTION --}}
        <section id="couple" class="py-24 px-6 bg-cream relative overflow-hidden">
            <div class="absolute top-0 right-0 w-64 h-64 bg-gold/5 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2"></div>
            
            <div class="space-y-24 relative z-10">
                {{-- Groom --}}
                <div class="reveal-up text-center group">
                    <div class="relative w-64 h-80 mx-auto mb-8">
                        <div class="absolute inset-0 border border-gold translate-x-3 translate-y-3 transition-transform duration-500 group-hover:translate-x-2 group-hover:translate-y-2"></div>
                        <div class="absolute inset-0 border border-gold/30 -translate-x-3 -translate-y-3 transition-transform duration-500 group-hover:-translate-x-2 group-hover:-translate-y-2"></div>
                        <div class="relative w-full h-full overflow-hidden bg-gray-200">
                             <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?q=80&w=1974' }}" 
                                  class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-105 group-hover:scale-100">
                        </div>
                    </div>
                    <h3 class="font-heading text-4xl text-dark mb-2">{{ $invitation->groom_name }}</h3>
                    <p class="font-body text-xs tracking-widest text-gold uppercase mb-4">The Groom</p>
                    <p class="font-heading text-lg italic text-gray-500">
                        Putra dari {{ $invitation->groom_father }} & {{ $invitation->groom_mother }}
                    </p>
                </div>

                {{-- Bride --}}
                <div class="reveal-up text-center group">
                    <div class="relative w-64 h-80 mx-auto mb-8">
                        <div class="absolute inset-0 border border-gold translate-x-3 translate-y-3 transition-transform duration-500 group-hover:translate-x-2 group-hover:translate-y-2"></div>
                        <div class="absolute inset-0 border border-gold/30 -translate-x-3 -translate-y-3 transition-transform duration-500 group-hover:-translate-x-2 group-hover:-translate-y-2"></div>
                        <div class="relative w-full h-full overflow-hidden bg-gray-200">
                             <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1928' }}" 
                                  class="w-full h-full object-cover grayscale group-hover:grayscale-0 transition-all duration-700 scale-105 group-hover:scale-100">
                        </div>
                    </div>
                    <h3 class="font-heading text-4xl text-dark mb-2">{{ $invitation->bride_name }}</h3>
                    <p class="font-body text-xs tracking-widest text-gold uppercase mb-4">The Bride</p>
                    <p class="font-heading text-lg italic text-gray-500">
                        Putri dari {{ $invitation->bride_father }} & {{ $invitation->bride_mother }}
                    </p>
                </div>
            </div>
        </section>

        {{-- EVENTS SECTION --}}
        <section id="events" class="py-24 px-6 bg-white relative">
            <div class="reveal-up text-center mb-16">
                <h2 class="font-heading text-5xl mb-4">Wedding Events</h2>
                <div class="w-16 h-px bg-gold mx-auto"></div>
            </div>

            <div class="space-y-8">
                {{-- Akad --}}
                <div class="reveal-up relative overflow-hidden group">
                    <div class="absolute inset-0 bg-cream/50 transform -skew-y-2 group-hover:skew-y-0 transition-transform duration-500"></div>
                    <div class="relative p-8 text-center border border-gold/10 hover:border-gold/30 transition-colors bg-white/80 backdrop-blur-sm">
                        <span class="text-4xl block mb-4">💍</span>
                        <h3 class="font-heading text-3xl mb-2 text-dark">Akad Nikah</h3>
                        <p class="font-body text-xs text-gold uppercase tracking-widest font-bold mb-6">
                            {{ $invitation->akad_date?->translatedFormat('l, d F Y') }}
                        </p>
                        
                        <div class="space-y-4 text-gray-600 mb-8">
                            <div class="flex flex-col items-center">
                                <span class="font-bold font-body text-sm mb-1">{{ $invitation->akad_date?->format('H:i') }} WIB</span>
                                <span class="text-xs">Until Finish</span>
                            </div>
                            <div class="px-8">
                                <p class="font-heading text-lg italic mb-1">{{ $invitation->akad_venue }}</p>
                                <p class="text-xs font-body leading-relaxed">{{ $invitation->akad_address }}</p>
                            </div>
                        </div>

                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" 
                           class="inline-block px-8 py-3 bg-dark text-white text-[10px] font-bold uppercase tracking-widest hover:bg-gold transition-colors">
                            Google Maps
                        </a>
                    </div>
                </div>

                {{-- Resepsi --}}
                <div class="reveal-up relative overflow-hidden group">
                    <div class="absolute inset-0 bg-cream/50 transform skew-y-2 group-hover:skew-y-0 transition-transform duration-500"></div>
                    <div class="relative p-8 text-center border border-gold/10 hover:border-gold/30 transition-colors bg-white/80 backdrop-blur-sm">
                        <span class="text-4xl block mb-4">🥂</span>
                        <h3 class="font-heading text-3xl mb-2 text-dark">Resepsi</h3>
                        <p class="font-body text-xs text-gold uppercase tracking-widest font-bold mb-6">
                            {{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}
                        </p>
                        
                        <div class="space-y-4 text-gray-600 mb-8">
                            <div class="flex flex-col items-center">
                                <span class="font-bold font-body text-sm mb-1">{{ $invitation->resepsi_date?->format('H:i') }} WIB</span>
                                <span class="text-xs">Until Finish</span>
                            </div>
                            <div class="px-8">
                                <p class="font-heading text-lg italic mb-1">{{ $invitation->resepsi_venue }}</p>
                                <p class="text-xs font-body leading-relaxed">{{ $invitation->resepsi_address }}</p>
                            </div>
                        </div>

                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" 
                           class="inline-block px-8 py-3 bg-dark text-white text-[10px] font-bold uppercase tracking-widest hover:bg-gold transition-colors">
                            Google Maps
                        </a>
                    </div>
                </div>
            </div>
        </section>

        {{-- LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section class="py-24 px-6 bg-stone-50">
            <div class="reveal-up text-center mb-16">
                <span class="font-script text-4xl text-gold mb-2 block">Our Journey</span>
                <h2 class="font-heading text-4xl text-dark">Love Story</h2>
            </div>

            <div class="relative space-y-12 pl-8 border-l border-gold/20 ml-4 md:ml-0 md:pl-0 md:border-none">
                @foreach($invitation->love_story as $index => $story)
                <div class="reveal-up relative md:flex md:items-center md:gap-12 md:even:flex-row-reverse group">
                    {{-- Dot for mobile --}}
                    <div class="absolute -left-[37px] top-6 w-4 h-4 bg-gold rounded-full border-4 border-white md:hidden"></div>
                    
                    {{-- Date Circle (Desktop) --}}
                    <div class="hidden md:flex w-24 h-24 shrink-0 rounded-full border border-gold/30 items-center justify-center bg-white z-10 group-hover:border-gold transition-colors">
                        <span class="font-heading font-bold text-xl text-gold">{{ $story['year'] ?? '' }}</span>
                    </div>

                    <div class="bg-white p-8 rounded-2xl shadow-sm border border-gold/5 flex-1 relative group-hover:-translate-y-1 transition-transform duration-300">
                        <span class="font-heading text-5xl text-gold/10 absolute top-4 right-4">{{ $story['year'] ?? '' }}</span>
                        <h4 class="font-heading text-2xl mb-3 text-dark relative z-10">{{ $story['title'] ?? '' }}</h4>
                        <p class="font-body text-sm text-gray-500 leading-relaxed relative z-10">{{ $story['content'] ?? '' }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- GALLERY GRID --}}
        @if($metadata && isset($metadata['gallery']) && count($metadata['gallery']) > 0)
        <section id="gallery" class="py-2 bg-white">
            <div class="grid grid-cols-2 md:grid-cols-3 gap-1">
                @foreach($metadata['gallery'] as $img)
                    <div @click="openGallery('{{ asset('storage/' . $img) }}')" class="reveal-zoom aspect-square overflow-hidden group cursor-pointer">
                        <img src="{{ asset('storage/' . $img) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110">
                    </div>
                @endforeach
            </div>
        </section>
        @endif

        {{-- GIFTS --}}
        @if($invitation->bank_accounts && count($invitation->bank_accounts) > 0)
        <section class="py-24 px-6 bg-dark text-white text-center">
            <div class="reveal-up max-w-lg mx-auto">
                <h2 class="font-heading text-4xl mb-6 text-gold-light">Wedding Gift</h2>
                <p class="font-heading text-xl italic text-gray-300 mb-12">
                    Your blessing is the greatest gift of all. However, if you wish to honor us with a gift, we have provided our digital details below.
                </p>

                <div class="grid gap-6">
                    @foreach($invitation->bank_accounts as $bank)
                    <div class="p-6 rounded-xl border border-white/10 bg-white/5 backdrop-blur hover:bg-white/10 transition-colors">
                        <p class="font-body text-[10px] uppercase tracking-widest mb-2 opacity-50">{{ $bank['bank_name'] ?? 'Bank' }}</p>
                        <p class="font-mono text-xl tracking-wider mb-2 text-gold-light select-all">{{ $bank['account_number'] ?? '' }}</p>
                        <p class="font-heading italic text-sm text-gray-400 mb-6">a.n {{ $bank['account_holder'] ?? '' }}</p>
                        
                        <button @click="copyToClipboard('{{ $bank['account_number'] ?? '' }}', $event)" 
                                class="text-[10px] uppercase tracking-widest border border-white/20 py-2 px-6 hover:bg-white hover:text-dark transition-colors">
                            Copy Number
                        </button>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES --}}
        <section id="rsvp" class="py-24 px-6 bg-cream"
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
            <div class="max-w-md mx-auto">
                {{-- Header --}}
                <div class="reveal-up text-center mb-12">
                    <span class="font-script text-4xl text-gold block mb-2">Wishes</span>
                    <h2 class="font-heading text-4xl text-dark">Ucapan & RSVP</h2>
                    <div class="w-12 h-px bg-gold mx-auto mt-4"></div>
                </div>

                {{-- Stats --}}
                <div class="reveal-up grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-white rounded-2xl p-5 text-center border border-gold/10 shadow-sm">
                        <div class="font-heading text-3xl font-bold text-gold" x-text="stats.total_wishes">0</div>
                        <div class="font-body text-[10px] uppercase tracking-widest text-gray-400 mt-1">Ucapan</div>
                    </div>
                    <div class="bg-white rounded-2xl p-5 text-center border border-gold/10 shadow-sm">
                        <div class="font-heading text-3xl font-bold text-gold" x-text="stats.total_confirmed">0</div>
                        <div class="font-body text-[10px] uppercase tracking-widest text-gray-400 mt-1">Tamu Hadir</div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="reveal-up bg-white rounded-2xl p-6 border border-gold/10 shadow-lg mb-8">
                    <div x-show="success" x-transition class="mb-5 p-4 bg-emerald-50 border border-emerald-200 rounded-xl text-emerald-700 text-sm flex items-center gap-2">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                        <span class="font-body">Terima kasih! Ucapan Anda telah tersimpan.</span>
                    </div>
                    <div x-show="error" x-transition class="mb-5 p-4 bg-red-50 border border-red-200 rounded-xl text-red-700 text-sm font-body" x-text="error"></div>

                    <form @submit.prevent="submitForm" class="space-y-5">
                        <div>
                            <label class="block font-body text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Nama</label>
                            <input type="text" x-model="name" placeholder="Nama lengkap Anda" 
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 font-body text-sm focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-all">
                        </div>
                        <div>
                            <label class="block font-body text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Ucapan & Doa</label>
                            <textarea x-model="message" rows="3" placeholder="Tulis ucapan dan doa Anda..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-200 font-body text-sm focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-all resize-none"></textarea>
                        </div>
                        <div>
                            <label class="block font-body text-xs font-medium text-gray-600 uppercase tracking-wider mb-3">Konfirmasi Kehadiran</label>
                            <div class="grid grid-cols-2 gap-3">
                                <button type="button" @click="status = 'confirmed'"
                                    :class="status === 'confirmed' ? 'bg-dark text-white border-dark' : 'bg-white text-gray-600 border-gray-200'"
                                    class="py-3 rounded-xl font-body text-xs font-bold uppercase tracking-wider border transition-all">✓ Hadir</button>
                                <button type="button" @click="status = 'declined'"
                                    :class="status === 'declined' ? 'bg-dark text-white border-dark' : 'bg-white text-gray-600 border-gray-200'"
                                    class="py-3 rounded-xl font-body text-xs font-bold uppercase tracking-wider border transition-all">✗ Tidak Hadir</button>
                            </div>
                        </div>
                        <div x-show="status === 'confirmed'" x-transition>
                            <label class="block font-body text-xs font-medium text-gray-600 uppercase tracking-wider mb-2">Jumlah Tamu</label>
                            <select x-model="pax" class="w-full px-4 py-3 rounded-xl border border-gray-200 font-body text-sm focus:outline-none focus:border-gold focus:ring-2 focus:ring-gold/20 transition-all appearance-none bg-white">
                                <option value="1">1 Orang</option>
                                <option value="2">2 Orang</option>
                                <option value="3">3 Orang</option>
                                <option value="4">4 Orang</option>
                                <option value="5">5 Orang</option>
                            </select>
                        </div>
                        <button type="submit" :disabled="loading" 
                            class="w-full py-4 bg-dark text-white rounded-xl font-body text-[10px] font-bold uppercase tracking-widest hover:bg-gold transition-colors disabled:opacity-50">
                            <span x-show="!loading">Kirim Ucapan & Konfirmasi</span>
                            <span x-show="loading">Mengirim...</span>
                        </button>
                    </form>
                </div>

                {{-- Wishes List --}}
                <div class="space-y-4">
                    <h3 class="font-body text-[10px] font-bold uppercase tracking-widest text-gray-500">Ucapan Terbaru</h3>
                    <template x-for="wish in wishes" :key="wish.id">
                        <div class="reveal-up bg-white rounded-xl p-4 border border-gold/10 shadow-sm">
                            <div class="flex gap-3">
                                <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gold to-gold-dark text-white flex items-center justify-center font-heading font-bold text-sm flex-shrink-0" x-text="wish.initial"></div>
                                <div class="flex-1 min-w-0">
                                    <div class="flex items-center justify-between mb-1">
                                        <h4 class="font-heading font-semibold text-dark text-base truncate" x-text="wish.name"></h4>
                                        <span class="font-body text-[10px] text-gray-400" x-text="wish.time"></span>
                                    </div>
                                    <p class="font-body text-sm text-gray-500 leading-relaxed" x-text="wish.message"></p>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div x-show="wishes.length === 0" class="bg-white rounded-xl p-8 border border-dashed border-gold/20 text-center">
                        <p class="font-body text-sm text-gray-400">Belum ada ucapan. Jadilah yang pertama!</p>
                    </div>
                </div>
            </div>
        </section>

        {{-- FOOTER --}}
        <footer class="py-20 bg-white text-center border-t border-gold/10 pb-32">
            <h2 class="font-heading text-5xl mb-6 text-dark">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <p class="font-body text-[10px] uppercase tracking-[0.4em] text-gray-400">See You At The Wedding</p>
        </footer>

        {{-- BOTTOM NAV --}}
        <nav class="fixed bottom-6 left-1/2 -translate-x-1/2 bg-dark/90 backdrop-blur-md px-6 py-3 rounded-full z-40 border border-white/10 shadow-2xl flex items-center gap-6">
            <a href="#home" class="text-white/50 hover:text-gold transition-colors"><span class="sr-only">Home</span>🏠</a>
            <a href="#couple" class="text-white/50 hover:text-gold transition-colors"><span class="sr-only">Couple</span>💍</a>
            <a href="#events" class="text-white/50 hover:text-gold transition-colors"><span class="sr-only">Events</span>📅</a>
            @if($invitation->music_url)
            <button @click="toggleAudio()" 
                    class="w-8 h-8 rounded-full bg-gold/20 flex items-center justify-center text-gold border border-gold transition-all"
                    :class="{ 'pulse-ring': isPlaying, 'opacity-50': !isPlaying }">
                <span x-text="isPlaying ? '🎵' : '🔇'" class="text-xs"></span>
            </button>
            @endif
        </nav>

        {{-- GALLERY MODAL --}}
        <div x-show="showGalleryModal" 
             x-transition.opacity
             @click.self="showGalleryModal = false"
             class="fixed inset-0 z-[10000] bg-black/95 flex items-center justify-center p-4">
            <button @click="showGalleryModal = false" class="absolute top-6 right-6 text-white text-4xl font-light hover:text-gold transition-colors">&times;</button>
            <img :src="modalImage" class="max-h-[85vh] max-w-full rounded shadow-2xl">
        </div>

    </div>
</div>
