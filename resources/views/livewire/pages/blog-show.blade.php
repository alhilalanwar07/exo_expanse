<div>
    {{-- JSON-LD Article Structured Data --}}
    @push('seo')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'Article',
        'headline' => $article->title,
        'description' => $article->getEffectiveMetaDescription(),
        'image' => $article->image ? asset('storage/' . $article->image) : null,
        'author' => [
            '@type' => 'Person',
            'name' => $article->user->name ?? 'Admin',
        ],
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'ExoInvite',
            'url' => url('/'),
        ],
        'datePublished' => $article->published_at?->toIso8601String(),
        'dateModified' => $article->updated_at?->toIso8601String(),
        'mainEntityOfPage' => [
            '@type' => 'WebPage',
            '@id' => route('articles.show', $article->slug),
        ],
        'wordCount' => str_word_count(strip_tags($article->content)),
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>

    {{-- BreadcrumbList Structured Data --}}
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'BreadcrumbList',
        'itemListElement' => [
            ['@type' => 'ListItem', 'position' => 1, 'name' => 'Home', 'item' => url('/')],
            ['@type' => 'ListItem', 'position' => 2, 'name' => 'Blog', 'item' => route('articles.index')],
            ['@type' => 'ListItem', 'position' => 3, 'name' => $article->title],
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    <!-- Breadcrumb Navigation -->
    <nav class="bg-slate-50 dark:bg-slate-900 border-b border-slate-200 dark:border-slate-700" aria-label="Breadcrumb">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <ol class="flex items-center gap-2 text-sm text-slate-500">
                <li><a href="{{ url('/') }}" class="hover:text-rose-500 transition-colors">Home</a></li>
                <li><span class="mx-1">/</span></li>
                <li><a href="{{ route('articles.index') }}" class="hover:text-rose-500 transition-colors">Blog</a></li>
                <li><span class="mx-1">/</span></li>
                <li class="text-slate-700 dark:text-slate-300 font-medium truncate max-w-[200px]">{{ $article->title }}</li>
            </ol>
        </div>
    </nav>

    <!-- Article Header -->
    <header class="bg-gradient-to-br from-rose-500 to-amber-500 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1 text-white/70 hover:text-white text-sm mb-6 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Blog
            </a>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $article->title }}</h1>
            <div class="mt-4 flex flex-wrap items-center gap-4 text-white/70 text-sm">
                <span>{{ $article->user->name ?? 'Admin' }}</span>
                <span>&bull;</span>
                <time datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('d F Y, H:i') }}</time>
                @if($article->reading_time)
                <span>&bull;</span>
                <span>{{ $article->reading_time }} menit baca</span>
                @endif
            </div>
            @if($article->focus_keyword)
            <div class="mt-3">
                <span class="inline-block bg-white/20 backdrop-blur-sm rounded-full px-3 py-1 text-xs font-medium">{{ $article->focus_keyword }}</span>
            </div>
            @endif
        </div>
    </header>

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Featured Image -->
        @if($article->image)
            <figure class="rounded-2xl overflow-hidden mb-10 shadow-lg">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[500px] object-cover" loading="eager">
            </figure>
        @endif

        <!-- Content Body -->
        <article class="prose prose-lg prose-slate dark:prose-invert max-w-none
            prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white
            prose-p:text-slate-600 dark:prose-p:text-slate-300
            prose-a:text-rose-500 prose-a:no-underline hover:prose-a:underline
            prose-img:rounded-xl prose-img:shadow-md">
            {!! strip_tags($article->content, '<p><br><h2><h3><h4><strong><em><ul><ol><li><blockquote><a><img>') !!}
        </article>

        <!-- Share & Back -->
        <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700">
            <!-- Share Buttons -->
            <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                <div>
                    <span class="text-sm font-medium text-slate-600 dark:text-slate-400 block mb-2">Bagikan artikel ini:</span>
                    <div class="flex items-center gap-3">
                        <a href="https://wa.me/?text={{ urlencode($article->title . ' ' . route('articles.show', $article->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-green-500 text-white text-xs font-medium rounded-full hover:bg-green-600 transition-colors" aria-label="Bagikan ke WhatsApp">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/><path d="M12 0C5.373 0 0 5.373 0 12c0 2.625.846 5.059 2.284 7.034L.789 23.492a.5.5 0 00.611.611l4.458-1.495A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 22c-2.347 0-4.518-.805-6.238-2.152l-.436-.362-2.634.883.883-2.634-.362-.436A9.956 9.956 0 012 12C2 6.486 6.486 2 12 2s10 4.486 10 10-4.486 10-10 10z"/></svg>
                            WhatsApp
                        </a>
                        <a href="https://twitter.com/intent/tweet?text={{ urlencode($article->title) }}&url={{ urlencode(route('articles.show', $article->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-slate-800 text-white text-xs font-medium rounded-full hover:bg-slate-900 transition-colors" aria-label="Bagikan ke X/Twitter">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/></svg>
                            Twitter
                        </a>
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(route('articles.show', $article->slug)) }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-blue-600 text-white text-xs font-medium rounded-full hover:bg-blue-700 transition-colors" aria-label="Bagikan ke Facebook">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                            Facebook
                        </a>
                    </div>
                </div>
                <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-rose-500 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                    Kembali ke Blog
                </a>
            </div>
        </div>
    </div>
</div>
