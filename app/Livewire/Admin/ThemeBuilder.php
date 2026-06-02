<?php

namespace App\Livewire\Admin;

use App\Models\Theme;
use App\Services\ImageService;
use App\Services\ThemeBuilderService;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Url;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('components.layouts.admin')]
class ThemeBuilder extends Component
{
    use WithFileUploads;

    #[Locked]
    public ?int $themeId = null;

    // Metadata
    public string $name = '';
    public string $slug = '';
    public string $description = '';
    public string $category = '';
    public string $view_file = 'themes.generic';
    public bool $is_active = true;
    public bool $is_premium = false;

    // Colors
    public string $primary_color = '#C9A227';
    public string $secondary_color = '#1A1A1A';
    public string $accent_color = '#E8D5A3';
    public string $text_color = '#4A4A4A';
    public string $heading_color = '#1A1A1A';
    public string $background_color = '#FDF8F0';

    // Fonts
    public string $heading_font = 'Playfair Display';
    public string $body_font = 'Inter';
    public string $accent_font = 'Great Vibes';

    // Layout
    public int $container_max_width = 480;
    public int $heading_size = 40;
    public string $border_radius = '16px';
    public ?string $overlay_gradient = null;
    public int $overlay_opacity = 60;
    public string $button_style = 'rounded';

    // Advanced
    public string $custom_css = '';

    // File upload
    public $thumbnail;

    // UI state
    #[Url(history: true)]
    public string $activeTab = 'metadata';

    // Import JSON
    public string $importJson = '';
    public bool $showImportModal = false;

    /**
     * Mount component: create mode or edit mode.
     */
    public function mount(?int $id = null): void
    {
        if ($id) {
            $theme = Theme::findOrFail($id);
            $this->themeId = $theme->id;
            $this->fillFromTheme($theme);
        }
    }

    /**
     * Fill all properties from a Theme model.
     */
    protected function fillFromTheme(Theme $theme): void
    {
        $this->name = $theme->name;
        $this->slug = $theme->slug;
        $this->description = $theme->description ?? '';
        $this->category = $theme->category ?? '';
        $this->view_file = $theme->view_file ?? 'themes.generic';
        $this->is_active = $theme->is_active;
        $this->is_premium = $theme->is_premium;

        $this->primary_color = $theme->primary_color ?? '#C9A227';
        $this->secondary_color = $theme->secondary_color ?? '#1A1A1A';
        $this->accent_color = $theme->accent_color ?? '#E8D5A3';
        $this->text_color = $theme->text_color ?? '#4A4A4A';
        $this->heading_color = $theme->heading_color ?? '#1A1A1A';
        $this->background_color = $theme->background_color ?? '#FDF8F0';

        $this->heading_font = $theme->heading_font ?? 'Playfair Display';
        $this->body_font = $theme->body_font ?? 'Inter';
        $this->accent_font = $theme->accent_font ?? 'Great Vibes';

        $this->container_max_width = $theme->container_max_width ?? 480;
        $this->heading_size = $theme->heading_size ?? 40;
        $this->border_radius = $theme->border_radius ?? '16px';
        $this->overlay_gradient = $theme->overlay_gradient;
        $this->overlay_opacity = $theme->overlay_opacity ?? 60;
        $this->button_style = $theme->button_style ?? 'rounded';

        $this->custom_css = $theme->custom_css ?? '';
    }

    /**
     * Auto-generate slug from name.
     */
    public function updatedName(): void
    {
        if (! $this->themeId) {
            $this->slug = Str::slug($this->name);
        }
    }

    /**
     * Get available view files (Blade templates) from themes directory.
     */
    #[Computed]
    public function availableViewFiles(): array
    {
        $viewPath = resource_path('views/livewire/themes');
        $files = [];

        if (is_dir($viewPath)) {
            foreach (glob($viewPath . '/*.blade.php') as $file) {
                $basename = basename($file, '.blade.php');
                $files['themes.' . $basename] = ucwords(str_replace('-', ' ', $basename));
            }
        }

        return $files;
    }

    /**
     * Get available Google Fonts.
     */
    #[Computed]
    public function availableFonts(): array
    {
        return ThemeBuilderService::getAvailableFonts();
    }

    /**
     * Get theme categories.
     */
    #[Computed]
    public function categories(): array
    {
        return Theme::CATEGORIES;
    }

    /**
     * Get predefined color palettes.
     */
    #[Computed]
    public function colorPresets(): array
    {
        return [
            'Royal Gold' => [
                'primary' => '#C9A227', 'secondary' => '#1A1A1A', 'accent' => '#E8D5A3',
                'text' => '#4A4A4A', 'heading' => '#1A1A1A', 'background' => '#FDF8F0',
            ],
            'Rose Garden' => [
                'primary' => '#D4A5A5', 'secondary' => '#3D3D3D', 'accent' => '#9CAF88',
                'text' => '#5A5A5A', 'heading' => '#3D3D3D', 'background' => '#FFFBFC',
            ],
            'Sage Nature' => [
                'primary' => '#6B8E6B', 'secondary' => '#2D3B2D', 'accent' => '#C4A35A',
                'text' => '#4A5548', 'heading' => '#2D3B2D', 'background' => '#F5F7F2',
            ],
            'Midnight Dark' => [
                'primary' => '#f59e0b', 'secondary' => '#0f172a', 'accent' => '#fbbf24',
                'text' => '#e2e8f0', 'heading' => '#f8fafc', 'background' => '#1e293b',
            ],
            'Islamic Noir' => [
                'primary' => '#D4AF37', 'secondary' => '#1a1a1a', 'accent' => '#FFD700',
                'text' => '#f0f0f0', 'heading' => '#D4AF37', 'background' => '#050505',
            ],
            'Mystic Forest' => [
                'primary' => '#134640', 'secondary' => '#071A18', 'accent' => '#D4AF37',
                'text' => '#E8E3D1', 'heading' => '#D4AF37', 'background' => '#071A18',
            ],
            'Clean Minimal' => [
                'primary' => '#1f2937', 'secondary' => '#f8fafc', 'accent' => '#f59e0b',
                'text' => '#374151', 'heading' => '#111827', 'background' => '#f8fafc',
            ],
            'Burgundy Adat' => [
                'primary' => '#5d071f', 'secondary' => '#3d0d19', 'accent' => '#d4b051',
                'text' => '#ffffff', 'heading' => '#d4b051', 'background' => '#5d071f',
            ],
        ];
    }

    /**
     * Apply a color preset.
     */
    public function applyPreset(string $presetName): void
    {
        $presets = $this->colorPresets;

        if (isset($presets[$presetName])) {
            $preset = $presets[$presetName];
            $this->primary_color = $preset['primary'];
            $this->secondary_color = $preset['secondary'];
            $this->accent_color = $preset['accent'];
            $this->text_color = $preset['text'];
            $this->heading_color = $preset['heading'];
            $this->background_color = $preset['background'];
        }
    }

    /**
     * Handle thumbnail upload.
     */
    public function updatedThumbnail(): void
    {
        $this->validate([
            'thumbnail' => 'image|max:2048',
        ]);
    }

    /**
     * Save theme (create or update).
     */
    public function save(bool $redirect = false): void
    {
        $validated = $this->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|max:255|unique:themes,slug,' . $this->themeId,
            'description' => 'nullable|string|max:1000',
            'category' => 'nullable|string|max:100',
            'view_file' => 'required|string|max:255',
            'is_active' => 'boolean',
            'is_premium' => 'boolean',
            'primary_color' => 'required|string|max:20',
            'secondary_color' => 'required|string|max:20',
            'accent_color' => 'required|string|max:20',
            'text_color' => 'required|string|max:20',
            'heading_color' => 'required|string|max:20',
            'background_color' => 'required|string|max:20',
            'heading_font' => 'required|string|max:100',
            'body_font' => 'required|string|max:100',
            'accent_font' => 'required|string|max:100',
            'container_max_width' => 'required|integer|min:300|max:800',
            'heading_size' => 'required|integer|min:16|max:72',
            'border_radius' => 'required|string|max:20',
            'overlay_gradient' => 'nullable|string|max:500',
            'overlay_opacity' => 'required|integer|min:0|max:100',
            'button_style' => 'required|string|in:rounded,pill,square,boxy',
            'custom_css' => 'nullable|string|max:10000',
        ]);

        // Validate custom CSS for XSS
        if (! empty($validated['custom_css']) && ! ThemeBuilderService::validateCustomCss($validated['custom_css'])) {
            $this->addError('custom_css', 'Custom CSS mengandung kode berbahaya. Hapus javascript:, <script>, expression(), atau behavior:.');
            return;
        }

        // Handle thumbnail upload
        $thumbnailUrl = null;
        if ($this->thumbnail) {
            // Delete old thumbnail if editing
            if ($this->themeId) {
                $theme = Theme::find($this->themeId);
                if ($theme && $theme->thumbnail_url && str_starts_with($theme->thumbnail_url, '/storage/')) {
                    $oldPath = str_replace('/storage/', '', $theme->thumbnail_url);
                    Storage::disk('public')->delete($oldPath);
                }
            }

            $imageService = app(ImageService::class);
            $path = $imageService->storeAsWebp($this->thumbnail, 'themes');
            $thumbnailUrl = '/storage/' . $path;
        }

        $data = collect($validated)->except(['thumbnail'])->toArray();

        if ($thumbnailUrl) {
            $data['thumbnail_url'] = $thumbnailUrl;
        }

        if ($this->themeId) {
            $theme = Theme::findOrFail($this->themeId);
            $theme->update($data);
            $message = 'Tema berhasil diperbarui.';
        } else {
            $theme = Theme::create($data);
            $this->themeId = $theme->id;
            $message = 'Tema baru berhasil dibuat.';
        }

        $this->thumbnail = null;
        $this->dispatch('toast', message: $message, type: 'success');

        if ($redirect) {
            $this->redirect(route('admin.themes'), navigate: true);
        }
    }

    /**
     * Save and redirect back to list.
     */
    public function saveAndClose(): void
    {
        $this->save(redirect: true);
    }

    /**
     * Reset colors to defaults.
     */
    public function resetColors(): void
    {
        $this->primary_color = '#C9A227';
        $this->secondary_color = '#1A1A1A';
        $this->accent_color = '#E8D5A3';
        $this->text_color = '#4A4A4A';
        $this->heading_color = '#1A1A1A';
        $this->background_color = '#FDF8F0';
    }

    /**
     * Reset fonts to defaults.
     */
    public function resetFonts(): void
    {
        $this->heading_font = 'Playfair Display';
        $this->body_font = 'Inter';
        $this->accent_font = 'Great Vibes';
        $this->heading_size = 40;
    }

    /**
     * Reset layout to defaults.
     */
    public function resetLayout(): void
    {
        $this->container_max_width = 480;
        $this->border_radius = '16px';
        $this->overlay_opacity = 60;
        $this->button_style = 'rounded';
    }

    /**
     * Export current form state as JSON string.
     */
    #[Computed]
    public function exportJson(): string
    {
        $data = [
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'category' => $this->category,
            'view_file' => $this->view_file,
            'is_active' => $this->is_active,
            'is_premium' => $this->is_premium,
            'primary_color' => $this->primary_color,
            'secondary_color' => $this->secondary_color,
            'accent_color' => $this->accent_color,
            'text_color' => $this->text_color,
            'heading_color' => $this->heading_color,
            'background_color' => $this->background_color,
            'heading_font' => $this->heading_font,
            'body_font' => $this->body_font,
            'accent_font' => $this->accent_font,
            'container_max_width' => $this->container_max_width,
            'heading_size' => $this->heading_size,
            'border_radius' => $this->border_radius,
            'overlay_gradient' => $this->overlay_gradient,
            'overlay_opacity' => $this->overlay_opacity,
            'button_style' => $this->button_style,
            'custom_css' => $this->custom_css,
        ];

        return json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Import theme from JSON string.
     */
    public function importFromJson(): void
    {
        $this->validate([
            'importJson' => 'required|string',
        ]);

        try {
            $data = json_decode($this->importJson, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            $this->addError('importJson', 'Format JSON tidak valid: ' . $e->getMessage());
            return;
        }

        // Fill properties from imported data
        $this->name = $data['name'] ?? $this->name;
        $this->slug = $data['slug'] ?? $this->slug;
        $this->description = $data['description'] ?? '';
        $this->category = $data['category'] ?? '';
        $this->view_file = $data['view_file'] ?? 'themes.generic';
        $this->is_active = $data['is_active'] ?? true;
        $this->is_premium = $data['is_premium'] ?? false;

        $this->primary_color = $data['primary_color'] ?? $this->primary_color;
        $this->secondary_color = $data['secondary_color'] ?? $this->secondary_color;
        $this->accent_color = $data['accent_color'] ?? $this->accent_color;
        $this->text_color = $data['text_color'] ?? $this->text_color;
        $this->heading_color = $data['heading_color'] ?? $this->heading_color;
        $this->background_color = $data['background_color'] ?? $this->background_color;

        $this->heading_font = $data['heading_font'] ?? $this->heading_font;
        $this->body_font = $data['body_font'] ?? $this->body_font;
        $this->accent_font = $data['accent_font'] ?? $this->accent_font;

        $this->container_max_width = $data['container_max_width'] ?? $this->container_max_width;
        $this->heading_size = $data['heading_size'] ?? $this->heading_size;
        $this->border_radius = $data['border_radius'] ?? $this->border_radius;
        $this->overlay_gradient = $data['overlay_gradient'] ?? null;
        $this->overlay_opacity = $data['overlay_opacity'] ?? $this->overlay_opacity;
        $this->button_style = $data['button_style'] ?? $this->button_style;
        $this->custom_css = $data['custom_css'] ?? '';

        $this->showImportModal = false;
        $this->importJson = '';
        $this->dispatch('toast', message: 'Data JSON berhasil diimpor ke form. Klik Simpan untuk menyimpan.', type: 'success');
    }

    /**
     * Generate preview CSS variables string.
     */
    #[Computed]
    public function previewCss(): string
    {
        return <<<CSS
        --color-primary: {$this->primary_color};
        --color-secondary: {$this->secondary_color};
        --color-accent: {$this->accent_color};
        --color-text: {$this->text_color};
        --color-heading: {$this->heading_color};
        --color-background: {$this->background_color};
        --font-heading: '{$this->heading_font}', serif;
        --font-body: '{$this->body_font}', sans-serif;
        --font-accent: '{$this->accent_font}', cursive;
        --container-max-width: {$this->container_max_width}px;
        --heading-size: {$this->heading_size}px;
        --border-radius: {$this->border_radius};
        CSS;
    }

    /**
     * Build Google Fonts URL for preview.
     */
    #[Computed]
    public function previewFontsUrl(): string
    {
        $fonts = collect([$this->heading_font, $this->body_font, $this->accent_font])
            ->unique()
            ->map(fn($font) => str_replace(' ', '+', $font) . ':wght@300;400;500;600;700')
            ->implode('&family=');

        return "https://fonts.googleapis.com/css2?family={$fonts}&display=swap";
    }

    public function render()
    {
        return view('livewire.admin.theme-builder');
    }
}
