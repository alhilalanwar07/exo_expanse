<div x-data="{ toast: { show: false, message: '', type: 'success' } }"
     x-on:toast.window="toast.message = $event.detail.message; toast.type = $event.detail.type; toast.show = true; setTimeout(() => toast.show = false, 3000)">

    <x-toast-notification />

    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Pengaturan Sistem</h1>
        <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola konfigurasi dasar aplikasi dan preferensi aksesibilitas situs.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Sidebar Menu Pengaturan (Opsional visual saja) -->
        <div class="lg:col-span-1 space-y-1">
            <nav class="flex flex-col gap-1">
                <a href="#" class="px-4 py-2.5 bg-indigo-50 dark:bg-indigo-900/30 text-indigo-700 dark:text-indigo-400 text-sm font-medium rounded-xl transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    Informasi Dasar
                </a>
                <a href="#" class="px-4 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium rounded-xl transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Keamanan & Akses
                </a>
                <a href="#" class="px-4 py-2.5 text-slate-600 dark:text-slate-400 hover:bg-slate-50 dark:hover:bg-slate-800 text-sm font-medium rounded-xl transition-colors flex items-center gap-3">
                    <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                    Email Konfigurasi
                </a>
            </nav>
        </div>

        <!-- Form Konten Utama -->
        <div class="lg:col-span-2">
            <form wire:submit="saveSettings" class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                <div class="p-6 sm:p-8 space-y-8">
                    
                    <!-- Area Identitas Situs -->
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Profil Aplikasi</h3>
                        <div class="space-y-5">
                            <div>
                                <label for="appName" class="block text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2 mb-1.5">
                                    Nama Aplikasi / Web
                                </label>
                                <input type="text" id="appName" wire:model="appName" class="block w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-slate-50 dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cth: ExoInvite">
                                @error('appName') <span class="text-xs text-rose-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="supportEmail" class="block text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2 mb-1.5">
                                    Email Bantuan (Support)
                                </label>
                                <input type="email" id="supportEmail" wire:model="supportEmail" class="block w-full px-4 py-2.5 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-slate-50 dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cth: halo@exoinvite.com">
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Alamat email yang ditampilkan kepada tamu atau pengguna jika butuh bantuan teknis.</p>
                                @error('supportEmail') <span class="text-xs text-rose-500 mt-1.5 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <hr class="border-slate-200 dark:border-slate-700">

                    <!-- Area Sistem Otorisasi -->
                    <div>
                        <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-4">Pengaturan Sistem & Akses</h3>
                        <ul class="space-y-4">
                            <!-- Switch 1: Pendaftaran -->
                            <li class="flex items-center justify-between p-4 rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-slate-900 dark:text-white">Buka Registrasi Publik</span>
                                    <span class="text-sm text-slate-500 dark:text-slate-400 mt-1">Izinkan tamu membuat akun baru melalui halaman Register.</span>
                                </div>
                                <button type="button" wire:click="$toggle('enableRegistration')" class="{{ $enableRegistration ? 'bg-indigo-600' : 'bg-slate-300 dark:bg-slate-600' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900" role="switch" aria-checked="{{ $enableRegistration ? 'true' : 'false' }}">
                                    <span class="sr-only">Toggle Registration</span>
                                    <span class="{{ $enableRegistration ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </li>

                            <!-- Switch 2: Maintenance Mode -->
                            <li class="flex items-center justify-between p-4 rounded-xl border border-rose-100 dark:border-rose-900/30 bg-rose-50/30 dark:bg-rose-900/10">
                                <div class="flex flex-col">
                                    <span class="text-sm font-medium text-rose-900 dark:text-rose-400">Mode Perawatan (Maintenance)</span>
                                    <span class="text-sm text-rose-600 dark:text-rose-500/70 mt-1">Matikan situs untuk publik saat Anda melakukan pemeliharaan database.</span>
                                </div>
                                <button type="button" wire:click="$toggle('maintenanceMode')" class="{{ $maintenanceMode ? 'bg-rose-600' : 'bg-slate-300 dark:bg-slate-600' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-rose-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900" role="switch" aria-checked="{{ $maintenanceMode ? 'true' : 'false' }}">
                                    <span class="sr-only">Toggle Maintenance</span>
                                    <span class="{{ $maintenanceMode ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </li>
                        </ul>
                    </div>

                </div>
                
                <!-- Footer Aksi -->
                <div class="px-6 py-4 bg-slate-50 dark:bg-slate-800 border-t border-slate-200 dark:border-slate-700 flex items-center justify-end">
                    <button type="submit" class="inline-flex items-center gap-2 px-6 py-2.5 text-sm font-medium text-white bg-indigo-600 rounded-xl shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-all">
                        <svg wire:loading wire:target="saveSettings" class="w-4 h-4 animate-spin -ml-1 mr-2" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
