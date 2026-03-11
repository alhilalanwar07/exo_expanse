<div>
    <!-- Article Header -->
    <div class="bg-gradient-to-br from-rose-500 to-amber-500 text-white py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-1 text-white/70 hover:text-white text-sm mb-6 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Blog
            </a>
            <h1 class="text-3xl md:text-4xl font-bold leading-tight">{{ $article->title }}</h1>
            <div class="mt-4 flex items-center gap-4 text-white/70 text-sm">
                <span>{{ $article->user->name ?? 'Admin' }}</span>
                <span>&bull;</span>
                <time>{{ $article->published_at->translatedFormat('d F Y, H:i') }}</time>
            </div>
        </div>
    </div>

    <!-- Article Content -->
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        <!-- Featured Image -->
        @if($article->image)
            <div class="rounded-2xl overflow-hidden mb-10 shadow-lg">
                <img src="{{ asset('storage/' . $article->image) }}" alt="{{ $article->title }}" class="w-full h-auto max-h-[500px] object-cover">
            </div>
        @endif

        <!-- Content Body -->
        <article class="prose prose-lg prose-slate dark:prose-invert max-w-none
            prose-headings:font-bold prose-headings:text-slate-900 dark:prose-headings:text-white
            prose-p:text-slate-600 dark:prose-p:text-slate-300
            prose-a:text-rose-500 prose-a:no-underline hover:prose-a:underline
            prose-img:rounded-xl prose-img:shadow-md">
            {!! \Illuminate\Support\Str::markdown($article->content) !!}
        </article>

        <!-- Share & Back -->
        <div class="mt-12 pt-8 border-t border-slate-200 dark:border-slate-700 flex items-center justify-between">
            <a href="{{ route('articles.index') }}" class="inline-flex items-center gap-2 text-slate-500 hover:text-rose-500 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                Kembali ke Blog
            </a>
        </div>
    </div>
</div>
