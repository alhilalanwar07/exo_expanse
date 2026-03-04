<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Storage;

#[Layout('components.layouts.admin')]
class ThemeManagement extends Component
{
    use WithPagination, WithFileUploads;

    public $newThumbnail;
    public $uploadThemeId;
    
    public $showImportModal = false;
    public $themeCode = '';


    #[Url(history: true)]
    public $search = '';

    public $perPage = 12;

    public function updatingSearch()
    {
        $this->resetPage(); // Reset offset paginasi jika lagi ngetik pencarian
    }

    public function updatingPerPage()
    {
        $this->resetPage();
    }

    public function toggleActive($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['is_active' => !$theme->is_active]);
    }

    public function togglePremium($id)
    {
        $theme = Theme::findOrFail($id);
        $theme->update(['is_premium' => !$theme->is_premium]);
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

            $path = $this->newThumbnail->store('themes', 'public');
            $theme->update(['thumbnail_url' => '/storage/' . $path]);
            
            $this->reset(['uploadThemeId', 'newThumbnail']);
            session()->flash('message', 'Thumbnail berhasil diperbarui.');
        }
    }

    public function importTheme()
    {
        $this->validate([
            'themeCode' => 'required|string'
        ]);

        try {
            $code = trim($this->themeCode);
            $code = rtrim($code, ",;"); // Remove trailing comma/semicolon for safety
            
            // Evaluasi string PHP menjadi array
            $themeData = eval("return $code;");

            if (!is_array($themeData)) {
                throw new \Exception("Format tidak valid. Pastikan Anda menyalin array PHP yang benar.");
            }

            // Validasi data minimal (opsional)
            if (!isset($themeData['slug']) || !isset($themeData['name'])) {
                throw new \Exception("Array harus memiliki key 'name' dan 'slug'.");
            }

            Theme::create($themeData);

            $this->reset(['themeCode', 'showImportModal']);
            session()->flash('message', 'Tema baru berhasil ditambahkan.');
            
        } catch (\ParseError $e) {
            $this->addError('themeCode', 'Gagal mem-parsing array PHP: Cek syntax array Anda (contoh: kurang kurung, koma, dsb).');
        } catch (\Exception $e) {
            $this->addError('themeCode', 'Error: ' . $e->getMessage());
        }
    }


    public function render()
    {
        $themes = Theme::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.theme-management', [
            'themes' => $themes
        ]);
    }
}
