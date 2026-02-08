<div>
<div>
    <!-- Hero Section -->
    <section class="relative min-h-screen flex items-center justify-center overflow-hidden bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
        <!-- Animated Background Elements -->
        <div class="absolute inset-0 overflow-hidden">
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-rose-500/30 rounded-full blur-3xl animate-float"></div>
            <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-500/30 rounded-full blur-3xl animate-float" style="animation-delay: 2s;"></div>
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-purple-500/20 rounded-full blur-3xl"></div>
        </div>

        <!-- Grid Pattern Overlay -->
        <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.05%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-40"></div>

        <!-- Content -->
        <div class="relative z-10 max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <!-- Badge -->
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 text-white/80 text-sm mb-8">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                Platform Undangan Digital #1 Indonesia
            </div>

            <!-- Main Heading -->
            <h1 class="text-5xl sm:text-6xl lg:text-7xl font-bold text-white mb-6 leading-tight">
                Undangan Digital
                <span class="block text-gradient animate-gradient">Premium & Elegan</span>
            </h1>

            <!-- Subtitle -->
            <p class="text-xl text-white/70 max-w-2xl mx-auto mb-10">
                Buat undangan pernikahan, ulang tahun, dan acara spesial lainnya dengan desain stunning yang ramah lingkungan.
            </p>

            <!-- CTA Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center mb-16">
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-gradient-to-r from-rose-500 to-amber-500 rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg shadow-rose-500/30">
                    Buat Undangan Gratis
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                    </svg>
                </a>
                <a href="#features" class="inline-flex items-center justify-center px-8 py-4 text-lg font-semibold text-white bg-white/10 backdrop-blur-sm border border-white/20 rounded-xl hover:bg-white/20 transition-all">
                    Lihat Fitur
                </a>
            </div>

            <!-- Stats -->
            <div class="grid grid-cols-3 gap-8 max-w-2xl mx-auto">
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-1">10K+</div>
                    <div class="text-white/60 text-sm">Undangan Dibuat</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-1">50+</div>
                    <div class="text-white/60 text-sm">Tema Premium</div>
                </div>
                <div class="text-center">
                    <div class="text-4xl font-bold text-white mb-1">99%</div>
                    <div class="text-white/60 text-sm">Kepuasan Pelanggan</div>
                </div>
            </div>
        </div>

        <!-- Scroll Indicator -->
        <div class="absolute bottom-8 left-1/2 -translate-x-1/2 animate-bounce">
            <svg class="w-6 h-6 text-white/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/>
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-24 bg-white">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Fitur Unggulan</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Semua yang Anda butuhkan untuk membuat undangan digital yang sempurna</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-rose-50 to-amber-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-rose-500 to-amber-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Tema Premium</h3>
                    <p class="text-slate-600">Pilihan tema elegan dan modern untuk berbagai acara. Dari pernikahan hingga ulang tahun.</p>
                </div>

                <!-- Feature 2 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-purple-50 to-pink-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">RSVP Digital</h3>
                    <p class="text-slate-600">Kelola konfirmasi kehadiran tamu dengan mudah. Terima notifikasi real-time.</p>
                </div>

                <!-- Feature 3 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-blue-50 to-cyan-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Galeri Foto</h3>
                    <p class="text-slate-600">Tampilkan momen indah dengan galeri foto yang interaktif dan responsif.</p>
                </div>

                <!-- Feature 4 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-green-50 to-emerald-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Integrasi Maps</h3>
                    <p class="text-slate-600">Petunjuk lokasi langsung terintegrasi dengan Google Maps.</p>
                </div>

                <!-- Feature 5 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-orange-50 to-yellow-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-orange-500 to-yellow-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Countdown Timer</h3>
                    <p class="text-slate-600">Hitung mundur menuju hari spesial dengan animasi yang elegan.</p>
                </div>

                <!-- Feature 6 -->
                <div class="group p-8 rounded-2xl bg-gradient-to-br from-indigo-50 to-violet-50 hover:shadow-xl transition-all duration-300">
                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-violet-500 rounded-xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h3 class="text-xl font-semibold text-slate-900 mb-3">Amplop Digital</h3>
                    <p class="text-slate-600">Terima hadiah secara digital dengan fitur amplop virtual.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Themes Preview Section -->
    <section class="py-24 bg-slate-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-slate-900 mb-4">Tema Pilihan</h2>
                <p class="text-lg text-slate-600 max-w-2xl mx-auto">Desain elegan yang bisa disesuaikan dengan gaya Anda</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6">
                @forelse($this->themes as $theme)
                <div class="group relative rounded-2xl overflow-hidden bg-white shadow-lg hover:shadow-2xl transition-all duration-300 transform hover:-translate-y-2">
                    <div class="aspect-[3/4] bg-gradient-to-br from-rose-100 to-amber-100 flex items-center justify-center">
                        <span class="text-6xl">💍</span>
                    </div>
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                    <div class="absolute bottom-0 left-0 right-0 p-6 translate-y-full group-hover:translate-y-0 transition-transform duration-300">
                        <h3 class="text-xl font-semibold text-white mb-2">{{ $theme->name }}</h3>
                        @if($theme->is_premium)
                            <span class="inline-block px-3 py-1 bg-amber-500 text-white text-xs font-medium rounded-full">Premium</span>
                        @else
                            <span class="inline-block px-3 py-1 bg-green-500 text-white text-xs font-medium rounded-full">Gratis</span>
                        @endif
                    </div>
                </div>
                @empty
                <div class="col-span-4 text-center py-12 text-slate-500">
                    Tema akan segera tersedia
                </div>
                @endforelse
            </div>

            <div class="text-center mt-12">
                <a href="{{ route('register') }}" class="inline-flex items-center px-6 py-3 text-rose-600 font-semibold hover:text-rose-700">
                    Lihat Semua Tema
                    <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </a>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-24 bg-gradient-to-br from-rose-500 via-pink-500 to-amber-500">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-4xl font-bold text-white mb-6">Siap Membuat Undangan Impian?</h2>
            <p class="text-xl text-white/80 mb-10">Mulai sekarang, gratis! Tidak perlu kartu kredit.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-10 py-4 text-lg font-semibold text-rose-600 bg-white rounded-xl hover:bg-rose-50 transition-all transform hover:scale-105 shadow-xl">
                Mulai Sekarang
                <svg class="w-5 h-5 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                </svg>
            </a>
        </div>
    </section>

    <!-- Footer -->
    <footer class="py-12 bg-slate-900">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex flex-col md:flex-row justify-between items-center gap-6">
                <div class="text-2xl font-bold text-gradient">ExoInvite</div>
                <div class="flex gap-6">
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">Tentang Kami</a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">Kontak</a>
                    <a href="#" class="text-slate-400 hover:text-white transition-colors">Kebijakan Privasi</a>
                </div>
                <div class="text-slate-500 text-sm">
                    © {{ date('Y') }} ExoInvite. All rights reserved.
                </div>
            </div>
        </div>
    </footer>
</div>
</div>
