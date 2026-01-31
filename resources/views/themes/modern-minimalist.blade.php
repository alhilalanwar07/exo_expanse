{{-- Modern Minimalist Theme - Mobile First Design --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="theme-color" content="#1e293b">
    <title>{{ $invitation->title }} | ExoInvite</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:300,400,500,600,700|playfair-display:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        * { -webkit-tap-highlight-color: transparent; }
        body { font-family: 'Inter', sans-serif; overflow-x: hidden; }
        .font-display { font-family: 'Playfair Display', serif; }
        
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes line-grow { from { width: 0; } to { width: 100%; } }
        
        .animate-fade-in-up { animation: fadeInUp 0.8s ease-out forwards; }
        .scroll-animate { opacity: 0; transform: translateY(20px); transition: all 0.6s cubic-bezier(0.4, 0, 0.2, 1); }
        .scroll-animate.visible { opacity: 1; transform: translateY(0); }
        
        .minimal-gradient { background: linear-gradient(180deg, #f8fafc 0%, #f1f5f9 50%, #e2e8f0 100%); }
        .glass { background: rgba(255,255,255,0.95); backdrop-filter: blur(10px); }
        
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-800 no-scrollbar">
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
        <div class="absolute inset-0 bg-slate-900"></div>
        <div class="absolute inset-0 backdrop-blur-sm bg-slate-900/80"></div>
        
        <!-- Cover Content -->
        <div class="relative z-20 h-full flex items-center justify-center">
            <div class="text-center px-8 max-w-sm mx-auto animate-fade-in-up">
                
                <!-- Subtitle -->
                <p class="text-slate-400 text-xs tracking-[0.5em] uppercase mb-8">{{ $invitation->cover_subtitle ?? 'Wedding Invitation' }}</p>
                
                <!-- Photo Frame OR Initials -->
                <div class="relative mx-auto w-44 h-52 mb-8">
                    <!-- Decorative Frame -->
                    <div class="absolute inset-0 border border-slate-600 rounded-t-full"></div>
                    <div class="absolute inset-1 border border-slate-700 rounded-t-full"></div>
                    
                    <!-- Photo or Initials -->
                    <div class="absolute inset-3 rounded-t-full overflow-hidden bg-slate-800 flex items-center justify-center">
                        @if($invitation->cover_photo)
                            <img src="{{ asset('storage/' . $invitation->cover_photo) }}" alt="Cover" class="w-full h-full object-cover">
                        @else
                            <!-- Initials Display -->
                            <div class="text-center">
                                <span class="font-display text-5xl text-slate-300">
                                    {{ mb_substr($firstName, 0, 1) }}
                                </span>
                                <span class="font-display text-3xl text-slate-500 mx-1">&</span>
                                <span class="font-display text-5xl text-slate-300">
                                    {{ mb_substr($secondName, 0, 1) }}
                                </span>
                            </div>
                        @endif
                    </div>
                </div>
                
                <div class="h-px bg-gradient-to-r from-transparent via-slate-500 to-transparent mb-6"></div>
                
                <!-- Couple Names -->
                <h1 class="font-display text-4xl text-white tracking-wide">{{ $firstName }}</h1>
                <p class="text-slate-500 text-2xl my-3">&</p>
                <h1 class="font-display text-4xl text-white tracking-wide">{{ $secondName }}</h1>
                
                <div class="h-px bg-gradient-to-r from-transparent via-slate-500 to-transparent mt-6 mb-6"></div>
                
                <!-- Kepada Section -->
                <div class="bg-slate-800/50 rounded-xl px-6 py-5 border border-slate-700">
                    <p class="text-slate-400 text-xs uppercase tracking-widest mb-1">Dear</p>
                    <p class="text-white text-xl font-medium mb-2">{{ $guest->name ?? 'Honored Guest' }}</p>
                    <p class="text-slate-400 text-sm leading-relaxed">
                        We cordially invite you to join us in celebrating our special day
                    </p>
                </div>
                
                <!-- Open Button -->
                <button @click="opened = true; document.getElementById('cover').classList.add('hidden'); document.body.style.overflow = 'auto';"
                    class="mt-6 w-full px-8 py-4 border border-white text-white text-sm tracking-widest uppercase hover:bg-white hover:text-slate-900 transition-all active:scale-95">
                    Open Invitation
                </button>
            </div>
        </div>
    </section>

    <!-- Main -->
    <main class="relative" style="display: none;" x-data x-init="$el.style.display = 'block'">
        
        <!-- Hero -->
        <section class="min-h-screen flex items-center justify-center minimal-gradient py-20">
            <div class="text-center px-8 scroll-animate" data-animate>
                <p class="text-slate-400 text-xs tracking-[0.5em] uppercase mb-10">We're Getting Married</p>
                
                <h1 class="font-display text-5xl text-slate-800 tracking-wide">{{ $firstName }}</h1>
                <p class="text-slate-400 text-3xl my-4">&</p>
                <h1 class="font-display text-5xl text-slate-800 tracking-wide">{{ $secondName }}</h1>
                
                <div class="mt-12 inline-block border border-slate-300 px-8 py-4">
                    <p class="text-slate-500 text-sm tracking-wide">{{ $invitation->event_date?->translatedFormat('l') }}</p>
                    <p class="font-display text-2xl text-slate-800 my-1">{{ $invitation->event_date?->translatedFormat('d F Y') }}</p>
                </div>
            </div>
        </section>

        <!-- Couple -->
        <section class="py-20 px-8 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="text-center text-xs tracking-[0.5em] text-slate-400 uppercase mb-16 scroll-animate" data-animate>The Couple</h2>
                
                <div class="text-center mb-16 scroll-animate" data-animate>
                    <div class="w-28 h-28 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center text-4xl">🤵</div>
                    <h3 class="font-display text-2xl text-slate-800">{{ $content['groom_fullname'] ?? $content['groom_name'] ?? '' }}</h3>
                    @if($content['groom_parents'] ?? false)
                        <p class="text-slate-500 text-sm mt-2">Son of {{ $content['groom_parents'] }}</p>
                    @endif
                </div>
                
                <div class="text-center text-3xl text-slate-300 my-8">&</div>
                
                <div class="text-center scroll-animate" data-animate>
                    <div class="w-28 h-28 mx-auto mb-6 rounded-full bg-slate-100 flex items-center justify-center text-4xl">👰</div>
                    <h3 class="font-display text-2xl text-slate-800">{{ $content['bride_fullname'] ?? $content['bride_name'] ?? '' }}</h3>
                    @if($content['bride_parents'] ?? false)
                        <p class="text-slate-500 text-sm mt-2">Daughter of {{ $content['bride_parents'] }}</p>
                    @endif
                </div>
            </div>
        </section>

        <!-- Countdown -->
        @if($invitation->countdown_enabled)
        <section class="py-20 px-8 bg-slate-900 text-white">
            <div class="max-w-md mx-auto text-center">
                <p class="text-xs tracking-[0.5em] text-slate-400 uppercase mb-10 scroll-animate" data-animate>Counting Down</p>
                <div class="grid grid-cols-4 gap-4 scroll-animate" data-animate x-data="countdown('{{ $invitation->event_date?->toISOString() ?? now()->addDays(30)->toISOString() }}')">
                    @foreach(['Days' => 'days', 'Hours' => 'hours', 'Mins' => 'minutes', 'Secs' => 'seconds'] as $label => $var)
                    <div class="border border-slate-700 p-4">
                        <div class="text-3xl font-light" x-text="{{ $var }}">00</div>
                        <div class="text-slate-500 text-xs mt-2 tracking-wider">{{ $label }}</div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- Events -->
        <section class="py-20 px-8 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="text-center text-xs tracking-[0.5em] text-slate-400 uppercase mb-16 scroll-animate" data-animate>Events</h2>

                @if(in_array($content['event_type'] ?? 'both', ['akad_only', 'both']))
                <div class="border border-slate-200 p-6 mb-6 scroll-animate" data-animate>
                    <p class="text-xs tracking-widest text-slate-400 uppercase mb-4">Holy Matrimony</p>
                    <p class="font-display text-xl text-slate-800">{{ \Carbon\Carbon::parse($content['akad_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                    <p class="text-slate-600 mt-2">{{ $content['akad_time'] ?? '08:00' }} WIB</p>
                    <p class="text-slate-500 text-sm mt-4">{{ $content['akad_venue'] ?? '' }}</p>
                    <p class="text-slate-400 text-xs">{{ $content['akad_address'] ?? '' }}</p>
                </div>
                @endif

                @if(in_array($content['event_type'] ?? 'both', ['resepsi_only', 'both']))
                <div class="border border-slate-200 p-6 scroll-animate" data-animate>
                    <p class="text-xs tracking-widest text-slate-400 uppercase mb-4">Reception</p>
                    <p class="font-display text-xl text-slate-800">{{ \Carbon\Carbon::parse($content['resepsi_date'] ?? $invitation->event_date)->translatedFormat('l, d F Y') }}</p>
                    <p class="text-slate-600 mt-2">{{ $content['resepsi_time'] ?? '11:00' }} WIB</p>
                    <p class="text-slate-500 text-sm mt-4">{{ $content['resepsi_venue'] ?? '' }}</p>
                    <p class="text-slate-400 text-xs">{{ $content['resepsi_address'] ?? '' }}</p>
                </div>
                @endif

                @if($content['maps_url'] ?? false)
                <a href="{{ $content['maps_url'] }}" target="_blank" class="mt-8 block text-center py-4 border border-slate-800 text-slate-800 text-sm tracking-widest uppercase hover:bg-slate-800 hover:text-white transition-all scroll-animate" data-animate>
                    View Location
                </a>
                @endif
            </div>
        </section>

        <!-- Gallery -->
        @if($invitation->photos->count() > 0)
        <section class="py-20 px-8 bg-slate-50">
            <div class="max-w-md mx-auto">
                <h2 class="text-center text-xs tracking-[0.5em] text-slate-400 uppercase mb-12 scroll-animate" data-animate>Gallery</h2>
                <div class="grid grid-cols-2 gap-2 scroll-animate" data-animate>
                    @foreach($invitation->photos as $photo)
                    <div class="aspect-square overflow-hidden {{ $loop->first ? 'col-span-2' : '' }}">
                        <img src="{{ asset('storage/' . $photo->path) }}" class="w-full h-full object-cover grayscale hover:grayscale-0 transition-all duration-500">
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        <!-- RSVP -->
        @if($invitation->rsvp_enabled)
        <section class="py-20 px-8 bg-white">
            <div class="max-w-md mx-auto">
                <h2 class="text-center text-xs tracking-[0.5em] text-slate-400 uppercase mb-4 scroll-animate" data-animate>RSVP</h2>
                <p class="text-center text-slate-500 text-sm mb-10 scroll-animate" data-animate>Kindly confirm your attendance</p>
                <livewire:invitation.rsvp-form :invitation="$invitation" :guest="$guest" theme="minimal" />
            </div>
        </section>
        @endif

        <!-- Wishes -->
        @if($invitation->wishes_enabled)
        <section class="py-20 px-8 bg-slate-50">
            <div class="max-w-md mx-auto">
                <h2 class="text-center text-xs tracking-[0.5em] text-slate-400 uppercase mb-4 scroll-animate" data-animate>Wishes</h2>
                <p class="text-center text-slate-500 text-sm mb-10 scroll-animate" data-animate>Send your blessings</p>
                <livewire:invitation.wishes :invitation="$invitation" theme="minimal" />
            </div>
        </section>
        @endif

        <!-- Footer -->
        <footer class="py-16 px-8 bg-slate-900 text-center">
            <p class="font-display text-2xl text-white tracking-wide">{{ $firstName }} & {{ $secondName }}</p>
            <p class="text-slate-500 text-sm mt-2">{{ $invitation->event_date?->translatedFormat('d.m.Y') }}</p>
            <div class="h-px bg-slate-700 max-w-xs mx-auto my-8"></div>
            <p class="text-slate-600 text-xs tracking-wide">ExoInvite</p>
        </footer>
    </main>

    @if($invitation->music_enabled && $invitation->music_url)
    <div x-data="{ playing: false }" class="fixed bottom-6 right-6 z-40">
        <audio id="bgMusic" src="{{ $invitation->music_url }}" loop></audio>
        <button @click="playing = !playing; playing ? $el.previousElementSibling.play() : $el.previousElementSibling.pause()" class="w-12 h-12 bg-slate-900 text-white rounded-full shadow-xl flex items-center justify-center text-lg active:scale-95 transition-transform">
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
