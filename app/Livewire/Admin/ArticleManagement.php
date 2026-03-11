<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Computed;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ArticleManagement extends Component
{
    use WithPagination;

    public string $search = '';
    public string $statusFilter = '';

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function deleteArticle(int $id): void
    {
        Article::findOrFail($id)->delete();
    }

    public function togglePublish(int $id): void
    {
        $article = Article::findOrFail($id);

        if ($article->status === 'published') {
            $article->update(['status' => 'draft', 'published_at' => null]);
        } else {
            $article->update(['status' => 'published', 'published_at' => now()]);
        }
    }

    public function render()
    {
        $articles = Article::query()
            ->when($this->search, fn ($q) => $q->where('title', 'like', "%{$this->search}%"))
            ->when($this->statusFilter, fn ($q) => $q->where('status', $this->statusFilter))
            ->orderByDesc('created_at')
            ->paginate(15);

        return view('livewire.admin.article-management', [
            'articles' => $articles,
        ]);
    }
}
