<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ThemeManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $newThumbnail;

    public $uploadThemeId;

    #[Url(history: true)]
    public $search = '';

    public $perPage = 12;

    public $confirmDeleteId = null;

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['is_active' => ! $theme->is_active]);
    }

    public function togglePremium($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['is_premium' => ! $theme->is_premium]);
    }

    public function triggerUpload($themeId)
    {
        $this->uploadThemeId = $themeId;
    }

    public function updatedNewThumbnail()
    {
        $this->validate([
            'newThumbnail' => 'image|max:2048', // 2MB Max
        ]);

        if ($this->uploadThemeId && $this->newThumbnail) {
            $theme = Theme::findOrFail($this->uploadThemeId);

            // Delete old thumbnail if it is from storage
            if ($theme->thumbnail_url && str_starts_with($theme->thumbnail_url, '/storage/')) {
                $oldPath = str_replace('/storage/', '', $theme->thumbnail_url);
                Storage::disk('public')->delete($oldPath);
            }

            $imageService = app(ImageService::class);
            $path = $imageService->storeAsWebp($this->newThumbnail, 'themes');
            $theme->update(['thumbnail_url' => '/storage/'.$path]);

            $this->reset(['uploadThemeId', 'newThumbnail']);
            $this->dispatch('toast', message: 'Thumbnail berhasil diperbarui.', type: 'success');
        }
    }

    /**
     * Duplicate a theme.
     */
    public function duplicateTheme(int $id): void
    {
        $theme = Theme::findOrFail($id);
        $newTheme = $theme->duplicate();
        $this->dispatch('toast', message: "Tema '{$newTheme->name}' berhasil dibuat sebagai duplikat.", type: 'success');
    }

    /**
     * Confirm delete (show confirmation).
     */
    public function confirmDelete(int $id): void
    {
        $this->confirmDeleteId = $id;
    }

    /**
     * Cancel delete confirmation.
     */
    public function cancelDelete(): void
    {
        $this->confirmDeleteId = null;
    }

    /**
     * Delete a theme (only if not used by any invitation).
     */
    public function deleteTheme(int $id): void
    {
        $theme = Theme::withCount('invitations')->findOrFail($id);

        if ($theme->invitations_count > 0) {
            $this->dispatch('toast', message: "Gagal menghapus: Tema '{$theme->name}' digunakan oleh {$theme->invitations_count} undangan.", type: 'error');
            $this->confirmDeleteId = null;
            return;
        }

        // Delete thumbnail if from storage
        if ($theme->thumbnail_url && str_starts_with($theme->thumbnail_url, '/storage/')) {
            $oldPath = str_replace('/storage/', '', $theme->thumbnail_url);
            Storage::disk('public')->delete($oldPath);
        }

        $themeName = $theme->name;
        $theme->delete();

        $this->confirmDeleteId = null;
        $this->dispatch('toast', message: "Tema '{$themeName}' berhasil dihapus.", type: 'success');
    }

    /**
     * Export theme as JSON (dispatch browser download).
     */
    public function exportTheme(int $id): void
    {
        $theme = Theme::findOrFail($id);
        $json = json_encode($theme->toExportArray(), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);

        $this->dispatch('download-json', filename: $theme->slug . '.json', content: $json);
    }

    public function render()
    {
        $themes = Theme::withCount('invitations')
            ->where(function ($query) {
                $query->where('name', 'like', '%'.$this->search.'%')
                    ->orWhere('slug', 'like', '%'.$this->search.'%');
            })
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.theme-management', [
            'themes' => $themes,
        ]);
    }
}

