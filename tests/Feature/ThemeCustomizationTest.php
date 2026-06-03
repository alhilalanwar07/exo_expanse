<?php

namespace Tests\Feature;

use App\Models\Invitation;
use App\Models\Theme;
use App\Models\User;
use App\Services\ThemeBuilderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ThemeCustomizationTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected Theme $theme;

    protected Invitation $invitation;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create();

        $this->theme = Theme::create([
            'name' => 'Test Theme',
            'slug' => 'test-theme',
            'view_file' => 'themes.test',
            'is_active' => true,
            'is_premium' => false,
            'primary_color' => '#FFFFFF',
            'secondary_color' => '#000000',
            'accent_color' => '#FF0000',
            'text_color' => '#333333',
            'heading_color' => '#000000',
            'background_color' => '#F5F5F5',
            'heading_font' => 'Playfair Display',
            'body_font' => 'Roboto',
            'accent_font' => 'Great Vibes',
            'container_max_width' => 1200,
            'heading_size' => 48,
            'border_radius' => 8,
        ]);

        $this->invitation = Invitation::create([
            'user_id' => $this->user->id,
            'theme_id' => $this->theme->id,
            'slug' => 'test-invitation',
            'title' => 'Test Invitation',
            'event_date' => now()->addMonth(),
        ]);
    }

    public function test_can_get_theme_customization()
    {
        $customization = $this->invitation->getThemeCustomization();

        $this->assertIsArray($customization);
        $this->assertArrayHasKey('colors', $customization);
        $this->assertArrayHasKey('fonts', $customization);
        $this->assertArrayHasKey('custom_css', $customization);
    }

    public function test_can_get_theme_styles()
    {
        $styles = $this->invitation->getThemeStyles();

        $this->assertIsString($styles);
        $this->assertStringContainsString(':root', $styles);
        $this->assertStringContainsString('--color-primary', $styles);
        $this->assertStringContainsString('--font-heading', $styles);
    }

    public function test_can_get_theme_config()
    {
        $config = $this->invitation->getThemeConfig();

        $this->assertIsArray($config);
        $this->assertEquals($this->theme->id, $config['id']);
        $this->assertEquals($this->theme->name, $config['name']);
        $this->assertArrayHasKey('colors', $config);
        $this->assertArrayHasKey('fonts', $config);
        $this->assertArrayHasKey('layout', $config);
        $this->assertArrayHasKey('googleFontsUrl', $config);
    }

    public function test_can_update_theme_customization()
    {
        $colors = [
            'primary' => '#FF0000',
            'accent' => '#00FF00',
        ];

        $fonts = [
            'heading_font' => 'Cormorant Garamond',
        ];

        $this->invitation->updateThemeCustomization(
            colors: $colors,
            fonts: $fonts,
            customCss: '.custom { color: blue; }'
        );

        $this->invitation->refresh();

        $customization = $this->invitation->getThemeCustomization();
        $this->assertEquals($colors['primary'], $customization['colors']['primary']);
        $this->assertEquals($fonts['heading_font'], $customization['fonts']['heading_font']);
        $this->assertStringContainsString('.custom', $customization['custom_css']);
    }

    public function test_custom_colors_override_theme_colors()
    {
        $this->invitation->updateThemeCustomization(
            colors: ['primary' => '#CUSTOM123']
        );

        $config = $this->invitation->getThemeConfig();

        $this->assertEquals('#CUSTOM123', $config['colors']['primary']);
    }

    public function test_custom_fonts_override_theme_fonts()
    {
        $this->invitation->updateThemeCustomization(
            fonts: ['heading_font' => 'Custom Font']
        );

        $config = $this->invitation->getThemeConfig();

        $this->assertEquals('Custom Font', $config['fonts']['heading']);
    }

    public function test_theme_builder_validates_css()
    {
        $validCss = '.class { color: red; }';
        $invalidCss = '<script>alert("xss")</script>';

        $this->assertTrue(ThemeBuilderService::validateCustomCss($validCss));
        $this->assertFalse(ThemeBuilderService::validateCustomCss($invalidCss));
    }

    public function test_theme_builder_gets_available_fonts()
    {
        $fonts = ThemeBuilderService::getAvailableFonts();

        $this->assertIsArray($fonts);
        $this->assertArrayHasKey('Playfair Display', $fonts);
        $this->assertArrayHasKey('Roboto', $fonts);
        $this->assertGreaterThan(10, count($fonts));
    }

    public function test_google_fonts_import_url_is_valid()
    {
        $url = ThemeBuilderService::getGoogleFontsImport($this->theme);

        $this->assertStringStartsWith('https://fonts.googleapis.com', $url);
        $this->assertStringContainsString('Playfair', $url);
        $this->assertStringContainsString('Roboto', $url);
    }

    public function test_theme_to_config_array()
    {
        $config = $this->theme->toConfigArray();

        $this->assertEquals($this->theme->id, $config['id']);
        $this->assertEquals($this->theme->name, $config['name']);
        $this->assertArrayHasKey('colors', $config);
        $this->assertArrayHasKey('fonts', $config);
        $this->assertArrayHasKey('layout', $config);
    }
}
