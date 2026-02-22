<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Kelola Tema</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Aktifkan, nonaktifkan, dan atur ketersediaan (Premium/Gratis) koleksi tema Anda.</p>
        </div>
        <div class="flex items-center gap-3">
            <button class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Buat Tema Baru
            </button>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif
    @error('newThumbnail')
        <div class="mb-6 p-4 bg-red-100 border border-red-200 text-red-700 rounded-xl">
            {{ $message }}
        </div>
    @enderror

    <!-- Hidden File Input for Thumbnail Upload -->
    <input type="file" id="thumbnail-upload" class="hidden" wire:model="newThumbnail" accept="image/*">


    <!-- Papan Utama Tabel (Pencarian) -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col mb-8">
        <div class="p-4 sm:p-6 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <!-- Pencarian (Livewire Binding) -->
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cari berdasarkan nama tema atau slug...">
                
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>

            <!-- Per Page Selector -->
            <div class="flex items-center gap-2">
                <label for="perPage" class="text-sm font-medium text-slate-500 dark:text-slate-400">Tampilkan:</label>
                <select id="perPage" wire:model.live="perPage" class="block w-20 py-2 px-3 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200">
                    <option value="12">12</option>
                    <option value="24">24</option>
                    <option value="48">48</option>
                    <option value="100">100</option>
                    <option value="200">200</option>
                </select>
            </div>
        </div>
    </div>

    <!-- Grid Layout Daftar Tema -->
    <div class="relative min-h-[400px]">
        <div wire:loading.class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-900/50 backdrop-blur-[1px] rounded-xl" class="hidden"></div>
        <div class="grid grid-cols-2 md:grid-cols-6 lg:grid-cols-8 gap-3 sm:gap-4">
            @forelse($themes as $theme)
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden hover:shadow-md transition-shadow flex flex-col">
                    <!-- Thumbnail area -->
                    <div class="aspect-[9/16] bg-slate-100 dark:bg-slate-900 relative overflow-hidden group">
                        @if($theme->thumbnail_url)
                            <img src="{{ $theme->thumbnail_url }}" alt="{{ $theme->name }}" class="w-full h-full object-cover object-top group-hover:scale-105 transition-transform duration-500">
                        @else
                            <div class="w-full h-full flex flex-col items-center justify-center text-slate-400 dark:text-slate-500">
                                <svg class="w-12 h-12 mb-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <span class="text-sm font-medium">Bawaan Sistem</span>
                            </div>
                        @endif
                        
                        <div class="absolute top-3 left-3 flex gap-2">
                            @if($theme->is_premium)
                                <span class="bg-amber-100 text-amber-800 dark:bg-amber-900/80 dark:text-amber-300 text-[10px] uppercase font-bold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-sm flex items-center gap-1 border border-amber-200/50 dark:border-amber-700/50">
                                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Premium
                                </span>
                            @else
                                <span class="bg-white/90 text-slate-800 dark:bg-slate-800/90 dark:text-slate-200 text-[10px] uppercase font-bold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-sm border border-slate-200/50 dark:border-slate-700/50">Gratis</span>
                            @endif
                            
                            @if($theme->is_active)
                                <span class="bg-emerald-100 text-emerald-800 dark:bg-emerald-900/80 dark:text-emerald-300 text-[10px] uppercase font-bold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-sm border border-emerald-200/50 dark:border-emerald-700/50">Aktif</span>
                            @else
                                <span class="bg-rose-100 text-rose-800 dark:bg-rose-900/80 dark:text-rose-300 text-[10px] uppercase font-bold px-2.5 py-1 rounded-lg backdrop-blur-md shadow-sm border border-rose-200/50 dark:border-rose-700/50">Nonaktif</span>
                            @endif
                        </div>

                        <!-- Update Thumbnail Overlay -->
                        <div class="absolute inset-x-0 bottom-0 p-3 bg-gradient-to-t from-black/80 to-transparent flex justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                            <label for="thumbnail-upload" wire:click="triggerUpload({{ $theme->id }})" class="cursor-pointer text-xs font-medium text-white bg-indigo-600 hover:bg-indigo-700 py-1.5 px-3 rounded-lg shadow-sm flex items-center gap-1.5 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                <span wire:loading.remove wire:target="newThumbnail">Ganti Thumbnail</span>
                                <span wire:loading wire:target="newThumbnail">Mengunggah...</span>
                            </label>
                        </div>
                    </div>

                    <!-- Detail & Action -->
                    <div class="p-5 flex-1 flex flex-col">
                        <div class="mb-4">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white truncate" title="{{ $theme->name }}">{{ $theme->name }}</h3>
                            <p class="text-xs text-slate-500 dark:text-slate-400 font-mono mt-1">{{ $theme->slug }}</p>
                        </div>
                        
                        <div class="mt-auto space-y-3">
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Status Aktif</span>
                                <!-- Toggle Active -->
                                <button type="button" wire:click="toggleActive({{ $theme->id }})" class="{{ $theme->is_active ? 'bg-indigo-600' : 'bg-slate-200 dark:bg-slate-700' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:ring-offset-2 dark:focus:ring-offset-slate-900" role="switch" aria-checked="{{ $theme->is_active ? 'true' : 'false' }}">
                                    <span class="sr-only">Toggle Active status</span>
                                    <span class="{{ $theme->is_active ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1.5">
                                    <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Premium Label
                                </span>
                                <!-- Toggle Premium -->
                                <button type="button" wire:click="togglePremium({{ $theme->id }})" class="{{ $theme->is_premium ? 'bg-amber-500' : 'bg-slate-200 dark:bg-slate-700' }} relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 dark:focus:ring-offset-slate-900" role="switch" aria-checked="{{ $theme->is_premium ? 'true' : 'false' }}">
                                    <span class="sr-only">Toggle Premium status</span>
                                    <span class="{{ $theme->is_premium ? 'translate-x-5' : 'translate-x-0' }} pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"></span>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700">
                    <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                    </div>
                    <h3 class="text-base font-semibold text-slate-900 dark:text-white mb-2">Tema Tidak Ditemukan</h3>
                    <p class="text-sm text-slate-500 dark:text-slate-400 max-w-sm mx-auto">Kami tidak dapat menemukan nama tema atau slug yang sesuai dengan pencarian Anda.</p>
                </div>
            @endforelse
        </div>
    </div>
    <!-- Paginasi -->
    @if($themes->hasPages())
        <div class="mt-8 w-full bg-slate-50 dark:bg-slate-800/50 p-4 sm:p-6 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
            {{ $themes->links() }}
        </div>
    @endif

</div>
