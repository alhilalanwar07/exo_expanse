<?php

namespace App\Livewire;

use App\Models\Invitation;
use App\Models\Theme;
use App\Services\ThemeBuilderService;
use Illuminate\View\View;
use Livewire\Component;

new class extends Component
{
    public Invitation $invitation;

    public int $selectedThemeId;

    public array $customColors = [
        'primary' => '',
        'secondary' => '',
        'accent' => '',
        'text' => '',
        'heading' => '',
        'background' => '',
    ];

    public array $customFonts = [
        'heading_font' => '',
        'body_font' => '',
        'accent_font' => '',
    ];

    public string $customCss = '';

    public bool $showPreview = true;

    public bool $showSuccess = false;

    #[\Livewire\Attributes\Computed]
    public function themes()
    {
        return Theme::where('is_active', true)->get();
    }

    #[\Livewire\Attributes\Computed]
    public function currentTheme()
    {
        return Theme::find($this->selectedThemeId);
    }

    #[\Livewire\Attributes\Computed]
    public function themePreviewStyles()
    {
        return ThemeBuilderService::buildStyles(
            $this->currentTheme(),
            $this->customColors,
            $this->customFonts,
            $this->customCss
        );
    }

    public function mount(Invitation $invitation): void
    {
        $this->invitation = $invitation;
        $this->selectedThemeId = $invitation->theme_id;

        // Load existing customization
        if ($invitation->theme_customization) {
            $custom = $invitation->theme_customization;
            $this->customColors = $custom['colors'] ?? $this->customColors;
            $this->customFonts = $custom['fonts'] ?? $this->customFonts;
            $this->customCss = $custom['custom_css'] ?? '';
        }
    }

    public function selectTheme(int $themeId): void
    {
        $this->selectedThemeId = $themeId;
        $this->resetCustomization();
    }

    public function resetCustomization(): void
    {
        $this->customColors = [
            'primary' => '',
            'secondary' => '',
            'accent' => '',
            'text' => '',
            'heading' => '',
            'background' => '',
        ];
        $this->customFonts = [
            'heading_font' => '',
            'body_font' => '',
            'accent_font' => '',
        ];
        $this->customCss = '';
    }

    public function updateColor(string $colorKey, string $value): void
    {
        $this->customColors[$colorKey] = $value;
    }

    public function updateFont(string $fontKey, string $value): void
    {
        $this->customFonts[$fontKey] = $value;
    }

    public function updateCustomCss(string $css): void
    {
        $this->customCss = $css;
    }

    public function save(): void
    {
        $this->invitation->update([
            'theme_id' => $this->selectedThemeId,
            'theme_customization' => [
                'colors' => $this->customColors,
                'fonts' => $this->customFonts,
                'custom_css' => $this->customCss,
            ],
        ]);

        $this->showSuccess = true;
        $this->dispatch('theme-updated', invitationId: $this->invitation->id);

        // Hide success message after 3 seconds
        $this->js('setTimeout(() => { $wire.showSuccess = false; $wire.$refresh(); }, 3000)');
    }

    public function togglePreview(): void
    {
        $this->showPreview = ! $this->showPreview;
    }
}; ?>

<div class="theme-customizer">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <h2 class="text-2xl font-bold text-gray-900">Customize Tema Undangan</h2>
            <button
                wire:click="togglePreview"
                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-lg hover:bg-gray-50">
                {{ $showPreview ? 'Sembunyikan' : 'Tampilkan' }} Preview
            </button>
        </div>
        <p class="mt-2 text-sm text-gray-600">
            Pilih tema dan sesuaikan warna, font, serta CSS sesuai keinginan Anda
        </p>
    </div>

    <!-- Success Message -->
    @if($showSuccess)
        <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
            <p class="text-sm font-medium text-green-800">
                ✓ Tema berhasil disimpan!
            </p>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Panel: Controls -->
        <div class="lg:col-span-1">
            <div class="sticky top-6 space-y-6">
                <!-- Theme Selection -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Pilih Tema</h3>

                    <div class="space-y-3">
                        @foreach($this->themes as $theme)
                            <button
                                wire:click="selectTheme({{ $theme->id }})"
                                class="w-full text-left p-3 rounded-lg border-2 transition-all {{ $selectedThemeId === $theme->id ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-medium text-gray-900">{{ $theme->name }}</h4>
                                        @if($theme->is_premium)
                                            <span class="inline-block mt-1 px-2 py-1 text-xs font-semibold text-yellow-700 bg-yellow-100 rounded">
                                                Premium
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                <!-- Color Preview -->
                                <div class="mt-3 flex gap-2">
                                    <div class="w-6 h-6 rounded border border-gray-200"
                                         style="background-color: {{ $theme->primary_color }}"></div>
                                    <div class="w-6 h-6 rounded border border-gray-200"
                                         style="background-color: {{ $theme->secondary_color }}"></div>
                                    <div class="w-6 h-6 rounded border border-gray-200"
                                         style="background-color: {{ $theme->accent_color }}"></div>
                                </div>
                            </button>
                        @endforeach
                    </div>
                </div>

                <!-- Color Customization -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Warna Kustom</h3>

                    <div class="space-y-3">
                        @foreach(['primary' => 'Warna Utama', 'secondary' => 'Warna Sekunder', 'accent' => 'Aksen', 'text' => 'Teks', 'heading' => 'Judul', 'background' => 'Latar Belakang'] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $label }}
                                </label>
                                <div class="flex items-center gap-2">
                                    <input
                                        type="color"
                                        wire:change="updateColor('{{ $key }}', $event.target.value)"
                                        value="{{ $customColors[$key] ?: ($this->currentTheme ? $this->currentTheme->{$key . '_color'} : '#000000') }}"
                                        class="w-12 h-10 rounded border border-gray-300 cursor-pointer">
                                    <input
                                        type="text"
                                        wire:change="updateColor('{{ $key }}', $event.target.value)"
                                        value="{{ $customColors[$key] ?: ($this->currentTheme ? $this->currentTheme->{$key . '_color'} : '#000000') }}"
                                        placeholder="#000000"
                                        class="flex-1 px-3 py-2 border border-gray-300 rounded-lg text-sm font-mono"
                                        pattern="^#[0-9A-Fa-f]{6}$">
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Font Customization -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Font</h3>

                    @php
                        $fontOptions = \App\Services\ThemeBuilderService::getAvailableFonts();
                    @endphp

                    <div class="space-y-3">
                        @foreach(['heading_font' => 'Font Judul', 'body_font' => 'Font Body', 'accent_font' => 'Font Aksen'] as $key => $label)
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    {{ $label }}
                                </label>
                                <select
                                    wire:change="updateFont('{{ $key }}', $event.target.value)"
                                    class="w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="">Default</option>
                                    @foreach($fontOptions as $font => $type)
                                        <option value="{{ $font }}" {{ ($customFonts[$key] ?: ($this->currentTheme ? $this->currentTheme->$key : '')) === $font ? 'selected' : '' }}>
                                            {{ $font }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Custom CSS -->
                <div class="bg-white rounded-lg border border-gray-200 p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Custom CSS</h3>

                    <textarea
                        wire:change="updateCustomCss($event.target.value)"
                        placeholder="/* Tambahkan CSS kustom di sini */&#10;.my-custom-style { ... }"
                        rows="4"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg text-xs font-mono focus:outline-none focus:ring-2 focus:ring-blue-500">{{ $customCss }}</textarea>
                    <p class="mt-2 text-xs text-gray-500">
                        Gunakan CSS variables: var(--color-primary), var(--font-heading), dll
                    </p>
                </div>

                <!-- Save Button -->
                <button
                    wire:click="save"
                    class="w-full px-6 py-3 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700 transition-colors">
                    💾 Simpan Perubahan
                </button>

                <!-- Reset Button -->
                <button
                    wire:click="resetCustomization"
                    class="w-full px-6 py-2 bg-gray-200 text-gray-800 font-medium rounded-lg hover:bg-gray-300 transition-colors">
                    ↻ Reset ke Default
                </button>
            </div>
        </div>

        <!-- Right Panel: Preview -->
        @if($showPreview)
            <div class="lg:col-span-2">
                <div class="sticky top-6">
                    <div class="bg-white rounded-lg border border-gray-200 overflow-hidden shadow-lg">
                        <!-- Preview Header -->
                        <div class="bg-gray-100 px-6 py-4 border-b border-gray-200">
                            <h3 class="text-sm font-semibold text-gray-900">
                                Preview - {{ $this->currentTheme->name ?? 'Loading...' }}
                            </h3>
                        </div>

                        <!-- Preview Styles -->
                        <style>
                            {!! $this->themePreviewStyles !!}
                        </style>

                        <!-- Preview Content -->
                        <div class="p-8" style="background-color: var(--color-background);">
                            <div class="max-w-full">
                                <!-- Sample Header -->
                                <div class="mb-8 pb-8 border-b-2" style="border-color: var(--color-accent);">
                                    <h1 class="text-4xl font-bold mb-2" style="color: var(--color-heading); font-family: var(--font-heading);">
                                        {{ $invitation->couple_names }}
                                    </h1>
                                    <p style="color: var(--color-accent); font-family: var(--font-accent); font-size: 1.25rem;">
                                        Getting Married
                                    </p>
                                </div>

                                <!-- Sample Content -->
                                <div class="grid grid-cols-2 gap-6 mb-8">
                                    <div>
                                        <h2 style="color: var(--color-heading); font-family: var(--font-heading);" class="text-lg font-semibold mb-2">
                                            Acara Akad
                                        </h2>
                                        <p style="color: var(--color-text); font-family: var(--font-body);" class="text-sm">
                                            Tempat: Grand Hotel<br>
                                            Waktu: 10.00 - 12.00 WIB
                                        </p>
                                    </div>
                                    <div>
                                        <h2 style="color: var(--color-heading); font-family: var(--font-heading);" class="text-lg font-semibold mb-2">
                                            Acara Resepsi
                                        </h2>
                                        <p style="color: var(--color-text); font-family: var(--font-body);" class="text-sm">
                                            Tempat: Ballroom Paradise<br>
                                            Waktu: 18.00 - 21.00 WIB
                                        </p>
                                    </div>
                                </div>

                                <!-- Sample Button -->
                                <button class="btn-primary px-6 py-2 rounded-lg font-medium">
                                    Konfirmasi Kehadiran
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
