{{-- Elegant Rose Theme - Mobile First Design --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#be185d">
    <title>{{ $invitation->title }} | ExoInvite</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=playfair-display:400,500,600,700|cormorant-garamond:300,400,500,600|great-vibes:400&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { 
            font-family: 'Cormorant Garamond', serif; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        .font-display { font-family: 'Great Vibes', cursive; }
        .font-title { font-family: 'Playfair Display', serif; }
        
        /* Rose Theme Colors */
        :root {
            --rose-50: #fff1f2;
            --rose-100: #ffe4e6;
            --rose-200: #fecdd3;
            --rose-400: #fb7185;
            --rose-500: #f43f5e;
            --rose-600: #e11d48;
            --rose-700: #be185d;
            --rose-800: #9f1239;
            --rose-900: #881337;
        }
        
        .rose-gradient { 
            background: linear-gradient(180deg, #fff1f2 0%, #ffe4e6 30%, #fecdd3 70%, #fda4af 100%); 
        }
        
        /* Smooth Animations */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(40px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        @keyframes pulse-soft {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
        @keyframes float {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        @keyframes heart-beat {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.1); }
        }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .animate-fade-in { animation: fadeIn 0.6s ease-out forwards; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-heart { animation: heart-beat 1.5s ease-in-out infinite; }
        .animate-pulse-soft { animation: pulse-soft 2s ease-in-out infinite; }
        
        /* Scroll animations */
        .scroll-animate { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-animate.visible { opacity: 1; transform: translateY(0); }
        
        /* Decorative elements */
        .rose-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5c-5 8-15 10-22 6 3 12-3 22-12 27 15 3 22 15 22 27 0-12 7-24 22-27-9-5-15-15-12-27-7 4-17 2-22-6z' fill='%23fda4af' fill-opacity='0.15'/%3E%3C/svg%3E");
        }
        
        /* Glass effect */
        .glass {
            background: rgba(255,255,255,0.85);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
        }
        
        /* Section styling */
        .section-divider {
            height: 60px;
            background: linear-gradient(180deg, transparent 0%, rgba(253,164,175,0.2) 50%, transparent 100%);
        }
        
        /* Hide scrollbar but keep functionality */
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-rose-50 text-rose-900 no-scrollbar">
    @php 
        $content = $invitation->content ?? []; 
        $nameOrder = $content['name_order'] ?? 'groom_first';
        $firstName = $nameOrder === 'bride_first' ? ($content['bride_name'] ?? 'Juliet') : ($content['groom_name'] ?? 'Romeo');
        $secondName = $nameOrder === 'bride_first' ? ($content['groom_name'] ?? 'Romeo') : ($content['bride_name'] ?? 'Juliet');
        $groomData = ['name' => $content['groom_name'] ?? '', 'fullname' => $content['groom_fullname'] ?? '', 'parents' => $content['groom_parents'] ?? '', 'instagram' => $content['groom_instagram'] ?? ''];
        $brideData = ['name' => $content['bride_name'] ?? '', 'fullname' => $content['bride_fullname'] ?? '', 'parents' => $content['bride_parents'] ?? '', 'instagram' => $content['bride_instagram'] ?? ''];
    @endphp

    <!-- Cover / Opening Screen -->
    <section id="cover" class="fixed inset-0 z-50" 
             x-data="{ opened: false }" x-show="!opened" 
             x-transition:leave="transition ease-in duration-500" 
             x-transition:leave-opacity="opacity-0">
        
        <!-- Blurred Background -->
        <div class="absolute inset-0 rose-gradient rose-pattern"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-white/30"></div>
        
        <!-- Decorative floating elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
            <div class="absolute top-10 left-5 text-6xl animate-float opacity-60" style="animation-delay: 0s;">🌸</div>
            <div class="absolute top-20 right-8 text-4xl animate-float opacity-60" style="animation-delay: 0.5s;">💐</div>
            <div class="absolute bottom-32 left-10 text-5xl animate-float opacity-60" style="animation-delay: 1s;">🌷</div>
            <div class="absolute bottom-20 right-5 text-4xl animate-float opacity-60" style="animation-delay: 1.5s;">🌹</div>
        </div>
        
        <!-- Cover Content -->
        <div class="relative z-20 h-full flex items-center justify-center">
            <div class="text-center px-6 max-w-sm mx-auto animate-fade-in-up">
                
                <!-- Subtitle -->
                <p class="font-display text-3xl text-rose-600 mb-6">{{ $invitation->cover_subtitle ?? 'We Invite You To' }}</p>
                
                <!-- Photo Frame with Arch Design OR Initials -->
                <div class="relative mx-auto w-48 h-56 mb-6">
                    <!-- Decorative Arch -->
                    <div class="absolute inset-0 border-2 border-rose-300 rounded-t-full"></div>
                    <div class="absolute inset-1 border border-rose-200 rounded-t-full"></div>
                    
                    <!-- Photo or Initials -->
                    <div class="absolute inset-3 rounded-t-full overflow-hidden bg-gradient-to-br from-rose-100 to-rose-200 flex items-center justify-center">
                        @if($invitation->cover_photo)
                            <img src="{{ asset('storage/' . $invitation->cover_photo) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <!-- Initials Display -->
                            <div class="text-center">
                                <span class="font-display text-6xl text-rose-500">
                                    {{ mb_substr($firstName, 0, 1) }}
                                </span>
                                <span class="font-display text-4xl text-rose-400 mx-1">&</span>
                                <span class="font-display text-6xl text-rose-500">
                                    {{ mb_substr($secondName, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Decorative Flowers -->
                    <div class="absolute -top-3 -left-3 text-3xl">🌹</div>
                    <div class="absolute -top-3 -right-3 text-3xl">🌹</div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-2xl">💕</div>
                </div>
                
                <!-- Couple Names -->
                <h1 class="font-display text-5xl text-rose-800 mb-1">{{ $firstName }}</h1>
                <div class="text-3xl text-rose-400 my-2 animate-pulse-soft">&</div>
                <h1 class="font-display text-5xl text-rose-800">{{ $secondName }}</h1>
                
                <!-- Kepada Section -->
                <div class="mt-8 glass rounded-2xl px-6 py-5 border border-rose-200">
                    <p class="text-rose-500 text-sm uppercase tracking-wider mb-1">Kepada</p>
                    <p class="font-title text-2xl text-rose-800 font-semibold mb-2">{{ $guest->name ?? 'Tamu Undangan' }}</p>
                    <p class="text-rose-600/70 text-sm leading-relaxed">
                        Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara kami
                    </p>
                </div>
                
                <!-- Open Button -->
                <button @click="opened = true; document.getElementById('cover').classList.add('hidden'); document.body.style.overflow = 'auto';"
                    class="mt-6 w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-full shadow-lg shadow-rose-500/30 active:scale-95 transition-transform">
                    <span class="flex items-center justify-center gap-2">
                        💌 Buka Undangan
                    </span>
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="relative" style="display: none;" x-data x-init="$el.style.display = 'block'">
        
        <!-- Hero Section -->
        <section class="min-h-screen flex items-center justify-center rose-gradient rose-pattern relative overflow-hidden pt-10 pb-20">
            <div class="text-center px-6 scroll-animate" data-animate>
                <p class="text-rose-500 text-xs tracking-[0.4em] uppercase mb-6">Together With Their Families</p>
                
                <h1 class="font-display text-6xl text-rose-800 leading-tight">{{ $firstName }}</h1>
                <div class="text-4xl text-rose-400 my-4 animate-pulse-soft">&</div>
                <h1 class="font-display text-6xl text-rose-800 leading-tight">{{ $secondName }}</h1>
                
                <div class="mt-10 inline-block">
                    <div class="glass rounded-2xl px-8 py-4 shadow-lg">
                        <p class="text-rose-600 font-title text-lg">
                            {{ $invitation->event_date?->translatedFormat('l') }}
                        </p>
                        <p class="text-3xl font-title font-bold text-rose-800 my-1">
                            {{ $invitation->event_date?->translatedFormat('d F Y') }}
                        </p>
                    </div>
                </div>
                
                @if($content['welcome_message'] ?? false)
                    <p class="mt-8 text-rose-700/80 text-base leading-relaxed max-w-xs mx-auto italic">
                        "{{ $content['welcome_message'] }}"
                    </p>
                @endif
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Couple Section -->
        <section class="py-16 px-6 bg-white relative">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-rose-700 text-center mb-12 scroll-animate" data-animate>
                    Mempelai
                </h2>
                
                <!-- Groom -->
                <div class="text-center mb-12 scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center border-4 border-white shadow-xl">
                        @if($content['groom_photo'] ?? false)
                            <img src="{{ asset('storage/' . $content['groom_photo']) }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="text-5xl">🤵</span>
                        @endif
                    </div>
                    <h3 class="font-display text-3xl text-rose-800">{{ $groomData['fullname'] ?: $groomData['name'] }}</h3>
                    @if($groomData['parents'])
                        <p class="text-rose-600/70 mt-2">Putra dari {{ $groomData['parents'] }}</p>
                    @endif
                    @if($groomData['instagram'])
                        <a href="https://instagram.com/{{ $groomData['instagram'] }}" target="_blank" class="inline-flex items-center gap-1 mt-3 text-pink-500 text-sm">
                            <span>📸</span> @{{ $groomData['instagram'] }}
                        </a>
                    @endif
                </div>
                
                <div class="text-center text-4xl text-rose-300 my-8 animate-heart">💕</div>
                
                <!-- Bride -->
                <div class="text-center scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-pink-100 to-pink-200 flex items-center justify-center border-4 border-white shadow-xl">
                        @if($content['bride_photo'] ?? false)
                            <img src="{{ asset('storage/' . $content['bride_photo']) }}" class="w-full h-full object-cover rounded-full">
                        @else
                            <span class="text-5xl">👰</span>
                        @endif
                    </div>
                    <h3 class="font-display text-3xl text-rose-800">{{ $brideData['fullname'] ?: $brideData['name'] }}</h3>
                    @if($brideData['parents'])
                        <p class="text-rose-600/70 mt-2">Putri dari {{ $brideData['parents'] }}</p>
                    @endif
                    @if($brideData['instagram'])
                        <a href="https://instagram.com/{{ $brideData['instagram'] }}" target="_blank" class="inline-flex items-center gap-1 mt-3 text-pink-500 text-sm">
                            <span>📸</span> @{{ $brideData['instagram'] }}
                        </a>
                    @endif
                </div>
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Quote Section -->
        @if($content['quran_verse'] ?? false)
        <section class="py-16 px-6 bg-gradient-to-b from-white to-rose-50 relative">
            <div class="max-w-sm mx-auto text-center scroll-animate" data-animate>
                <span class="text-5xl mb-6 block">📖</span>
                <p class="text-rose-700 italic leading-relaxed text-lg">
                    "{{ $content['quran_verse'] }}"
                </p>
            </div>
        </section>
        @endif

        <div class="section-divider"></div>

        <!-- Countdown Section -->
        @if($invitation->countdown_enabled)
        <section class="py-16 px-6 bg-rose-50 rose-pattern">
            <div class="max-w-md mx-auto text-center">
                <h2 class="font-display text-4xl text-rose-700 mb-10 scroll-animate" data-animate>
                    ⏰ Hitung Mundur
                </h2>
                <div class="grid grid-cols-4 gap-3 scroll-animate" data-animate x-data="countdown('{{ $invitation->event_date?->toISOString() ?? now()->addDays(30)->toISOString() }}')">
                    @foreach(['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds'] as $label => $var)
                    <div class="glass rounded-2xl p-4 shadow-lg">
                        <div class="text-3xl font-bold text-rose-600" x-text="{{ $var }}">00</div>
                        <div class="text-rose-400 text-xs mt-1">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <div class="section-divider"></div>

        <!-- Event Section -->
        <section class="py-16 px-6 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-rose-700 text-center mb-12 scroll-animate" data-animate>
                    📅 Waktu & Tempat
                </h2>

                @if(in_array($content['event_type'] ?? 'both', ['akad_only', 'both']))
                <!-- Akad -->
                <div class="glass rounded-3xl p-6 mb-6 shadow-lg scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-2xl">💒</span>
                        <h3 class="font-title text-xl font-bold text-rose-800">Akad Nikah</h3>
                    </div>
                    <div class="space-y-3 text-rose-700">
                        <p class="flex items-center gap-3">
                            <span class="text-lg">📆</span>
                            <span>{{ \Carbon\Carbon::parse($content['akad_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-lg">🕐</span>
                            <span>{{ $content['akad_time'] ?? '08:00' }} WIB - Selesai</span>
                        </p>
                        <p class="flex items-start gap-3">
                            <span class="text-lg">📍</span>
                            <span>
                                <strong>{{ $content['akad_venue'] ?? 'Lokasi Akad' }}</strong><br>
                                <span class="text-rose-500 text-sm">{{ $content['akad_address'] ?? '' }}</span>
                            </span>
                        </p>
                    </div>
                </div>
                @endif

                @if(in_array($content['event_type'] ?? 'both', ['resepsi_only', 'both']))
                <!-- Resepsi -->
                <div class="glass rounded-3xl p-6 shadow-lg scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-emerald-100 rounded-full flex items-center justify-center text-2xl">🍽️</span>
                        <h3 class="font-title text-xl font-bold text-rose-800">Resepsi</h3>
                    </div>
                    <div class="space-y-3 text-rose-700">
                        <p class="flex items-center gap-3">
                            <span class="text-lg">📆</span>
                            <span>{{ \Carbon\Carbon::parse($content['resepsi_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</span>
                        </p>
                        <p class="flex items-center gap-3">
                            <span class="text-lg">🕐</span>
                            <span>{{ $content['resepsi_time'] ?? '11:00' }} WIB - Selesai</span>
                        </p>
                        <p class="flex items-start gap-3">
                            <span class="text-lg">📍</span>
                            <span>
                                <strong>{{ $content['resepsi_venue'] ?? 'Lokasi Resepsi' }}</strong><br>
                                <span class="text-rose-500 text-sm">{{ $content['resepsi_address'] ?? '' }}</span>
                            </span>
                        </p>
                    </div>
                </div>
                @endif

                <!-- Maps Button -->
                @if($content['maps_url'] ?? false)
                <a href="{{ $content['maps_url'] }}" target="_blank" 
                   class="mt-6 w-full py-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white font-semibold rounded-full shadow-lg flex items-center justify-center gap-2 active:scale-95 transition-transform scroll-animate" data-animate>
                    <span>🗺️</span> Buka Google Maps
                </a>
                @endif
            </div>
        </section>

        <div class="section-divider"></div>

        <!-- Gallery Section -->
        @if($invitation->photos->count() > 0)
        <section class="py-16 px-6 bg-rose-50 rose-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-rose-700 text-center mb-10 scroll-animate" data-animate>
                    📸 Galeri
                </h2>
                <div class="grid grid-cols-2 gap-3 scroll-animate" data-animate>
                    @foreach($invitation->photos as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden shadow-lg {{ $loop->first ? 'col-span-2' : '' }}">
                        <img src="{{ asset('storage/' . $photo->path) }}" alt="Gallery" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <div class="section-divider"></div>

        <!-- Gift Section -->
        @if($invitation->gift_enabled && count($invitation->gift_accounts ?? []) > 0)
        <section class="py-16 px-6 bg-white">
            <div class="max-w-md mx-auto text-center">
                <h2 class="font-display text-4xl text-rose-700 mb-4 scroll-animate" data-animate>
                    🎁 Hadiah
                </h2>
                <p class="text-rose-600/70 mb-8 scroll-animate" data-animate>Doa restu Anda sudah cukup bagi kami. Namun jika ingin memberikan hadiah:</p>
                
                @foreach($invitation->gift_accounts as $account)
                <div class="glass rounded-2xl p-5 mb-4 text-left scroll-animate" data-animate>
                    <p class="font-bold text-rose-800">{{ $account['bank'] ?? 'Bank' }}</p>
                    <p class="text-2xl font-mono text-rose-600 my-2">{{ $account['account_number'] ?? '' }}</p>
                    <p class="text-rose-500">a.n {{ $account['account_name'] ?? '' }}</p>
                    <button onclick="navigator.clipboard.writeText('{{ $account['account_number'] ?? '' }}')" 
                            class="mt-3 px-4 py-2 bg-rose-100 text-rose-600 rounded-full text-sm active:scale-95 transition-transform">
                        📋 Salin No. Rekening
                    </button>
                </div>
                @endforeach
            </div>
        </section>
        @endif

        <div class="section-divider"></div>

        <!-- RSVP Section -->
        @if($invitation->rsvp_enabled)
        <section class="py-16 px-6 bg-rose-50 rose-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-rose-700 text-center mb-4 scroll-animate" data-animate>
                    📝 Konfirmasi
                </h2>
                <p class="text-center text-rose-600/70 mb-8 scroll-animate" data-animate>Mohon konfirmasi kehadiran Anda</p>
                
                <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" theme="rose" />
            </div>
        </section>
        @endif

        <div class="section-divider"></div>

        <!-- Wishes Section -->
        @if($invitation->wishes_enabled)
        <section class="py-16 px-6 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-rose-700 text-center mb-4 scroll-animate" data-animate>
                    💬 Ucapan
                </h2>
                <p class="text-center text-rose-600/70 mb-8 scroll-animate" data-animate>Kirimkan ucapan dan doa terbaik</p>
                
                <livewire:invitation.wishes :invitation="$invitation" theme="rose" />
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="py-12 px-6 bg-gradient-to-b from-rose-100 to-rose-200 text-center">
            <p class="font-display text-3xl text-rose-700 mb-2">{{ $firstName }} & {{ $secondName }}</p>
            <p class="text-rose-500 text-sm">{{ $invitation->event_date?->translatedFormat('d.m.Y') }}</p>
            <p class="mt-6 text-rose-400 text-xs">Made with 💕 by ExoInvite</p>
        </footer>

    </main>

    <!-- Background Music -->
    @if($invitation->music_enabled && $invitation->music_url)
    <div x-data="{ playing: false }" class="fixed bottom-6 right-6 z-40">
        <audio id="bgMusic" src="{{ $invitation->music_url }}" loop></audio>
        <button @click="playing = !playing; playing ? $refs.audio.play() : $refs.audio.pause()" 
                x-ref="musicBtn"
                class="w-14 h-14 glass rounded-full shadow-xl flex items-center justify-center text-2xl active:scale-95 transition-transform">
            <span x-show="!playing">🔇</span>
            <span x-show="playing">🎵</span>
        </button>
    </div>
    @endif

    <script>
        // Countdown function
        function countdown(targetDate) {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() {
                    this.update();
                    setInterval(() => this.update(), 1000);
                },
                update() {
                    const target = new Date(targetDate).getTime();
                    const now = new Date().getTime();
                    const diff = Math.max(0, target - now);
                    
                    this.days = String(Math.floor(diff / (1000 * 60 * 60 * 24))).padStart(2, '0');
                    this.hours = String(Math.floor((diff % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60))).padStart(2, '0');
                    this.minutes = String(Math.floor((diff % (1000 * 60 * 60)) / (1000 * 60))).padStart(2, '0');
                    this.seconds = String(Math.floor((diff % (1000 * 60)) / 1000)).padStart(2, '0');
                }
            };
        }

        // Scroll animations
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                    }
                });
            }, { threshold: 0.1, rootMargin: '0px 0px -50px 0px' });

            document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
            
            // Lock body scroll when cover is shown
            document.body.style.overflow = 'hidden';
        });
    </script>
</body>
</html>
