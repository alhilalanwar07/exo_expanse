<?php

namespace App\Livewire\Pages;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('layouts.guest')]
#[Title('Blog - ExoInvite')]
class BlogList extends Component
{
    use WithPagination;

    public function render()
    {
        $articles = Article::where('status', 'published')
            ->whereNotNull('published_at')
            ->orderByDesc('published_at')
            ->paginate(12);

        return view('livewire.pages.blog-list', [
            'articles' => $articles,
        ]);
    }
}
