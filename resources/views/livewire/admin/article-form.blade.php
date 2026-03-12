<div>
    <div class="mb-8">
        <a href="{{ route('admin.articles') }}" class="inline-flex items-center gap-1 text-sm text-slate-500 hover:text-indigo-600 transition-colors mb-4" wire:navigate>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            Kembali ke Artikel
        </a>
        <h1 class="text-2xl font-bold text-slate-900 dark:text-white">
            {{ $article ? 'Edit Artikel' : 'Buat Artikel Baru' }}
        </h1>
    </div>

    @if(session('success'))
        <div class="mb-6 p-4 rounded-xl bg-emerald-50 dark:bg-emerald-900/20 text-emerald-700 dark:text-emerald-400 text-sm font-medium">
            {{ session('success') }}
        </div>
    @endif

    @if($generateError)
        <div class="mb-6 p-4 rounded-xl bg-red-50 dark:bg-red-900/20 text-red-700 dark:text-red-400 text-sm font-medium">
            {{ $generateError }}
        </div>
    @endif

    {{-- Generate All Banner --}}
    <div class="mb-6 bg-gradient-to-r from-purple-500/10 to-indigo-500/10 dark:from-purple-900/20 dark:to-indigo-900/20 border border-purple-200 dark:border-purple-800 rounded-2xl p-5 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div class="flex items-start gap-3">
            <div class="p-2 bg-purple-100 dark:bg-purple-900/30 rounded-xl">
                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            </div>
            <div>
                <h3 class="font-semibold text-slate-900 dark:text-white text-sm">AI Content Generator</h3>
                <p class="text-xs text-slate-500 dark:text-slate-400 mt-0.5">Isi judul lalu klik generate — AI akan membuat konten, SEO, dan ringkasan otomatis.</p>
            </div>
        </div>
        <button
            type="button"
            wire:click="generateAll"
            wire:loading.attr="disabled"
            wire:target="generateAll,regenerateContent"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-purple-600 hover:bg-purple-700 disabled:opacity-50 disabled:cursor-wait text-white text-sm font-medium rounded-xl transition-colors whitespace-nowrap"
        >
            <svg wire:loading.remove wire:target="generateAll" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
            <svg wire:loading wire:target="generateAll" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
            <span wire:loading.remove wire:target="generateAll">Generate Semua</span>
            <span wire:loading wire:target="generateAll">Sedang generate...</span>
        </button>
    </div>

    <form wire:submit="save" class="space-y-8 relative">
        {{-- Loading overlay --}}
        <div wire:loading wire:target="generateAll,regenerateContent" class="absolute inset-0 bg-white/60 dark:bg-slate-900/60 backdrop-blur-sm z-10 rounded-2xl flex items-center justify-center">
            <div class="text-center">
                <svg class="w-10 h-10 animate-spin text-purple-500 mx-auto mb-3" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                <p class="text-sm font-medium text-slate-700 dark:text-slate-300">AI sedang menulis artikel...</p>
                <p class="text-xs text-slate-400 mt-1">Proses ini membutuhkan beberapa saat</p>
            </div>
        </div>
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
            {{-- Main Content --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Title & Slug --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                    <div>
                        <label for="title" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Judul Artikel <span class="text-red-500">*</span></label>
                        <input wire:model.live.debounce.500ms="title" type="text" id="title" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors" placeholder="Masukkan judul artikel...">
                        @error('title') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="slug" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">
                            Slug URL
                            <button type="button" wire:click="generateSlug" class="ml-2 text-xs text-indigo-500 hover:text-indigo-700">Generate dari judul</button>
                        </label>
                        <div class="flex items-center gap-2">
                            <span class="text-sm text-slate-400">/blog/</span>
                            <input wire:model="slug" type="text" id="slug" class="flex-1 rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors" placeholder="slug-artikel">
                        </div>
                        @error('slug') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Content Editor --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                    <div class="flex items-center justify-between mb-2">
                        <label class="block text-sm font-medium text-slate-700 dark:text-slate-300">Konten Artikel <span class="text-red-500">*</span></label>
                        <button
                            type="button"
                            wire:click="regenerateContent"
                            wire:loading.attr="disabled"
                            wire:target="generateAll,regenerateContent"
                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-amber-500 hover:bg-amber-600 disabled:opacity-50 disabled:cursor-wait text-white text-xs font-medium rounded-lg transition-colors"
                        >
                            <svg wire:loading.remove wire:target="regenerateContent" class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            <svg wire:loading wire:target="regenerateContent" class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/></svg>
                            <span wire:loading.remove wire:target="regenerateContent">Regenerate Konten</span>
                            <span wire:loading wire:target="regenerateContent">Generating...</span>
                        </button>
                    </div>
                    <div
                        wire:ignore
                        x-data="{
                            content: @entangle('content'),
                            init() {
                                const editor = this.$refs.editor;
                                editor.innerHTML = this.content;
                                editor.addEventListener('input', () => {
                                    this.content = editor.innerHTML;
                                });

                                Livewire.on('content-updated', (event) => {
                                    editor.innerHTML = event.content;
                                    this.content = event.content;
                                });
                            }
                        }"
                    >
                        {{-- Toolbar --}}
                        <div class="flex flex-wrap gap-1 p-2 border border-b-0 border-slate-200 dark:border-slate-700 rounded-t-xl bg-slate-50 dark:bg-slate-900">
                            <button type="button" onclick="document.execCommand('bold')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Bold">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M6 4h8a4 4 0 014 4 4 4 0 01-4 4H6z"/><path d="M6 12h9a4 4 0 014 4 4 4 0 01-4 4H6z"/></svg>
                            </button>
                            <button type="button" onclick="document.execCommand('italic')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Italic">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="19" y1="4" x2="10" y2="4"/><line x1="14" y1="20" x2="5" y2="20"/><line x1="15" y1="4" x2="9" y2="20"/></svg>
                            </button>
                            <div class="w-px h-8 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                            <button type="button" onclick="document.execCommand('formatBlock', false, 'h2')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors text-xs font-bold" title="Heading 2">H2</button>
                            <button type="button" onclick="document.execCommand('formatBlock', false, 'h3')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors text-xs font-bold" title="Heading 3">H3</button>
                            <button type="button" onclick="document.execCommand('formatBlock', false, 'p')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors text-xs font-medium" title="Paragraph">P</button>
                            <div class="w-px h-8 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                            <button type="button" onclick="document.execCommand('insertUnorderedList')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Bullet List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="8" y1="6" x2="21" y2="6"/><line x1="8" y1="12" x2="21" y2="12"/><line x1="8" y1="18" x2="21" y2="18"/><circle cx="3" cy="6" r="1" fill="currentColor"/><circle cx="3" cy="12" r="1" fill="currentColor"/><circle cx="3" cy="18" r="1" fill="currentColor"/></svg>
                            </button>
                            <button type="button" onclick="document.execCommand('insertOrderedList')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Numbered List">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="10" y1="6" x2="21" y2="6"/><line x1="10" y1="12" x2="21" y2="12"/><line x1="10" y1="18" x2="21" y2="18"/><text x="1" y="8" font-size="8" fill="currentColor" stroke="none">1</text><text x="1" y="14" font-size="8" fill="currentColor" stroke="none">2</text><text x="1" y="20" font-size="8" fill="currentColor" stroke="none">3</text></svg>
                            </button>
                            <div class="w-px h-8 bg-slate-200 dark:bg-slate-700 mx-1"></div>
                            <button type="button" onclick="document.execCommand('formatBlock', false, 'blockquote')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Blockquote">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M3 21c3 0 7-1 7-8V5c0-1.25-.756-2.017-2-2H4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2 1 0 1 0 1 1v1c0 1-1 2-2 2s-1 .008-1 1.031V21z"/><path d="M15 21c3 0 7-1 7-8V5c0-1.25-.757-2.017-2-2h-4c-1.25 0-2 .75-2 1.972V11c0 1.25.75 2 2 2h.75c0 2.25.25 4-2.75 4v3z"/></svg>
                            </button>
                            <button type="button" onclick="var url=prompt('URL link:');if(url)document.execCommand('createLink',false,url)" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Insert Link">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M10 13a5 5 0 007.54.54l3-3a5 5 0 00-7.07-7.07l-1.72 1.71"/><path d="M14 11a5 5 0 00-7.54-.54l-3 3a5 5 0 007.07 7.07l1.71-1.71"/></svg>
                            </button>
                            <button type="button" onclick="document.execCommand('removeFormat')" class="p-2 rounded-lg hover:bg-slate-200 dark:hover:bg-slate-700 text-slate-600 dark:text-slate-400 transition-colors" title="Hapus Format">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M17 10H3M21 6H3M21 14H3M17 18H3"/></svg>
                            </button>
                        </div>

                        {{-- Editor Area --}}
                        <div
                            x-ref="editor"
                            contenteditable="true"
                            class="prose prose-sm dark:prose-invert max-w-none min-h-[400px] p-4 border border-slate-200 dark:border-slate-700 rounded-b-xl bg-white dark:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 text-slate-800 dark:text-slate-200 overflow-y-auto"
                            style="max-height: 600px"
                        ></div>
                    </div>
                    @error('content') <p class="text-xs text-red-500 mt-2">{{ $message }}</p> @enderror
                </div>
                {{-- Excerpt --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6">
                    <label for="excerpt" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Ringkasan / Excerpt</label>
                    <textarea wire:model="excerpt" id="excerpt" rows="3" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors" placeholder="Ringkasan singkat artikel (opsional, akan di-generate otomatis jika kosong)..."></textarea>
                    <p class="text-xs text-slate-400 mt-1">Maks. 500 karakter. Akan otomatis dibuat dari konten jika kosong.</p>
                    @error('excerpt') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Publish --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Terbitkan</h3>

                    <div>
                        <label for="status" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Status</label>
                        <select wire:model="status" id="status" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors">
                            <option value="draft">Draft</option>
                            <option value="published">Published</option>
                        </select>
                    </div>

                    @if($article)
                        <div class="text-xs text-slate-400 space-y-1">
                            <p>Dibuat: {{ $article->created_at->translatedFormat('d M Y, H:i') }}</p>
                            <p>Diperbarui: {{ $article->updated_at->translatedFormat('d M Y, H:i') }}</p>
                            @if($article->published_at)
                                <p>Diterbitkan: {{ $article->published_at->translatedFormat('d M Y, H:i') }}</p>
                            @endif
                        </div>
                    @endif

                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-xl transition-colors">
                            <span wire:loading.remove wire:target="save">
                                {{ $article ? 'Simpan' : 'Buat Artikel' }}
                            </span>
                            <span wire:loading wire:target="save">Menyimpan...</span>
                        </button>
                    </div>
                </div>

                {{-- Featured Image --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                    <h3 class="font-semibold text-slate-900 dark:text-white">Gambar Utama</h3>

                    @if($image)
                        <div class="relative">
                            <img src="{{ $image->temporaryUrl() }}" alt="Preview" class="w-full rounded-xl object-cover aspect-video">
                            <button type="button" wire:click="$set('image', null)" class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @elseif($existing_image)
                        <div class="relative">
                            <img src="{{ asset('storage/' . $existing_image) }}" alt="Current" class="w-full rounded-xl object-cover aspect-video">
                            <button type="button" wire:click="removeImage" class="absolute top-2 right-2 p-1 bg-red-500 text-white rounded-lg hover:bg-red-600">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    @endif

                    <div>
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-slate-300 dark:border-slate-600 rounded-xl cursor-pointer hover:border-indigo-400 transition-colors bg-slate-50 dark:bg-slate-900">
                            <div class="flex flex-col items-center justify-center pt-5 pb-6">
                                <svg class="w-8 h-8 text-slate-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                <p class="text-xs text-slate-400">Klik untuk upload gambar</p>
                            </div>
                            <input wire:model="image" type="file" class="hidden" accept="image/*">
                        </label>
                        <p class="text-xs text-slate-400 mt-1">JPG, PNG, WebP. Maks. 2MB.</p>
                        @error('image') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- SEO --}}
                <div class="bg-white dark:bg-slate-800 rounded-2xl border border-slate-200 dark:border-slate-700 p-6 space-y-4">
                    <h3 class="font-semibold text-slate-900 dark:text-white">SEO</h3>

                    <div>
                        <label for="meta_description" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Meta Description</label>
                        <textarea wire:model="meta_description" id="meta_description" rows="2" maxlength="160" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm" placeholder="Deskripsi untuk mesin pencari..."></textarea>
                        <p class="text-xs text-slate-400 mt-1">{{ strlen($meta_description) }}/160 karakter</p>
                        @error('meta_description') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="focus_keyword" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Focus Keyword</label>
                        <input wire:model="focus_keyword" type="text" id="focus_keyword" maxlength="100" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm" placeholder="Kata kunci utama...">
                        @error('focus_keyword') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label for="meta_keywords" class="block text-sm font-medium text-slate-700 dark:text-slate-300 mb-1">Meta Keywords</label>
                        <input wire:model="meta_keywords" type="text" id="meta_keywords" maxlength="255" class="w-full rounded-xl border-slate-200 dark:border-slate-700 bg-white dark:bg-slate-900 text-slate-800 dark:text-slate-200 focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 transition-colors text-sm" placeholder="keyword1, keyword2, keyword3...">
                        @error('meta_keywords') <p class="text-xs text-red-500 mt-1">{{ $message }}</p> @enderror
                    </div>

                    {{-- SEO Preview --}}
                    <div class="pt-4 border-t border-slate-200 dark:border-slate-700">
                        <p class="text-xs font-medium text-slate-500 dark:text-slate-400 mb-2">Preview Google</p>
                        <div class="bg-slate-50 dark:bg-slate-900 rounded-xl p-3 space-y-1">
                            <p class="text-sm text-blue-700 dark:text-blue-400 font-medium truncate">
                                {{ $title ?: 'Judul Artikel' }} - ExoInvite
                            </p>
                            <p class="text-xs text-emerald-700 dark:text-emerald-400 truncate">
                                exoinvite.site/blog/{{ $slug ?: 'slug-artikel' }}
                            </p>
                            <p class="text-xs text-slate-500 line-clamp-2">
                                {{ $meta_description ?: 'Meta description akan ditampilkan di sini...' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>
