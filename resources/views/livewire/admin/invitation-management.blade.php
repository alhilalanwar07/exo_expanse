<div>
    <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">Daftar Undangan</h1>
            <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">Kelola dan pantau semua undangan yang telah dibuat pengguna.</p>
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
                <input wire:model.live.debounce.300ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-slate-200 dark:border-slate-700 rounded-xl leading-5 bg-white dark:bg-slate-900 placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-colors text-slate-800 dark:text-slate-200" placeholder="Cari undangan atau nama klien...">
                
                <!-- Loading Indicator untuk Pencarian -->
                <div wire:loading wire:target="search" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                    <svg class="w-4 h-4 text-indigo-500 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </div>
            </div>
            
        </div>

        <!-- Tabel Data -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 dark:divide-slate-700">
                <thead class="bg-slate-50 dark:bg-slate-800">
                    <tr>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Rincian Undangan
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Pemilik/Klien
                        </th>
                        <th scope="col" class="px-6 py-4 text-left text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Tema Custom
                        </th>
                        <th scope="col" class="px-6 py-4 text-right text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider">
                            Aksi
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-slate-800 divide-y divide-slate-200 dark:divide-slate-700 relative">
                    <!-- Overlay Loading Transparan -->
                    <div wire:loading.class="absolute inset-0 z-10 bg-white/50 dark:bg-slate-800/50 backdrop-blur-[1px]" class="hidden"></div>
                    
                    @forelse($invitations as $invitation)
                        <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-10 w-10">
                                        <div class="h-10 w-10 rounded-full bg-gradient-to-tr from-emerald-100 to-teal-50 dark:from-emerald-900/30 dark:to-teal-900/20 flex items-center justify-center text-emerald-700 dark:text-emerald-400 font-bold border border-emerald-200 dark:border-emerald-800">
                                            <svg class="w-5 h-5 opacity-75" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 19v-8.93a2 2 0 01.89-1.664l7-4.666a2 2 0 012.22 0l7 4.666A2 2 0 0121 10.07V19M3 19a2 2 0 002 2h14a2 2 0 002-2M3 19l6.75-4.5M21 19l-6.75-4.5M3 10l6.75 4.5M21 10l-6.75 4.5m0 0l-1.14.76a2 2 0 01-2.22 0l-1.14-.76"/></svg>
                                        </div>
                                    </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-slate-900 dark:text-white max-w-[200px] truncate" title="{{ $invitation->title }}">
                                                {{ $invitation->title ?? 'Tanpa Judul' }}
                                            </div>
                                            <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="text-sm text-indigo-500 hover:text-indigo-600 dark:text-indigo-400 transition-colors hover:underline">
                                                {{ url('/i/' . $invitation->slug) }}
                                            </a>
                                        </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-slate-900 dark:text-white">{{ $invitation->user->name ?? 'Pengguna Dihapus' }}</div>
                                <div class="text-xs text-slate-500 dark:text-slate-400">{{ $invitation->created_at->format('d M Y - H:i') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-md text-xs font-medium bg-slate-100 text-slate-800 dark:bg-slate-700 dark:text-slate-300 border border-slate-200 dark:border-slate-600">
                                    {{ $invitation->theme->name ?? 'Bawaan' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="inline-flex items-center px-3 py-1.5 border border-slate-200 dark:border-slate-700 rounded-lg text-xs font-semibold bg-white text-slate-700 hover:bg-slate-50 dark:bg-slate-800 dark:text-slate-300 dark:hover:bg-slate-700 mr-2 transition-all">
                                    Lihat
                                </a>
                                <button wire:click="confirmDelete({{ $invitation->id }})" class="inline-flex items-center px-3 py-1.5 border border-rose-200 dark:border-rose-800/60 rounded-lg text-xs font-semibold bg-rose-50 text-rose-700 hover:bg-rose-100 dark:bg-rose-900/30 dark:text-rose-400 dark:hover:bg-rose-900/50 transition-all">
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
                                <h3 class="text-sm font-medium text-slate-900 dark:text-white mb-1">Undangan Tidak Ditemukan</h3>
                                <p class="text-sm text-slate-500 dark:text-slate-400">Belum ada undangan yang dibuat atau tidak ada yang sesuai pencarian.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Paginasi -->
        @if($invitations->hasPages())
            <div class="px-6 py-4 border-t border-slate-200 dark:border-slate-700 bg-slate-50/50 dark:bg-slate-800/50">
                {{ $invitations->links() }}
            </div>
        @endif
    </div>

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
                
                <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-2" id="modal-title">Hapus Undangan</h3>
                <p class="text-sm text-slate-500 dark:text-slate-400 mb-6">Anda yakin ingin menghapus undangan ini? Akses ke URL undangan akan dihentikan dan data tamu akan lenyap.</p>
                
                <div class="flex items-center justify-center gap-3">
                    <button wire:click="$set('isDeleteModalOpen', false)" type="button" class="w-full sm:w-auto px-4 py-2.5 text-sm font-medium text-slate-700 bg-white dark:bg-slate-800 dark:text-slate-300 border border-slate-300 dark:border-slate-600 rounded-xl shadow-sm hover:bg-slate-50 dark:hover:bg-slate-700 focus:outline-none focus:ring-2 focus:ring-slate-500/50 transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteInvitation" type="button" class="w-full sm:w-auto inline-flex justify-center items-center gap-2 px-4 py-2.5 text-sm font-medium text-white bg-rose-600 rounded-xl shadow-sm hover:bg-rose-700 focus:outline-none focus:ring-4 focus:ring-rose-500/20 transition-colors">
                        <svg wire:loading wire:target="deleteInvitation" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
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
