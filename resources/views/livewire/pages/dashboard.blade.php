<div>
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-bold text-slate-900 dark:text-white">Dashboard</h1>
            <p class="text-slate-600 dark:text-slate-400">Kelola undangan digital Anda</p>
        </div>
        <a href="{{ route('invitations.new') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-xl hover:opacity-90 transition-all transform hover:scale-105 shadow-lg shadow-rose-500/30">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Buat Undangan Baru
        </a>
    </div>

    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-100 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-rose-100 dark:bg-rose-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $invitations->count() }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">Total Undangan</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-blue-100 dark:bg-blue-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $invitations->sum('guests_count') }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">Total Tamu</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 dark:bg-green-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $invitations->sum('guests_confirmed_count') }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">Tamu Hadir</div>
                </div>
            </div>
        </div>

        <div class="bg-white dark:bg-slate-800 rounded-2xl p-6 shadow-sm border border-slate-200 dark:border-slate-700">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-amber-100 dark:bg-amber-900/30 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>
                </div>
                <div>
                    <div class="text-2xl font-bold text-slate-900 dark:text-white">{{ $invitations->sum('wishes_count') }}</div>
                    <div class="text-sm text-slate-500 dark:text-slate-400">Total Ucapan</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invitations List -->
    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-700 overflow-hidden">
        <div class="p-6 border-b border-slate-200 dark:border-slate-700">
            <h2 class="text-xl font-semibold text-slate-900 dark:text-white">Undangan Saya</h2>
        </div>

        @if($invitations->isEmpty())
            <div class="p-12 text-center">
                <div class="w-20 h-20 bg-slate-100 dark:bg-slate-700 rounded-full flex items-center justify-center mx-auto mb-4">
                    <svg class="w-10 h-10 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                </div>
                <h3 class="text-lg font-medium text-slate-900 dark:text-white mb-2">Belum ada undangan</h3>
                <p class="text-slate-500 dark:text-slate-400 mb-6">Mulai buat undangan digital pertama Anda</p>
                <a href="{{ route('invitations.new') }}" class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-medium rounded-xl hover:opacity-90 transition-all">
                    Buat Undangan
                </a>
            </div>
        @else
            <div class="divide-y divide-slate-200 dark:divide-slate-700">
                @foreach($invitations as $invitation)
                    <div class="p-6 hover:bg-slate-50 dark:hover:bg-slate-700/50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-4">
                                <div class="w-14 h-14 bg-gradient-to-br from-rose-100 to-amber-100 dark:from-rose-900/30 dark:to-amber-900/30 rounded-xl flex items-center justify-center">
                                    <span class="text-2xl">💍</span>
                                </div>
                                <div>
                                    <h3 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $invitation->title }}</h3>
                                    <div class="flex items-center gap-4 text-sm text-slate-500 dark:text-slate-400">
                                        <span>{{ $invitation->theme?->name ?? 'No theme' }}</span>
                                        <span>•</span>
                                        <span>{{ $invitation->event_date?->format('d M Y') ?? 'No date' }}</span>
                                    </div>
                                    <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="text-xs text-rose-500 hover:underline mt-1 block font-mono">
                                        {{ route('invitation.show', $invitation->slug) }}
                                    </a>
                                </div>
                            </div>
                            <div class="flex items-center gap-2">
                                <a href="{{ route('invitation.show', $invitation->slug) }}" target="_blank" class="p-2 text-slate-400 hover:text-slate-600 dark:hover:text-slate-300 transition-colors" title="Preview">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                                <a href="{{ route('invitations.sebar', $invitation->id) }}" class="px-3 py-1.5 bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-sm font-medium rounded-lg hover:shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center gap-1.5" title="Sebar Undangan">
                                    📤 Sebar
                                </a>
                                <button wire:click="openShareModal('{{ addslashes($invitation->title) }}', '{{ $invitation->slug }}')" class="p-2 text-slate-400 hover:text-green-500 transition-colors" title="Bagikan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                                    </svg>
                                </button>
                                <a href="{{ route('invitations.edit', $invitation->id) }}" class="p-2 text-slate-400 hover:text-blue-500 transition-colors" title="Edit">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <button wire:click="confirmDeletion({{ $invitation->id }}, '{{ addslashes($invitation->title) }}')" class="p-2 text-slate-400 hover:text-red-500 transition-colors" title="Hapus Undangan">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Cloud-style Delete Confirmation Modal -->
    @if($confirmingInvitationDeletion)
    <div
        class="fixed inset-0 z-[999] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
            wire:click="cancelDeletion"
        ></div>

        <!-- Modal Card -->
        <div 
            class="relative w-full max-w-sm transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left align-middle shadow-2xl transition-all border border-slate-100 dark:border-slate-800"
        >
            <div class="p-6 text-center">
                <!-- Icon -->
                <div class="bg-red-50 dark:bg-red-900/20 w-16 h-16 rounded-full flex items-center justify-center mx-auto mb-5 ring-4 ring-red-50 dark:ring-red-900/10">
                    <svg class="w-8 h-8 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                </div>

                <h3 class="text-xl font-bold text-slate-900 dark:text-white mb-2">
                    Hapus Undangan?
                </h3>
                
                <p class="text-slate-500 dark:text-slate-400 text-sm mb-6 leading-relaxed">
                    Undangan <span class="font-semibold text-slate-800 dark:text-slate-200">"{{ $invitationTitleToDelete }}"</span> akan dihapus permanen. Tindakan ini tidak dapat dibatalkan.
                </p>

                <!-- Action Buttons -->
                <div class="flex flex-col-reverse sm:flex-row gap-3">
                    <button 
                        wire:click="cancelDeletion"
                        type="button"
                        class="w-full sm:w-1/2 py-2.5 px-4 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors"
                    >
                        Batal
                    </button>
                    
                    <button 
                        wire:click="deleteInvitation"
                        type="button"
                        class="w-full sm:w-1/2 py-2.5 px-4 text-sm font-semibold text-white bg-red-500 hover:bg-red-600 rounded-xl shadow-lg shadow-red-500/20 transition-all flex items-center justify-center gap-2"
                        wire:loading.attr="disabled"
                        wire:target="deleteInvitation"
                    >
                        <span wire:loading.remove wire:target="deleteInvitation">Ya, Hapus</span>
                        <span wire:loading wire:target="deleteInvitation" class="flex items-center gap-2">
                            <svg class="animate-spin h-4 w-4" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Proses...
                        </span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Share Modal -->
    @if($showingShareModal)
    <div
        class="fixed inset-0 z-[999] flex items-center justify-center min-h-screen px-4 py-6 sm:px-0"
        role="dialog"
        aria-modal="true"
    >
        <!-- Backdrop -->
        <div 
            class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm transition-opacity" 
            wire:click="closeShareModal"
        ></div>

        <!-- Modal Card -->
        <div 
            class="relative w-full max-w-md transform overflow-hidden rounded-2xl bg-white dark:bg-slate-900 text-left align-middle shadow-2xl transition-all border border-slate-100 dark:border-slate-800"
        >
            <div class="p-6">
                <!-- Header -->
                <div class="flex items-center gap-3 mb-6">
                    <div class="bg-green-50 dark:bg-green-900/20 w-12 h-12 rounded-full flex items-center justify-center">
                        <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white">Bagikan Undangan</h3>
                        <p class="text-sm text-slate-500 dark:text-slate-400">{{ $shareInvitationTitle }}</p>
                    </div>
                </div>

                <!-- Recipient Name Input -->
                <div class="mb-4">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Nama Penerima (opsional)
                    </label>
                    <input 
                        type="text" 
                        wire:model.live="shareRecipientName"
                        placeholder="Contoh: Bapak Budi"
                        class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-white dark:bg-slate-800 text-slate-900 dark:text-white placeholder-slate-400 focus:ring-2 focus:ring-green-500 focus:border-transparent"
                    >
                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">Nama akan muncul di halaman "Kepada" undangan</p>
                </div>

                <!-- Generated Link -->
                <div class="mb-6">
                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">
                        Link Undangan
                    </label>
                    <div class="flex gap-2">
                        <input 
                            type="text" 
                            value="{{ $this->getShareUrl() }}"
                            readonly
                            class="flex-1 px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-600 bg-slate-50 dark:bg-slate-800 text-slate-600 dark:text-slate-300 text-sm"
                        >
                        <button 
                            onclick="navigator.clipboard.writeText('{{ $this->getShareUrl() }}'); this.innerHTML='✓'; setTimeout(() => this.innerHTML='📋', 1000);"
                            class="px-4 py-3 bg-slate-100 dark:bg-slate-700 hover:bg-slate-200 dark:hover:bg-slate-600 rounded-xl transition-colors text-lg"
                            title="Salin Link"
                        >
                            📋
                        </button>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="flex flex-col gap-3">
                    <a 
                        href="{{ $this->getWhatsAppUrl() }}"
                        target="_blank"
                        class="w-full py-3 px-4 text-sm font-semibold text-white bg-green-500 hover:bg-green-600 rounded-xl shadow-lg shadow-green-500/20 transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                        </svg>
                        Kirim via WhatsApp
                    </a>
                    <button 
                        wire:click="closeShareModal"
                        type="button"
                        class="w-full py-3 px-4 text-sm font-semibold text-slate-600 dark:text-slate-300 bg-slate-100 dark:bg-slate-800 hover:bg-slate-200 dark:hover:bg-slate-700 rounded-xl transition-colors"
                    >
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>