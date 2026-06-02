<div x-data="{
    toast: { show: false, message: '', type: 'success' },
    activeTab: @entangle('activeTab'),
    showImportModal: @entangle('showImportModal'),
}" x-on:toast.window="toast.message = $event.detail.message; toast.type = $event.detail.type; toast.show = true; setTimeout(() => toast.show = false, 3000)">

    <x-toast-notification />

    {{-- Header --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div>
            <nav class="flex items-center text-sm text-slate-500 dark:text-slate-400 mb-2">
                <a href="{{ route('admin.themes') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Kelola Tema</a>
                <svg class="w-4 h-4 mx-2 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                <span class="text-slate-800 dark:text-white font-medium">{{ $themeId ? 'Edit Tema' : 'Buat Tema Baru' }}</span>
            </nav>
            <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
                {{ $themeId ? 'Edit: ' . $name : 'Theme Builder' }}
            </h1>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.themes') }}" class="inline-flex items-center gap-2 px-4 py-2.5 text-sm font-medium text-slate-600 dark:text-slate-300 bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-700 rounded-xl hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Kembali
            </a>
            <button wire:click="save" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl shadow-sm transition-colors focus:ring-4 focus:ring-indigo-500/20 disabled:opacity-50">
                <span wire:loading.remove wire:target="save, saveAndClose">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </span>
                <svg wire:loading wire:target="save, saveAndClose" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                Simpan
            </button>
            <button wire:click="saveAndClose" wire:loading.attr="disabled" class="inline-flex items-center gap-2 px-5 py-2.5 text-sm font-semibold text-white bg-emerald-600 hover:bg-emerald-700 rounded-xl shadow-sm transition-colors focus:ring-4 focus:ring-emerald-500/20 disabled:opacity-50">
                Simpan & Tutup
            </button>
        </div>
    </div>

    {{-- Main Layout: Tabs + Preview --}}
    <div class="flex flex-col xl:flex-row gap-6">

        {{-- LEFT: Form Panel --}}
        <div class="flex-1 min-w-0">
            {{-- Tab Navigation --}}
            <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden mb-6">
                <div class="flex overflow-x-auto border-b border-slate-200 dark:border-slate-700">
                    @php
                        $tabs = [
                            'metadata' => ['label' => 'Metadata', 'icon' => 'M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                            'colors' => ['label' => 'Warna', 'icon' => 'M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01'],
                            'fonts' => ['label' => 'Tipografi', 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z'],
                            'layout' => ['label' => 'Layout', 'icon' => 'M4 5a1 1 0 011-1h14a1 1 0 011 1v2a1 1 0 01-1 1H5a1 1 0 01-1-1V5zM4 13a1 1 0 011-1h6a1 1 0 011 1v6a1 1 0 01-1 1H5a1 1 0 01-1-1v-6zM16 13a1 1 0 011-1h2a1 1 0 011 1v6a1 1 0 01-1 1h-2a1 1 0 01-1-1v-6z'],
                            'advanced' => ['label' => 'Advanced', 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z'],
                        ];
                    @endphp
                    @foreach($tabs as $key => $tab)
                        <button
                            @click="activeTab = '{{ $key }}'"
                            :class="activeTab === '{{ $key }}'
                                ? 'border-b-2 border-indigo-500 text-indigo-600 dark:text-indigo-400 bg-indigo-50/50 dark:bg-indigo-900/20'
                                : 'text-slate-500 dark:text-slate-400 hover:text-slate-700 dark:hover:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700/30'"
                            class="flex items-center gap-2 px-5 py-3.5 text-sm font-medium transition-all whitespace-nowrap"
                        >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $tab['icon'] }}"/></svg>
                            {{ $tab['label'] }}
                        </button>
                    @endforeach
                </div>

                <div class="p-5 sm:p-6">
                    {{-- ═══════════════════════ TAB: METADATA ═══════════════════════ --}}
                    <div x-show="activeTab === 'metadata'" x-cloak>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                            {{-- Name --}}
                            <div>
                                <label for="tb-name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Nama Tema <span class="text-rose-500">*</span></label>
                                <input type="text" id="tb-name" wire:model.blur="name" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm" placeholder="e.g. Royal Gold">
                                @error('name') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Slug --}}
                            <div>
                                <label for="tb-slug" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Slug <span class="text-rose-500">*</span></label>
                                <input type="text" id="tb-slug" wire:model.blur="slug" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm font-mono" placeholder="royal-gold">
                                @error('slug') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Description --}}
                            <div class="md:col-span-2">
                                <label for="tb-desc" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Deskripsi</label>
                                <textarea id="tb-desc" wire:model.blur="description" rows="3" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm" placeholder="Deskripsi singkat tentang tema ini..."></textarea>
                                @error('description') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Category --}}
                            <div>
                                <label for="tb-cat" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Kategori</label>
                                <select id="tb-cat" wire:model="category" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm">
                                    <option value="">Pilih Kategori...</option>
                                    @foreach($this->categories as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>

                            {{-- View File --}}
                            <div>
                                <label for="tb-view" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Template Blade <span class="text-rose-500">*</span></label>
                                <select id="tb-view" wire:model="view_file" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm">
                                    @foreach($this->availableViewFiles as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('view_file') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            {{-- Toggles --}}
                            <div class="md:col-span-2 flex flex-wrap gap-6 pt-2">
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="is_active" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer-checked:bg-indigo-600 peer-focus:ring-4 peer-focus:ring-indigo-500/20 transition-colors"></div>
                                        <div class="absolute left-[2px] top-[2px] w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300">Aktif</span>
                                </label>
                                <label class="flex items-center gap-3 cursor-pointer">
                                    <div class="relative">
                                        <input type="checkbox" wire:model.live="is_premium" class="sr-only peer">
                                        <div class="w-11 h-6 bg-slate-200 dark:bg-slate-700 rounded-full peer-checked:bg-amber-500 peer-focus:ring-4 peer-focus:ring-amber-500/20 transition-colors"></div>
                                        <div class="absolute left-[2px] top-[2px] w-5 h-5 bg-white rounded-full shadow transition-transform peer-checked:translate-x-5"></div>
                                    </div>
                                    <span class="text-sm font-medium text-slate-700 dark:text-slate-300 flex items-center gap-1">
                                        <svg class="w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                        Premium
                                    </span>
                                </label>
                            </div>

                            {{-- Thumbnail Upload --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1.5">Thumbnail</label>
                                <div class="flex items-start gap-4">
                                    @if($themeId)
                                        @php $theme = \App\Models\Theme::find($themeId); @endphp
                                        @if($theme && $theme->thumbnail_url)
                                            <div class="w-20 h-32 rounded-xl overflow-hidden border border-slate-200 dark:border-slate-700 flex-shrink-0 bg-slate-100 dark:bg-slate-900">
                                                <img src="{{ $theme->protected_thumbnail }}" class="w-full h-full object-cover object-top">
                                            </div>
                                        @endif
                                    @endif
                                    <div class="flex-1">
                                        <input type="file" wire:model="thumbnail" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-400 transition-colors">
                                        <p class="text-xs text-slate-400 mt-1">Max 2MB. Format: JPG, PNG, WebP. Akan dikonversi ke WebP.</p>
                                        @error('thumbnail') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                                        <div wire:loading wire:target="thumbnail" class="text-xs text-indigo-500 mt-1 flex items-center gap-1">
                                            <svg class="w-3 h-3 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                            Mengunggah...
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════ TAB: COLORS ═══════════════════════ --}}
                    <div x-show="activeTab === 'colors'" x-cloak>
                        {{-- Color Presets --}}
                        <div class="mb-6">
                            <div class="flex items-center justify-between mb-3">
                                <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Preset Warna</h3>
                                <button wire:click="resetColors" class="text-xs text-slate-500 hover:text-rose-500 transition-colors font-medium">Reset Default</button>
                            </div>
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                                @foreach($this->colorPresets as $presetName => $preset)
                                    <button wire:click="applyPreset('{{ $presetName }}')" class="group relative p-3 rounded-xl border border-slate-200 dark:border-slate-700 hover:border-indigo-400 dark:hover:border-indigo-500 hover:shadow-md transition-all text-left">
                                        <div class="flex gap-1 mb-2">
                                            @foreach(['primary', 'secondary', 'accent', 'background'] as $colorKey)
                                                <div class="w-5 h-5 rounded-full border border-white/50 shadow-sm" style="background-color: {{ $preset[$colorKey] }}"></div>
                                            @endforeach
                                        </div>
                                        <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400 group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $presetName }}</span>
                                    </button>
                                @endforeach
                            </div>
                        </div>

                        <div class="h-px bg-slate-200 dark:bg-slate-700 mb-6"></div>

                        {{-- Color Pickers --}}
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-5">
                            @php
                                $colorFields = [
                                    'primary_color' => ['label' => 'Primary', 'desc' => 'Warna utama tema'],
                                    'secondary_color' => ['label' => 'Secondary', 'desc' => 'Warna pendamping'],
                                    'accent_color' => ['label' => 'Accent', 'desc' => 'Warna aksen/highlight'],
                                    'text_color' => ['label' => 'Text', 'desc' => 'Warna teks body'],
                                    'heading_color' => ['label' => 'Heading', 'desc' => 'Warna judul/heading'],
                                    'background_color' => ['label' => 'Background', 'desc' => 'Warna latar belakang'],
                                ];
                            @endphp

                            @foreach($colorFields as $field => $info)
                                <div class="group" wire:key="color-{{ $field }}">
                                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ $info['label'] }}</label>
                                    <p class="text-[11px] text-slate-400 mb-2">{{ $info['desc'] }}</p>
                                    <div class="flex items-center gap-3 p-3 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700 group-hover:border-indigo-300 dark:group-hover:border-indigo-600 transition-colors">
                                        <div class="relative">
                                            <input type="color" wire:model.live="{{ $field }}" class="w-10 h-10 rounded-lg border-2 border-white dark:border-slate-600 shadow-sm cursor-pointer appearance-none [&::-webkit-color-swatch-wrapper]:p-0 [&::-webkit-color-swatch]:rounded-md [&::-webkit-color-swatch]:border-none">
                                        </div>
                                        <input type="text" wire:model.live.debounce.300ms="{{ $field }}" class="flex-1 px-3 py-2 border border-slate-200 dark:border-slate-600 rounded-lg bg-white dark:bg-slate-800 text-sm font-mono text-slate-700 dark:text-slate-300 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 uppercase" maxlength="9" placeholder="#000000">
                                    </div>
                                    @error($field) <span class="text-rose-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- ═══════════════════════ TAB: FONTS ═══════════════════════ --}}
                    <div x-show="activeTab === 'fonts'" x-cloak>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pengaturan Font</h3>
                            <button wire:click="resetFonts" class="text-xs text-slate-500 hover:text-rose-500 transition-colors font-medium">Reset Default</button>
                        </div>

                        <div class="space-y-6">
                            @php
                                $fontFields = [
                                    'heading_font' => ['label' => 'Font Heading', 'desc' => 'Untuk judul utama, nama pengantin, dll.', 'sample' => 'Ahmad & Siti'],
                                    'body_font' => ['label' => 'Font Body', 'desc' => 'Untuk paragraf, deskripsi, dan konten.', 'sample' => 'Dengan memohon rahmat dan ridho Allah SWT, kami bermaksud menyelenggarakan...'],
                                    'accent_font' => ['label' => 'Font Accent', 'desc' => 'Untuk elemen dekoratif dan aksen.', 'sample' => 'The Wedding of'],
                                ];
                            @endphp

                            @foreach($fontFields as $field => $info)
                                <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700" wire:key="font-{{ $field }}">
                                    <div class="flex flex-col sm:flex-row sm:items-start gap-4">
                                        <div class="flex-1">
                                            <label for="tb-{{ $field }}" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">{{ $info['label'] }}</label>
                                            <p class="text-[11px] text-slate-400 mb-3">{{ $info['desc'] }}</p>
                                            <select id="tb-{{ $field }}" wire:model.live="{{ $field }}" class="w-full px-4 py-2.5 border border-slate-200 dark:border-slate-600 rounded-xl bg-white dark:bg-slate-800 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm">
                                                @foreach($this->availableFonts as $font => $type)
                                                    <option value="{{ $font }}">{{ $font }} ({{ $type }})</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        <div class="sm:w-64 p-4 bg-white dark:bg-slate-800 rounded-xl border border-slate-200 dark:border-slate-700 min-h-[4rem] flex items-center justify-center">
                                            <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family={{ urlencode($this->$field) }}&display=swap">
                                            <span class="text-center text-slate-700 dark:text-slate-300" style="font-family: '{{ $this->$field }}', serif; font-size: {{ $field === 'heading_font' ? '1.5rem' : ($field === 'accent_font' ? '1.75rem' : '0.875rem') }};">
                                                {{ $info['sample'] }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                            {{-- Heading Size --}}
                            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Ukuran Heading</label>
                                <p class="text-[11px] text-slate-400 mb-3">Ukuran font untuk judul utama ({{ $heading_size }}px)</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-slate-400 font-mono w-8">16</span>
                                    <input type="range" wire:model.live="heading_size" min="16" max="72" step="2" class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <span class="text-xs text-slate-400 font-mono w-8">72</span>
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 min-w-[3rem] text-right">{{ $heading_size }}px</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════ TAB: LAYOUT ═══════════════════════ --}}
                    <div x-show="activeTab === 'layout'" x-cloak>
                        <div class="flex items-center justify-between mb-5">
                            <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Pengaturan Layout</h3>
                            <button wire:click="resetLayout" class="text-xs text-slate-500 hover:text-rose-500 transition-colors font-medium">Reset Default</button>
                        </div>

                        <div class="space-y-6">
                            {{-- Container Width --}}
                            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Lebar Container</label>
                                <p class="text-[11px] text-slate-400 mb-3">Lebar maksimum kontainer tema ({{ $container_max_width }}px)</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-slate-400 font-mono w-8">300</span>
                                    <input type="range" wire:model.live="container_max_width" min="300" max="800" step="10" class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <span class="text-xs text-slate-400 font-mono w-8">800</span>
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 min-w-[3rem] text-right">{{ $container_max_width }}px</span>
                                </div>
                            </div>

                            {{-- Border Radius --}}
                            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Border Radius</label>
                                <p class="text-[11px] text-slate-400 mb-3">Tingkat kelengkungan sudut elemen</p>
                                <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                                    @php
                                        $radiusOptions = ['0px', '4px', '8px', '12px', '16px', '20px', '24px', '28px', '32px', '9999px'];
                                    @endphp
                                    @foreach($radiusOptions as $radius)
                                        <button
                                            wire:click="$set('border_radius', '{{ $radius }}')"
                                            class="p-3 rounded-xl border-2 transition-all text-center {{ $border_radius === $radius ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600' }}"
                                        >
                                            <div class="w-8 h-8 mx-auto bg-indigo-500/20 border-2 border-indigo-500/50 mb-1" style="border-radius: {{ $radius }}"></div>
                                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400">{{ $radius }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Button Style --}}
                            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Gaya Tombol</label>
                                <p class="text-[11px] text-slate-400 mb-3">Bentuk tombol di dalam tema</p>
                                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                                    @php
                                        $buttonStyles = [
                                            'rounded' => ['label' => 'Rounded', 'class' => 'rounded-xl'],
                                            'pill' => ['label' => 'Pill', 'class' => 'rounded-full'],
                                            'square' => ['label' => 'Square', 'class' => 'rounded-none'],
                                            'boxy' => ['label' => 'Boxy', 'class' => 'rounded-md'],
                                        ];
                                    @endphp
                                    @foreach($buttonStyles as $styleKey => $style)
                                        <button
                                            wire:click="$set('button_style', '{{ $styleKey }}')"
                                            class="p-4 border-2 transition-all {{ $button_style === $styleKey ? 'border-indigo-500 bg-indigo-50 dark:bg-indigo-900/30' : 'border-slate-200 dark:border-slate-700 hover:border-slate-300 dark:hover:border-slate-600' }} rounded-xl"
                                        >
                                            <div class="px-4 py-2 bg-indigo-600 text-white text-xs font-semibold text-center {{ $style['class'] }} mb-2">
                                                Button
                                            </div>
                                            <span class="text-[11px] font-medium text-slate-600 dark:text-slate-400">{{ $style['label'] }}</span>
                                        </button>
                                    @endforeach
                                </div>
                            </div>

                            {{-- Overlay Opacity --}}
                            <div class="p-5 bg-slate-50 dark:bg-slate-900/50 rounded-xl border border-slate-200 dark:border-slate-700">
                                <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Overlay Opacity</label>
                                <p class="text-[11px] text-slate-400 mb-3">Transparansi overlay pada gambar latar ({{ $overlay_opacity }}%)</p>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs text-slate-400 font-mono w-4">0</span>
                                    <input type="range" wire:model.live="overlay_opacity" min="0" max="100" step="5" class="flex-1 h-2 bg-slate-200 dark:bg-slate-700 rounded-lg appearance-none cursor-pointer accent-indigo-600">
                                    <span class="text-xs text-slate-400 font-mono w-8">100</span>
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400 min-w-[3rem] text-right">{{ $overlay_opacity }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ═══════════════════════ TAB: ADVANCED ═══════════════════════ --}}
                    <div x-show="activeTab === 'advanced'" x-cloak>
                        <div class="space-y-6">
                            {{-- Custom CSS --}}
                            <div>
                                <label for="tb-css" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-1">Custom CSS</label>
                                <p class="text-[11px] text-slate-400 mb-3">Tambahkan CSS kustom untuk tema ini. Hindari penggunaan javascript:, &lt;script&gt;, atau expression().</p>
                                <textarea id="tb-css" wire:model.blur="custom_css" rows="12" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-900 text-emerald-400 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm font-mono leading-relaxed" placeholder="/* Tambahkan CSS kustom di sini */&#10;.my-class {&#10;    color: var(--color-primary);&#10;}"></textarea>
                                @error('custom_css') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                            </div>

                            <div class="h-px bg-slate-200 dark:bg-slate-700"></div>

                            {{-- Export JSON --}}
                            <div>
                                <div class="flex items-center justify-between mb-3">
                                    <div>
                                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300">Export / Import JSON</h3>
                                        <p class="text-[11px] text-slate-400">Salin konfigurasi tema atau impor dari JSON</p>
                                    </div>
                                    <button @click="showImportModal = true" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-lg transition-colors border border-indigo-100 dark:border-indigo-800/50">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Import JSON
                                    </button>
                                </div>
                                <div x-data="{ copied: false }" class="relative">
                                    <textarea readonly rows="8" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-600 dark:text-slate-400 text-xs font-mono leading-relaxed">{{ $this->exportJson }}</textarea>
                                    <button
                                        @click="navigator.clipboard.writeText($el.parentElement.querySelector('textarea').value); copied = true; setTimeout(() => copied = false, 2000)"
                                        class="absolute top-3 right-3 px-3 py-1.5 text-xs font-medium bg-white dark:bg-slate-800 border border-slate-200 dark:border-slate-600 rounded-lg hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors shadow-sm"
                                    >
                                        <span x-show="!copied">📋 Salin</span>
                                        <span x-show="copied" class="text-emerald-600">✅ Tersalin!</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- RIGHT: Live Preview Panel --}}
        <div class="xl:w-[400px] flex-shrink-0">
            <div class="xl:sticky xl:top-6">
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm overflow-hidden">
                    <div class="px-5 py-3.5 border-b border-slate-200 dark:border-slate-700 flex items-center justify-between">
                        <h3 class="text-sm font-semibold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <svg class="w-4 h-4 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                            Live Preview
                        </h3>
                        <span class="inline-flex items-center gap-1 px-2 py-0.5 text-[10px] font-bold uppercase tracking-wider text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-900/30 rounded-md">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                            Real-time
                        </span>
                    </div>

                    {{-- Preview Mockup --}}
                    <div class="p-4">
                        <link rel="stylesheet" href="{{ $this->previewFontsUrl }}">
                        <div
                            class="rounded-2xl overflow-hidden border border-slate-200 dark:border-slate-700 shadow-inner"
                            style="{{ $this->previewCss }}"
                        >
                            {{-- Cover Section --}}
                            <div class="relative py-12 px-6 text-center overflow-hidden" style="background-color: var(--color-secondary);">
                                <div class="absolute inset-0" style="background: linear-gradient(135deg, var(--color-primary) 0%, var(--color-secondary) 100%); opacity: 0.3;"></div>
                                <div class="relative z-10">
                                    <p class="text-xs tracking-[0.3em] uppercase mb-3 opacity-70" style="font-family: var(--font-body); color: var(--color-background);">Wedding Invitation</p>
                                    <h2 class="mb-4" style="font-family: var(--font-accent); font-size: calc(var(--heading-size) * 0.6); color: var(--color-background);">The Wedding of</h2>
                                    <h1 class="mb-6 leading-tight" style="font-family: var(--font-heading); font-size: calc(var(--heading-size) * 0.75); color: var(--color-background);">
                                        Ahmad <span style="font-size: 0.6em">&</span> Siti
                                    </h1>
                                    <div class="inline-block px-5 py-2 text-xs font-semibold tracking-wider uppercase" style="background-color: var(--color-background); color: var(--color-secondary); border-radius: var(--border-radius);">
                                        🕊️ Buka Undangan
                                    </div>
                                </div>
                            </div>

                            {{-- Content Section --}}
                            <div class="py-8 px-6 text-center" style="background-color: var(--color-background);">
                                <p class="text-xs uppercase tracking-widest mb-3 opacity-60" style="font-family: var(--font-body); color: var(--color-text);">Pasangan Mempelai</p>
                                <h3 class="mb-1" style="font-family: var(--font-heading); font-size: calc(var(--heading-size) * 0.5); color: var(--color-heading);">Ahmad Fauzan</h3>
                                <p class="text-sm mb-4 opacity-60" style="font-family: var(--font-body); color: var(--color-text);">Putra dari Bpk. Hasan & Ibu Aminah</p>

                                <div class="my-4 mx-auto w-8 h-px" style="background-color: var(--color-accent);"></div>

                                <h3 class="mb-1" style="font-family: var(--font-heading); font-size: calc(var(--heading-size) * 0.5); color: var(--color-heading);">Siti Nurhaliza</h3>
                                <p class="text-sm mb-6 opacity-60" style="font-family: var(--font-body); color: var(--color-text);">Putri dari Bpk. Abdullah & Ibu Fatimah</p>
                            </div>

                            {{-- Event Section --}}
                            <div class="py-6 px-6 text-center" style="background-color: var(--color-primary); opacity: 0.95;">
                                <h3 class="mb-3" style="font-family: var(--font-heading); font-size: calc(var(--heading-size) * 0.45); color: var(--color-background);">Akad Nikah</h3>
                                <p class="text-xs uppercase tracking-widest mb-2 opacity-80" style="font-family: var(--font-body); color: var(--color-background);">Sabtu, 15 Juni 2026</p>
                                <p class="text-xs opacity-70 mb-4" style="font-family: var(--font-body); color: var(--color-background);">Masjid Al-Ikhlas, Jakarta Selatan</p>
                                <div class="inline-block px-4 py-1.5 text-[10px] font-semibold uppercase tracking-wider border" style="color: var(--color-background); border-color: var(--color-background); border-radius: var(--border-radius); opacity: 0.8;">
                                    Lihat Lokasi
                                </div>
                            </div>

                            {{-- Footer --}}
                            <div class="py-6 px-6 text-center" style="background-color: var(--color-background);">
                                <p class="mb-1" style="font-family: var(--font-accent); font-size: calc(var(--heading-size) * 0.5); color: var(--color-accent);">Thank You</p>
                                <p class="text-[10px] uppercase tracking-[0.4em] opacity-40" style="font-family: var(--font-body); color: var(--color-text);">Ahmad & Siti</p>
                            </div>
                        </div>

                        {{-- Color Swatch Summary --}}
                        <div class="flex items-center gap-1.5 mt-4 px-1">
                            @foreach(['primary_color', 'secondary_color', 'accent_color', 'text_color', 'heading_color', 'background_color'] as $c)
                                <div class="flex-1 h-6 rounded-md border border-white dark:border-slate-600 shadow-sm" style="background-color: {{ $this->$c }}" title="{{ $c }}"></div>
                            @endforeach
                        </div>
                        <div class="flex items-center justify-between mt-1.5 px-1">
                            <span class="text-[9px] text-slate-400 font-mono">PRI</span>
                            <span class="text-[9px] text-slate-400 font-mono">SEC</span>
                            <span class="text-[9px] text-slate-400 font-mono">ACC</span>
                            <span class="text-[9px] text-slate-400 font-mono">TXT</span>
                            <span class="text-[9px] text-slate-400 font-mono">HDG</span>
                            <span class="text-[9px] text-slate-400 font-mono">BG</span>
                        </div>
                    </div>
                </div>

                {{-- Quick Info Card --}}
                @if($themeId)
                    @php $themeInfo = \App\Models\Theme::withCount('invitations')->find($themeId); @endphp
                    @if($themeInfo)
                        <div class="mt-4 p-4 bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 shadow-sm">
                            <h4 class="text-xs font-semibold text-slate-500 dark:text-slate-400 uppercase tracking-wider mb-3">Info Tema</h4>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">ID</span>
                                    <span class="font-mono text-slate-700 dark:text-slate-300">#{{ $themeInfo->id }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Undangan</span>
                                    <span class="font-semibold text-slate-700 dark:text-slate-300">{{ $themeInfo->invitations_count }} digunakan</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Dibuat</span>
                                    <span class="text-slate-700 dark:text-slate-300">{{ $themeInfo->created_at->diffForHumans() }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-slate-500 dark:text-slate-400">Terakhir diubah</span>
                                    <span class="text-slate-700 dark:text-slate-300">{{ $themeInfo->updated_at->diffForHumans() }}</span>
                                </div>
                            </div>
                            <div class="mt-3 pt-3 border-t border-slate-200 dark:border-slate-700">
                                <a href="{{ route('invitation.demo', ['theme' => $themeInfo->slug]) }}" target="_blank" class="flex items-center justify-center gap-2 w-full py-2 text-xs font-semibold text-indigo-600 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-900/30 hover:bg-indigo-100 dark:hover:bg-indigo-900/50 rounded-xl transition-colors border border-indigo-100 dark:border-indigo-800/50">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                    Buka Demo Tema
                                </a>
                            </div>
                        </div>
                    @endif
                @endif
            </div>
        </div>
    </div>

    {{-- Import JSON Modal --}}
    <div x-cloak>
        <template x-teleport="body">
            <div x-show="showImportModal" class="fixed inset-0 z-[100] overflow-y-auto w-screen h-screen flex items-center justify-center p-4" role="dialog" aria-modal="true">
                <div x-show="showImportModal" x-transition.opacity class="fixed inset-0 bg-slate-900/75 backdrop-blur-sm" @click="showImportModal = false"></div>
                <div x-show="showImportModal"
                     x-transition:enter="ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     x-transition:leave="ease-in duration-200"
                     x-transition:leave-start="opacity-100 scale-100"
                     x-transition:leave-end="opacity-0 scale-95"
                     class="relative bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-2xl w-full max-w-2xl border border-slate-200 dark:border-slate-700">
                    <form wire:submit="importFromJson">
                        <div class="px-6 pt-6 pb-4">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-100 dark:bg-indigo-900/50 flex items-center justify-center">
                                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                </div>
                                <div>
                                    <h3 class="text-lg font-bold text-slate-900 dark:text-white">Import JSON</h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400">Paste konfigurasi tema dalam format JSON</p>
                                </div>
                            </div>
                            <textarea wire:model="importJson" rows="14" class="w-full px-4 py-3 border border-slate-200 dark:border-slate-600 rounded-xl bg-slate-50 dark:bg-slate-900 text-slate-700 dark:text-slate-300 text-sm font-mono focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500" placeholder='{"name": "My Theme", "slug": "my-theme", ...}'></textarea>
                            @error('importJson') <span class="text-rose-500 text-xs mt-1 block font-medium">{{ $message }}</span> @enderror
                        </div>
                        <div class="bg-slate-50 dark:bg-slate-700/50 px-6 py-4 flex flex-col sm:flex-row-reverse gap-2 rounded-b-2xl">
                            <button type="submit" class="inline-flex justify-center items-center rounded-xl px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-sm font-semibold text-white transition-colors">
                                <span wire:loading.remove wire:target="importFromJson">Import ke Form</span>
                                <span wire:loading wire:target="importFromJson" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg>
                                    Mengimpor...
                                </span>
                            </button>
                            <button type="button" @click="showImportModal = false" class="inline-flex justify-center rounded-xl px-6 py-2.5 border border-slate-300 dark:border-slate-600 text-sm font-semibold text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-700 transition-colors">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </template>
    </div>
</div>
