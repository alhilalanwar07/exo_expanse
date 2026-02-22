@section('title', 'Wedding of ' . $invitation->groom_nickname . ' & ' . $invitation->bride_nickname)

@push('head')
    <meta name="theme-color" content="#1a1a1a">
    <link href="https://fonts.googleapis.com" rel="preconnect"/>
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect"/>
    <link href="https://fonts.googleapis.com/css2?family=Great+Vibes&family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=Lato:wght@300;400;700&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,typography"></script>
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        primary: "#C5A86F",
                        "background-light": "#F9F7F2",
                        "background-dark": "#1a1a1a",
                        "text-dark": "#2C2C2C",
                        "text-light": "#E5E5E5",
                        "marble-black": "#2D2D2D",
                    },
                    fontFamily: {
                        script: ["'Great Vibes'", "cursive"],
                        serif: ["'Playfair Display'", "serif"],
                        sans: ["'Lato'", "sans-serif"],
                    },
                    backgroundImage: {
                        'texture-light': "url('https://www.transparenttextures.com/patterns/cream-paper.png')",
                        'texture-dark': "url('https://www.transparenttextures.com/patterns/black-linen.png')",
                    },
                    animation: {
                        'float': 'float 6s ease-in-out infinite',
                        'spin-slow': 'spin 12s linear infinite',
                    },
                    keyframes: {
                        float: {
                            '0%, 100%': { transform: 'translateY(0)' },
                            '50%': { transform: 'translateY(-10px)' },
                        }
                    }
                },
            },
        };
    </script>
    <style>
        .text-shadow-gold {
            text-shadow: 1px 1px 2px rgba(197, 168, 111, 0.4);
        }

        /* Ornament corner for decorative elements */
        .ornament-corner {
            position: absolute;
            width: 300px;
            height: auto;
            opacity: 0.8;
            z-index: 0;
            pointer-events: none;
        }

        /* Marble accent blending */
        .marble-accent {
            position: absolute;
            z-index: 0;
            opacity: 0.6;
            mix-blend-mode: multiply;
        }
        .dark .marble-accent {
            mix-blend-mode: soft-light;
            opacity: 0.3;
        }

        /* Scroll reveal */
        [data-reveal] {
            opacity: 0;
            transform: translateY(30px);
            transition: opacity 0.7s ease-out, transform 0.7s ease-out;
        }
        [data-reveal].revealed {
            opacity: 1;
            transform: translateY(0);
        }

        /* Scrollbar */
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
    </style>
@endpush

<div class="bg-background-light dark:bg-background-dark text-text-dark dark:text-text-light font-sans transition-colors duration-300 relative overflow-x-hidden min-h-screen"
     x-data="{
        opened: false,
        playing: false,
        audio: null,
        activeSection: 'home',
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

        init() {
            @if($invitation->background_music)
                this.audio = new Audio('{{ str_starts_with($invitation->background_music, 'http') ? $invitation->background_music : asset('storage/' . $invitation->background_music) }}');
                this.audio.loop = true;
            @endif
            this.loadWishes();
            this.loadStats();
            this.$watch('opened', (v) => {
                if (v) setTimeout(() => { this.setupScrollSpy(); this.initReveal(); }, 500);
            });
        },
        setupScrollSpy() {
            const ids = ['home','couple','events','gallery','gift','rsvp'];
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) this.activeSection = e.target.id; });
            }, { threshold: 0.2, rootMargin: '-10% 0px -40% 0px' });
            ids.forEach(id => { const el = document.getElementById(id); if (el) obs.observe(el); });
        },
        initReveal() {
            const obs = new IntersectionObserver((entries) => {
                entries.forEach(e => { if (e.isIntersecting) { e.target.classList.add('revealed'); obs.unobserve(e.target); } });
            }, { threshold: 0.1, rootMargin: '0px 0px -80px 0px' });
            document.querySelectorAll('[data-reveal]').forEach(el => obs.observe(el));
        },
        openInvitation() {
            this.opened = true;
            this.playMusic();
            setTimeout(() => document.getElementById('home')?.scrollIntoView({ behavior: 'smooth' }), 100);
        },
        playMusic() {
            if (this.audio) this.audio.play().then(() => this.playing = true).catch(() => this.playing = false);
        },
        toggleMusic() {
            if (!this.audio) return;
            if (this.playing) { this.audio.pause(); this.playing = false; }
            else { this.audio.play(); this.playing = true; }
        },
        scrollTo(id) {
            this.activeSection = id;
            document.getElementById(id)?.scrollIntoView({ behavior: 'smooth' });
        },
        async submitForm() {
            if (!this.name.trim() || !this.message.trim()) { this.error = 'Mohon lengkapi nama dan ucapan Anda.'; return; }
            this.loading = true; this.error = '';
            try {
                await fetch('/api/invitations/' + this.invitationId + '/rsvp', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]')?.content || '' },
                    body: JSON.stringify({ name: this.name, status: this.status, pax: this.pax })
                });
                const wishRes = await fetch('/api/invitations/' + this.invitationId + '/wishes', {
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
            try { const r = await fetch('/api/invitations/' + this.invitationId + '/wishes'); const d = await r.json(); this.wishes = d.wishes || []; } catch(e) {}
        },
        async loadStats() {
            try { const r = await fetch('/api/invitations/' + this.invitationId + '/stats'); this.stats = await r.json(); } catch(e) {}
        }
     }">

    {{-- Background Texture --}}
    <div class="fixed inset-0 pointer-events-none opacity-40 bg-texture-light dark:bg-texture-dark z-[-1]"></div>

    {{-- Top-right Ornament --}}
    <div class="absolute top-0 right-0 w-64 md:w-96 transform translate-x-20 -translate-y-10 z-0 pointer-events-none opacity-80">
        <img alt="Gold leaf" class="w-full h-full object-cover opacity-30 mix-blend-multiply dark:mix-blend-screen"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDJxRrTEgbE9KjiewP_UiDzkG13U-D880bB1ZGTyUCwI_46ks5Na-V_4lCnUZMX34WPoGfPpXgQXGc28PDzU80XZiLf8cLrcsEVrXzciguAS2pIelD1g45S55FjcSKF7Iwpz_iFeIbukhQHKx6l7RJAQBlbZli3_2QC0xElsPjQ4ydn328XD9nIQAYf5A9PBweHkahBTwJEKsAklm-IP468jvaAGJiOkqPJRSr4zdnIq2d5hIu1mebw-tqoLj1Uc2g6kw452qpTRbIE"
             style="mask-image: url('data:image/svg+xml;utf8,<svg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22><path fill=%22%23FF0066%22 d=%22M44.7,-76.4C58.9,-69.2,71.8,-59.1,81.6,-46.6C91.4,-34.1,98.2,-19.2,95.8,-5.3C93.5,8.6,82,21.5,70.9,32.4C59.8,43.3,49.1,52.2,37.3,58.8C25.5,65.4,12.7,69.7,-1.4,72.1C-15.5,74.5,-31,75,-44.3,69.5C-57.6,64,-68.7,52.5,-75.6,39.5C-82.5,26.5,-85.2,12,-82.7,-1.4C-80.2,-14.8,-72.5,-27.1,-63.4,-37.8C-54.3,-48.5,-43.8,-57.6,-32.1,-66.2C-20.4,-74.8,-7.5,-82.9,3.7,-89.3C14.9,-95.7,29.8,-100.4,44.7,-76.4Z%22 transform=%22translate(100 100)%22 /></svg>');"/>
    </div>

    {{-- Bottom-left Ornament --}}
    <div class="fixed bottom-0 left-0 w-64 h-64 pointer-events-none z-0">
        <img alt="Floral decor" class="w-full h-full object-cover opacity-20 dark:opacity-10"
             src="https://lh3.googleusercontent.com/aida-public/AB6AXuDHTln8O_4OVjyphgod5DfRaL9EV_SEhhJEWqPloDVBZAwfmYqLoUAKamDywOrnnj6v4okbPcocdrW5iBGHe9EwitHs6AOXbVzggXkMm_qDTAHESyx6AaaygxwqTuDI_H_ruPZkV-0uDf8OATxLb-cw1KtJJsGvIkO0C-YO0_D8wsIKJsI9Zw7PWfg43vXNTtPWJQYzHHwYrxwXgDPc-ZkMk97g35Tvi3ignL_vbviyDYw_FDo9yj12z1yxqeuEaGg6XNbJf8Hsj23t"
             style="mask-image: url('data:image/svg+xml;utf8,<svg viewBox=%220 0 200 200%22 xmlns=%22http://www.w3.org/2000/svg%22><path fill=%22%23FF0066%22 d=%22M47.7,-58.3C62.3,-48.9,74.9,-36.4,80.6,-21.8C86.3,-7.2,85.1,9.5,77.5,23.9C69.9,38.3,55.9,50.4,41.1,59.3C26.3,68.2,10.7,73.9,-3.3,77.8C-17.3,81.7,-29.7,83.8,-41.8,78.3C-53.9,72.8,-65.7,59.7,-72.5,45.2C-79.3,30.7,-81.1,14.8,-78.3,0.3C-75.5,-14.2,-68.1,-27.3,-58.3,-38.3C-48.5,-49.3,-36.3,-58.2,-23.3,-64.1C-10.3,-70,3.5,-72.9,17.2,-70.7C30.9,-68.5,44.5,-61.2,47.7,-58.3Z%22 transform=%22translate(100 100)%22 /></svg>');"/>
    </div>

    {{-- Dark Mode Toggle --}}
    <button class="fixed top-4 right-4 z-50 p-2 rounded-full bg-white/80 dark:bg-black/50 backdrop-blur-sm border border-primary/30 shadow-lg hover:shadow-xl transition-all"
            onclick="document.documentElement.classList.toggle('dark')">
        <svg class="w-5 h-5 text-primary" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z"/>
        </svg>
    </button>

    {{-- Music Button --}}
    <div x-show="opened" x-transition class="fixed bottom-24 right-4 z-50">
        <button @click="toggleMusic()"
                class="p-3 rounded-full bg-primary text-white shadow-lg hover:shadow-xl transition-all"
                :class="{ 'animate-spin-slow': playing, 'opacity-50': !playing }">
            <svg x-show="playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M18 3a1 1 0 00-1.196-.98l-10 2A1 1 0 006 5v9.114A4.369 4.369 0 005 14c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V7.82l8-1.6v5.894A4.37 4.37 0 0015 12c-1.657 0-3 .895-3 2s1.343 2 3 2 3-.895 3-2V3z"/>
            </svg>
            <svg x-show="!playing" class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M9.383 3.076A1 1 0 0110 4v12a1 1 0 01-1.707.707L4.586 13H2a1 1 0 01-1-1V8a1 1 0 011-1h2.586l3.707-3.707a1 1 0 011.09-.217zM12.293 7.293a1 1 0 011.414 0L15 8.586l1.293-1.293a1 1 0 111.414 1.414L16.414 10l1.293 1.293a1 1 0 01-1.414 1.414L15 11.414l-1.293 1.293a1 1 0 01-1.414-1.414L13.586 10l-1.293-1.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
    </div>

    {{-- ============================================================ --}}
    {{-- COVER SECTION — matches original HTML design exactly          --}}
    {{-- ============================================================ --}}
    <div x-show="!opened" x-transition.duration.1000ms
         class="fixed inset-0 z-40 flex flex-col items-center justify-center p-6 bg-background-light dark:bg-background-dark">

        <div class="max-w-md w-full bg-white/60 dark:bg-black/40 backdrop-blur-md rounded-[3rem] shadow-2xl border border-white/40 dark:border-gray-700 p-8 md:p-12 relative overflow-hidden animate-float">

            {{-- Background blob shape --}}
            <div class="absolute top-0 left-0 w-full h-full pointer-events-none opacity-10 dark:opacity-5">
                <svg class="w-full h-full text-black fill-current" viewBox="0 0 200 200" xmlns="http://www.w3.org/2000/svg">
                    <path d="M41.7,-68.6C54.4,-62.7,65.3,-53.4,74.3,-42.1C83.3,-30.8,90.4,-17.5,88.7,-4.8C87,7.9,76.5,20,66.1,30.3C55.6,40.6,45.2,49.1,33.9,56.9C22.6,64.7,10.4,71.8,-1.2,73.9C-12.8,76,-24.5,73.1,-35.3,66.7C-46.1,60.3,-56,50.4,-63.6,39.1C-71.2,27.8,-76.5,15.1,-75.7,2.8C-74.9,-9.5,-68,-21.4,-59.8,-32C-51.6,-42.6,-42.1,-51.9,-31.2,-58.8C-20.3,-65.7,-8,-70.2,4.8,-78.5L9.6,-86.8" transform="translate(100 100)"></path>
                </svg>
            </div>

            <div class="relative z-10 text-center flex flex-col items-center">
                {{-- Profile with spinning text --}}
                <div class="relative mb-8 group">
                    <div class="absolute inset-[-1.5rem] w-[calc(100%+3rem)] h-[calc(100%+3rem)] animate-spin-slow opacity-20 dark:opacity-30">
                        <svg height="100%" viewBox="0 0 100 100" width="100%">
                            <defs><path d="M 50, 50 m -37, 0 a 37,37 0 1,1 74,0 a 37,37 0 1,1 -74,0" id="circle"></path></defs>
                            <text class="tracking-widest uppercase" fill="currentColor" font-size="11" style="fill: #C5A86F;">
                                <textPath xlink:href="#circle">The Wedding of {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }} • </textPath>
                            </text>
                        </svg>
                    </div>
                    <div class="absolute inset-0 rounded-full border border-primary/30 transform scale-110"></div>
                    <div class="absolute inset-0 rounded-full border-2 border-primary/60 transform scale-105"></div>
                    <div class="w-48 h-48 md:w-56 md:h-56 rounded-full overflow-hidden border-4 border-white dark:border-gray-800 shadow-xl relative">
                        <img alt="Couple" class="w-full h-full object-cover transform transition-transform duration-700 group-hover:scale-110"
                             src="{{ $invitation->cover_image ? asset('storage/' . $invitation->cover_image) : 'https://lh3.googleusercontent.com/aida-public/AB6AXuBxXvCjnbjW1W6nD3HvPKMp-I4sDLOIR1BWTYBpMl4x4M5WwhTFZnmRMZ6hUss1h5rcxiZcYHql24tWYR7UdOxsQk6VHNONp2cVEm6aJW1pk3-tdqA7GQXtlyX7BJaAzG5ogXefCPG-a1Xih0nEoEhXoc_Qy9Spdr0xhWmB4JGH9iluLwepXdnuYgWqyqRjRB_ylB6L29BxBQwpWEmrWIE0fV1nvH2u5IuqoD_DHujwBdw5-DyOQEmh4OD7SobCDmjAqdHlIQxT6f6i' }}" />
                        <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
                    </div>
                </div>

                <h3 class="text-sm md:text-base uppercase tracking-[0.2em] text-gray-500 dark:text-gray-400 mb-2 font-serif">{{ $invitation->custom_styles['cover_subtitle'] ?? 'THE WEDDING OF' }}</h3>
                <h1 class="font-script text-5xl md:text-7xl text-primary mb-4 text-shadow-gold">
                    {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
                </h1>

                {{-- Quote (inside card, like original) --}}
                <p class="font-serif italic text-gray-600 dark:text-gray-300 text-sm md:text-base mb-8 max-w-xs mx-auto leading-relaxed">
                    "Two souls with but a single thought, two hearts that beat as one."
                </p>

                {{-- Countdown (inside card, like original) --}}
                <div x-data="{
                        targetDate: new Date('{{ $invitation->akad_date?->format('Y-m-d H:i:s') }}').getTime(),
                        days: 0, hours: 0, minutes: 0, seconds: 0,
                        update() {
                            const now = new Date().getTime();
                            const dist = this.targetDate - now;
                            if (dist > 0) {
                                this.days = Math.floor(dist / (1000*60*60*24));
                                this.hours = Math.floor((dist % (1000*60*60*24)) / (1000*60*60));
                                this.minutes = Math.floor((dist % (1000*60*60)) / (1000*60));
                                this.seconds = Math.floor((dist % (1000*60)) / 1000);
                            }
                        }
                    }"
                     x-init="setInterval(() => update(), 1000); update()"
                     class="grid grid-cols-4 gap-3 md:gap-4 w-full max-w-sm mb-10">
                    <div class="bg-white/80 dark:bg-gray-800/80 rounded-lg p-2 shadow-sm border border-primary/20 flex flex-col items-center">
                        <span class="font-serif text-xl md:text-2xl text-primary font-bold" x-text="days"></span>
                        <span class="text-[0.6rem] uppercase tracking-wider text-gray-500 dark:text-gray-400">Days</span>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 rounded-lg p-2 shadow-sm border border-primary/20 flex flex-col items-center">
                        <span class="font-serif text-xl md:text-2xl text-primary font-bold" x-text="hours"></span>
                        <span class="text-[0.6rem] uppercase tracking-wider text-gray-500 dark:text-gray-400">Hrs</span>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 rounded-lg p-2 shadow-sm border border-primary/20 flex flex-col items-center">
                        <span class="font-serif text-xl md:text-2xl text-primary font-bold" x-text="minutes"></span>
                        <span class="text-[0.6rem] uppercase tracking-wider text-gray-500 dark:text-gray-400">Mins</span>
                    </div>
                    <div class="bg-white/80 dark:bg-gray-800/80 rounded-lg p-2 shadow-sm border border-primary/20 flex flex-col items-center">
                        <span class="font-serif text-xl md:text-2xl text-primary font-bold" x-text="seconds"></span>
                        <span class="text-[0.6rem] uppercase tracking-wider text-gray-500 dark:text-gray-400">Secs</span>
                    </div>
                </div>

                {{-- Guest Name --}}
                @if($guestName)
                <div class="mb-6">
                    <p class="text-xs uppercase tracking-widest mb-1 text-gray-500">Dear,</p>
                    <h4 class="text-lg font-serif font-bold text-text-dark dark:text-text-light">{{ $guestName }}</h4>
                </div>
                @endif

                {{-- Open Button --}}
                <button @click="openInvitation()"
                        type="button"
                        class="group relative px-8 py-3 bg-primary text-white font-serif tracking-widest uppercase text-sm rounded-full shadow-lg hover:shadow-primary/40 transition-all duration-300 hover:-translate-y-1 overflow-hidden">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        Open Invitation
                    </span>
                    <div class="absolute inset-0 bg-white/20 transform -skew-x-12 -translate-x-full group-hover:translate-x-full transition-transform duration-700"></div>
                </button>
            </div>

            {{-- Bottom-left star ornament (from original) --}}
            <div class="absolute -bottom-10 -left-10 w-40 h-40 opacity-40 dark:opacity-20 pointer-events-none text-primary">
                <svg class="w-full h-full transform rotate-45" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12,2C12,2 14,8 18,8C22,8 22,12 22,12C22,12 16,14 16,18C16,22 12,22 12,22C12,22 8,18 8,18C8,14 2,12 2,12C2,12 8,8 8,8C10,8 12,2 12,2Z"></path>
                </svg>
            </div>
        </div>

        {{-- Scroll hint --}}
        <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 animate-bounce opacity-60">
            <svg class="w-8 h-8 text-gray-500 dark:text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </div>
    </div>

    {{-- ============================================================ --}}
    {{-- MAIN CONTENT — shown after opening                           --}}
    {{-- ============================================================ --}}
    <div x-show="opened"
         x-transition:enter="transition ease-out duration-1000"
         x-transition:enter-start="opacity-0 translate-y-12"
         x-transition:enter-end="opacity-100 translate-y-0"
         class="relative z-10 pb-24">

        {{-- HERO --}}
        <section id="home" class="min-h-[70vh] flex flex-col items-center justify-center p-8 text-center scroll-mt-0">
            <h2 class="font-script text-6xl md:text-7xl text-primary mb-3 text-shadow-gold">
                {{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}
            </h2>
            <p class="font-serif italic text-gray-600 dark:text-gray-300 text-sm md:text-base mb-4 max-w-sm mx-auto leading-relaxed">
                "Two souls with but a single thought, two hearts that beat as one."
            </p>
            <div class="w-20 h-[1px] bg-primary/40 mx-auto mb-6"></div>
            <p class="font-serif text-base text-gray-500 dark:text-gray-400">
                {{ $invitation->akad_date?->translatedFormat('d F Y') }}
            </p>
        </section>

        {{-- COUPLE --}}
        <section id="couple" class="py-20 px-6 relative max-w-4xl mx-auto scroll-mt-16">
            <div class="text-center mb-16" data-reveal>
                <h2 class="font-script text-5xl text-primary mb-2">The Couple</h2>
                <p class="font-serif text-gray-500 dark:text-gray-400">We request the honor of your presence</p>
            </div>

            <div class="grid md:grid-cols-2 gap-12 md:gap-8 items-center">
                {{-- Groom --}}
                <div class="text-center group" data-reveal>
                    <div class="w-48 h-48 mx-auto rounded-full border-4 border-primary/20 p-2 mb-6 relative hover:scale-105 transition-transform duration-500">
                        <div class="w-full h-full rounded-full overflow-hidden shadow-2xl">
                            <img src="{{ $invitation->groom_photo ? asset('storage/' . $invitation->groom_photo) : 'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?w=400' }}"
                                 class="w-full h-full object-cover" alt="Groom">
                        </div>
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-xs uppercase tracking-widest font-bold shadow-lg">Groom</div>
                    </div>
                    <h3 class="font-serif text-2xl font-bold mb-2 text-text-dark dark:text-text-light">{{ $invitation->groom_name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Putra dari Bpk. {{ $invitation->groom_father }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">& Ibu {{ $invitation->groom_mother }}</p>
                </div>

                {{-- Bride --}}
                <div class="text-center group" data-reveal>
                    <div class="w-48 h-48 mx-auto rounded-full border-4 border-primary/20 p-2 mb-6 relative hover:scale-105 transition-transform duration-500">
                        <div class="w-full h-full rounded-full overflow-hidden shadow-2xl">
                            <img src="{{ $invitation->bride_photo ? asset('storage/' . $invitation->bride_photo) : 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400' }}"
                                 class="w-full h-full object-cover" alt="Bride">
                        </div>
                        <div class="absolute -bottom-4 left-1/2 -translate-x-1/2 bg-primary text-white px-4 py-1 rounded-full text-xs uppercase tracking-widest font-bold shadow-lg">Bride</div>
                    </div>
                    <h3 class="font-serif text-2xl font-bold mb-2 text-text-dark dark:text-text-light">{{ $invitation->bride_name }}</h3>
                    <p class="text-sm text-gray-500 dark:text-gray-400 mb-1">Putri dari Bpk. {{ $invitation->bride_father }}</p>
                    <p class="text-sm text-gray-500 dark:text-gray-400">& Ibu {{ $invitation->bride_mother }}</p>
                </div>
            </div>
        </section>

        {{-- EVENTS --}}
        <section id="events" class="py-20 px-6 bg-white/30 dark:bg-white/5 scroll-mt-16">
            <div class="max-w-4xl mx-auto">
                <div class="text-center mb-16" data-reveal>
                    <h2 class="font-script text-5xl text-primary mb-2">Save The Date</h2>
                    <p class="font-serif text-gray-500 dark:text-gray-400">Our joyous moments</p>
                </div>

                <div class="grid md:grid-cols-2 gap-8">
                    {{-- Akad --}}
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-2xl p-8 border-t-4 border-primary shadow-lg hover:shadow-2xl transition-all duration-300 group" data-reveal>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-serif text-2xl font-bold text-text-dark dark:text-text-light">Akad Nikah</h3>
                            <svg class="w-7 h-7 text-primary group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="space-y-4 text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-primary/70 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold">{{ $invitation->akad_date?->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <svg class="w-4 h-4 text-primary/70 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"/>
                                </svg>
                                <span class="text-sm font-semibold">{{ $invitation->akad_date?->format('H:i') }} WIB</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <svg class="w-4 h-4 text-primary/70 shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <span class="text-sm font-bold block mb-1">{{ $invitation->akad_venue }}</span>
                                    <span class="text-xs opacity-80 leading-relaxed block">{{ $invitation->akad_address }}</span>
                                </div>
                            </div>
                        </div>
                        @if($invitation->akad_maps_link)
                        <a href="{{ $invitation->akad_maps_link }}" target="_blank" class="mt-6 inline-flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-widest hover:underline">
                            Open Map 
                            <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10.293 3.293a1 1 0 011.414 0l6 6a1 1 0 010 1.414l-6 6a1 1 0 01-1.414-1.414L14.586 11H3a1 1 0 110-2h11.586l-4.293-4.293a1 1 0 010-1.414z" clip-rule="evenodd"/>
                            </svg>
                        </a>
                        @endif
                    </div>

                    {{-- Resepsi --}}
                    @if($invitation->resepsi_date)
                    <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-2xl p-8 border-t-4 border-primary/50 shadow-lg hover:shadow-2xl transition-all duration-300 group" data-reveal>
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="font-serif text-2xl font-bold text-text-dark dark:text-text-light">Resepsi</h3>
                            <svg class="w-7 h-7 text-primary/70 group-hover:rotate-12 transition-transform" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a2 2 0 00-2 2v12a2 2 0 002 2h8a2 2 0 002-2V4a2 2 0 00-2-2H6zm1 2a1 1 0 000 2h6a1 1 0 100-2H7zm6 7a1 1 0 011 1v3a1 1 0 11-2 0v-3a1 1 0 011-1zm-3 3a1 1 0 100 2h.01a1 1 0 100-2H10zm-4 1a1 1 0 011-1h.01a1 1 0 110 2H7a1 1 0 01-1-1zm1-4a1 1 0 100 2h.01a1 1 0 100-2H7zm2 1a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zm4-4a1 1 0 100 2h.01a1 1 0 100-2H13zM9 9a1 1 0 011-1h.01a1 1 0 110 2H10a1 1 0 01-1-1zM7 8a1 1 0 000 2h.01a1 1 0 000-2H7z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="space-y-4 text-gray-600 dark:text-gray-300">
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-primary/70" style="font-size: 18px;">calendar_today</span>
                                <span class="text-sm font-semibold">{{ $invitation->resepsi_date?->translatedFormat('l, d F Y') }}</span>
                            </div>
                            <div class="flex items-center gap-3">
                                <span class="material-icons text-primary/70" style="font-size: 18px;">access_time</span>
                                <span class="text-sm font-semibold">{{ $invitation->resepsi_date?->format('H:i') }} WIB - Selesai</span>
                            </div>
                            <div class="flex items-start gap-3">
                                <span class="material-icons text-primary/70 mt-0.5" style="font-size: 18px;">location_on</span>
                                <div>
                                    <span class="text-sm font-bold block mb-1">{{ $invitation->resepsi_venue }}</span>
                                    <span class="text-xs opacity-80 leading-relaxed block">{{ $invitation->resepsi_address }}</span>
                                </div>
                            </div>
                        </div>
                        @if($invitation->resepsi_maps_link)
                        <a href="{{ $invitation->resepsi_maps_link }}" target="_blank" class="mt-6 inline-flex items-center gap-2 text-primary font-bold text-xs uppercase tracking-widest hover:underline">
                            Open Map <span class="material-icons" style="font-size: 14px;">arrow_forward</span>
                        </a>
                        @endif
                    </div>
                    @endif
                </div>
            </div>
        </section>

        {{-- GALLERY --}}
        @if($invitation->gallery && count($invitation->gallery) > 0)
        <section id="gallery" class="py-20 px-6 scroll-mt-16">
            <div class="max-w-5xl mx-auto">
                <div class="text-center mb-16" data-reveal>
                    <h2 class="font-script text-5xl text-primary mb-2">Our Gallery</h2>
                    <p class="font-serif text-gray-500 dark:text-gray-400">Capturing love</p>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 auto-rows-[200px]">
                    @foreach($invitation->gallery as $index => $photo)
                    <div class="relative overflow-hidden rounded-xl cursor-pointer group {{ $index % 3 == 0 ? 'md:row-span-2' : '' }}" data-reveal>
                        <img src="{{ asset('storage/' . $photo) }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" alt="Gallery">
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                            <svg class="w-7 h-7 text-white" fill="currentColor" viewBox="0 0 20 20">
                                <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                                <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </section>
        @endif

        {{-- LOVE STORY --}}
        @if($invitation->love_story && count($invitation->love_story) > 0)
        <section id="lovestory" class="py-20 px-6 scroll-mt-16">
            <div class="max-w-2xl mx-auto">
                <div class="text-center mb-16" data-reveal>
                    <h2 class="font-script text-5xl text-primary mb-2">Our Journey</h2>
                    <p class="font-serif text-gray-500 dark:text-gray-400">Kisah Cinta Kami</p>
                </div>

                <div class="relative" style="padding-left: 32px;">
                    <div class="absolute left-[15px] top-0 bottom-0 w-[2px] bg-gradient-to-b from-transparent via-primary/40 to-transparent"></div>

                    <div class="space-y-8">
                        @foreach($invitation->love_story as $index => $story)
                        <div class="relative" data-reveal>
                            <div class="absolute -left-[25px] top-6 w-4 h-4 rounded-full bg-white dark:bg-gray-800 border-[3px] border-primary shadow-[0_0_0_4px_rgba(197,168,111,0.15)] z-10"></div>
                            <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md p-6 rounded-2xl border border-primary/20 shadow-lg hover:shadow-2xl transition-all duration-300 relative overflow-hidden group">
                                <div class="inline-block px-3 py-1 rounded-full bg-primary/10 text-primary text-[11px] font-bold mb-3 tracking-widest border border-primary/20">{{ $story['date'] ?? '' }}</div>
                                <h3 class="font-serif text-xl font-bold text-text-dark dark:text-text-light mb-2 group-hover:text-primary transition-colors">{{ $story['title'] ?? '' }}</h3>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed">{{ $story['description'] ?? '' }}</p>
                                <div class="absolute -bottom-2 -right-2 font-script text-5xl text-primary/5 group-hover:text-primary/10 transition-colors rotate-[-12deg] pointer-events-none">❦</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- GIFT --}}
        @if($invitation->enable_gift)
        <section id="gift" class="py-20 px-6 bg-white/30 dark:bg-white/5 scroll-mt-16">
            <div class="max-w-2xl mx-auto text-center">
                <div class="mb-16" data-reveal>
                    <h2 class="font-script text-5xl text-primary mb-2">Wedding Gift</h2>
                    <p class="font-serif text-gray-500 dark:text-gray-400">Your blessing is our greatest gift</p>
                </div>

                <p class="font-serif italic text-gray-600 dark:text-gray-300 mb-10 max-w-lg mx-auto" data-reveal>
                    "Your presence at our wedding is the greatest gift of all. However, if you wish to honor us with a gift, a cashless contribution would be appreciated."
                </p>

                <div class="grid gap-6">
                    @if($invitation->bank_accounts)
                        @foreach($invitation->bank_accounts as $account)
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-xl p-6 border border-primary/20 shadow-lg relative overflow-hidden" x-data="{ copied: false }" data-reveal>
                            <div class="absolute top-0 left-0 w-1 h-full bg-primary/50"></div>
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="text-left">
                                    <p class="font-bold text-primary uppercase tracking-widest text-xs mb-1">{{ $account['bank'] }}</p>
                                    <p class="font-serif text-2xl text-text-dark dark:text-text-light font-bold mb-1">{{ $account['account_number'] }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">a.n {{ $account['account_name'] }}</p>
                                </div>
                                <button @click="navigator.clipboard.writeText('{{ $account['account_number'] }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-6 py-2 rounded-full border border-primary/30 text-primary text-sm font-bold uppercase tracking-wider hover:bg-primary hover:text-white transition-all flex items-center gap-2">
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="!copied">
                                        <path d="M8 3a1 1 0 011-1h2a1 1 0 110 2H9a1 1 0 01-1-1z"/>
                                        <path d="M6 3a2 2 0 00-2 2v11a2 2 0 002 2h8a2 2 0 002-2V5a2 2 0 00-2-2 3 3 0 01-3 3H9a3 3 0 01-3-3z"/>
                                    </svg>
                                    <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" x-show="copied">
                                        <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                    </svg>
                                    <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    @elseif($invitation->bank_name)
                        <div class="bg-white/60 dark:bg-gray-800/60 backdrop-blur-md rounded-xl p-6 border border-primary/20 shadow-lg relative overflow-hidden" x-data="{ copied: false }" data-reveal>
                            <div class="absolute top-0 left-0 w-1 h-full bg-primary/50"></div>
                            <div class="flex items-center justify-between flex-wrap gap-4">
                                <div class="text-left">
                                    <p class="font-bold text-primary uppercase tracking-widest text-xs mb-1">{{ $invitation->bank_name }}</p>
                                    <p class="font-serif text-2xl text-text-dark dark:text-text-light font-bold mb-1">{{ $invitation->bank_account }}</p>
                                    <p class="text-sm text-gray-500 dark:text-gray-400">a.n {{ $invitation->bank_holder }}</p>
                                </div>
                                <button @click="navigator.clipboard.writeText('{{ $invitation->bank_account }}'); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="px-6 py-2 rounded-full border border-primary/30 text-primary text-sm font-bold uppercase tracking-wider hover:bg-primary hover:text-white transition-all flex items-center gap-2">
                                    <span class="material-icons" style="font-size: 16px;" x-text="copied ? 'check' : 'content_copy'"></span>
                                    <span x-text="copied ? 'Copied' : 'Copy'"></span>
                                </button>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </section>
        @endif

        {{-- RSVP & WISHES --}}
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <section id="rsvp" class="py-20 px-6 scroll-mt-16">
            <div class="max-w-2xl mx-auto bg-white/80 dark:bg-gray-800/80 backdrop-blur-md rounded-2xl p-8 md:p-12 shadow-xl border border-primary/20" data-reveal>
                <div class="text-center mb-10">
                    <h2 class="font-script text-4xl md:text-5xl text-primary mb-2">RSVP</h2>
                    <p class="font-serif text-sm text-gray-500 dark:text-gray-400">Please confirm your attendance</p>
                </div>

                {{-- Stats --}}
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-primary/10 rounded-xl p-4 text-center border border-primary/20">
                        <span class="block text-2xl font-bold text-primary" x-text="stats.total_wishes">0</span>
                        <span class="text-xs uppercase tracking-widest text-gray-500">Wishes</span>
                    </div>
                    <div class="bg-primary/10 rounded-xl p-4 text-center border border-primary/20">
                        <span class="block text-2xl font-bold text-primary" x-text="stats.total_confirmed">0</span>
                        <span class="text-xs uppercase tracking-widest text-gray-500">Attending</span>
                    </div>
                </div>

                {{-- Success --}}
                <div x-show="success" x-transition class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-green-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-bold text-green-700 dark:text-green-400 text-sm">Thank You!</p>
                        <p class="text-xs text-green-600 dark:text-green-500">Your wish and confirmation has been sent.</p>
                    </div>
                </div>

                {{-- Error --}}
                <div x-show="error" x-transition class="mb-6 bg-red-50 dark:bg-red-900/20 border border-red-200 dark:border-red-800 rounded-lg p-4 flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="font-bold text-red-700 dark:text-red-400 text-sm">Error</p>
                        <p class="text-xs text-red-600 dark:text-red-500" x-text="error"></p>
                    </div>
                </div>

                <form @submit.prevent="submitForm" class="space-y-6">
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">Name</label>
                        <input type="text" x-model="name" class="w-full bg-transparent border-2 border-primary/20 rounded-lg px-4 py-3 focus:border-primary focus:ring-0 transition-colors text-text-dark dark:text-text-light placeholder-gray-400" placeholder="Your Name">
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">Message</label>
                        <textarea x-model="message" rows="3" class="w-full bg-transparent border-2 border-primary/20 rounded-lg px-4 py-3 focus:border-primary focus:ring-0 transition-colors text-text-dark dark:text-text-light placeholder-gray-400" placeholder="Write your wishes..."></textarea>
                    </div>
                    <div>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">Attendance</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" @click="status = 'confirmed'"
                                    :class="status === 'confirmed' ? 'bg-primary text-white border-primary' : 'bg-transparent text-gray-500 border-primary/20 hover:border-primary'"
                                    class="py-3 rounded-lg border-2 font-bold text-sm transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                                Attend
                            </button>
                            <button type="button" @click="status = 'declined'"
                                    :class="status === 'declined' ? 'bg-gray-600 text-white border-gray-600' : 'bg-transparent text-gray-500 border-primary/20 hover:border-gray-600'"
                                    class="py-3 rounded-lg border-2 font-bold text-sm transition-all flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
                                </svg>
                                Sorry
                            </button>
                        </div>
                    </div>
                    <div x-show="status === 'confirmed'" x-transition>
                        <label class="block text-xs font-bold uppercase tracking-widest text-gray-500 dark:text-gray-400 mb-2">Number of Guests</label>
                        <select x-model="pax" class="w-full bg-transparent border-2 border-primary/20 rounded-lg px-4 py-3 focus:border-primary focus:ring-0 transition-colors text-text-dark dark:text-text-light">
                            @foreach(range(1, 5) as $i)
                                <option value="{{ $i }}">{{ $i }} Person</option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" :disabled="loading" class="w-full py-4 bg-primary text-white font-bold uppercase tracking-widest rounded-lg shadow-lg hover:shadow-primary/50 transition-all transform hover:-translate-y-1 disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center gap-2">
                        <svg x-show="loading" class="w-4 h-4 animate-spin" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M4 2a1 1 0 011 1v2.101a7.002 7.002 0 0111.601 2.566 1 1 0 11-1.885.666A5.002 5.002 0 005.999 7H9a1 1 0 010 2H4a1 1 0 01-1-1V3a1 1 0 011-1zm.008 9.057a1 1 0 011.276.61A5.002 5.002 0 0014.001 13H11a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0v-2.101a7.002 7.002 0 01-11.601-2.566 1 1 0 01.61-1.276z" clip-rule="evenodd"/>
                        </svg>
                        <span x-text="loading ? 'Sending...' : 'Send Confirmation'"></span>
                    </button>
                </form>

                {{-- Wishes List --}}
                <div class="mt-12 space-y-4">
                    <h3 class="text-center font-serif text-xl border-b border-primary/20 pb-4 mb-6 text-gray-600 dark:text-gray-300">Newest Wishes</h3>

                    <template x-for="wish in wishes" :key="wish.id">
                        <div class="bg-white/50 dark:bg-black/20 rounded-xl p-4 border border-primary/10 flex gap-4">
                            <div class="w-10 h-10 rounded-full bg-primary text-white flex items-center justify-center font-bold font-serif shrink-0 text-lg" x-text="wish.name.charAt(0)"></div>
                            <div class="flex-1 min-w-0">
                                <div class="flex justify-between items-start mb-1">
                                    <h4 class="font-bold text-sm text-text-dark dark:text-text-light truncate" x-text="wish.name"></h4>
                                    <span class="text-[10px] text-gray-400 shrink-0 ml-2" x-text="wish.created_at_human"></span>
                                </div>
                                <p class="text-sm text-gray-600 dark:text-gray-400 leading-relaxed mb-2" x-text="wish.message"></p>
                                <template x-if="wish.status === 'confirmed'">
                                    <span class="inline-flex items-center gap-1 text-[10px] font-bold text-green-600 bg-green-100 dark:bg-green-900/30 px-2 py-0.5 rounded-full">
                                        <svg class="w-2.5 h-2.5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                        Attending
                                    </span>
                                </template>
                            </div>
                        </div>
                    </template>

                    <div x-show="wishes.length === 0" class="text-center py-8 text-gray-500">
                        <svg class="w-9 h-9 mx-auto opacity-20 mb-2" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
                        </svg>
                        <p class="text-sm">Be the first to send a wish!</p>
                    </div>
                </div>
            </div>
        </section>
        @endif

        {{-- FOOTER --}}
        <footer class="py-12 text-center mb-16">
            <h2 class="font-script text-4xl text-primary mb-4">{{ $invitation->groom_nickname }} & {{ $invitation->bride_nickname }}</h2>
            <div class="w-20 h-[1px] bg-primary/30 mx-auto mb-4"></div>
            <p class="text-gray-500 dark:text-gray-400 text-xs uppercase tracking-widest">Thank you for your love and support</p>
        </footer>
    </div>

    {{-- BOTTOM NAV --}}
    <nav x-show="opened" x-transition class="fixed bottom-4 left-1/2 transform -translate-x-1/2 z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md rounded-full shadow-2xl border border-primary/20 px-5 py-2.5 flex items-center gap-5 md:gap-7">
        <button @click="scrollTo('home')" :class="activeSection === 'home' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"/>
            </svg>
        </button>
        <button @click="scrollTo('couple')" :class="activeSection === 'couple' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M9 6a3 3 0 11-6 0 3 3 0 016 0zM17 6a3 3 0 11-6 0 3 3 0 016 0zM12.93 17c.046-.327.07-.66.07-1a6.97 6.97 0 00-1.5-4.33A5 5 0 0119 16v1h-6.07zM6 11a5 5 0 015 5v1H1v-1a5 5 0 015-5z"/>
            </svg>
        </button>
        <button @click="scrollTo('events')" :class="activeSection === 'events' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd"/>
            </svg>
        </button>
        @if($invitation->gallery && count($invitation->gallery) > 0)
        <button @click="scrollTo('gallery')" :class="activeSection === 'gallery' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4 3a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V5a2 2 0 00-2-2H4zm12 12H4l4-8 3 6 2-4 3 6z" clip-rule="evenodd"/>
            </svg>
        </button>
        @endif
        @if($invitation->enable_gift)
        <button @click="scrollTo('gift')" :class="activeSection === 'gift' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M5 5a3 3 0 015-2.236A3 3 0 0114.83 6H16a2 2 0 110 4h-5V9a1 1 0 10-2 0v1H4a2 2 0 110-4h1.17C5.06 5.687 5 5.35 5 5zm4 1V5a1 1 0 10-1 1h1zm3 0a1 1 0 10-1-1v1h1z" clip-rule="evenodd"/>
                <path d="M9 11H3v5a2 2 0 002 2h4v-7zM11 18h4a2 2 0 002-2v-5h-6v7z"/>
            </svg>
        </button>
        @endif
        @if($invitation->enable_rsvp || $invitation->enable_wishes)
        <button @click="scrollTo('rsvp')" :class="activeSection === 'rsvp' ? 'text-primary' : 'text-gray-400 hover:text-primary'" class="transition-colors p-1">
            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd"/>
            </svg>
        </button>
        @endif
    </nav>
</div>
