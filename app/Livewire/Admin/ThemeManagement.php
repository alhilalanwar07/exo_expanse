<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.admin')]
class ThemeManagement extends Component
{
    use WithPagination;

    #[Url(history: true)]
    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage(); // Reset offset paginasi jika lagi ngetik pencarian
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

    public function render()
    {
        $themes = Theme::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('slug', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(12);

        return view('livewire.admin.theme-management', [
            'themes' => $themes
        ]);
    }
}
