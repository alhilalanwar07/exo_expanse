<div x-data="{ scrolled: false, mobileMenu: false }" x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 50)">

    <!-- Sticky Navbar -->
    <nav class="fixed top-0 inset-x-0 z-50 transition-all duration-300"
         :class="scrolled ? 'navbar-glass py-3 shadow-2xl' : 'py-5'">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between">
            <x-logo variant="full" :white="true" />
            <!-- Desktop Nav -->
            <div class="hidden md:flex items-center gap-8">
                <a href="#features" class="text-sm text-white/70 hover:text-white transition-colors">Fitur</a>
                <a href="#themes" class="text-sm text-white/70 hover:text-white transition-colors">Tema</a>
                <a href="#how-it-works" class="text-sm text-white/70 hover:text-white transition-colors">Cara Kerja</a>
                <a href="#testimonials" class="text-sm text-white/70 hover:text-white transition-colors">Testimoni</a>
                <a href="{{ route('login') }}" class="text-sm text-white/80 hover:text-white transition-colors font-medium">Masuk</a>
                <a href="{{ route('register') }}" class="px-5 py-2.5 text-sm font-semibold text-white bg-gradient-to-r from-rose-500 to-amber-500 rounded-lg hover:opacity-90 transition-all hover:scale-105 shadow-lg shadow-rose-500/20">
                    Daftar Gratis
                </a>
            </div>
            <!-- Mobile Hamburger -->
            <button @click="mobileMenu = !mobileMenu" class="md:hidden text-white/80 hover:text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path x-show="!mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    <path x-show="mobileMenu" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
        <!-- Mobile Menu -->
        <div x-show="mobileMenu" x-transition class="md:hidden navbar-glass mt-2 mx-4 rounded-xl p-4 space-y-3">
            <a href="#features" class="block text-white/70 hover:text-white py-2 text-sm">Fitur</a>
            <a href="#themes" class="block text-white/70 hover:text-white py-2 text-sm">Tema</a>
            <a href="#how-it-works" class="block text-white/70 hover:text-white py-2 text-sm">Cara Kerja</a>
            <a href="#testimonials" class="block text-white/70 hover:text-white py-2 text-sm">Testimoni</a>
            <div class="pt-2 border-t border-white/10 flex gap-3">
                <a href="{{ route('login') }}" class="flex-1 text-center py-2.5 text-sm text-white/80 border border-white/20 rounded-lg">Masuk</a>
                <a href="{{ route('register') }}" class="flex-1 text-center py-2.5 text-sm text-white bg-gradient-to-r from-rose-500 to-amber-500 rounded-lg font-semibold">Daftar</a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-950 via-purple-950 to-slate-950">
        <!-- Animated Background Orbs -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-[500px] h-[500px] bg-rose-500/20 rounded-full blur-[100px] animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-[500px] h-[500px] bg-amber-500/20 rounded-full blur-[100px] animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/3 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-purple-500/15 rounded-full blur-[120px] animate-float" style="animation-delay: 4s;"></div>
            <div class="absolute top-20 left-20 w-40 h-40 bg-fuchsia-500/10 rounded-full blur-[80px] animate-float" style="animation-delay: 1s;"></div>
            <div class="absolute bottom-40 right-20 w-32 h-32 bg-cyan-500/10 rounded-full blur-[60px] animate-float" style="animation-delay: 3s;"></div>
        </div>

        <!-- Grid Pattern -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.04%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50"></div>

        <!-- Floating Particles -->
        <div class="absolute inset-0 overflow-hidden pointer-events-none">
            <div class="absolute top-[15%] left-[10%] w-1.5 h-1.5 bg-rose-400/60 rounded-full animate-float" style="animation-delay: 0.5s; animation-duration: 8s;"></div>
            <div class="absolute top-[25%] right-[15%] w-1 h-1 bg-amber-400/50 rounded-full animate-float" style="animation-delay: 1.5s; animation-duration: 7s;"></div>
            <div class="absolute top-[60%] left-[20%] w-2 h-2 bg-purple-400/40 rounded-full animate-float" style="animation-delay: 2.5s; animation-duration: 9s;"></div>
            <div class="absolute top-[70%] right-[25%] w-1 h-1 bg-pink-400/50 rounded-full animate-float" style="animation-delay: 3.5s; animation-duration: 6s;"></div>
            <div class="absolute top-[40%] left-[75%] w-1.5 h-1.5 bg-cyan-400/40 rounded-full animate-float" style="animation-delay: 0s; animation-duration: 10s;"></div>
        </div>

        <!-- Content -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center pt-24">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2.5 px-5 py-2.5 rounded-full bg-white/[0.07] backdrop-blur-md border border-white/[0.12] text-white/80 text-sm mb-10 animate-fade-in-up">
                <span class="relative flex h-2.5 w-2.5">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-green-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-green-400"></span>
                </span>
                Platform Undangan Digital #1 Indonesia
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl sm:text-6xl lg:text-8xl font-bold text-white mb-8 leading-[1.1] tracking-tight animate-fade-in-up" style="animation-delay: 0.15s;">
                Buat Undangan Digital
                <span class="block text-gradient animate-gradient mt-2">Premium & Elegan</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-lg sm:text-xl text-white/60 max-w-2xl mx-auto mb-12 leading-relaxed animate-fade-in-up" style="animation-delay: 0.3s;">
                Desain undangan pernikahan, ulang tahun, dan acara spesial lainnya. Stunning, ramah lingkungan, dan siap dibagikan dalam hitungan menit.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-20 animate-fade-in-up" style="animation-delay: 0.45s;">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-rose-500 to-amber-500 rounded-2xl hover:opacity-90 transition-all transform hover:scale-105 shadow-2xl shadow-rose-500/30 hover:shadow-rose-500/40">
                    Buat Undangan Gratis
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-white/[0.07] backdrop-blur-md border border-white/[0.15] rounded-2xl hover:bg-white/[0.12] transition-all">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Lihat Fitur
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-6 max-w-3xl mx-auto animate-fade-in-up" style="animation-delay: 0.6s;"
                 x-data="{ shown: false, counts: { invitations: 0, themes: 0, satisfaction: 0, cities: 0 } }"
                 x-intersect.once="shown = true; let duration = 2000; let steps = 60;
                    let targets = { invitations: 10000, themes: 50, satisfaction: 99, cities: 34 };
                    Object.keys(targets).forEach(key => {
                        let increment = targets[key] / steps;
                        let current = 0;
                        let interval = setInterval(() => {
                            current += increment;
                            if (current >= targets[key]) { counts[key] = targets[key]; clearInterval(interval); }
                            else { counts[key] = Math.floor(current); }
                        }, duration / steps);
                    });">
                <div class="text-center p-4 rounded-2xl bg-white/[0.05] backdrop-blur-sm border border-white/[0.08]">
                    <div class="text-3xl sm:text-4xl font-bold text-white mb-1" x-text="counts.invitations.toLocaleString() + '+'">10,000+</div>
                    <div class="text-white/50 text-sm">Undangan Dibuat</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/[0.05] backdrop-blur-sm border border-white/[0.08]">
                    <div class="text-3xl sm:text-4xl font-bold text-white mb-1" x-text="counts.themes + '+'">50+</div>
                    <div class="text-white/50 text-sm">Tema Premium</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/[0.05] backdrop-blur-sm border border-white/[0.08]">
                    <div class="text-3xl sm:text-4xl font-bold text-white mb-1" x-text="counts.satisfaction + '%'">99%</div>
                    <div class="text-white/50 text-sm">Kepuasan</div>
                </div>
                <div class="text-center p-4 rounded-2xl bg-white/[0.05] backdrop-blur-sm border border-white/[0.08]">
                    <div class="text-3xl sm:text-4xl font-bold text-white mb-1" x-text="counts.cities + ' Kota'">34 Kota</div>
                    <div class="text-white/50 text-sm">Se-Indonesia</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <div class="w-6 h-10 border-2 border-white/30 rounded-full flex items-start justify-center p-1.5">
                <div class="w-1.5 h-3 bg-white/50 rounded-full animate-pulse"></div>
            </div>
        </div>
    </section>

    <!-- Trusted By Marquee -->
    <section class="py-6 bg-slate-900 border-b border-white/5 overflow-hidden">
        <div class="flex animate-marquee whitespace-nowrap">
            @foreach(range(0, 1) as $i)
            <div class="flex items-center gap-12 mx-12 text-white/30 text-sm font-medium">
                <span class="flex items-center gap-2"><span class="text-lg">💒</span> Wedding Organizer</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">🎂</span> Birthday Parties</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">🎓</span> Graduation Events</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">🏢</span> Corporate Events</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">👶</span> Baby Shower</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">💑</span> Engagement</span>
                <span class="text-white/10">•</span>
                <span class="flex items-center gap-2"><span class="text-lg">🕌</span> Aqiqah</span>
                <span class="text-white/10">•</span>
            </div>
            @endforeach
        </div>
    </section>

    <!-- How It Works -->
    <section id="how-it-works" class="py-24 sm:py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-96 h-96 bg-rose-100/50 rounded-full blur-[120px] -translate-y-1/2 translate-x-1/2"></div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-rose-50 text-rose-600 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                    Mudah & Cepat
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4">3 Langkah Mudah</h2>
                <p class="text-lg text-slate-500 max-w-xl mx-auto">Buat undangan digital premium dalam hitungan menit, tanpa perlu keahlian desain.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8 lg:gap-12">
                <!-- Step 1 -->
                <div class="relative group">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-rose-500 to-pink-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-rose-500/30 group-hover:scale-110 transition-transform">1</div>
                    <div class="pt-16 pl-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Pilih Tema</h3>
                        <p class="text-slate-500 leading-relaxed">Pilih dari 50+ tema premium yang dirancang oleh desainer profesional untuk berbagai acara.</p>
                    </div>
                </div>
                <!-- Step 2 -->
                <div class="relative group">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-purple-500 to-indigo-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-purple-500/30 group-hover:scale-110 transition-transform">2</div>
                    <div class="pt-16 pl-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Isi Detail Acara</h3>
                        <p class="text-slate-500 leading-relaxed">Masukkan detail acara, foto, lokasi, dan informasi tamu melalui builder yang intuitif.</p>
                    </div>
                </div>
                <!-- Step 3 -->
                <div class="relative group">
                    <div class="absolute -top-4 -left-4 w-16 h-16 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center text-white text-2xl font-bold shadow-lg shadow-amber-500/30 group-hover:scale-110 transition-transform">3</div>
                    <div class="pt-16 pl-6">
                        <h3 class="text-xl font-bold text-slate-900 mb-3">Bagikan ke Tamu</h3>
                        <p class="text-slate-500 leading-relaxed">Sebarkan link undangan via WhatsApp, Instagram, atau platform lainnya. Pantau RSVP real-time.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 sm:py-32 bg-slate-50 relative overflow-hidden">
        <div class="absolute bottom-0 left-0 w-80 h-80 bg-purple-100/40 rounded-full blur-[100px] translate-y-1/2 -translate-x-1/2"></div>
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-20">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-purple-50 text-purple-600 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"/></svg>
                    Fitur Lengkap
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4">Semua yang Anda Butuhkan</h2>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto">Fitur lengkap untuk membuat undangan digital yang sempurna dan berkesan.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @php
                $features = [
                    ['Tema Premium', 'Pilihan 50+ tema elegan dan modern untuk berbagai acara spesial Anda.', 'from-rose-500 to-pink-500', 'from-rose-50 to-pink-50', 'M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z'],
                    ['RSVP Digital', 'Kelola konfirmasi kehadiran tamu secara real-time dengan dashboard.', 'from-purple-500 to-indigo-500', 'from-purple-50 to-indigo-50', 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Galeri Foto & Video', 'Tampilkan momen indah dengan galeri interaktif dan responsif.', 'from-blue-500 to-cyan-500', 'from-blue-50 to-cyan-50', 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z'],
                    ['Integrasi Maps', 'Petunjuk lokasi terintegrasi langsung dengan Google Maps.', 'from-emerald-500 to-teal-500', 'from-emerald-50 to-teal-50', 'M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z'],
                    ['Countdown Timer', 'Hitung mundur menuju hari spesial dengan animasi yang elegan.', 'from-orange-500 to-amber-500', 'from-orange-50 to-amber-50', 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    ['Amplop Digital', 'Terima hadiah dan angpao secara digital dengan fitur amplop virtual.', 'from-violet-500 to-fuchsia-500', 'from-violet-50 to-fuchsia-50', 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
                @endphp

                @foreach($features as $feature)
                <div class="group p-8 rounded-3xl bg-white border border-slate-100 hover:shadow-2xl hover:shadow-slate-200/50 hover:border-slate-200 transition-all duration-500 hover:-translate-y-1">
                    <div class="w-14 h-14 bg-gradient-to-br {{ $feature[2] }} rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 group-hover:rotate-3 transition-all duration-300 shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $feature[4] }}"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">{{ $feature[0] }}</h3>
                    <p class="text-slate-500 leading-relaxed">{{ $feature[1] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Themes Preview Section -->
    <section id="themes" class="py-24 sm:py-32 bg-white relative overflow-hidden">
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[600px] h-[600px] bg-gradient-to-br from-rose-100/30 to-amber-100/30 rounded-full blur-[120px]"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-amber-50 text-amber-600 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"/></svg>
                    Koleksi Tema
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4">Tema Pilihan Terbaik</h2>
                <p class="text-lg text-slate-500 max-w-2xl mx-auto">Desain elegan yang dibuat oleh desainer profesional, siap untuk disesuaikan.</p>
            </div>

            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">
                @php
                $gradients = [
                    'from-amber-200 via-yellow-100 to-amber-300',
                    'from-rose-200 via-pink-100 to-rose-300',
                    'from-slate-200 via-gray-100 to-slate-300',
                    'from-emerald-200 via-green-100 to-emerald-300',
                    'from-purple-200 via-violet-100 to-purple-300',
                    'from-sky-200 via-blue-100 to-sky-300',
                    'from-orange-200 via-amber-100 to-orange-300',
                    'from-teal-200 via-cyan-100 to-teal-300',
                ];
                $emojis = ['💍', '🌸', '✨', '🍃', '👑', '🌊', '🔥', '🦋'];
                @endphp

                @forelse($this->themes as $index => $theme)
                <div class="group relative rounded-3xl overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-500 transform hover:-translate-y-2 border border-slate-100">
                    <div class="aspect-[3/4] relative bg-slate-100 flex items-center justify-center overflow-hidden">
                        @if($theme->thumbnail_url)
                            <img src="{{ $theme->protected_thumbnail }}" alt="{{ $theme->name }}" class="absolute inset-0 w-full h-full object-cover group-hover:scale-110 transition-transform duration-700">
                        @else
                            <div class="absolute inset-0 bg-gradient-to-br {{ $gradients[$index % count($gradients)] }} flex items-center justify-center">
                                <span class="text-7xl group-hover:scale-125 transition-transform duration-500">{{ $emojis[$index % count($emojis)] }}</span>
                            </div>
                            <!-- Decorative Lines for fallback -->
                            <div class="absolute inset-4 border border-white/40 rounded-2xl pointer-events-none"></div>
                            <div class="absolute top-6 left-6 right-6 text-center pointer-events-none">
                                <div class="text-xs text-slate-600/60 font-medium uppercase tracking-widest">Wedding Invitation</div>
                            </div>
                        @endif
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/30 to-transparent sm:opacity-0 sm:group-hover:opacity-100 transition-opacity duration-500"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 sm:translate-y-full sm:group-hover:translate-y-0 transition-transform duration-500">
                        <h3 class="text-lg font-bold text-white mb-2">{{ $theme->name }}</h3>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('invitation.demo', ['theme' => $theme->slug]) }}" class="inline-flex items-center gap-1 px-3 py-1 bg-white/90 backdrop-blur-sm text-slate-800 text-xs font-medium rounded-full hover:bg-white transition-colors">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                Live Demo
                            </a>
                            @if($theme->is_premium)
                                <span class="inline-flex items-center gap-1 px-3 py-1 bg-amber-500/90 backdrop-blur-sm text-white text-xs font-medium rounded-full">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                    Premium
                                </span>
                            @else
                                <span class="inline-block px-3 py-1 bg-emerald-500/90 backdrop-blur-sm text-white text-xs font-medium rounded-full">Gratis</span>
                            @endif
                        </div>
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-16 text-slate-400">
                    <div class="text-5xl mb-4">🎨</div>
                    <p class="text-lg">Tema akan segera tersedia</p>
                </div>
                @endforelse
            </div>

            <div class="text-center mt-14">
                <a href="{{ route('invitation.demo') }}" class="group inline-flex items-center gap-2 px-8 py-4 text-rose-600 font-semibold hover:text-rose-700 bg-rose-50 hover:bg-rose-100 rounded-2xl transition-all">
                    Lihat Semua Tema
                    <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonials -->
    <section id="testimonials" class="py-24 sm:py-32 bg-gradient-to-b from-slate-50 to-white relative overflow-hidden">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-green-50 text-green-600 text-sm font-medium mb-4">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                    Testimoni
                </div>
                <h2 class="text-4xl sm:text-5xl font-bold text-slate-900 mb-4">Dipercaya Ribuan Pasangan</h2>
                <p class="text-lg text-slate-500 max-w-xl mx-auto">Dengarkan cerita dari mereka yang sudah merasakan kemudahan ExoInvite.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @php
                $testimonials = [
                    ['Rina & Andi', 'Jakarta', 'Tema-temanya sangat elegan! Tamu undangan kami banyak yang memuji desainnya. Proses pembuatannya juga sangat mudah.', '⭐⭐⭐⭐⭐'],
                    ['Dina & Budi', 'Surabaya', 'Fitur RSVP digitalnya sangat membantu kami mengatur jumlah tamu. Countdown timernya juga keren banget!', '⭐⭐⭐⭐⭐'],
                    ['Sari & Fajar', 'Bandung', 'Sangat puas dengan ExoInvite! Hemat budget cetak, ramah lingkungan, dan hasilnya premium. Recommended!', '⭐⭐⭐⭐⭐'],
                ];
                @endphp

                @foreach($testimonials as $t)
                <div class="p-8 rounded-3xl bg-white border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-500 group">
                    <div class="text-2xl mb-4">{{ $t[3] }}</div>
                    <p class="text-slate-600 leading-relaxed mb-6 italic">"{{ $t[2] }}"</p>
                    <div class="flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-gradient-to-br from-rose-400 to-amber-400 flex items-center justify-center text-white font-bold text-lg">
                            {{ substr($t[0], 0, 1) }}
                        </div>
                        <div>
                            <div class="font-semibold text-slate-900">{{ $t[0] }}</div>
                            <div class="text-sm text-slate-400">{{ $t[1] }}</div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 sm:py-32 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-rose-500 via-pink-500 to-amber-500"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%23ffffff%22 fill-opacity=%220.06%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')]"></div>
        <!-- Floating orbs -->
        <div class="absolute top-10 left-10 w-40 h-40 bg-white/10 rounded-full blur-3xl animate-float"></div>
        <div class="absolute bottom-10 right-10 w-60 h-60 bg-white/10 rounded-full blur-3xl animate-float" style="animation-delay: 3s;"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/20 text-white text-sm font-medium mb-8 backdrop-blur-sm">
                🎉 Gratis Selamanya untuk Paket Basic
            </div>
            <h2 class="text-4xl sm:text-6xl font-bold text-white mb-6 leading-tight">Siap Membuat Undangan Impian?</h2>
            <p class="text-xl text-white/80 mb-12 max-w-xl mx-auto">Mulai sekarang, gratis! Tidak perlu kartu kredit. Upgrade kapan saja.</p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('register') }}" class="group inline-flex items-center justify-center px-10 py-5 text-lg font-bold text-rose-600 bg-white rounded-2xl hover:bg-rose-50 transition-all transform hover:scale-105 shadow-2xl">
                    Mulai Sekarang — Gratis!
                    <svg class="w-5 h-5 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="pt-16 pb-8 bg-slate-950">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-10 mb-16">
                <!-- Brand -->
                <div class="lg:col-span-1">
                    <x-logo variant="full" :white="true" class="mb-4" />
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Platform undangan digital premium #1 di Indonesia. Buat undangan berkesan dalam hitungan menit.</p>
                    <div class="flex gap-4">
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all" aria-label="Instagram">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all" aria-label="WhatsApp">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/></svg>
                        </a>
                        <a href="#" class="w-10 h-10 rounded-xl bg-white/5 hover:bg-white/10 flex items-center justify-center text-slate-400 hover:text-white transition-all" aria-label="TikTok">
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-2.88 2.5 2.89 2.89 0 01-2.89-2.89 2.89 2.89 0 012.89-2.89c.28 0 .54.04.79.1v-3.5a6.37 6.37 0 00-.79-.05A6.34 6.34 0 003.15 15.2a6.34 6.34 0 006.34 6.34 6.34 6.34 0 006.34-6.34V8.7a8.16 8.16 0 004.76 1.52v-3.4a4.85 4.85 0 01-1-.13z"/></svg>
                        </a>
                    </div>
                </div>
                <!-- Links -->
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Produk</h4>
                    <ul class="space-y-3">
                        <li><a href="#features" class="text-slate-400 hover:text-white text-sm transition-colors">Fitur</a></li>
                        <li><a href="#themes" class="text-slate-400 hover:text-white text-sm transition-colors">Tema</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Harga</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Contoh Undangan</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Perusahaan</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Tentang Kami</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Blog</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Karir</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Kontak</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-white font-semibold mb-4 text-sm uppercase tracking-wider">Legal</h4>
                    <ul class="space-y-3">
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Kebijakan Privasi</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Syarat & Ketentuan</a></li>
                        <li><a href="#" class="text-slate-400 hover:text-white text-sm transition-colors">Refund Policy</a></li>
                    </ul>
                </div>
            </div>

            <!-- Bottom Bar -->
            <div class="pt-8 border-t border-white/10 flex flex-col sm:flex-row justify-between items-center gap-4">
                <div class="text-slate-500 text-sm">© {{ date('Y') }} ExoInvite. All rights reserved.</div>
                <div class="text-slate-600 text-xs">Made with ❤️ in Indonesia</div>
            </div>
        </div>
    </footer>

</div>
