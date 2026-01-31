{{-- Rustic Garden Theme - Mobile First Design --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#78350f">
    <title>{{ $invitation->title }} | ExoInvite</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=amatic-sc:400,700|josefin-sans:300,400,500,600|great-vibes:400&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Josefin Sans', sans-serif; overflow-x: hidden; }
        .font-display { font-family: 'Great Vibes', cursive; }
        .font-rustic { font-family: 'Amatic SC', cursive; }
        
        .rustic-gradient { background: linear-gradient(180deg, #fef3c7 0%, #fde68a 30%, #fcd34d 70%, #f59e0b 100%); }
        .rustic-cream { background: linear-gradient(180deg, #fffbeb 0%, #fef3c7 50%, #fde68a 100%); }
        
        .leaf-pattern {
            background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M30 5c-5 8-15 10-22 6 3 12-3 22-12 27 15 3 22 15 22 27 0-12 7-24 22-27-9-5-15-15-12-27-7 4-17 2-22-6z' fill='%2322c55e' fill-opacity='0.08'/%3E%3C/svg%3E");
        }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(40px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes sway { 0%, 100% { transform: rotate(-3deg); } 50% { transform: rotate(3deg); } }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
        
        .animate-sway { animation: sway 4s ease-in-out infinite; }
        .animate-float { animation: float 3s ease-in-out infinite; }
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        
        .scroll-animate { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-animate.visible { opacity: 1; transform: translateY(0); }
        
        .glass { background: rgba(255,255,255,0.9); backdrop-filter: blur(12px); }
        .section-divider { height: 40px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 20'%3E%3Cpath d='M0 20 Q25 0 50 20 Q75 40 100 20 L100 0 L0 0Z' fill='%23fef3c7'/%3E%3C/svg%3E") no-repeat center; background-size: cover; }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-amber-50 text-amber-900 no-scrollbar">
    @php 
        $content = $invitation->content ?? []; 
        $nameOrder = $content['name_order'] ?? 'groom_first';
        $firstName = $nameOrder === 'bride_first' ? ($content['bride_name'] ?? 'Juliet') : ($content['groom_name'] ?? 'Romeo');
        $secondName = $nameOrder === 'bride_first' ? ($content['groom_name'] ?? 'Romeo') : ($content['bride_name'] ?? 'Juliet');
    @endphp

    <!-- Cover -->
    <section id="cover" class="fixed inset-0 z-50" 
             x-data="{ opened: false }" x-show="!opened" x-transition:leave="transition ease-in duration-500">
        
        <!-- Blurred Background -->
        <div class="absolute inset-0 rustic-cream leaf-pattern"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-amber-50/40"></div>
        
        <!-- Decorative elements -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none z-10">
            <div class="absolute top-10 left-5 text-5xl animate-sway origin-bottom opacity-60" style="animation-delay: 0s;">🌿</div>
            <div class="absolute top-20 right-8 text-4xl animate-float opacity-60" style="animation-delay: 0.5s;">🌻</div>
            <div class="absolute bottom-32 left-8 text-4xl animate-sway origin-bottom opacity-60" style="animation-delay: 1s;">🍃</div>
            <div class="absolute bottom-24 right-6 text-5xl animate-float opacity-60" style="animation-delay: 1.5s;">🌾</div>
        </div>
        
        <!-- Cover Content -->
        <div class="relative z-20 h-full flex items-center justify-center">
            <div class="text-center px-6 max-w-sm mx-auto animate-fade-in-up">
                
                <!-- Subtitle -->
                <p class="font-display text-3xl text-amber-700 mb-6">{{ $invitation->cover_subtitle ?? 'We Invite You To' }}</p>
                
                <!-- Photo Frame with Arch Design OR Initials -->
                <div class="relative mx-auto w-48 h-56 mb-6">
                    <!-- Decorative Arch -->
                    <div class="absolute inset-0 border-2 border-amber-400 rounded-t-full"></div>
                    <div class="absolute inset-1 border border-amber-300 rounded-t-full"></div>
                    
                    <!-- Photo or Initials -->
                    <div class="absolute inset-3 rounded-t-full overflow-hidden bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center">
                        @if($invitation->cover_photo)
                            <img src="{{ asset('storage/' . $invitation->cover_photo) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <!-- Initials Display -->
                            <div class="text-center">
                                <span class="font-display text-6xl text-amber-600">
                                    {{ mb_substr($firstName, 0, 1) }}
                                </span>
                                <span class="font-display text-4xl text-amber-500 mx-1">&</span>
                                <span class="font-display text-6xl text-amber-600">
                                    {{ mb_substr($secondName, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                    
                    <!-- Decorative Flowers -->
                    <div class="absolute -top-3 -left-3 text-3xl">🌻</div>
                    <div class="absolute -top-3 -right-3 text-3xl">�</div>
                    <div class="absolute -bottom-2 left-1/2 -translate-x-1/2 text-2xl">🌿</div>
                </div>
                
                <!-- Couple Names -->
                <h1 class="font-display text-5xl text-amber-800 mb-1">{{ $firstName }}</h1>
                <div class="text-3xl text-amber-500 my-2">&</div>
                <h1 class="font-display text-5xl text-amber-800">{{ $secondName }}</h1>
                
                <!-- Kepada Section -->
                <div class="mt-8 glass rounded-2xl px-6 py-5 border border-amber-200">
                    <p class="text-amber-600 text-sm uppercase tracking-wider mb-1">Kepada</p>
                    <p class="font-rustic text-2xl text-amber-800 font-bold mb-2">{{ $guest->name ?? 'Tamu Undangan' }}</p>
                    <p class="text-amber-700/70 text-sm leading-relaxed">
                        Tanpa mengurangi rasa hormat, kami mengundang Bapak/Ibu/Saudara/i untuk hadir di acara kami
                    </p>
                </div>
                
                <!-- Open Button -->
                <button @click="opened = true; document.getElementById('cover').classList.add('hidden'); document.body.style.overflow = 'auto';"
                    class="mt-6 w-full py-4 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-full shadow-lg shadow-amber-600/30 active:scale-95 transition-transform">
                    🌻 Buka Undangan
                </button>
            </div>
        </div>
    </section>

    <!-- Main Content -->
    <main class="relative" style="display: none;" x-data x-init="$el.style.display = 'block'">
        
        <!-- Hero -->
        <section class="min-h-screen flex items-center justify-center rustic-cream leaf-pattern pt-10 pb-20">
            <div class="text-center px-6 scroll-animate" data-animate>
                <p class="text-amber-600 text-xs tracking-[0.4em] uppercase mb-6">Together With Their Families</p>
                
                <h1 class="font-display text-6xl text-amber-800">{{ $firstName }}</h1>
                <div class="text-4xl text-amber-500 my-4">&</div>
                <h1 class="font-display text-6xl text-amber-800">{{ $secondName }}</h1>
                
                <div class="mt-10 inline-block glass rounded-2xl px-8 py-4 border border-amber-200 shadow-lg">
                    <p class="text-amber-600 text-lg">{{ $invitation->event_date?->translatedFormat('l') }}</p>
                    <p class="text-3xl font-rustic font-bold text-amber-800 my-1">{{ $invitation->event_date?->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </section>

        <!-- Couple Section -->
        <section class="py-16 px-6 bg-white leaf-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-amber-700 text-center mb-12 scroll-animate" data-animate>Mempelai</h2>
                
                <div class="text-center mb-12 scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center border-4 border-white shadow-xl">
                        <span class="text-5xl">🤵</span>
                    </div>
                    <h3 class="font-display text-3xl text-amber-800">{{ $content['groom_fullname'] ?? $content['groom_name'] ?? '' }}</h3>
                    @if($content['groom_parents'] ?? false)
                        <p class="text-amber-600/70 mt-2">Putra dari {{ $content['groom_parents'] }}</p>
                    @endif
                </div>
                
                <div class="text-center text-4xl text-amber-400 my-8">🌻</div>
                
                <div class="text-center scroll-animate" data-animate>
                    <div class="w-32 h-32 mx-auto mb-4 rounded-full bg-gradient-to-br from-amber-100 to-amber-200 flex items-center justify-center border-4 border-white shadow-xl">
                        <span class="text-5xl">👰</span>
                    </div>
                    <h3 class="font-display text-3xl text-amber-800">{{ $content['bride_fullname'] ?? $content['bride_name'] ?? '' }}</h3>
                    @if($content['bride_parents'] ?? false)
                        <p class="text-amber-600/70 mt-2">Putri dari {{ $content['bride_parents'] }}</p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Countdown -->
        @if($invitation->countdown_enabled)
        <section class="py-16 px-6 bg-amber-50 leaf-pattern">
            <div class="max-w-md mx-auto text-center">
                <h2 class="font-display text-4xl text-amber-700 mb-10 scroll-animate" data-animate>⏰ Hitung Mundur</h2>
                <div class="grid grid-cols-4 gap-3 scroll-animate" data-animate x-data="countdown('{{ $invitation->event_date?->toISOString() ?? now()->addDays(30)->toISOString() }}')">
                    @foreach(['Hari' => 'days', 'Jam' => 'hours', 'Menit' => 'minutes', 'Detik' => 'seconds'] as $label => $var)
                    <div class="glass rounded-2xl p-4 shadow-lg border border-amber-200">
                        <div class="text-3xl font-bold text-amber-700" x-text="{{ $var }}">00</div>
                        <div class="text-amber-500 text-xs mt-1">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Event Section -->
        <section class="py-16 px-6 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-amber-700 text-center mb-12 scroll-animate" data-animate>📅 Waktu & Tempat</h2>

                @if(in_array($content['event_type'] ?? 'both', ['akad_only', 'both']))
                <div class="glass rounded-3xl p-6 mb-6 shadow-lg border border-amber-100 scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-amber-100 rounded-full flex items-center justify-center text-2xl">💒</span>
                        <h3 class="font-rustic text-2xl font-bold text-amber-800">Akad Nikah</h3>
                    </div>
                    <div class="space-y-3 text-amber-700">
                        <p class="flex items-center gap-3"><span>📆</span>{{ \Carbon\Carbon::parse($content['akad_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                        <p class="flex items-center gap-3"><span>🕐</span>{{ $content['akad_time'] ?? '08:00' }} WIB</p>
                        <p class="flex items-start gap-3"><span>📍</span><span><strong>{{ $content['akad_venue'] ?? '' }}</strong><br><span class="text-amber-500 text-sm">{{ $content['akad_address'] ?? '' }}</span></span></p>
                    </div>
                </div>
                @endif

                @if(in_array($content['event_type'] ?? 'both', ['resepsi_only', 'both']))
                <div class="glass rounded-3xl p-6 shadow-lg border border-amber-100 scroll-animate" data-animate>
                    <div class="flex items-center gap-3 mb-4">
                        <span class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-2xl">🍽️</span>
                        <h3 class="font-rustic text-2xl font-bold text-amber-800">Resepsi</h3>
                    </div>
                    <div class="space-y-3 text-amber-700">
                        <p class="flex items-center gap-3"><span>📆</span>{{ \Carbon\Carbon::parse($content['resepsi_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                        <p class="flex items-center gap-3"><span>🕐</span>{{ $content['resepsi_time'] ?? '11:00' }} WIB</p>
                        <p class="flex items-start gap-3"><span>📍</span><span><strong>{{ $content['resepsi_venue'] ?? '' }}</strong><br><span class="text-amber-500 text-sm">{{ $content['resepsi_address'] ?? '' }}</span></span></p>
                    </div>
                </div>
                @endif

                @if($content['maps_url'] ?? false)
                <a href="{{ $content['maps_url'] }}" target="_blank" class="mt-6 w-full py-4 bg-gradient-to-r from-amber-600 to-amber-700 text-white font-semibold rounded-full shadow-lg flex items-center justify-center gap-2 active:scale-95 transition-transform scroll-animate" data-animate>
                    🗺️ Buka Google Maps
                </a>
                @endif
            </div>
        </section>

        <!-- Gallery -->
        @if($invitation->photos->count() > 0)
        <section class="py-16 px-6 bg-amber-50 leaf-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-amber-700 text-center mb-10 scroll-animate" data-animate>📸 Galeri</h2>
                <div class="grid grid-cols-2 gap-3 scroll-animate" data-animate>
                    @foreach($invitation->photos as $photo)
                    <div class="aspect-square rounded-2xl overflow-hidden shadow-lg {{ $loop->first ? 'col-span-2' : '' }}">
                        <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP -->
        @if($invitation->rsvp_enabled)
        <section class="py-16 px-6 bg-white leaf-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-amber-700 text-center mb-4 scroll-animate" data-animate>📝 Konfirmasi</h2>
                <p class="text-center text-amber-600/70 mb-8 scroll-animate" data-animate>Mohon konfirmasi kehadiran Anda</p>
                <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" theme="rustic" />
            </div>
        </section>
        @endif

        <!-- Wishes -->
        @if($invitation->wishes_enabled)
        <section class="py-16 px-6 bg-amber-50 leaf-pattern">
            <div class="max-w-md mx-auto">
                <h2 class="font-display text-4xl text-amber-700 text-center mb-4 scroll-animate" data-animate>💬 Ucapan</h2>
                <p class="text-center text-amber-600/70 mb-8 scroll-animate" data-animate>Kirimkan ucapan dan doa terbaik</p>
                <livewire:invitation.wishes :invitation="$invitation" theme="rustic" />
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="py-12 px-6 bg-gradient-to-b from-amber-100 to-amber-200 text-center">
            <p class="font-display text-3xl text-amber-700 mb-2">{{ $firstName }} & {{ $secondName }}</p>
            <p class="text-amber-500 text-sm">{{ $invitation->event_date?->translatedFormat('d.m.Y') }}</p>
            <p class="mt-6 text-amber-400 text-xs">Made with 🌻 by ExoInvite</p>
        </footer>
    </main>

    @if($invitation->music_enabled && $invitation->music_url)
    <div x-data="{ playing: false }" class="fixed bottom-6 right-6 z-40">
        <audio id="bgMusic" src="{{ $invitation->music_url }}" loop></audio>
        <button @click="playing = !playing; playing ? $el.previousElementSibling.play() : $el.previousElementSibling.pause()" class="w-14 h-14 glass rounded-full shadow-xl flex items-center justify-center text-2xl border border-amber-200 active:scale-95 transition-transform">
            <span x-show="!playing">🔇</span><span x-show="playing">🎵</span>
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
