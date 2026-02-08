<div class="min-h-screen bg-gradient-to-br from-rose-50 via-white to-amber-50 dark:from-slate-900 dark:via-slate-800 dark:to-slate-900">
    <!-- Header -->
    <header class="sticky top-0 z-40 bg-white/80 dark:bg-slate-800/80 backdrop-blur-lg border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <a href="{{ route('dashboard') }}" class="text-slate-500 hover:text-slate-700 dark:text-slate-400 dark:hover:text-slate-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </a>
                <div>
                    <h1 class="font-bold text-slate-800 dark:text-white">{{ $invitationId ? 'Edit Undangan' : 'Buat Undangan Baru' }}</h1>
                    <p class="text-xs text-slate-500 dark:text-slate-400">{{ $this->title ?: 'Belum ada judul' }}</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <button wire:click="save" wire:loading.attr="disabled" class="px-4 py-2 text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-slate-700 rounded-lg transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="save">💾 Simpan Draft</span>
                    <span wire:loading wire:target="save" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"/></svg>
                        Menyimpan...
                    </span>
                </button>
                <button wire:click="publish" wire:loading.attr="disabled" class="px-5 py-2 bg-gradient-to-r from-rose-500 to-amber-500 text-white font-semibold rounded-lg hover:shadow-lg hover:shadow-rose-500/30 transition-all flex items-center gap-2">
                    <span wire:loading.remove wire:target="publish">🚀 Publish</span>
                    <span wire:loading wire:target="publish">Publishing...</span>
                </button>
            </div>
        </div>
    </header>

    <!-- Flash Messages -->
    @if(session('success'))
        <div class="max-w-7xl mx-auto px-4 mt-4">
            <div class="bg-emerald-50 dark:bg-emerald-900/30 border border-emerald-200 dark:border-emerald-800 rounded-xl p-4 flex items-center gap-3">
                <span class="text-2xl">✅</span>
                <p class="text-emerald-700 dark:text-emerald-300">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 py-6">
        <div class="grid lg:grid-cols-5 gap-6">
            
            <!-- Left: Preview Panel -->
            <div class="lg:col-span-2 order-2 lg:order-1">
                <div class="sticky top-24">
                    <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden border border-slate-200 dark:border-slate-700">
                        <div class="bg-slate-100 dark:bg-slate-700 px-4 py-2 flex items-center gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-3 h-3 rounded-full bg-red-400"></div>
                                <div class="w-3 h-3 rounded-full bg-yellow-400"></div>
                                <div class="w-3 h-3 rounded-full bg-green-400"></div>
                            </div>
                            <span class="text-xs text-slate-500 dark:text-slate-400 ml-2">Preview</span>
                        </div>
                        <div class="aspect-[9/16] bg-gradient-to-br from-rose-100 to-amber-100 dark:from-rose-900/30 dark:to-amber-900/30 flex items-center justify-center p-6">
                            <div class="text-center">
                                @if($this->cover_photo)
                                    <img src="{{ asset('storage/' . $this->cover_photo) }}" alt="Cover" class="w-32 h-32 rounded-full mx-auto mb-4 object-cover shadow-lg">
                                @else
                                    <div class="w-32 h-32 rounded-full mx-auto mb-4 bg-white/50 dark:bg-slate-700/50 flex items-center justify-center">
                                        <span class="text-5xl">💍</span>
                                    </div>
                                @endif
                                <p class="text-sm text-slate-500 dark:text-slate-400 mb-2">{{ $this->cover_subtitle ?: 'The Wedding of' }}</p>
                                <h2 class="text-2xl font-bold text-slate-800 dark:text-white">
                                    @if($this->groom_name && $this->bride_name)
                                        {{ $this->name_order === 'bride_first' ? $this->bride_name : $this->groom_name }}
                                        <span class="text-rose-500">&</span>
                                        {{ $this->name_order === 'bride_first' ? $this->groom_name : $this->bride_name }}
                                    @else
                                        {{ $this->title ?: 'Nama Mempelai' }}
                                    @endif
                                </h2>
                                @if($this->event_date)
                                    <p class="mt-4 text-slate-600 dark:text-slate-300">
                                        {{ \Carbon\Carbon::parse($this->event_date)->translatedFormat('l, d F Y') }}
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>
                    
                    @if($invitationId && $this->invitation?->is_published)
                        <a href="{{ route('invitation.show', $this->invitation->slug) }}" target="_blank" class="mt-4 block text-center py-3 bg-slate-100 dark:bg-slate-700 rounded-xl text-slate-600 dark:text-slate-300 hover:bg-slate-200 dark:hover:bg-slate-600 transition-all">
                            🔗 Lihat Undangan Live
                        </a>
                    @endif
                </div>
            </div>

            <!-- Right: Form Panel -->
            <div class="lg:col-span-3 order-1 lg:order-2">
                <!-- Tab Navigation -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 mb-6 overflow-hidden">
                    <div class="flex overflow-x-auto scrollbar-hide">
                        @foreach($tabs as $key => $tabInfo)
                            <button 
                                wire:click="setTab('{{ $key }}')"
                                @class([
                                    'flex-1 min-w-[100px] px-4 py-4 text-center transition-all border-b-2 whitespace-nowrap',
                                    'border-rose-500 text-rose-600 dark:text-rose-400 bg-rose-50 dark:bg-rose-900/20' => $tab === $key,
                                    'border-transparent text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/50' => $tab !== $key,
                                ])
                            >
                                <span class="text-xl block mb-1">{{ $tabInfo['icon'] }}</span>
                                <span class="text-sm font-medium">{{ $tabInfo['label'] }}</span>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Tab Content -->
                <div class="bg-white dark:bg-slate-800 rounded-2xl shadow-lg border border-slate-200 dark:border-slate-700 p-6">
                    
                    {{-- TAB: COVER --}}
                    @if($tab === 'cover')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    🎨 Data Cover
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Atur tampilan cover undangan</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Judul Undangan *</label>
                                <input wire:model.blur="title" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Pernikahan Andi & Rina">
                                @error('title') <span class="text-rose-500 text-sm mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Kecil Atas</label>
                                    <input wire:model.blur="cover_subtitle" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="The Wedding of">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Teks Judul Cover</label>
                                    <input wire:model.blur="cover_title" type="text" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all" placeholder="Andi & Rina">
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Foto Cover</label>
                                @if($this->cover_photo)
                                    <div class="relative inline-block">
                                        <img src="{{ asset('storage/' . $this->cover_photo) }}" alt="Cover" class="w-40 h-40 object-cover rounded-xl">
                                        <button wire:click="removeCoverPhoto" type="button" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full flex items-center justify-center shadow-lg hover:bg-red-600">
                                            ✕
                                        </button>
                                    </div>
                                @else
                                    <label class="block w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center cursor-pointer hover:border-rose-400 transition-all">
                                        <input type="file" wire:model="coverPhotoUpload" accept="image/*" class="hidden">
                                        <div class="text-4xl mb-2">📷</div>
                                        <p class="text-slate-500 dark:text-slate-400">Klik untuk upload foto cover</p>
                                        <p class="text-xs text-slate-400 mt-1">Max 5MB (JPG, PNG)</p>
                                    </label>
                                @endif
                                <div wire:loading wire:target="coverPhotoUpload" class="mt-2 text-rose-500">Mengupload...</div>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: MEMPELAI --}}
                    @if($tab === 'mempelai')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    💑 Data Mempelai
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Informasi kedua mempelai</p>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Urutan Nama</label>
                                <select wire:model.live="name_order" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500 transition-all">
                                    <option value="groom_first">🤵 Pria & 👰 Wanita</option>
                                    <option value="bride_first">👰 Wanita & 🤵 Pria</option>
                                </select>
                            </div>

                            <!-- Mempelai Pria -->
                            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-blue-800 dark:text-blue-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-blue-500 rounded-lg flex items-center justify-center text-white">🤵</span>
                                    Mempelai Pria
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Panggilan *</label>
                                        <input wire:model.blur="groom_nickname" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500" placeholder="Andi">
                                        @error('groom_nickname') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Lengkap</label>
                                        <input wire:model.blur="groom_name" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Andi Prasetyo, S.Kom">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Ayah Pria</label>
                                        <input wire:model.blur="groom_father" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Bpk. Budiman">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Ibu Pria</label>
                                        <input wire:model.blur="groom_mother" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Ibu Siti">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Instagram</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-slate-400">@</span>
                                            <input wire:model.blur="groom_instagram" type="text" class="w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="username">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Mempelai Wanita -->
                            <div class="bg-pink-50 dark:bg-pink-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-pink-800 dark:text-pink-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-pink-500 rounded-lg flex items-center justify-center text-white">👰</span>
                                    Mempelai Wanita
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Panggilan *</label>
                                        <input wire:model.blur="bride_nickname" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 focus:ring-2 focus:ring-pink-500/20 focus:border-pink-500" placeholder="Rina">
                                        @error('bride_nickname') <span class="text-rose-500 text-xs">{{ $message }}</span> @enderror
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Lengkap</label>
                                        <input wire:model.blur="bride_name" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Rina Wulandari, S.Pd">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Ayah Wanita</label>
                                        <input wire:model.blur="bride_father" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Bpk. Ahmad">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Ibu Wanita</label>
                                        <input wire:model.blur="bride_mother" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Ibu Dewi">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Instagram</label>
                                        <div class="relative">
                                            <span class="absolute left-3 top-2.5 text-slate-400">@</span>
                                            <input wire:model.blur="bride_instagram" type="text" class="w-full pl-8 pr-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="username">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: ACARA --}}
                    @if($tab === 'acara')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    📅 Data Acara
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Detail waktu dan tempat acara</p>
                            </div>

                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Tanggal Utama *</label>
                                    <input wire:model.blur="event_date" type="date" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                    @error('event_date') <span class="text-rose-500 text-sm">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Jenis Acara</label>
                                    <select wire:model.live="event_type" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 focus:ring-2 focus:ring-rose-500/20 focus:border-rose-500">
                                        <option value="both">💒 Akad & 🍽️ Resepsi</option>
                                        <option value="akad_only">💒 Akad Saja</option>
                                        <option value="resepsi_only">🍽️ Resepsi Saja</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Akad -->
                            @if(in_array($this->event_type, ['akad_only', 'both']))
                            <div class="bg-amber-50 dark:bg-amber-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-amber-800 dark:text-amber-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-amber-500 rounded-lg flex items-center justify-center text-white">💒</span>
                                    Akad Nikah
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Tanggal</label>
                                        <input wire:model.blur="akad_date" type="date" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Waktu</label>
                                        <input wire:model.blur="akad_time" type="time" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Tempat</label>
                                        <input wire:model.blur="akad_venue" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Masjid Al-Ikhlas">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
                                        <input wire:model.blur="akad_address" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Jl. Merdeka No. 123">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Resepsi -->
                            @if(in_array($this->event_type, ['resepsi_only', 'both']))
                            <div class="bg-emerald-50 dark:bg-emerald-900/20 rounded-xl p-5 space-y-4">
                                <h3 class="font-bold text-emerald-800 dark:text-emerald-200 flex items-center gap-2">
                                    <span class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center text-white">🍽️</span>
                                    Resepsi
                                </h3>
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Tanggal</label>
                                        <input wire:model.blur="resepsi_date" type="date" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Waktu</label>
                                        <input wire:model.blur="resepsi_time" type="time" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Nama Tempat</label>
                                        <input wire:model.blur="resepsi_venue" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Gedung Serbaguna">
                                    </div>
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">Alamat</label>
                                        <input wire:model.blur="resepsi_address" type="text" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="Jl. Mawar No. 456">
                                    </div>
                                </div>
                            </div>
                            @endif

                            <!-- Maps -->
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Maps Akad</label>
                                    <input wire:model.blur="akad_maps_link" type="url" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700" placeholder="https://maps.google.com/...">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Maps Resepsi</label>
                                    <input wire:model.blur="resepsi_maps_link" type="url" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700" placeholder="https://maps.google.com/...">
                                </div>
                            </div>
                            <p class="text-xs text-slate-400 mt-1">Tamu bisa langsung buka lokasi di Maps</p>

                            <!-- Love Story -->
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-6 mt-6">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2 mb-4">
                                    📖 Kisah Cinta
                                </h4>
                                
                                <div class="space-y-4">
                                    @foreach($this->love_story as $index => $story)
                                        <div class="bg-slate-50 dark:bg-slate-700/50 p-4 rounded-xl space-y-3 relative group border border-slate-200 dark:border-slate-600">
                                            <button wire:click="removeLoveStory({{ $index }})" type="button" class="absolute top-2 right-2 text-rose-500 hover:text-rose-600 opacity-50 group-hover:opacity-100 transition-opacity">✕</button>
                                            
                                            <div class="grid grid-cols-4 gap-3">
                                                <div class="col-span-1">
                                                    <label class="block text-xs text-slate-500 mb-1">Waktu</label>
                                                    <input wire:model.blur="love_story.{{ $index }}.date" type="text" placeholder="2020" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm">
                                                </div>
                                                <div class="col-span-3">
                                                    <label class="block text-xs text-slate-500 mb-1">Judul Momen</label>
                                                    <input wire:model.blur="love_story.{{ $index }}.title" type="text" placeholder="Pertama Bertemu" class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm font-medium">
                                                </div>
                                            </div>
                                            <div>
                                                <label class="block text-xs text-slate-500 mb-1">Cerita Singkat</label>
                                                <textarea wire:model.blur="love_story.{{ $index }}.description" rows="2" placeholder="Ceritakan sedikit tentang momen ini..." class="w-full px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm resize-none"></textarea>
                                            </div>
                                        </div>
                                    @endforeach
                                    
                                    <button wire:click="addLoveStory" type="button" class="text-rose-500 font-medium text-sm hover:text-rose-600 flex items-center gap-1">
                                        <span>+ Tambah Cerita</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- TAB: GALLERY --}}
                    @if($tab === 'gallery')
                        <div class="space-y-8">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    📸 Foto & Galeri
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Upload foto mempelai dan galeri pernikahan</p>
                            </div>

                            {{-- Foto Mempelai --}}
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Mempelai Pria --}}
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-2xl p-6">
                                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                                        🤵 Foto Mempelai Pria
                                    </h4>
                                    <div class="flex flex-col items-center">
                                        @if($this->groom_photo)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $this->groom_photo) }}" alt="Groom" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                                <button wire:click="removeGroomPhoto" type="button" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-sm">✕</button>
                                            </div>
                                        @else
                                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-blue-500 to-blue-600 flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                                {{ strtoupper(substr($this->groom_nickname ?: 'P', 0, 1)) }}
                                            </div>
                                        @endif
                                        <p class="mt-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $this->groom_nickname ?: 'Pria' }}</p>
                                        <label class="mt-4 px-4 py-2 bg-white dark:bg-slate-600 rounded-lg text-sm text-slate-600 dark:text-slate-300 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-500 transition-all border border-slate-200 dark:border-slate-500">
                                            <input type="file" wire:model="groomPhotoUpload" accept="image/*" class="hidden">
                                            📷 {{ $this->groom_photo ? 'Ganti Foto' : 'Upload Foto' }}
                                        </label>
                                        <div wire:loading wire:target="groomPhotoUpload" class="mt-2 text-xs text-rose-500">Mengupload...</div>
                                    </div>
                                </div>

                                {{-- Mempelai Wanita --}}
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-2xl p-6">
                                    <h4 class="text-sm font-semibold text-slate-700 dark:text-slate-300 mb-4 flex items-center gap-2">
                                        👰 Foto Mempelai Wanita
                                    </h4>
                                    <div class="flex flex-col items-center">
                                        @if($this->bride_photo)
                                            <div class="relative group">
                                                <img src="{{ asset('storage/' . $this->bride_photo) }}" alt="Bride" class="w-32 h-32 rounded-full object-cover border-4 border-white shadow-lg">
                                                <button wire:click="removeBridePhoto" type="button" class="absolute -top-2 -right-2 w-8 h-8 bg-red-500 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-sm">✕</button>
                                            </div>
                                        @else
                                            <div class="w-32 h-32 rounded-full bg-gradient-to-br from-rose-500 to-pink-500 flex items-center justify-center text-white text-5xl font-bold shadow-lg">
                                                {{ strtoupper(substr($this->bride_nickname ?: 'W', 0, 1)) }}
                                            </div>
                                        @endif
                                        <p class="mt-3 text-sm font-medium text-slate-700 dark:text-slate-300">{{ $this->bride_nickname ?: 'Wanita' }}</p>
                                        <label class="mt-4 px-4 py-2 bg-white dark:bg-slate-600 rounded-lg text-sm text-slate-600 dark:text-slate-300 cursor-pointer hover:bg-slate-100 dark:hover:bg-slate-500 transition-all border border-slate-200 dark:border-slate-500">
                                            <input type="file" wire:model="bridePhotoUpload" accept="image/*" class="hidden">
                                            📷 {{ $this->bride_photo ? 'Ganti Foto' : 'Upload Foto' }}
                                        </label>
                                        <div wire:loading wire:target="bridePhotoUpload" class="mt-2 text-xs text-rose-500">Mengupload...</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Galeri Foto --}}
                            <div class="border-t border-slate-200 dark:border-slate-700 pt-6">
                                <h3 class="text-lg font-semibold text-slate-800 dark:text-white mb-4 flex items-center gap-2">
                                    🖼️ Galeri Foto
                                </h3>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mb-4">Upload foto-foto prewedding atau momen spesial lainnya</p>

                                <!-- Existing Photos -->
                                @if(count($existingPhotos) > 0)
                                    <div class="mb-6">
                                        <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">Foto Tersimpan</h4>
                                        <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                            @foreach($existingPhotos as $photo)
                                                <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700">
                                                    <img src="{{ $photo['url'] }}" alt="Photo" class="w-full h-full object-cover">
                                                    <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                        <button wire:click="removeExistingPhoto({{ $photo['id'] }})" type="button" class="w-10 h-10 bg-red-500 text-white rounded-full">✕</button>
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                <!-- Upload New -->
                                <div>
                                    <label class="block w-full border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl p-8 text-center cursor-pointer hover:border-rose-400 transition-all">
                                        <input type="file" wire:model="photos" accept="image/*" multiple class="hidden">
                                        <div class="text-5xl mb-3">📷</div>
                                        <p class="text-slate-600 dark:text-slate-300 font-medium">Klik untuk upload foto galeri</p>
                                        <p class="text-xs text-slate-400 mt-1">Bisa pilih beberapa foto sekaligus (max 5MB per foto)</p>
                                    </label>
                                    <div wire:loading wire:target="photos" class="mt-3 text-center text-rose-500">
                                        <svg class="animate-spin w-6 h-6 mx-auto" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                                        <p class="mt-2">Mengupload...</p>
                                    </div>
                                </div>

                                <!-- New Photo Previews -->
                                @if(count($photos) > 0)
                                    <div class="mt-6">
                                        <h4 class="text-sm font-medium text-slate-600 dark:text-slate-400 mb-3">Foto Baru (belum disimpan)</h4>
                                        <div class="grid grid-cols-3 md:grid-cols-4 gap-3">
                                            @foreach($photos as $index => $photo)
                                                @if($photo)
                                                    <div class="relative group aspect-square rounded-xl overflow-hidden bg-slate-100 dark:bg-slate-700 ring-2 ring-rose-500">
                                                        <img src="{{ $photo->temporaryUrl() }}" alt="Preview" class="w-full h-full object-cover">
                                                        <span class="absolute top-2 left-2 px-2 py-1 bg-rose-500 text-white text-xs rounded-full">Baru</span>
                                                        <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center">
                                                            <button wire:click="removePhoto({{ $index }})" type="button" class="w-10 h-10 bg-red-500 text-white rounded-full">✕</button>
                                                        </div>
                                                    </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @endif

                    {{-- TAB: TAMU --}}
                    @if($tab === 'tamu')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    👥 Daftar Tamu
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Kelola tamu undangan dan kirim link personal via WhatsApp</p>
                            </div>

                            @if(!$invitationId)
                                <div class="bg-amber-50 dark:bg-amber-900/20 border border-amber-200 dark:border-amber-800 rounded-xl p-4 flex items-center gap-3">
                                    <span class="text-2xl">⚠️</span>
                                    <p class="text-amber-700 dark:text-amber-300">Simpan undangan terlebih dahulu untuk menambahkan tamu.</p>
                                </div>
                            @else
                                <!-- Quick Add via Text -->
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5">
                                    <h4 class="font-medium text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        ✏️ Tambah Cepat
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Pisahkan nama tamu dengan koma atau enter</p>
                                    <textarea 
                                        wire:model="guestInput" 
                                        rows="3" 
                                        class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 resize-none text-slate-800 dark:text-white"
                                        placeholder="Bapak Budi, Ibu Siti, Pak Ahmad, Bu Rina"
                                    ></textarea>
                                    <button 
                                        wire:click="addGuestsFromText"
                                        wire:loading.attr="disabled"
                                        class="mt-3 px-5 py-2.5 bg-rose-500 text-white font-medium rounded-lg hover:bg-rose-600 transition-all flex items-center gap-2"
                                    >
                                        <span wire:loading.remove wire:target="addGuestsFromText">+ Tambah Tamu</span>
                                        <span wire:loading wire:target="addGuestsFromText">Menambahkan...</span>
                                    </button>
                                </div>

                                <!-- Upload CSV/Excel -->
                                <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-5">
                                    <h4 class="font-medium text-slate-700 dark:text-slate-200 mb-3 flex items-center gap-2">
                                        📁 Import dari File
                                    </h4>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 mb-3">Upload file CSV dengan kolom: nama, telepon (opsional)</p>
                                    
                                    <div class="flex gap-3 items-end">
                                        <div class="flex-1">
                                            <input 
                                                type="file" 
                                                wire:model="guestFile"
                                                accept=".csv,.txt,.xlsx,.xls"
                                                class="w-full px-4 py-2.5 border-2 border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm"
                                            >
                                        </div>
                                        <button 
                                            wire:click="importGuestsFromFile"
                                            wire:loading.attr="disabled"
                                            class="px-5 py-2.5 bg-emerald-500 text-white font-medium rounded-lg hover:bg-emerald-600 transition-all"
                                        >
                                            <span wire:loading.remove wire:target="importGuestsFromFile">📥 Import</span>
                                            <span wire:loading wire:target="importGuestsFromFile">Importing...</span>
                                        </button>
                                    </div>
                                    @error('guestFile') <span class="text-rose-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                                </div>

                                <!-- Guest List -->
                                @if(count($guests) > 0)
                                    <div class="bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 overflow-hidden">
                                        <div class="px-5 py-4 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                                            <h4 class="font-medium text-slate-700 dark:text-slate-200">
                                                Daftar Tamu ({{ count($guests) }})
                                            </h4>
                                        </div>
                                        
                                        <div class="overflow-x-auto">
                                            <table class="w-full text-sm">
                                                <thead class="bg-slate-50 dark:bg-slate-700/50">
                                                    <tr>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">#</th>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">Nama</th>
                                                        <th class="px-4 py-3 text-left font-medium text-slate-600 dark:text-slate-300">Status</th>
                                                        <th class="px-4 py-3 text-center font-medium text-slate-600 dark:text-slate-300">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="divide-y divide-slate-100 dark:divide-slate-700">
                                                    @foreach($guests as $index => $guest)
                                                    <tr class="hover:bg-slate-50 dark:hover:bg-slate-700/30">
                                                        <td class="px-4 py-3 text-slate-500">{{ $index + 1 }}</td>
                                                        <td class="px-4 py-3 font-medium text-slate-800 dark:text-white">{{ $guest['name'] }}</td>
                                                        <td class="px-4 py-3">
                                                            @if($guest['status'] === 'confirmed')
                                                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-medium">✓ Hadir</span>
                                                            @elseif($guest['status'] === 'declined')
                                                                <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">✗ Tidak Hadir</span>
                                                            @else
                                                                <span class="px-2 py-1 bg-slate-100 text-slate-600 rounded-full text-xs font-medium">Pending</span>
                                                            @endif
                                                        </td>
                                                        <td class="px-4 py-3">
                                                            <div class="flex items-center justify-center gap-2">
                                                                <!-- Copy Link -->
                                                                <button 
                                                                    onclick="navigator.clipboard.writeText('{{ $this->getGuestShareUrl($guest['name']) }}'); this.innerHTML='✓'; setTimeout(() => this.innerHTML='🔗', 1000);"
                                                                    class="w-8 h-8 bg-slate-100 dark:bg-slate-600 rounded-lg flex items-center justify-center hover:bg-slate-200 dark:hover:bg-slate-500 transition-colors"
                                                                    title="Salin Link"
                                                                >🔗</button>
                                                                
                                                                <!-- WhatsApp -->
                                                                <a 
                                                                    href="{{ $this->getGuestWhatsAppUrl($guest['name'], $guest['phone_number'] ?? null) }}"
                                                                    target="_blank"
                                                                    class="w-8 h-8 bg-green-100 dark:bg-green-900/30 rounded-lg flex items-center justify-center hover:bg-green-200 dark:hover:bg-green-800/50 transition-colors"
                                                                    title="Kirim WhatsApp"
                                                                >📱</a>
                                                                
                                                                <!-- Delete -->
                                                                <button 
                                                                    wire:click="deleteGuest({{ $guest['id'] }})"
                                                                    wire:confirm="Hapus tamu '{{ $guest['name'] }}'?"
                                                                    class="w-8 h-8 bg-red-100 dark:bg-red-900/30 rounded-lg flex items-center justify-center hover:bg-red-200 dark:hover:bg-red-800/50 transition-colors"
                                                                    title="Hapus"
                                                                >🗑️</button>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @else
                                    <div class="bg-slate-50 dark:bg-slate-700/50 rounded-xl p-8 text-center">
                                        <div class="w-16 h-16 bg-slate-200 dark:bg-slate-600 rounded-full flex items-center justify-center mx-auto mb-4">
                                            <span class="text-3xl">👥</span>
                                        </div>
                                        <p class="text-slate-500 dark:text-slate-400">Belum ada tamu ditambahkan</p>
                                        <p class="text-slate-400 dark:text-slate-500 text-sm mt-1">Tambahkan tamu menggunakan form di atas</p>
                                    </div>
                                @endif
                            @endif
                        </div>
                    @endif

                    {{-- TAB: SETTINGS --}}
                    @if($tab === 'settings')
                        <div class="space-y-6">
                            <div class="border-b border-slate-200 dark:border-slate-700 pb-4 mb-6">
                                <h2 class="text-xl font-bold text-slate-800 dark:text-white flex items-center gap-2">
                                    ⚙️ Pengaturan
                                </h2>
                                <p class="text-slate-500 dark:text-slate-400 text-sm mt-1">Tema, musik, dan fitur lainnya</p>
                            </div>

                            <!-- Theme Selection -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-3">Pilih Tema *</label>
                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                    @foreach($this->themes as $theme)
                                        <label class="relative cursor-pointer group">
                                            <input type="radio" wire:model.live="theme_id" value="{{ $theme->id }}" class="sr-only peer">
                                            <div class="rounded-xl overflow-hidden border-2 transition-all peer-checked:border-rose-500 peer-checked:ring-2 peer-checked:ring-rose-500/30 border-slate-200 dark:border-slate-700 hover:border-rose-300">
                                                {{-- Theme Preview with Colors --}}
                                                <div 
                                                    class="aspect-video relative flex items-center justify-center"
                                                    style="background: linear-gradient(135deg, {{ $theme->background_color ?? '#f8fafc' }} 0%, {{ $theme->secondary_color ?? '#e2e8f0' }} 100%);"
                                                >
                                                    {{-- Color Swatches --}}
                                                    <div class="absolute bottom-2 left-2 flex gap-1">
                                                        <div class="w-4 h-4 rounded-full border border-white/50 shadow-sm" style="background: {{ $theme->primary_color ?? '#d97706' }}" title="Primary"></div>
                                                        <div class="w-4 h-4 rounded-full border border-white/50 shadow-sm" style="background: {{ $theme->accent_color ?? '#f59e0b' }}" title="Accent"></div>
                                                        <div class="w-4 h-4 rounded-full border border-white/50 shadow-sm" style="background: {{ $theme->heading_color ?? '#1f2937' }}" title="Heading"></div>
                                                    </div>
                                                    
                                                    {{-- Premium Badge --}}
                                                    @if($theme->is_premium)
                                                        <div class="absolute top-2 left-2 px-2 py-0.5 bg-gradient-to-r from-amber-500 to-yellow-500 text-white text-xs font-bold rounded-full shadow">
                                                            ⭐ Premium
                                                        </div>
                                                    @endif
                                                    
                                                    {{-- Theme Icon/Preview --}}
                                                    <div class="text-center">
                                                        <span class="text-4xl drop-shadow-lg">
                                                            @switch($theme->slug)
                                                                @case('royal-gold')
                                                                    👑
                                                                    @break
                                                                @case('floral-romance')
                                                                    🌸
                                                                    @break
                                                                @case('modern-elegance')
                                                                    ✨
                                                                    @break
                                                                @case('sage-garden')
                                                                    🌿
                                                                    @break
                                                                @case('midnight-dark')
                                                                    🌙
                                                                    @break
                                                                @default
                                                                    💍
                                                            @endswitch
                                                        </span>
                                                    </div>
                                                </div>
                                                
                                                {{-- Theme Info --}}
                                                <div class="p-3 bg-white dark:bg-slate-800">
                                                    <h4 class="font-medium text-slate-800 dark:text-white text-sm">{{ $theme->name }}</h4>
                                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">
                                                        {{ $theme->heading_font ?? 'Default' }}
                                                    </p>
                                                </div>
                                            </div>
                                            
                                            {{-- Selected Checkmark --}}
                                            <div class="absolute top-2 right-2 w-6 h-6 rounded-full bg-rose-500 text-white flex items-center justify-center opacity-0 peer-checked:opacity-100 transition-opacity">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                        </label>
                                    @endforeach
                                </div>
                                @error('theme_id') <span class="text-rose-500 text-sm mt-2 block">{{ $message }}</span> @enderror
                            </div>

                            <!-- Personalization -->
                            <div class="space-y-4 border-t border-slate-200 dark:border-slate-700 pt-6">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300 flex items-center gap-2">
                                    🖌️ Personalisasi Tampilan
                                </h4>
                                
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-2">Warna Utama</label>
                                        <div class="flex items-center gap-3">
                                            <input 
                                                wire:model.live="settings.primary_color" 
                                                type="color" 
                                                class="h-10 w-20 p-1 rounded cursor-pointer bg-white dark:bg-slate-700 border border-slate-300 dark:border-slate-600"
                                            >
                                            <span class="text-sm font-mono text-slate-500 bg-slate-100 dark:bg-slate-800 px-2 py-1 rounded">
                                                {{ $this->settings['primary_color'] ?? '#d97706' }}
                                            </span>
                                        </div>
                                    </div>
                                    
                                    {{-- Future: Font Selection --}}
                                </div>
                            </div>

                            <!-- Feature Toggles -->
                            <div class="space-y-4">
                                <h4 class="font-medium text-slate-700 dark:text-slate-300">Fitur</h4>
                                
                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">⏰</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Countdown Timer</p>
                                            <p class="text-xs text-slate-500">Tampilkan hitung mundur ke hari H</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="countdown_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">💬</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">RSVP & Ucapan</p>
                                            <p class="text-xs text-slate-500">Tamu bisa konfirmasi kehadiran & kirim ucapan</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="rsvp_wishes_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">📸</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Galeri Foto</p>
                                            <p class="text-xs text-slate-500">Tampilkan foto-foto di undangan</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="gallery_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🎵</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Background Music</p>
                                            <p class="text-xs text-slate-500">Putar musik di undangan</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="music_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                @if($this->music_enabled)
                                    <div class="ml-12">
                                        <label class="block text-sm text-slate-600 dark:text-slate-400 mb-1">URL Musik (MP3)</label>
                                        <input wire:model.blur="music_url" type="url" class="w-full px-4 py-2.5 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700" placeholder="https://example.com/music.mp3">
                                    </div>
                                @endif

                                <label class="flex items-center justify-between p-4 bg-slate-50 dark:bg-slate-700/50 rounded-xl cursor-pointer">
                                    <div class="flex items-center gap-3">
                                        <span class="text-2xl">🎁</span>
                                        <div>
                                            <p class="font-medium text-slate-700 dark:text-slate-200">Gift / Angpao Digital</p>
                                            <p class="text-xs text-slate-500">Tampilkan rekening untuk transfer</p>
                                        </div>
                                    </div>
                                    <input type="checkbox" wire:model.live="gift_enabled" class="w-5 h-5 rounded text-rose-500 focus:ring-rose-500">
                                </label>

                                @if($this->gift_enabled)
                                    <div class="ml-12 space-y-3">
                                        @foreach($this->bank_accounts as $index => $account)
                                            <div class="flex gap-2 items-start">
                                                <div class="flex-1 grid grid-cols-3 gap-2">
                                                    <input wire:model.blur="bank_accounts.{{ $index }}.bank" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="Bank">
                                                    <input wire:model.blur="bank_accounts.{{ $index }}.account_number" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="No. Rekening">
                                                    <input wire:model.blur="bank_accounts.{{ $index }}.account_name" type="text" class="px-3 py-2 border border-slate-300 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-700 text-sm" placeholder="Atas Nama">
                                                </div>
                                                <button wire:click="removeGiftAccount({{ $index }})" type="button" class="w-9 h-9 bg-red-100 text-red-500 rounded-lg hover:bg-red-200">✕</button>
                                            </div>
                                        @endforeach
                                        <button wire:click="addGiftAccount" type="button" class="text-rose-500 text-sm font-medium hover:text-rose-600">+ Tambah Rekening</button>
                                    </div>
                                @endif
                            </div>

                            <!-- Quote -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-2">Ayat / Kutipan</label>
                                <textarea wire:model.blur="quran_verse" rows="3" class="w-full px-4 py-3 border-2 border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-700 resize-none" placeholder="QS. Ar-Rum: 21"></textarea>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
