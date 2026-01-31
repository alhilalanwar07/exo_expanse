{{-- Royal Gold Theme - Mobile First Design --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#7c2d12">
    <title>{{ $invitation->title }} | ExoInvite</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=cinzel:400,500,600,700|cormorant-garamond:400,500,600|great-vibes:400&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Cormorant Garamond', serif; overflow-x: hidden; }
        .font-display { font-family: 'Great Vibes', cursive; }
        .font-royal { font-family: 'Cinzel', serif; }
        
        .royal-gradient { background: linear-gradient(180deg, #1c1917 0%, #292524 50%, #1c1917 100%); }
        .gold-text { color: #d4af37; }
        .gold-gradient { background: linear-gradient(135deg, #f5d16c 0%, #d4af37 50%, #b8860b 100%); -webkit-background-clip: text; background-clip: text; color: transparent; }
        
        .ornament-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='80' height='80' viewBox='0 0 80 80' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M40 0 L45 15 L60 20 L45 25 L40 40 L35 25 L20 20 L35 15 Z' fill='%23d4af37' fill-opacity='0.05'/%3E%3C/svg%3E");
        }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes shimmer { 0%, 100% { opacity: 0.5; } 50% { opacity: 1; } }
        @keyframes glow { 0%, 100% { text-shadow: 0 0 10px rgba(212,175,55,0.5); } 50% { text-shadow: 0 0 20px rgba(212,175,55,0.8); } }
        
        .animate-fade-in-up { animation: fadeInUp 1s ease-out forwards; }
        .animate-shimmer { animation: shimmer 3s ease-in-out infinite; }
        .animate-glow { animation: glow 2s ease-in-out infinite; }
        
        .scroll-animate { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-animate.visible { opacity: 1; transform: translateY(0); }
        
        .gold-border { border: 1px solid rgba(212,175,55,0.3); }
        .gold-border-strong { border: 2px solid #d4af37; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-stone-900 text-stone-100 no-scrollbar">
    @php 
        $content = $invitation->content ?? []; 
        $nameOrder = $content['name_order'] ?? 'groom_first';
        $firstName = $nameOrder === 'bride_first' ? ($content['bride_name'] ?? 'Juliet') : ($content['groom_name'] ?? 'Romeo');
        $secondName = $nameOrder === 'bride_first' ? ($content['groom_name'] ?? 'Romeo') : ($content['bride_name'] ?? 'Juliet');
    @endphp

    <!-- Cover -->
    <section id="cover" class="fixed inset-0 z-50" 
             x-data="{ opened: false }" x-show="!opened" x-transition:leave="transition ease-in duration-700">
        
        <!-- Blurred Background -->
        <div class="absolute inset-0 royal-gradient ornament-pattern"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-stone-900/50"></div>
        
        <!-- Shimmer Lines -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
            <div class="absolute top-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent animate-shimmer"></div>
            <div class="absolute bottom-0 left-0 right-0 h-1 bg-gradient-to-r from-transparent via-amber-500 to-transparent animate-shimmer"></div>
        </div>
        
        <!-- Cover Content -->
        <div class="relative z-20 h-full flex items-center justify-center">
            <div class="text-center px-8 max-w-sm mx-auto animate-fade-in-up">
                
                <!-- Subtitle -->
                <p class="font-display text-3xl gold-text mb-6">{{ $invitation->cover_subtitle ?? 'We Invite You To' }}</p>
                
                <!-- Photo Frame with Arch Design OR Initials -->
                <div class="relative mx-auto w-48 h-56 mb-6">
                    <!-- Decorative Arch -->
                    <div class="absolute inset-0 border-2 border-amber-500 rounded-t-full"></div>
                    <div class="absolute inset-1 border border-amber-400/50 rounded-t-full"></div>
                    
                    <!-- Photo or Initials -->
                    <div class="absolute inset-3 rounded-t-full overflow-hidden bg-gradient-to-br from-stone-800 to-stone-900 flex items-center justify-center">
                        @if($invitation->cover_photo)
                            <img src="{{ asset('storage/' . $invitation->cover_photo) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <!-- Initials Display -->
                            <div class="text-center">
                                <span class="font-display text-6xl gold-gradient animate-glow">
                                    {{ mb_substr($firstName, 0, 1) }}
                                </span>
                                <span class="font-display text-4xl gold-text mx-1">&</span>
                                <span class="font-display text-6xl gold-gradient animate-glow">
                                    {{ mb_substr($secondName, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Decorative Elements -->
                    <div class="absolute -top-3 -left-3 text-2xl">👑</div>
                    <div class="absolute -top-3 -right-3 text-2xl">👑</div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-xl">✨</div>
                </div>
                
                <!-- Couple Names -->
                <h1 class="font-display text-5xl gold-gradient animate-glow">{{ $firstName }}</h1>
                <p class="text-2xl gold-text my-3 animate-shimmer">&</p>
                <h1 class="font-display text-5xl gold-gradient animate-glow">{{ $secondName }}</h1>
                
                <div class="h-px bg-gradient-to-r from-transparent via-amber-500 to-transparent mt-6 mb-6"></div>
                
                <!-- Kepada Section -->
                <div class="gold-border rounded-xl px-6 py-5 bg-stone-900/70">
                    <p class="gold-text text-xs uppercase tracking-widest mb-1">Kepada</p>
                    <p class="text-white font-royal text-xl mb-2">{{ $guest->name ?? 'Tamu Undangan' }}</p>
                    <p class="text-stone-400 text-sm leading-relaxed">
                        Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara kami
                    </p>
                </div>
                
                <!-- Open Button -->
                <button @click="opened = true; document.getElementById('cover').classList.add('hidden'); document.body.style.overflow = 'auto';"
                    class="mt-6 w-full py-4 bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 text-stone-900 font-royal font-bold text-sm tracking-widest uppercase rounded-lg shadow-lg shadow-amber-500/30 active:scale-95 transition-transform">
                    👑 Buka Undangan
                </button>
            </div>
        </div>
    </section>

    <!-- Main -->
    <main class="relative" style="display: none;" x-data x-init="$el.style.display = 'block'">
        
        <!-- Hero -->
        <section class="min-h-screen flex items-center justify-center royal-gradient ornament-pattern py-20">
            <div class="text-center px-8 scroll-animate" data-animate>
                <p class="gold-text text-xs tracking-[0.5em] uppercase font-royal mb-10">Together With Their Families</p>
                
                <h1 class="font-display text-6xl gold-gradient leading-tight">{{ $firstName }}</h1>
                <p class="text-3xl gold-text my-4 animate-shimmer">&</p>
                <h1 class="font-display text-6xl gold-gradient leading-tight">{{ $secondName }}</h1>
                
                <div class="mt-12 inline-block gold-border rounded-lg px-8 py-5 bg-stone-900/50">
                    <p class="gold-text text-sm font-royal">{{ $invitation->event_date?->translatedFormat('l') }}</p>
                    <p class="text-2xl text-white font-royal my-1">{{ $invitation->event_date?->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </section>

        <!-- Couple -->
        <section class="py-20 px-8 bg-stone-950 ornament-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="text-center font-royal text-xs tracking-[0.5em] gold-text uppercase mb-16 scroll-animate" data-animate>The Couple</h2>
                
                <div class="text-center mb-16 scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-stone-800 flex items-center justify-center gold-border-strong">
                        <span class="text-5xl">🤵</span>
                    </div>
                    <h3 class="font-display text-3xl gold-gradient">{{ $content['groom_fullname'] ?? $content['groom_name'] ?? '' }}</h3>
                    @if($content['groom_parents'] ?? false)
                        <p class="text-stone-400 mt-3">Putra dari {{ $content['groom_parents'] }}</p>
                    @endif
                </div>
                
                <div class="text-center text-4xl gold-text my-8 animate-shimmer">👑</div>
                
                <div class="text-center scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-6 rounded-full bg-stone-800 flex items-center justify-center gold-border-strong">
                        <span class="text-5xl">👰</span>
                    </div>
                    <h3 class="font-display text-3xl gold-gradient">{{ $content['bride_fullname'] ?? $content['bride_name'] ?? '' }}</h3>
                    @if($content['bride_parents'] ?? false)
                        <p class="text-stone-400 mt-3">Putri dari {{ $content['bride_parents'] }}</p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Countdown -->
        @if($invitation->countdown_enabled)
        <section class="py-20 px-8 royal-gradient ornament-pattern">
            <div class="max-w-md mx-auto text-center">
                <p class="font-royal text-xs tracking-[0.5em] gold-text uppercase mb-12 scroll-animate" data-animate>Counting Down</p>
                <div class="grid grid-cols-4 gap-3 scroll-animate" data-animate x-data="countdown('{{ $invitation->event_date?->toISOString() ?? now()->addDays(30)->toISOString() }}')">
                    @foreach(['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds'] as $label => $var)
                    <div class="gold-border rounded-xl p-4 bg-stone-900/50">
                        <div class="text-3xl font-bold gold-text" x-text="{{ $var }}">00</div>
                        <div class="text-stone-500 text-xs mt-1 font-royal uppercase tracking-wider">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Events -->
        <section class="py-20 px-8 bg-stone-950 ornament-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="text-center font-royal text-xs tracking-[0.5em] gold-text uppercase mb-16 scroll-animate" data-animate>Events</h2>

                @if(in_array($content['event_type'] ?? 'both', ['akad_only', 'both']))
                <div class="gold-border rounded-2xl p-6 mb-6 bg-stone-900/30 scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-amber-900/50 rounded-full flex items-center justify-center text-2xl gold-border">💒</span>
                        <h3 class="font-royal text-lg gold-text uppercase tracking-wider">Akad Nikah</h3>
                    </div>
                    <div class="space-y-3 text-stone-300">
                        <p class="flex items-center gap-3"><span class="gold-text">📆</span>{{ \Carbon\Carbon::parse($content['akad_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                        <p class="flex items-center gap-3"><span class="gold-text">🕐</span>{{ $content['akad_time'] ?? '08:00' }} WIB</p>
                        <p class="flex items-start gap-3"><span class="gold-text">📍</span><span><strong class="text-white">{{ $content['akad_venue'] ?? '' }}</strong><br><span class="text-stone-500 text-sm">{{ $content['akad_address'] ?? '' }}</span></span></p>
                    </div>
                </div>
                @endif

                @if(in_array($content['event_type'] ?? 'both', ['resepsi_only', 'both']))
                <div class="gold-border rounded-2xl p-6 bg-stone-900/30 scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-amber-900/50 rounded-full flex items-center justify-center text-2xl gold-border">🍽️</span>
                        <h3 class="font-royal text-lg gold-text uppercase tracking-wider">Resepsi</h3>
                    </div>
                    <div class="space-y-3 text-stone-300">
                        <p class="flex items-center gap-3"><span class="gold-text">📆</span>{{ \Carbon\Carbon::parse($content['resepsi_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                        <p class="flex items-center gap-3"><span class="gold-text">🕐</span>{{ $content['resepsi_time'] ?? '11:00' }} WIB</p>
                        <p class="flex items-start gap-3"><span class="gold-text">📍</span><span><strong class="text-white">{{ $content['resepsi_venue'] ?? '' }}</strong><br><span class="text-stone-500 text-sm">{{ $content['resepsi_address'] ?? '' }}</span></span></p>
                    </div>
                </div>
                @endif

                @if($content['maps_url'] ?? false)
                <a href="{{ $content['maps_url'] }}" target="_blank" class="mt-8 w-full py-4 bg-gradient-to-r from-amber-600 via-amber-500 to-amber-600 text-stone-900 font-royal font-bold text-sm tracking-widest uppercase rounded-lg flex items-center justify-center active:scale-95 transition-transform scroll-animate" data-animate>
                    🗺️ Buka Google Maps
                </a>
                @endif
            </div>
        </section>

        <!-- Gallery -->
        @if($invitation->photos->count() > 0)
        <section class="py-20 px-8 royal-gradient ornament-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="text-center font-royal text-xs tracking-[0.5em] gold-text uppercase mb-12 scroll-animate" data-animate>Gallery</h2>
                <div class="grid grid-cols-2 gap-3 scroll-animate" data-animate>
                    @foreach($invitation->photos as $photo)
                    <div class="aspect-square rounded-xl overflow-hidden gold-border {{ $loop->first ? 'col-span-2' : '' }}">
                        <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP -->
        @if($invitation->rsvp_enabled)
        <section class="py-20 px-8 bg-stone-950 ornament-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="text-center font-royal text-xs tracking-[0.5em] gold-text uppercase mb-4 scroll-animate" data-animate>RSVP</h2>
                <p class="text-center text-stone-400 mb-10 scroll-animate" data-animate>Mohon konfirmasi kehadiran Anda</p>
                <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" theme="royal" />
            </div>
        </section>
        @endif

        <!-- Wishes -->
        @if($invitation->wishes_enabled)
        <section class="py-20 px-8 royal-gradient ornament-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="text-center font-royal text-xs tracking-[0.5em] gold-text uppercase mb-4 scroll-animate" data-animate>Wishes</h2>
                <p class="text-center text-stone-400 mb-10 scroll-animate" data-animate>Kirimkan ucapan dan doa</p>
                <livewire:invitation.wishes :invitation="$invitation" theme="royal" />
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="py-16 px-8 bg-stone-950 text-center ornament-pattern">
            <div class="h-px bg-gradient-to-r from-transparent via-amber-500 to-transparent mb-8"></div>
            <p class="font-display text-3xl gold-gradient">{{ $firstName }} & {{ $secondName }}</p>
            <p class="gold-text text-sm mt-2 font-royal">{{ $invitation->event_date?->translatedFormat('d.m.Y') }}</p>
            <div class="h-px bg-gradient-to-r from-transparent via-amber-500 to-transparent mt-8 mb-6 max-w-xs mx-auto"></div>
            <p class="text-stone-600 text-xs font-royal tracking-widest uppercase">ExoInvite</p>
        </footer>
    </main>

    @if($invitation->music_enabled && $invitation->music_url)
    <div x-data="{ playing: false }" class="fixed bottom-6 right-6 z-40">
        <audio id="bgMusic" src="{{ $invitation->music_url }}" loop></audio>
        <button @click="playing = !playing; playing ? $el.previousElementSibling.play() : $el.previousElementSibling.pause()" class="w-14 h-14 bg-stone-900 gold-border rounded-full shadow-xl flex items-center justify-center text-2xl active:scale-95 transition-transform">
            <span x-show="!playing">🔇</span><span x-show="playing" class="gold-text">🎵</span>
        </button>
    </div>
    @endif

    <script>
        function countdown(targetDate) {
            return {
                days: '00', hours: '00', minutes: '00', seconds: '00',
                init() { this.update(); setInterval(() => this.update(), 1000); },
                update() {
                    const diff = Math.max(0, new Date(targetDate).getTime() - Date.now());
                    this.days = String(Math.floor(diff / 86400000)).padStart(2, '0');
                    this.hours = String(Math.floor((diff % 86400000) / 3600000)).padStart(2, '0');
                    this.minutes = String(Math.floor((diff % 3600000) / 60000)).padStart(2, '0');
                    this.seconds = String(Math.floor((diff % 60000) / 1000)).padStart(2, '0');
                }
            };
        }
        document.addEventListener('DOMContentLoaded', () => {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('visible'); });
            }, { threshold: 0.1 });
            document.querySelectorAll('.scroll-animate').forEach(el => observer.observe(el));
            document.body.style.overflow = 'hidden';
        });
    </script>
</body>
</html>
