<div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900 py-12 px-4 sm:px-6 lg:px-8">
    <!-- Background Effects -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-rose-500/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-amber-500/20 rounded-full blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md">
        <!-- Logo -->
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-bold bg-gradient-to-r from-rose-400 to-amber-400 bg-clip-text text-transparent">ExoInvite</a>
            <p class="mt-2 text-slate-300">Buat akun baru</p>
        </div>

        <!-- Global Error Alert -->
        @if($errors->any())
            <div class="mb-6 p-4 bg-red-500/20 border border-red-500/50 rounded-xl backdrop-blur-sm">
                <div class="flex items-start gap-3">
                    <div class="flex-shrink-0">
                        <svg class="w-5 h-5 text-red-400 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-red-400 font-medium">Registrasi Gagal</h3>
                        <ul class="mt-1 text-red-300 text-sm list-disc list-inside">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <!-- Register Form -->
        <div class="bg-slate-800/50 backdrop-blur-xl rounded-2xl p-8 shadow-2xl border border-slate-700/50">
            <form wire:submit="register" class="space-y-5">
                <!-- Name -->
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-200 mb-2">Nama Lengkap</label>
                    <input 
                        wire:model="name" 
                        type="text" 
                        id="name"
                        class="w-full px-4 py-3 bg-slate-900/50 border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                        placeholder="Masukkan nama lengkap"
                        autocomplete="name"
                    >
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-200 mb-2">Email</label>
                    <input 
                        wire:model="email" 
                        type="email" 
                        id="email"
                        class="w-full px-4 py-3 bg-slate-900/50 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                        placeholder="nama@email.com"
                        autocomplete="email"
                    >
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-200 mb-2">Password</label>
                    <input 
                        wire:model="password" 
                        type="password" 
                        id="password"
                        class="w-full px-4 py-3 bg-slate-900/50 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-600' }} rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                        placeholder="Minimal 8 karakter"
                        autocomplete="new-password"
                    >
                    <p class="mt-1 text-xs text-slate-400">Minimal 8 karakter</p>
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-200 mb-2">Konfirmasi Password</label>
                    <input 
                        wire:model="password_confirmation" 
                        type="password" 
                        id="password_confirmation"
                        class="w-full px-4 py-3 bg-slate-900/50 border border-slate-600 rounded-xl text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"
                        placeholder="Ulangi password"
                        autocomplete="new-password"
                    >
                </div>

                <!-- Submit Button -->
                <button 
                    type="submit"
                    class="w-full py-4 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-[1.02] shadow-lg shadow-rose-500/30 disabled:opacity-70 disabled:cursor-not-allowed disabled:transform-none"
                    wire:loading.attr="disabled"
                    wire:target="register"
                >
                    <span wire:loading.remove wire:target="register" class="flex items-center justify-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                        </svg>
                        Daftar Sekarang
                    </span>
                    <span wire:loading wire:target="register" class="flex items-center justify-center gap-2">
                        <svg class="animate-spin h-5 w-5" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Mendaftarkan akun...
                    </span>
                </button>
            </form>

            <!-- Login Link -->
            <p class="mt-6 text-center text-slate-300">
                Sudah punya akun? 
                <a href="{{ route('login') }}" class="text-rose-400 hover:text-rose-300 font-medium hover:underline">
                    Masuk di sini
                </a>
            </p>
        </div>

        <!-- Footer -->
        <p class="mt-8 text-center text-slate-500 text-sm">
            &copy; {{ date('Y') }} ExoInvite. All rights reserved.
        </p>
    </div>
</div>
