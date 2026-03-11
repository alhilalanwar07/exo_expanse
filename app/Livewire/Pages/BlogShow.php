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
        return view('livewire.pages.blog-show')
            ->title($this->article->title . ' - ExoInvite Blog');
    }
}
