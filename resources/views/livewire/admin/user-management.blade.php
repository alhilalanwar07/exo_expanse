<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Manajemen Pengguna</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola akun, peran, dan akses pengguna ExoInvite.</p>
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="createUser" class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors shadow-sm focus:ring-4 focus:ring-indigo-500/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                Tambah Pengguna
            </button>
        </div>
    </div>

    <!-- Papan Utama Tabel -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden flex flex-col">
        <!-- Header Tabel (Search & Filter) -->
        <div class="p-4 sm:p-6 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            
            <!-- Pencarian (Livewire Binding) -->
            <div class="relative max-w-md w-full">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cari berdasarkan nama atau email...">
                
                <!-- Loading Indicator untuk Pencarian -->
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            
            <!-- Filter Dropdown Dummy -->
            <div class="flex items-center gap-2">
                <select class="block w-full pl-3 pr-10 py-2 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm text-slate-700 dark:text-slate-300">
                    <option value="">Semua Peran</option>
                    <option value="user">User Biasa</option>
                    <option value="admin">Admin</option>
                </select>
            </div>
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Pengguna/Email
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Peran
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Tanggal Daftar
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 relative">
                    <!-- Overlay Loading Transparan -->
                    <div wire:loading.class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-800/50 backdrop-blur-[1px]" class="hidden"></div>
                    
                    @forelse($users as $user)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-indigo-100 to-blue-50 dark:from-indigo-900/30 dark:to-blue-900/20 flex items-center justify-center text-indigo-700 dark:text-indigo-400 font-bold border border-indigo-200 dark:border-indigo-800">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                    </div>
                                    <div class="ml-4">
                                        <div class="text-sm font-medium text-slate-900 dark:text-white">
                                            {{ $user->name }}
                                        </div>
                                        <div class="text-sm text-slate-500 dark:text-slate-400">
                                            {{ $user->email }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->isAdmin())
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-purple-100 text-purple-800 dark:bg-purple-900/30 dark:text-purple-400 border border-purple-200 dark:border-purple-800/50">
                                        Admin
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                        User Biasa
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-slate-500 dark:text-slate-400">
                                {{ $user->created_at->format('d M Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button wire:click="editUser({{ $user->id }})" class="inline-flex items-center px-3 py-1.5 border border-indigo-200 dark:border-indigo-800/60 rounded-lg text-xs font-semibold bg-indigo-50 text-indigo-700 hover:bg-indigo-100 dark:bg-indigo-900/30 dark:text-indigo-400 dark:hover:bg-indigo-900/50 mr-2 transition-all">
                                    Edit
                                </button>
                                <button wire:click="confirmDelete({{ $user->id }})" class="inline-flex items-center px-3 py-1.5 border border-rose-200 dark:border-rose-800/60 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-all">
                                    Hapus
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-12 text-center">
                                <div class="mx-auto w-16 h-16 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mb-4">
                                    <svg class="w-8 h-8 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                                </div>
                                <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-1">Pengguna Tidak Ditemukan</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Kami tidak dapat menemukan nama atau email pengguna yang sesuai pencarian Anda.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal Form Tambah/Edit Pengguna -->
    @if($isUserModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-lg p-4 transition-all transform scale-100 opacity-100">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                <!-- Header Modal -->
                <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                    <h3 class="text-lg font-semibold text-slate-800 dark:text-white" id="modal-title">
                        {{ $editingUserId ? 'Edit Pengguna' : 'Tambah Pengguna Baru' }}
                    </h3>
                    <button wire:click="$set('isUserModalOpen', false)" type="button" class="text-slate-400 hover:text-slate-500 dark:hover:text-slate-300 transition-colors rounded-lg p-1.5 focus:ring-2 focus:ring-slate-300 dark:focus:ring-slate-600">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
                
                <!-- Body Modal (Form) -->
                <div class="px-6 py-6">
                    <form wire:submit="saveUser" class="space-y-5">
                        <!-- Nama Lengkap -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Nama Lengkap</label>
                            <input type="text" id="name" wire:model="name" class="block w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition-colors" placeholder="Masukkan nama...">
                            @error('name') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label for="email" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Alamat Email</label>
                            <input type="email" id="email" wire:model="email" class="block w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition-colors" placeholder="nama@email.com">
                            @error('email') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Peran (Role) -->
                        <div>
                            <label for="role" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Peran Akses</label>
                            <select id="role" wire:model="role" class="block w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm shadow-sm focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition-colors">
                                <option value="user">User Biasa</option>
                                <option value="admin">Administrator</option>
                            </select>
                            @error('role') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                                Password 
                                @if($editingUserId) <span class="text-slate-400 text-xs font-normal">(Kosongkan jika tidak ingin mengubah)</span> @endif
                            </label>
                            <input type="password" id="password" wire:model="password" class="block w-full px-4 py-2.5 bg-white dark:bg-slate-900 border border-slate-300 dark:border-slate-600 rounded-xl text-sm shadow-sm placeholder-slate-400 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 dark:text-white transition-colors" placeholder="••••••••">
                            @error('password') <span class="text-xs text-rose-500 mt-1 block">{{ $message }}</span> @enderror
                        </div>

                        <!-- Aksi Form -->
                        <div class="pt-2 flex items-center justify-end gap-3">
                            <button type="button" wire:click="$set('isUserModalOpen', false)" class="px-4 py-2 text-sm font-medium text-slate-700 bg-white dark:bg-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 text-sm font-medium text-white bg-indigo-600 rounded-xl shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-500/20 transition-colors">
                                <svg wire:loading wire:target="saveUser" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Konfirmasi Hapus -->
    @if($isDeleteModalOpen)
    <div class="fixed inset-0 z-[100] flex items-center justify-center overflow-y-auto overflow-x-hidden bg-slate-900/50 dark:bg-slate-900/80 backdrop-blur-sm transition-opacity" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-sm p-4 transition-all transform scale-100 opacity-100">
            <div class="relative bg-white dark:bg-slate-800 rounded-2xl shadow-2xl border border-slate-200 dark:border-slate-700 overflow-hidden text-center p-6">
                
                <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-full bg-rose-100 dark:bg-rose-900/30 mb-5">
                    <svg class="h-7 w-7 text-rose-600 dark:text-rose-400" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2" id="modal-title">Hapus Pengguna</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Anda yakin ingin menghapus akun ini secara permanen? Seluruh data yang terkait dengan pengguna ini akan dihapus dan aksi ini tidak dapat dibatalkan.</p>
                
                <div class="flex items-center justify-center gap-3">
                    <button wire:click="$set('isDeleteModalOpen', false)" type="button" class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-slate-700 bg-white dark:bg-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteUser" type="button" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-rose-600 rounded-xl shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-500/20 transition-colors">
                        <svg wire:loading wire:target="deleteUser" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Ya, Hapus!
                    </button>
                </div>

            </div>
        </div>
    </div>
    @endif
</div>
