<div class="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
    <!-- Header -->
    <div class="sm:flex sm:justify-between sm:items-end mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-800 dark:text-white">Pustaka Musik</h1>
            <p class="text-slate-500 dark:text-slate-400 mt-1">Kelola latar belakang musik yang akan dipilih oleh klien Anda.</p>
        </div>
        <div class="mt-4 sm:mt-0 relative w-full sm:w-64 z-10">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </div>
            <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari judul atau artis..." class="w-full pl-10 pr-3 py-2 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-sm focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-slate-200">
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-4 bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 px-4 py-3 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            {{ session('message') }}
        </div>
    @endif

    <div class="grid grid-cols-1 xl:grid-cols-3 gap-6">
        <!-- Form Section -->
        <div class="xl:col-span-1">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl p-6 shadow-sm">
                <h2 class="text-xl font-bold text-slate-800 dark:text-white mb-5">{{ $editingMusicId ? 'Edit Musik' : 'Tambah Musik Baru' }}</h2>
                
                <form wire:submit.prevent="save" class="space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Lagu <span class="text-red-500">*</span></label>
                        <input type="text" id="title" wire:model="title" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white" placeholder="Contoh: A Thousand Years">
                        @error('title') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="artist" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Penyanyi / Artis</label>
                        <input type="text" id="artist" wire:model="artist" class="w-full rounded-lg border-slate-300 dark:border-slate-600 dark:bg-slate-700 focus:ring-indigo-500 focus:border-indigo-500 dark:text-white" placeholder="Contoh: Christina Perri">
                        @error('artist') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label for="musicFile" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">File Audio (MP3/WAV) {{ !$editingMusicId ? '<span class="text-red-500">*</span>' : '' }}</label>
                        
                        <div class="relative mt-1">
                            <input type="file" id="musicFile" wire:model="musicFile" accept="audio/mp3,audio/wav" class="block w-full text-sm text-slate-500 dark:text-slate-400
                                file:mr-4 file:py-2 file:px-4
                                file:rounded-full file:border-0
                                file:text-sm file:font-semibold
                                file:bg-indigo-50 file:text-indigo-700
                                hover:file:bg-indigo-100
                                dark:file:bg-indigo-900/30 dark:file:text-indigo-400 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 p-1 cursor-pointer">
                        </div>
                        
                        <div wire:loading wire:target="musicFile" class="text-indigo-600 text-sm mt-2 flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Mengunggah file...
                        </div>
                        
                        @error('musicFile') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                        
                        @if($editingMusicId && !$musicFile)
                            <p class="text-xs text-slate-500 dark:text-slate-400 mt-2">Biarkan kosong jika tidak ingin mengubah audio lama.</p>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 mt-2">
                        <input type="checkbox" id="isActive" wire:model="isActive" class="w-4 h-4 text-indigo-600 border-slate-300 rounded focus:ring-indigo-500">
                        <label for="isActive" class="text-sm font-medium text-slate-700 dark:text-slate-300">Aktifkan musik ini (Tampil di klien)</label>
                    </div>

                    <div class="pt-4 flex items-center justify-end gap-3 border-t border-slate-200 dark:border-slate-700">
                        @if($editingMusicId)
                            <button type="button" wire:click="resetInput" class="px-4 py-2 bg-slate-100 text-slate-700 hover:bg-slate-200 dark:bg-slate-700 dark:text-slate-200 dark:hover:bg-slate-600 rounded-lg text-sm font-medium transition-colors">
                                Batal
                            </button>
                        @endif
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-medium transition-colors disabled:opacity-50" wire:loading.attr="disabled">
                            <span wire:loading.remove wire:target="save">Simpan</span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Table Section -->
        <div class="xl:col-span-2">
            <div class="bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-2xl shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-100 dark:bg-slate-900/50 border-b border-slate-200 dark:border-slate-700">
                                <th class="px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Lagu & Artis</th>
                                <th class="px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-center">Putar Miusik</th>
                                <th class="px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">Status</th>
                                <th class="px-5 py-3 text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 dark:divide-slate-700">
                            @forelse($musics as $item)
                                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/50 transition-colors">
                                    <td class="px-5 py-4">
                                        <div class="font-bold text-slate-800 dark:text-white">{{ $item->title }}</div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">{{ $item->artist ?? '-' }}</div>
                                    </td>
                                    <td class="px-5 py-4 w-64 text-center">
                                        <audio controls class="h-10 w-full max-w-[200px] mx-auto rounded-full" preload="none">
                                            <source src="{{ asset($item->file_path) }}" type="audio/mpeg">
                                            Browser tidak mendukung pemutar audio.
                                        </audio>
                                    </td>
                                    <td class="px-5 py-4">
                                        <button wire:click="toggleActive({{ $item->id }})" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium border transition-colors {{ $item->is_active ? 'bg-emerald-50 text-emerald-700 border-emerald-200 dark:bg-emerald-900/30 dark:text-emerald-400 dark:border-emerald-800 hover:bg-emerald-100' : 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-slate-700 dark:text-slate-400 dark:border-slate-600 hover:bg-slate-200' }}">
                                            <span class="w-2 h-2 rounded-full mr-1.5 {{ $item->is_active ? 'bg-emerald-500' : 'bg-slate-400' }}"></span>
                                            {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                                        </button>
                                    </td>
                                    <td class="px-5 py-4 text-right">
                                        <button wire:click="edit({{ $item->id }})" class="p-2 text-blue-600 hover:bg-blue-50 dark:text-blue-400 dark:hover:bg-blue-900/30 rounded-lg transition-colors" title="Edit">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                        </button>
                                        <button onclick="confirm('Yakin ingin menghapus lagu ini secara permanen?') || event.stopImmediatePropagation()" wire:click="delete({{ $item->id }})" class="p-2 text-red-600 hover:bg-red-50 dark:text-red-400 dark:hover:bg-red-900/30 rounded-lg transition-colors" title="Hapus">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-5 py-10 text-center">
                                        <svg class="w-12 h-12 text-slate-300 dark:text-slate-600 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 19V6l12-3v13M9 19c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zm12-3c0 1.105-1.343 2-3 2s-3-.895-3-2 1.343-2 3-2 3 .895 3 2zM9 10l12-3"></path></svg>
                                        <p class="text-slate-500 dark:text-slate-400 font-medium pb-1">Tidak ada data musik ditemukan.</p>
                                        <p class="text-slate-400 dark:text-slate-500 text-sm">Silakan gunakan form di samping untuk mengunggah lagu.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <!-- Pagination -->
                @if($musics->hasPages())
                    <div class="border-t border-slate-200 dark:border-slate-700 px-5 py-4">
                        {{ $musics->links(data: ['scrollTo' => false]) }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
