<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use Livewire\Attributes\Layout;
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
        $this->dispatch('toast', message: 'Artikel berhasil dihapus.', type: 'success');
    }

    public function togglePublish(int $id): void
    {
        $article = Article::findOrFail($id);

        if ($article->status === 'published') {
            $article->update(['status' => 'draft', 'published_at' => null]);
            $this->dispatch('toast', message: 'Artikel berhasil di-unpublish.', type: 'success');
        } else {
            $article->update(['status' => 'published', 'published_at' => now()]);
            $this->dispatch('toast', message: 'Artikel berhasil dipublikasikan!', type: 'success');
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
