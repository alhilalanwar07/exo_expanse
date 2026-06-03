<div>
    {{-- CollectionPage Structured Data --}}
    @push('seo')
    <script type="application/ld+json">
    {!! json_encode([
        '@context' => 'https://schema.org',
        '@type' => 'CollectionPage',
        'name' => 'Blog ExoInvite',
        'description' => 'Artikel terbaru seputar tips undangan digital, inspirasi pernikahan, dan panduan membuat undangan online.',
        'url' => route('articles.index'),
        'publisher' => [
            '@type' => 'Organization',
            'name' => 'ExoInvite',
            'url' => url('/'),
        ],
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
    </script>
    @endpush

    <!-- Navbar -->
    <nav class="bg-white dark:bg-slate-800 border-b border-slate-200 dark:border-slate-700">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex items-center justify-between h-16">
            <x-logo variant="full" />
            <div class="flex items-center gap-4">
                <a href="{{ route('articles.index') }}" class="text-sm font-medium text-rose-500">Blog</a>
                @auth
                <a href="{{ route('dashboard') }}" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-rose-500 transition-colors">Dashboard</a>
                @else
                <a href="{{ route('login') }}" class="text-sm font-medium text-slate-600 dark:text-slate-300 hover:text-rose-500 transition-colors">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="bg-gradient-to-br from-rose-500 to-amber-500 text-white py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Blog</h1>
            <p class="text-lg text-white/80">Artikel & inspirasi terbaru seputar undangan digital dari ExoInvite</p>
        </div>
    </div>

    <!-- Articles Grid -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        @if($articles->isEmpty())
            <div class="text-center py-20">
                <svg class="w-16 h-16 mx-auto text-slate-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"/>
                </svg>
                <h3 class="text-lg font-medium text-slate-500">Belum ada artikel</h3>
                <p class="text-slate-400 mt-1">Artikel baru akan segera hadir.</p>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($articles as $article)
                    <a href="{{ route('articles.show', $article->slug) }}" class="group block bg-white dark:bg-slate-800 rounded-2xl overflow-hidden shadow-sm hover:shadow-xl transition-all duration-300 border border-slate-200 dark:border-slate-700">
                        <!-- Image -->
                        <div class="aspect-video overflow-hidden bg-slate-100 dark:bg-slate-700">
                            @if($article->image)
                                <img src="{{ img_url($article->image) }}" alt="{{ $article->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300" loading="lazy">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <svg class="w-12 h-12 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                            @endif
                        </div>
                        <!-- Content -->
                        <div class="p-5">
                            <time class="text-xs text-slate-400 font-medium" datetime="{{ $article->published_at->toIso8601String() }}">{{ $article->published_at->translatedFormat('d F Y') }}</time>
                            <h2 class="mt-2 text-lg font-bold text-slate-900 dark:text-white group-hover:text-rose-500 transition-colors line-clamp-2">{{ $article->title }}</h2>
                            @if($article->excerpt)
                                <p class="mt-2 text-sm text-slate-500 dark:text-slate-400 line-clamp-3">{{ $article->excerpt }}</p>
                            @endif
                            @if($article->reading_time)
                                <span class="mt-2 inline-block text-xs text-slate-400">{{ $article->reading_time }} menit baca</span>
                            @endif
                        </div>
                    </a>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="mt-10">
                {{ $articles->links() }}
            </div>
        @endif
    </div>
</div>
