<?php

namespace App\Livewire\Admin;

use App\Models\Article;
use App\Services\ArticleGeneratorService;
use Illuminate\Support\Str;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ArticleForm extends Component
{
    use WithFileUploads;

    public ?Article $article = null;

    public string $title = '';

    public string $slug = '';

    public string $excerpt = '';

    public string $content = '';

    public string $meta_description = '';

    public string $focus_keyword = '';

    public string $meta_keywords = '';

    public string $status = 'draft';

    public $image;

    public ?string $existing_image = null;

    public bool $generating = false;

    public ?string $generateError = null;

    public function mount(?int $id = null): void
    {
        if ($id) {
            $this->article = Article::findOrFail($id);
            $this->title = $this->article->title;
            $this->slug = $this->article->slug;
            $this->excerpt = $this->article->excerpt ?? '';
            $this->content = $this->article->content ?? '';
            $this->meta_description = $this->article->meta_description ?? '';
            $this->focus_keyword = $this->article->focus_keyword ?? '';
            $this->meta_keywords = $this->article->meta_keywords ?? '';
            $this->status = $this->article->status;
            $this->existing_image = $this->article->image;
        }
    }

    public function updatedTitle(): void
    {
        if (! $this->article) {
            $this->slug = Str::slug($this->title);
        }
    }

    public function generateSlug(): void
    {
        $this->slug = Str::slug($this->title);
    }

    /**
     * Generate all fields (content + SEO + excerpt) from the title using AI.
     */
    public function generateAll(): void
    {
        if (empty(trim($this->title))) {
            $this->generateError = 'Masukkan judul artikel terlebih dahulu.';

            return;
        }

        $this->generating = true;
        $this->generateError = null;

        $service = app(ArticleGeneratorService::class);
        $result = $service->generate($this->title);

        if ($result) {
            $this->content = $result['content'];
            $this->excerpt = $result['excerpt'];
            $this->meta_description = $result['meta_description'];
            $this->focus_keyword = $result['focus_keyword'];
            $this->meta_keywords = $result['meta_keywords'];

            if (empty($this->slug)) {
                $this->slug = Str::slug($this->title);
            }

            $this->dispatch('content-updated', content: $this->content);
            $this->dispatch('toast', message: 'Artikel berhasil di-generate!', type: 'success');
        } else {
            $this->generateError = 'Gagal generate artikel. Coba lagi nanti.';
            $this->dispatch('toast', message: 'Gagal generate artikel.', type: 'error');
        }

        $this->generating = false;
    }

    /**
     * Regenerate only the content, then auto-update SEO + excerpt to match.
     */
    public function regenerateContent(): void
    {
        if (empty(trim($this->title))) {
            $this->generateError = 'Masukkan judul artikel terlebih dahulu.';

            return;
        }

        $this->generating = true;
        $this->generateError = null;

        $service = app(ArticleGeneratorService::class);
        $result = $service->generate($this->title);

        if ($result) {
            $this->content = $result['content'];
            $this->excerpt = $result['excerpt'];
            $this->meta_description = $result['meta_description'];
            $this->focus_keyword = $result['focus_keyword'];
            $this->meta_keywords = $result['meta_keywords'];

            $this->dispatch('content-updated', content: $this->content);
            $this->dispatch('toast', message: 'Konten berhasil di-regenerate!', type: 'success');
        } else {
            $this->generateError = 'Gagal regenerate konten. Coba lagi nanti.';
            $this->dispatch('toast', message: 'Gagal regenerate konten.', type: 'error');
        }

        $this->generating = false;
    }

    public function save(): void
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:articles,slug'.($this->article ? ','.$this->article->id : ''),
            'excerpt' => 'nullable|string|max:500',
            'content' => 'required|string',
            'meta_description' => 'nullable|string|max:160',
            'focus_keyword' => 'nullable|string|max:100',
            'meta_keywords' => 'nullable|string|max:255',
            'status' => 'required|in:draft,published',
            'image' => 'nullable|image|max:10240',
        ]);

        $data = [
            'title' => $this->title,
            'slug' => $this->slug,
            'excerpt' => $this->excerpt ?: Str::limit(strip_tags($this->content), 200),
            'content' => $this->content,
            'meta_description' => $this->meta_description,
            'focus_keyword' => $this->focus_keyword,
            'meta_keywords' => $this->meta_keywords,
            'status' => $this->status,
            'reading_time' => Article::calculateReadingTime($this->content),
        ];

        if ($this->status === 'published' && (! $this->article || $this->article->status !== 'published')) {
            $data['published_at'] = now();
        } elseif ($this->status === 'draft') {
            $data['published_at'] = null;
        }

        if ($this->image) {
            $imageService = app(\App\Services\ImageService::class);
            $data['image'] = $imageService->storeAsWebp($this->image, 'articles');
        }

        if ($this->article) {
            $this->article->update($data);
            $this->dispatch('toast', message: 'Artikel berhasil diperbarui.', type: 'success');
        } else {
            $data['user_id'] = auth()->id();
            $article = Article::create($data);
            $this->dispatch('toast', message: 'Artikel berhasil dibuat.', type: 'success');
            $this->redirect(route('admin.articles.edit', $article->id), navigate: true);
        }
    }

    public function removeImage(): void
    {
        $this->existing_image = null;
        if ($this->article) {
            $this->article->update(['image' => null]);
        }
        $this->dispatch('toast', message: 'Gambar berhasil dihapus.', type: 'success');
    }

    public function togglePublish(): void
    {
        if (! $this->article) {
            return;
        }

        if ($this->article->status === 'published') {
            $this->article->update(['status' => 'draft', 'published_at' => null]);
            $this->status = 'draft';
            $this->dispatch('toast', message: 'Artikel berhasil di-unpublish.', type: 'success');
        } else {
            $this->article->update(['status' => 'published', 'published_at' => now()]);
            $this->status = 'published';
            $this->dispatch('toast', message: 'Artikel berhasil dipublikasikan!', type: 'success');
        }

        $this->article->refresh();
    }

    public function render()
    {
        return view('livewire.admin.article-form');
    }
}
