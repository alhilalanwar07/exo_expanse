@section('title', 'Wedding Invitation: ' . $invitation->groom_name . ' & ' . $invitation->bride_name)

@push('styles')
<style>
    /* Map CSS Variables to Tailwind Utility Classes */
    .font-heading { font-family: var(--font-heading, "Playfair Display"); }
    .font-body { font-family: var(--font-body, "Inter"); }
    .font-accent { font-family: var(--font-accent, "Cursive"); }

    /* Theme Colors Mapping */
    :root {
        --color-primary-50: color-mix(in srgb, var(--color-primary), white 90%);
        --color-primary-100: color-mix(in srgb, var(--color-primary), white 80%);
        --color-primary-900: color-mix(in srgb, var(--color-primary), black 40%);
    }
    
    .text-primary { color: var(--color-primary); }
    .bg-primary { background-color: var(--color-primary); }
    .border-primary { border-color: var(--color-primary); }

    .text-secondary { color: var(--color-secondary); }
    .bg-secondary { background-color: var(--color-secondary); }
    
    .text-accent { color: var(--color-accent); }
    .bg-accent { background-color: var(--color-accent); }


    [x-cloak] { display: none !important; }

    .parallax {
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

    .animate-float {
        animation: float 6s ease-in-out infinite;
    }

    @keyframes float {
        0%, 100% { transform: translateY(0); }
        50% { transform: translateY(-20px); }
    }
</style>
@endpush

<div x-data="{ 
    opened: false,
    open() {
        this.opened = true;
        const audio = document.querySelector('audio');
        if (audio) audio.play();
    }
}" x-cloak class="min-h-screen bg-slate-50">

    {{-- 1. OPENING COVER --}}
    <section 
        x-show="!opened" 
        x-transition:leave="transition ease-in-out duration-1000"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform -translate-y-full"
        class="fixed inset-0 z-[100] flex items-center justify-center bg-slate-900 overflow-hidden"
    >
        <div class="absolute inset-0 opacity-50 grayscale">
            <img src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://images.unsplash.com/photo-1519741497674-611481863552?q=80&w=2070&auto=format&fit=crop' }}" class="w-full h-full object-cover">
        </div>
        
        <div class="relative z-10 text-center text-white px-6 max-w-2xl">
            <h4 class="font-body tracking-[0.3em] uppercase text-sm mb-6 opacity-80">Wedding Invitation</h4>
            <h1 class="font-heading text-5xl md:text-7xl mb-12 leading-tight">
                {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
            </h1>

            <div class="bg-white/10 backdrop-blur-md p-8 rounded-2xl mb-10 border border-white/20">
                <p class="font-body text-xs tracking-widest uppercase mb-4 opacity-70">Dearly Invited</p>
                <h3 class="font-heading text-2xl font-bold">{{ $guestName }}</h3>
            </div>

            <button 
                @click="open()"
                class="inline-flex items-center gap-2 px-10 py-4 bg-white text-slate-900 rounded-full font-bold tracking-widest uppercase text-xs hover:bg-slate-100 transition-all transform active:scale-95 shadow-2xl"
            >
                🕊️ Buka Undangan
            </button>
        </div>
    </section>

    {{-- MAIN CONTENT --}}
    <main x-show="opened" class="relative">
        
        {{-- Hero Section --}}
        <section class="relative h-screen flex flex-col items-center justify-center text-center p-6 overflow-hidden">
            <div class="absolute inset-0 z-0">
                <img src="{{ $invitation->hero_image ? asset('storage/' . $invitation->hero_image) : 'https://images.unsplash.com/photo-1511285560929-80b456fea0bc?q=80&w=2069&auto=format&fit=crop' }}" class="w-full h-full object-cover opacity-20">
            </div>

            <div class="relative z-10 space-y-6">
                <span class="font-accent text-5xl md:text-7xl text-slate-800">The Wedding Of</span>
                <h2 class="font-heading text-6xl md:text-9xl text-slate-900">
                    {{ $invitation->groom_nickname }} <span class="text-3xl md:text-5xl block md:inline">&</span> {{ $invitation->bride_nickname }}
                </h2>
                <div class="font-body text-xl tracking-[0.5em] text-slate-500 font-light">
                    {{ $invitation->akad_date?->translatedFormat('d.m.Y') }}
                </div>
            </div>

            <div class="absolute bottom-10 left-1/2 -translate-x-1/2 animate-bounce opacity-30 text-3xl">↓</div>
        </section>

        {{-- Couple Section --}}
        <section class="py-24 bg-white">
            <div class="container mx-auto px-6 max-w-5xl">
                <div class="text-center mb-20">
                    <img src="https://indoinvite.com/nikah/template/elegan-nature/images/bismillah.png" class="h-12 mx-auto grayscale mb-6 opacity-40">
                    <p class="text-slate-500 font-body leading-relaxed max-w-2xl mx-auto italic">
                        "Maha Suci Allah yang telah menciptakan makhluk-Nya berpasang-pasangan. Ya Allah, perkenankanlah kami merangkai janji suci dalam sebuah pernikahan."
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-16 md:gap-32 items-center">
                    {{-- Groom --}}
                    <div class="text-center group">
                        <div class="relative w-64 h-80 mx-auto mb-8 rounded-[4rem] overflow-hidden rotate-2 group-hover:rotate-0 transition-transform duration-700 shadow-2xl">
                            <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1506794778202-cad84cf45f1d?q=80&w=1974' }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-heading text-4xl text-slate-800 mb-4">{{ $invitation->groom_name }}</h3>
                        <p class="font-body text-sm text-slate-500 mb-4 uppercase tracking-widest">Putra Dari</p>
                        <p class="font-body font-bold text-slate-700">
                            Bpk. {{ $invitation->groom_father }} <br> & <br> Ibu {{ $invitation->groom_mother }}
                        </p>
                    </div>

                    {{-- Bride --}}
                    <div class="text-center group">
                        <div class="relative w-64 h-80 mx-auto mb-8 rounded-[4rem] overflow-hidden -rotate-2 group-hover:rotate-0 transition-transform duration-700 shadow-2xl">
                            <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?q=80&w=1928' }}" class="w-full h-full object-cover">
                        </div>
                        <h3 class="font-heading text-4xl text-slate-800 mb-4">{{ $invitation->bride_name }}</h3>
                        <p class="font-body text-sm text-slate-500 mb-4 uppercase tracking-widest">Putri Dari</p>
                        <p class="font-body font-bold text-slate-700">
                            Bpk. {{ $invitation->bride_father }} <br> & <br> Ibu {{ $invitation->bride_mother }}
                        </p>
                    </div>
                </div>
            </div>
        </section>

        {{-- Event Section --}}
        <section class="py-32 bg-slate-900 text-white relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <img src="https://images.unsplash.com/photo-1519225421980-715cb0215aed?q=80&w=2070" class="w-full h-full object-cover">
            </div>

            <div class="container mx-auto px-6 max-w-5xl relative z-10">
                <div class="text-center mb-16">
                    <h2 class="font-heading text-5xl mb-4">Waktu & Tempat</h2>
                    <div class="w-24 h-1 bg-white/20 mx-auto"></div>
                </div>

                <div class="grid md:grid-cols-2 gap-10">
                    {{-- Akad --}}
                    <div class="bg-white/5 backdrop-blur-lg p-12 rounded-3xl border border-white/10 text-center">
                        <div class="text-4xl mb-6">💍</div>
                        <h3 class="font-heading text-2xl mb-2">Akad Nikah</h3>
                        <p class="font-body text-slate-400 mb-8 tracking-widest uppercase text-sm font-bold">
                            {{ $invitation->akad_date?->translatedFormat('l, d F Y') }}
                        </p>
                        <div class="space-y-4 mb-10">
                            <p class="font-body font-bold text-lg">{{ $invitation->akad_venue }}</p>
                            <p class="font-body text-slate-300 text-sm leading-relaxed">{{ $invitation->akad_address }}</p>
                        </div>
                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="inline-block px-8 py-3 bg-white text-slate-900 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition-colors">Lihat Lokasi</a>
                    </div>

                    {{-- Resepsi --}}
                    <div class="bg-white/5 backdrop-blur-lg p-12 rounded-3xl border border-white/10 text-center">
                        <div class="text-4xl mb-6">🥂</div>
                        <h3 class="font-heading text-2xl mb-2">Resepsi</h3>
                        <p class="font-body text-slate-400 mb-8 tracking-widest uppercase text-sm font-bold">
                            {{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}
                        </p>
                        <div class="space-y-4 mb-10">
                            <p class="font-body font-bold text-lg">{{ $invitation->resepsi_venue }}</p>
                            <p class="font-body text-slate-300 text-sm leading-relaxed">{{ $invitation->resepsi_address }}</p>
                        </div>
                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="inline-block px-8 py-3 bg-white text-slate-900 rounded-full font-bold text-xs uppercase tracking-widest hover:bg-slate-100 transition-colors">Lihat Lokasi</a>
                    </div>
                </div>
            </div>
        </section>

        {{-- Gallery Section --}}
        @if($invitation->enable_gallery && $invitation->gallery_images)
            <section class="py-24 bg-white">
                <div class="container mx-auto px-6">
                    <div class="text-center mb-16">
                        <h2 class="font-heading text-5xl text-slate-900 mb-4">Moment Bahagia</h2>
                        <div class="w-16 h-1 bg-slate-200 mx-auto"></div>
                    </div>

                    <div class="columns-1 md:columns-3 gap-6 space-y-6 max-w-6xl mx-auto">
                        @foreach($invitation->gallery_images as $image)
                            <div class="break-inside-avoid rounded-2xl overflow-hidden shadow-lg hover:scale-[1.02] transition-transform duration-500">
                                <img src="{{ asset('storage/' . $image) }}" class="w-full">
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- Love Story --}}
        @if($invitation->love_story)
            <section class="py-24 bg-slate-50">
                <div class="container mx-auto px-6 max-w-4xl">
                    <div class="text-center mb-16">
                        <h2 class="font-heading text-5xl text-slate-900 mb-4">Kisah Cinta</h2>
                        <div class="w-16 h-1 bg-slate-200 mx-auto"></div>
                    </div>

                    <div class="space-y-12 relative before:absolute before:inset-0 before:ml-5 before:-translate-x-px md:before:mx-auto md:before:translate-x-0 before:h-full before:w-0.5 before:bg-gradient-to-b before:from-transparent before:via-slate-300 before:to-transparent">
                        @foreach($invitation->love_story as $story)
                            <div class="relative flex items-center justify-between md:justify-normal md:odd:flex-row-reverse group is-active">
                                <div class="flex items-center justify-center w-10 h-10 rounded-full border border-white bg-slate-200 text-slate-900 shadow shrink-0 md:order-1 md:group-odd:-translate-x-1/2 md:group-even:translate-x-1/2">
                                    ❤️
                                </div>
                                <div class="w-[calc(100%-4rem)] md:w-[calc(50%-2.5rem)] p-6 rounded-2xl bg-white shadow-xl border border-slate-100">
                                    <div class="font-heading text-xl text-slate-800 mb-2">{{ $story['title'] ?? 'Our Story' }}</div>
                                    <div class="font-body text-xs font-bold text-slate-400 mb-3 uppercase tracking-tighter">{{ $story['date'] ?? '' }}</div>
                                    <p class="font-body text-slate-600 text-sm leading-relaxed">{{ $story['content'] ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- RSVP & Wishes - Using Reusable Component --}}
        <x-invitation.rsvp-wishes :invitation="$invitation" theme="default" />

        {{-- Digital Gift/Angpao --}}
        @if($invitation->enable_gifts && $invitation->gift_accounts)
            <section class="py-24 bg-slate-900 text-white">
                <div class="container mx-auto px-6 max-w-4xl">
                    <div class="text-center mb-16">
                        <h2 class="font-heading text-4xl mb-4">Kado Digital</h2>
                        <p class="font-body text-slate-400">Doa restu Anda merupakan karunia yang sangat berarti bagi kami. Namun jika memberi adalah ungkapan kasih, kami dengan senang hati menerimanya.</p>
                    </div>

                    <div class="grid md:grid-cols-2 gap-8">
                        @foreach($invitation->gift_accounts as $account)
                            <div x-data="{ copied: false }" class="bg-white/5 backdrop-blur-md p-8 rounded-3xl border border-white/10 text-center relative group">
                                <div class="h-10 mb-6 flex items-center justify-center">
                                    <img src="{{ $account['bank_logo'] ?? '' }}" 
                                         class="max-h-full opacity-80" 
                                         onerror="this.src='https://indoinvite.com/nikah/template/modern-lite/images/atm-card.png'">
                                </div>
                                <div class="font-body text-slate-400 text-xs mb-1 uppercase tracking-widest font-bold">{{ $account['bank_name'] ?? 'Bank' }}</div>
                                <div class="font-heading text-2xl mb-4 tracking-widest">{{ $account['account_number'] ?? '' }}</div>
                                <div class="font-body text-sm text-slate-300 mb-6 font-bold uppercase italic">A/N {{ $account['account_holder'] ?? '' }}</div>
                                
                                <button 
                                    @click="navigator.clipboard.writeText('{{ $account['account_number'] ?? '' }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                    class="w-full py-3 rounded-full border border-white/20 text-xs font-bold uppercase tracking-widest hover:bg-white hover:text-slate-900 transition-all active:scale-95"
                                >
                                    <span x-show="!copied">📋 Salin No. Rekening</span>
                                    <span x-show="copied">✅ Berhasil Disalin</span>
                                </button>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
        @endif

        {{-- Footer --}}
        <footer class="py-24 bg-white text-center border-t border-slate-100">
            <div class="container mx-auto px-6">
                <span class="font-accent text-5xl text-slate-800 mb-6 block">Thank You</span>
                <h4 class="font-heading text-3xl text-slate-900 mb-2">
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                </h4>
                <p class="font-body text-slate-500 text-xs tracking-[0.5em] uppercase mt-12 opacity-50">Exo Expanse © 2026</p>
            </div>
        </footer>

        {{-- Floating Music FAB --}}
        @if($invitation->music_enabled && $invitation->music_url)
            <div x-data="{ playing: true }" class="fixed bottom-8 right-8 z-50">
                <button 
                    @click="playing = !playing; $refs.audio.paused ? $refs.audio.play() : $refs.audio.pause()"
                    class="w-14 h-14 bg-white text-slate-900 rounded-full flex items-center justify-center shadow-2xl active:scale-90 transition-all"
                    :class="playing ? 'animate-spin-slow' : 'opacity-40'"
                >
                    <span x-show="playing">🎵</span>
                    <span x-show="!playing">🔇</span>
                </button>
                <audio x-ref="audio" loop autoplay src="{{ asset('storage/' . $invitation->music_url) }}"></audio>
            </div>
        @endif

    </main>
</div>