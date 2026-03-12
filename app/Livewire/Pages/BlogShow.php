<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('layouts.guest')]
class BlogShow extends Component
{
    public Article $article;

    public function mount(string $slug): void
    {
        $this->article = Article::where('slug', $slug)
            ->where('status', 'published')
            ->firstOrFail();
    }

    public function render()
    {
        $ogImage = $this->article->image
            ? asset('storage/'.$this->article->image)
            : null;

        return view('livewire.pages.blog-show')
            ->title($this->article->title.' - ExoInvite Blog')
            ->layoutData([
                'metaDescription' => $this->article->getEffectiveMetaDescription(),
                'metaKeywords' => $this->article->meta_keywords,
                'canonicalUrl' => route('articles.show', $this->article->slug),
                'ogType' => 'article',
                'ogImage' => $ogImage,
            ]);
    }
}
