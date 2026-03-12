{!! '<?xml version="1.0" encoding="UTF-8"?>' !!}
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9"
        xmlns:image="http://www.google.com/schemas/sitemap-image/1.1">

    {{-- Homepage --}}
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>weekly</changefreq>
        <priority>1.0</priority>
    </url>

    {{-- Blog Index --}}
    <url>
        <loc>{{ route('articles.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>

    {{-- Articles --}}
    @foreach($articles as $article)
    <url>
        <loc>{{ route('articles.show', $article->slug) }}</loc>
        <lastmod>{{ $article->updated_at->toW3cString() }}</lastmod>
        <changefreq>monthly</changefreq>
        <priority>0.8</priority>
        @if($article->image)
        <image:image>
            <image:loc>{{ asset('storage/' . $article->image) }}</image:loc>
        </image:image>
        @endif
    </url>
    @endforeach

</urlset>
