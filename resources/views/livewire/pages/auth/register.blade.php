<div class="relative min-h-screen flex flex-col justify-center overflow-hidden bg-gradient-to-br from-slate-950 via-purple-950 to-slate-950 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Animated Background Orbs -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -left-40 w-[500px] h-[500px] bg-rose-500/20 rounded-full blur-[100px] animate-float" style="animation-delay: 1s;"></div>
        <div class="absolute -bottom-40 -right-40 w-[500px] h-[500px] bg-amber-500/20 rounded-full blur-[100px] animate-float" style="animation-delay: 3s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-[600px] h-[600px] bg-purple-500/15 rounded-full blur-[120px] animate-float" style="animation-delay: 5s;"></div>
    </div>

    <!-- Grid Pattern -->
    <div class="absolute inset-0 bg-[url('data:image/svg+xml,%3Csvg width=%2260%22 height=%2260%22 viewBox=%220 0 60 60%22 xmlns=%22http://www.w3.org/2000/svg%22%3E%3Cg fill=%22none%22 fill-rule=%22evenodd%22%3E%3Cg fill=%22%239C92AC%22 fill-opacity=%220.04%22%3E%3Cpath d=%22M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z%22/%3E%3C/g%3E%3C/g%3E%3C/svg%3E')] opacity-50 pointer-events-none"></div>

    <!-- Floating Particles -->
    <div class="absolute inset-0 overflow-hidden pointer-events-none hidden sm:block">
        <div class="absolute top-[15%] right-[10%] w-1.5 h-1.5 bg-rose-400/60 rounded-full animate-float" style="animation-delay: 1.5s; animation-duration: 8s;"></div>
        <div class="absolute top-[25%] left-[15%] w-1 h-1 bg-amber-400/50 rounded-full animate-float" style="animation-delay: 2.5s; animation-duration: 7s;"></div>
        <div class="absolute top-[60%] right-[20%] w-2 h-2 bg-purple-400/40 rounded-full animate-float" style="animation-delay: 3.5s; animation-duration: 9s;"></div>
        <div class="absolute top-[70%] left-[25%] w-1 h-1 bg-pink-400/50 rounded-full animate-float" style="animation-delay: 4.5s; animation-duration: 6s;"></div>
        <div class="absolute top-[40%] right-[75%] w-1.5 h-1.5 bg-cyan-400/40 rounded-full animate-float" style="animation-delay: 1s; animation-duration: 10s;"></div>
    </div>

    <!-- Content -->
    <div class="relative z-10 w-full max-w-md mx-auto">
        <div class="text-center mb-8 animate-fade-in-up">
            <x-logo variant="full" :white="true" class="justify-center" />
            <p class="mt-4 text-sm font-medium tracking-[0.2em] uppercase text-white/50">Create Your Space</p>
            <h1 class="mt-2 text-3xl sm:text-4xl font-bold text-white tracking-tight">Buat Akun Baru</h1>
            <p class="mt-2 text-white/60">Mulai perjalanan undangan digital yang elegan.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl border border-rose-500/30 bg-rose-500/10 backdrop-blur-md shadow-sm animate-fade-in-up" style="animation-delay: 0.1s;">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-rose-300">Registrasi belum berhasil</h3>
                        <ul class="mt-1 text-sm text-rose-200/80 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-3xl border border-white/10 bg-white/[0.03] backdrop-blur-xl p-7 sm:p-8 shadow-2xl shadow-black/50 animate-fade-in-up" style="animation-delay: 0.15s;">
            <form wire:submit="register" class="space-y-5">
                <div>
                    <label for="name" class="block text-sm font-medium text-white/80 mb-2">Nama Lengkap</label>
                    <input
                        wire:model="name"
                        type="text"
                        id="name"
                        class="w-full px-4 py-3.5 rounded-xl bg-black/20 border {{ $errors->has('name') ? 'border-rose-500/50' : 'border-white/10' }} text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-transparent transition-all backdrop-blur-sm"
                        placeholder="Contoh: Putri Maharani"
                        autocomplete="name"
                    >
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-white/80 mb-2">Email</label>
                    <input
                        wire:model="email"
                        type="email"
                        id="email"
                        class="w-full px-4 py-3.5 rounded-xl bg-black/20 border {{ $errors->has('email') ? 'border-rose-500/50' : 'border-white/10' }} text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-transparent transition-all backdrop-blur-sm"
                        placeholder="nama@email.com"
                        autocomplete="email"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-white/80 mb-2">Password</label>
                    <input
                        wire:model="password"
                        type="password"
                        id="password"
                        class="w-full px-4 py-3.5 rounded-xl bg-black/20 border {{ $errors->has('password') ? 'border-rose-500/50' : 'border-white/10' }} text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-transparent transition-all backdrop-blur-sm"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                    >
                    <p class="mt-1.5 text-xs text-white/40">Gunakan kombinasi huruf dan angka agar lebih aman.</p>
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-white/80 mb-2">Konfirmasi Password</label>
                    <input
                        wire:model="password_confirmation"
                        type="password"
                        id="password_confirmation"
                        class="w-full px-4 py-3.5 rounded-xl bg-black/20 border {{ $errors->has('password_confirmation') ? 'border-rose-500/50' : 'border-white/10' }} text-white placeholder-white/30 focus:outline-none focus:ring-2 focus:ring-rose-500/50 focus:border-transparent transition-all backdrop-blur-sm"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                    >
                </div>

                <button
                    type="submit"
                    class="group w-full py-4 text-center rounded-xl text-white font-semibold bg-gradient-to-r from-rose-500 to-amber-500 hover:opacity-90 transition-all shadow-lg shadow-rose-500/25 disabled:opacity-70 disabled:cursor-not-allowed mt-2"
                    wire:loading.attr="disabled"
                    wire:target="register"
                >
                    <span wire:loading.remove wire:target="register" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1"/>
                        </svg>
                        Daftar Sekarang
                    </span>
                    <span wire:loading wire:target="register" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Menyiapkan...
                    </span>
                </button>
            </form>

            <p class="mt-7 text-center text-white/60 text-sm">
                Sudah punya akun?
                <a href="{{ route('login') }}" class="font-semibold text-rose-400 hover:text-rose-300 transition-colors">Masuk di sini</a>
            </p>
        </div>

        <p class="mt-8 text-center text-xs text-white/40 animate-fade-in-up" style="animation-delay: 0.3s;">
            &copy; {{ date('Y') }} ExoInvite. Crafted with elegance.
        </p>
        
        <!-- Back to Home -->
        <div class="mt-6 text-center animate-fade-in-up" style="animation-delay: 0.4s;">
            <a href="/" class="inline-flex items-center gap-2 text-sm text-white/50 hover:text-white/80 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
