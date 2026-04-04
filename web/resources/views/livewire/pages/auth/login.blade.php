<div class="relative min-h-screen overflow-hidden bg-gradient-to-b from-[#fdf8ef] via-[#fffdf8] to-[#f3e8d3] py-12 px-4 sm:px-6 lg:px-8">
    <img src="{{ asset('assets/themes/bunga_biru_putih_pinggir_atas.png') }}" alt="Ornamen" class="pointer-events-none absolute top-0 right-0 w-48 sm:w-72 opacity-80" />
    <img src="{{ asset('assets/themes/bunga_ungu_putih_pinggir_bawah.png') }}" alt="Ornamen" class="pointer-events-none absolute bottom-0 left-0 w-48 sm:w-72 opacity-70" />
    <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(circle_at_20%_15%,rgba(197,150,79,0.2),transparent_42%),radial-gradient(circle_at_82%_83%,rgba(149,105,59,0.15),transparent_38%)]"></div>

    <div class="relative mx-auto w-full max-w-lg">
        <div class="text-center mb-8">
            <x-logo variant="full" class="justify-center" />
            <p class="mt-2 text-sm font-medium tracking-[0.2em] uppercase text-[#9a6f3f]">Welcome Back</p>
            <h1 class="mt-4 text-3xl sm:text-4xl font-semibold text-[#4b2f18] font-playfair-display">Masuk ke Akun Anda</h1>
            <p class="mt-2 text-[#7b6248]">Lanjutkan mengelola undangan digital Anda dengan pengalaman premium.</p>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 rounded-2xl border border-rose-200 bg-rose-50/90 shadow-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0 mt-0.5">
                        <svg class="w-5 h-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="font-semibold text-rose-700">Login belum berhasil</h3>
                        <ul class="mt-1 text-sm text-rose-700/90 list-disc list-inside space-y-1">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="rounded-[28px] border border-[#e8d9be] bg-white/85 backdrop-blur-xl p-7 sm:p-8 shadow-[0_24px_60px_-24px_rgba(119,77,31,0.45)]">
            <form wire:submit="login" class="space-y-5">
                <div>
                    <label for="email" class="block text-sm font-semibold text-[#65472a] mb-2">Email</label>
                    <input
                        wire:model="email"
                        type="email"
                        id="email"
                        class="w-full px-4 py-3 rounded-xl bg-[#fffaf2] border {{ $errors->has('email') ? 'border-rose-400' : 'border-[#e9d9bd]' }} text-[#4b2f18] placeholder-[#b9a58b] focus:outline-none focus:ring-2 focus:ring-[#c79d62] focus:border-transparent transition-all"
                        placeholder="nama@email.com"
                        autocomplete="email"
                    >
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-[#65472a] mb-2">Password</label>
                    <input
                        wire:model="password"
                        type="password"
                        id="password"
                        class="w-full px-4 py-3 rounded-xl bg-[#fffaf2] border {{ $errors->has('password') ? 'border-rose-400' : 'border-[#e9d9bd]' }} text-[#4b2f18] placeholder-[#b9a58b] focus:outline-none focus:ring-2 focus:ring-[#c79d62] focus:border-transparent transition-all"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                    >
                </div>

                <div class="flex items-center justify-between">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-sm text-[#7e6548]">
                        <input wire:model="remember" type="checkbox" class="w-4 h-4 rounded border-[#ccb894] text-[#a37036] focus:ring-[#c79d62]">
                        Ingat saya
                    </label>
                    <span class="text-xs text-[#9a6f3f]">Aman & terenkripsi</span>
                </div>

                <button
                    type="submit"
                    class="w-full py-4 rounded-xl text-white font-semibold bg-gradient-to-r from-[#b17a3d] via-[#c9964f] to-[#8e5d2a] hover:opacity-95 transition-all shadow-lg shadow-amber-700/30 disabled:opacity-70 disabled:cursor-not-allowed"
                    wire:loading.attr="disabled"
                    wire:target="login"
                >
                    <span wire:loading.remove wire:target="login" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                        </svg>
                        Masuk
                    </span>
                    <span wire:loading wire:target="login" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Memproses...
                    </span>
                </button>
            </form>

            <p class="mt-6 text-center text-[#7e6548]">
                Belum punya akun?
                <a href="{{ route('register') }}" class="font-semibold text-[#9a6f3f] hover:text-[#7f582d] hover:underline">Daftar sekarang</a>
            </p>
        </div>

        <p class="mt-8 text-center text-xs text-[#9f8462]">
            &copy; {{ date('Y') }} ExoInvite. Crafted with elegance.
        </p>
    </div>
</div>
