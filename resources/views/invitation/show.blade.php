<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ $invitation->title }} | ExoInvite</title>
    <meta name="description" content="{{ Str::limit($invitation->content['welcome_message'] ?? 'Undangan Digital', 160) }}">
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=outfit:300,400,500,600,700,800|playfair-display:400,500,600,700&display=swap" rel="stylesheet" />
    
    <!-- Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <style>
        .font-display { font-family: 'Playfair Display', serif; }
        .parallax-bg { background-attachment: fixed; }
    </style>
</head>
    @if($invitation->theme && View::exists('components.themes.' . $invitation->theme->slug))
        {{-- Dynamic Theme Render --}}
        <x-dynamic-component 
            :component="'themes.' . $invitation->theme->slug"
            :data="$invitation"
            :guest="$guest"
        />
        
        {{-- Global Scripts (Music, RSVP Modal Wrapper if needed generically) --}}
        <!-- Music Player Global Fallback if theme doesn't implement it -->
        @if($invitation->music_enabled && $invitation->music_url && !str_contains($invitation->theme->slug, 'elegan-nature')) 
             {{-- Elegan Nature might have its own player or we can inject generic one --}}
             {{-- For now, let's keep generic player only if theme doesn't strictly prohibit it --}}
             <div x-data="audioPlayer('{{ asset('storage/' . $invitation->music_url) }}')" class="fixed bottom-6 right-6 z-50">
                <button 
                    @click="toggle"
                    class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-lg animate-spin-slow"
                    :class="{ 'animate-none': !playing }"
                >
                    <span x-show="!playing">🔇</span>
                    <span x-show="playing">🎵</span>
                </button>
                <audio x-ref="audio" loop>
                    <source src="{{ asset('storage/' . $invitation->music_url) }}" type="audio/mpeg">
                </audio>
            </div>
        @endif

    @else
        {{-- DEFAULT THEME (Hardcoded Fallback) --}}
        <div class="font-outfit antialiased bg-slate-900 text-white">
            <!-- Opening Cover -->
            <section id="cover" class="fixed inset-0 z-50 flex items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 transition-all duration-700" x-data="{ opened: false }" x-show="!opened" x-transition:leave="opacity-0 scale-105" x-transition:leave-end="opacity-0">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="absolute -top-40 -right-40 w-80 h-80 bg-rose-500/20 rounded-full blur-3xl animate-float"></div>
                    <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
                </div>
                
                <div class="relative text-center px-6">
                    <p class="text-white/60 text-lg mb-4">Undangan Pernikahan</p>
                    
                    <h1 class="font-display text-5xl sm:text-7xl font-bold mb-2">
                        <span class="text-gradient">{{ $content['groom_name'] ?? 'Mempelai Pria' }}</span>
                    </h1>
                    <div class="text-4xl my-4">&</div>
                    <h1 class="font-display text-5xl sm:text-7xl font-bold mb-8">
                        <span class="text-gradient">{{ $content['bride_name'] ?? 'Mempelai Wanita' }}</span>
                    </h1>

                    @if($guest)
                        <p class="text-white/70 text-lg mb-8">Kepada Yth: <span class="font-semibold text-white">{{ $guest->name }}</span></p>
                    @endif

                    <button 
                        @click="opened = true; document.getElementById('cover').classList.add('hidden')"
                        class="px-10 py-4 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg shadow-rose-500/30 animate-pulse-glow"
                    >
                        Buka Undangan
                    </button>
                </div>
            </section>

            <!-- Main Content -->
            <main id="main-content" class="relative">
                <!-- Hero Section -->
                <section class="min-h-screen flex items-center justify-center bg-gradient-to-br from-rose-900/40 via-purple-900/40 to-amber-900/40 parallax-bg">
                    <div class="absolute inset-0 bg-black/50"></div>
                    <div class="relative text-center px-6 py-20">
                        <p class="text-white/60 tracking-widest mb-6">THE WEDDING OF</p>
                        
                        <h1 class="font-display text-6xl sm:text-8xl font-bold mb-4">
                            {{ $content['groom_name'] ?? 'Mempelai Pria' }}
                        </h1>
                        <div class="text-5xl my-6 text-rose-400">&</div>
                        <h1 class="font-display text-6xl sm:text-8xl font-bold mb-8">
                            {{ $content['bride_name'] ?? 'Mempelai Wanita' }}
                        </h1>

                        <p class="text-2xl text-white/80 font-light">
                            {{ $invitation->event_date?->translatedFormat('l, d F Y') ?? 'Tanggal Acara' }}
                        </p>
                    </div>
                </section>

                <!-- Countdown Section -->
                <section class="py-20 bg-slate-900">
                    <div class="max-w-4xl mx-auto px-6 text-center">
                        <h2 class="font-display text-4xl font-bold mb-12">Menghitung Hari</h2>
                        
                        <div class="grid grid-cols-4 gap-4" x-data="countdown('{{ $invitation->event_date?->toISOString() ?? now()->addDays(30)->toISOString() }}')">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                                <div class="text-5xl font-bold text-gradient" x-text="days">00</div>
                                <div class="text-white/60 mt-2">Hari</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                                <div class="text-5xl font-bold text-gradient" x-text="hours">00</div>
                                <div class="text-white/60 mt-2">Jam</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                                <div class="text-5xl font-bold text-gradient" x-text="minutes">00</div>
                                <div class="text-white/60 mt-2">Menit</div>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-6">
                                <div class="text-5xl font-bold text-gradient" x-text="seconds">00</div>
                                <div class="text-white/60 mt-2">Detik</div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Event Details Section -->
                <section class="py-20 bg-gradient-to-b from-slate-900 to-purple-900/30">
                    <div class="max-w-4xl mx-auto px-6">
                        <h2 class="font-display text-4xl font-bold text-center mb-12">Waktu & Tempat</h2>
                        
                        <div class="grid md:grid-cols-2 gap-8">
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 text-center">
                                <div class="w-16 h-16 bg-rose-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <h3 class="font-display text-2xl font-semibold mb-2">Tanggal</h3>
                                <p class="text-white/80 text-lg">{{ $invitation->event_date?->translatedFormat('l, d F Y') ?? '-' }}</p>
                                <p class="text-white/60 mt-2">{{ $content['event_time'] ?? '-' }} WIB</p>
                            </div>
                            
                            <div class="bg-white/10 backdrop-blur-sm rounded-2xl p-8 text-center">
                                <div class="w-16 h-16 bg-amber-500/20 rounded-full flex items-center justify-center mx-auto mb-4">
                                    <svg class="w-8 h-8 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <h3 class="font-display text-2xl font-semibold mb-2">Lokasi</h3>
                                <p class="text-white/80 text-lg">{{ $content['venue_name'] ?? '-' }}</p>
                                <p class="text-white/60 mt-2">{{ $content['venue_address'] ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- RSVP & Wishes Section - Using Reusable Component -->
                <x-invitation.rsvp-wishes :invitation="$invitation" theme="dark" />

                <!-- Footer -->
                <footer class="py-12 bg-slate-900 border-t border-white/10">
                    <div class="text-center">
                        <p class="text-white/60 mb-2">Thank you for being part of our special day</p>
                        <p class="text-gradient font-display text-2xl font-bold">
                            {{ $content['groom_name'] ?? '' }} & {{ $content['bride_name'] ?? '' }}
                        </p>
                        <p class="text-white/40 text-sm mt-6">Powered by ExoInvite</p>
                    </div>
                </footer>

                @if($invitation->music_enabled && $invitation->music_url)
                    <div x-data="audioPlayer('{{ asset('storage/' . $invitation->music_url) }}')" class="fixed bottom-6 right-6 z-50">
                        <button 
                            @click="toggle"
                            class="w-12 h-12 rounded-full bg-white/10 backdrop-blur-md border border-white/20 flex items-center justify-center text-white hover:bg-white/20 transition-all shadow-lg animate-spin-slow"
                            :class="{ 'animate-none': !playing }"
                        >
                            <span x-show="!playing">🔇</span>
                            <span x-show="playing">🎵</span>
                        </button>
                        <audio x-ref="audio" loop>
                            <source src="{{ asset('storage/' . $invitation->music_url) }}" type="audio/mpeg">
                        </audio>
                    </div>
                @endif
            </main>
        </div>
    @endif

    <!-- Alpine.js for countdown & audio -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <script>
        function audioPlayer(url) {
            return {
                playing: false,
                init() {
                    // Auto play attempt
                    const audio = this.$refs.audio;
                    
                    // Listen to cover opening to start music
                    document.addEventListener('click', () => {
                        if (!this.playing) {
                            this.toggle();
                        }
                    }, { once: true });
                },
                toggle() {
                    const audio = this.$refs.audio;
                    if (this.playing) {
                        audio.pause();
                    } else {
                        audio.play().catch(e => console.log('Autoplay prevented'));
                    }
                    this.playing = !this.playing;
                }
            }
        }

        function countdown(targetDate) {
            return {
                days: '00',
                hours: '00',
                minutes: '00', 
                seconds: '00',
                init() {
                    this.updateCountdown();
                    setInterval(() => this.updateCountdown(), 1000);
                },
                updateCountdown() {
                    const target = new Date(targetDate).getTime();
                    const now = new Date().getTime();
                    const diff = target - now;
                    
                    if (diff > 0) {
                        this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                        this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                        this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                        this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
                    }
                }
            }
        }
    </script>
</body>
</html>
