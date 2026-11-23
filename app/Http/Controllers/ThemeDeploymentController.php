<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;

class ThemeDeploymentController extends Controller
{
    /**
     * Ensure required columns exist on the `themes` table even if an earlier unrelated migration blocks global migrate.
     */
    private function syncThemesSchema(): string
    {
        $migrationStatus = 'Sudah up-to-date';
        try {
            Artisan::call('migrate', ['--force' => true]);
            $migrationStatus = trim(Artisan::output()) ?: 'Migrasi sukses / up-to-date';
        } catch (\Throwable $e) {
            $migrationStatus = 'Global migrate dilewati: ' . $e->getMessage();
        }

        $requiredCols = ['description', 'category', 'custom_css', 'sections_config'];
        $missing = fn () => array_filter($requiredCols, fn (string $col) => ! Schema::hasColumn('themes', $col));

        if ($missing() !== []) {
            foreach ([
                'database/migrations/2026_06_02_000001_add_description_and_category_to_themes_table.php',
                'database/migrations/2026_06_02_000002_add_sections_config_to_themes_table.php',
            ] as $migPath) {
                try {
                    Artisan::call('migrate', ['--path' => $migPath, '--force' => true]);
                } catch (\Throwable $e) {
                    $migrationStatus .= ' | ' . basename($migPath) . ': ' . $e->getMessage();
                }
            }
        }

        if ($missing() !== []) {
            try {
                Schema::table('themes', function (Blueprint $table) {
                    if (! Schema::hasColumn('themes', 'description')) {
                        $table->text('description')->nullable()->after('slug');
                    }
                    if (! Schema::hasColumn('themes', 'category')) {
                        $table->string('category')->nullable()->after('description')->comment('e.g. Islamic, Adat, Modern, Nature, Minimalist');
                    }
                    if (! Schema::hasColumn('themes', 'custom_css')) {
                        $table->text('custom_css')->nullable()->after('button_style');
                    }
                    if (! Schema::hasColumn('themes', 'sections_config')) {
                        $table->json('sections_config')->nullable()->after('custom_css');
                    }
                });
                $migrationStatus .= ' | Kolom tabel themes disinkronkan via Schema';
            } catch (\Throwable $e) {
                $migrationStatus .= ' | Schema fallback error: ' . $e->getMessage();
            }
        }

        return $migrationStatus;
    }

    /**
     * Add or update the Crimson Botanical theme in database and clear caches on hosting.
     */
    public function addCrimsonBotanicalTheme(Request $request)
    {
        // 1. Pastikan migrasi dan skema tabel `themes` lengkap di hosting
        $migrationStatus = $this->syncThemesSchema();

        // 2. Data konfigurasi tema Crimson Botanical
        $themeData = [
            'name' => 'Crimson Botanical',
            'slug' => 'crimson-botanical',
            'view_file' => 'themes.crimson-botanical',
            'thumbnail_url' => '/themes/crimson-botanical/assets/thumbnail.jpg',
            'is_active' => true,
            'is_premium' => true,
            'category' => 'Floral',
            'description' => 'Tema vintage botanical berlatar parchment hangat dengan palet Crimson Wine & Warm Gold, bingkai arch elegan, countdown, galeri lightbox, amplop digital ATM, dan RSVP.',
            'primary_color' => '#8B1E2B',
            'secondary_color' => '#5C111B',
            'accent_color' => '#C59B5F',
            'text_color' => '#332527',
            'heading_color' => '#8B1E2B',
            'background_color' => '#F7F1E8',
            'heading_font' => 'Cormorant Garamond',
            'body_font' => 'Nunito Sans',
            'accent_font' => 'Galada',
            'container_max_width' => 440,
            'heading_size' => 40,
            'border_radius' => '16px',
            'button_style' => 'rounded',
            'overlay_opacity' => 45,
            'sections_config' => [
                'sections' => [
                    ['id' => 'cover', 'enabled' => true, 'order' => 0, 'config' => [
                        'title_text' => 'The Wedding Of',
                        'button_text' => 'Buka Undangan',
                    ]],
                    ['id' => 'hero', 'enabled' => true, 'order' => 1, 'config' => []],
                    ['id' => 'quote', 'enabled' => true, 'order' => 2, 'config' => [
                        'quote_title' => 'Q.S Ar-Rum : 21',
                        'quote_text' => 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.',
                    ]],
                    ['id' => 'couple', 'enabled' => true, 'order' => 3, 'config' => []],
                    ['id' => 'events', 'enabled' => true, 'order' => 4, 'config' => []],
                    ['id' => 'countdown', 'enabled' => true, 'order' => 5, 'config' => []],
                    ['id' => 'lovestory', 'enabled' => true, 'order' => 6, 'config' => []],
                    ['id' => 'gallery', 'enabled' => true, 'order' => 7, 'config' => []],
                    ['id' => 'gift', 'enabled' => true, 'order' => 8, 'config' => []],
                    ['id' => 'rsvp', 'enabled' => true, 'order' => 9, 'config' => []],
                    ['id' => 'closing', 'enabled' => true, 'order' => 10, 'config' => []],
                ],
                'nav' => [
                    'bg_color' => 'rgba(139, 30, 43, 0.96)',
                    'active_color' => '#C59B5F',
                    'inactive_color' => '#FFFFFF',
                ],
            ],
        ];

        // 3. Simpan atau perbarui data di database (filter sesuai kolom aktual)
        $existingColumns = Schema::getColumnListing('themes');
        $filteredThemeData = $existingColumns !== []
            ? array_intersect_key($themeData, array_flip($existingColumns))
            : $themeData;

        $theme = Theme::updateOrCreate(
            ['slug' => 'crimson-botanical'],
            $filteredThemeData
        );

        // 4. Hitung jumlah berkas aset public tema Crimson Botanical
        $publicAssetPath = public_path('themes/crimson-botanical/assets');
        $assetCount = File::isDirectory($publicAssetPath)
            ? count(File::files($publicAssetPath))
            : 0;

        // 5. Bersihkan cache aplikasi di hosting
        $cacheStatus = 'Cache view, route, dan config berhasil dibersihkan.';
        try {
            Artisan::call('view:clear');
            Artisan::call('route:clear');
            Artisan::call('config:clear');
        } catch (\Throwable $e) {
            $cacheStatus = 'Pembersihan cache: ' . $e->getMessage();
        }

        // 6. Response JSON jika diminta
        if ($request->wantsJson() || $request->query('format') === 'json') {
            return response()->json([
                'status' => 'success',
                'message' => 'Tema Crimson Botanical berhasil ditambahkan/diperbarui di hosting.',
                'theme' => [
                    'id' => $theme->id,
                    'name' => $theme->name,
                    'slug' => $theme->slug,
                    'view_file' => $theme->view_file,
                    'is_active' => $theme->is_active,
                    'is_premium' => $theme->is_premium,
                ],
                'assets_count' => $assetCount,
                'migration' => $migrationStatus,
                'cache' => $cacheStatus,
            ]);
        }

        // 7. Response HTML bernuansa Crimson Wine & Warm Gold
        $html = "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Deployment Tema Crimson Botanical Berhasil</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    background: linear-gradient(160deg, #5C111B 0%, #3A080F 100%);
                    color: #ffffff;
                    margin: 0;
                    padding: 40px 16px;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    min-height: 100vh;
                    box-sizing: border-box;
                }
                .card {
                    width: 100%;
                    max-width: 580px;
                    background: rgba(58, 8, 15, 0.94);
                    border: 1px solid rgba(197, 155, 95, 0.55);
                    border-radius: 18px;
                    padding: 32px 28px;
                    box-shadow: 0 18px 45px rgba(0,0,0,0.6), inset 0 0 18px rgba(197, 155, 95, 0.12);
                    text-align: center;
                }
                .badge {
                    display: inline-block;
                    background: #22c55e;
                    color: #052e16;
                    font-weight: 700;
                    padding: 4px 14px;
                    border-radius: 20px;
                    font-size: 12px;
                    margin-bottom: 16px;
                    letter-spacing: 0.5px;
                }
                h1 {
                    color: #C59B5F;
                    margin: 0 0 10px;
                    font-size: 24px;
                }
                p {
                    color: rgba(255,255,255,0.85);
                    font-size: 14px;
                    line-height: 1.6;
                    margin: 0 0 20px;
                }
                .details {
                    background: rgba(0,0,0,0.3);
                    border: 1px solid rgba(197, 155, 95, 0.25);
                    border-radius: 12px;
                    padding: 16px 20px;
                    text-align: left;
                    font-size: 13px;
                    margin: 20px 0;
                }
                .details-row {
                    display: flex;
                    justify-content: space-between;
                    padding: 6px 0;
                    border-bottom: 1px solid rgba(255,255,255,0.06);
                }
                .details-row:last-child { border-bottom: none; }
                .details-label { color: rgba(255,255,255,0.65); }
                .details-val { color: #F1D68A; font-weight: 600; }
                .btn-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: center;
                    margin-top: 24px;
                }
                .btn {
                    background: #8B1E2B;
                    color: #ffffff;
                    border: 1px solid #C59B5F;
                    font-weight: 700;
                    text-decoration: none;
                    padding: 10px 20px;
                    border-radius: 10px;
                    font-size: 13px;
                    transition: all 0.2s;
                }
                .btn:hover { background: #A52837; transform: translateY(-1px); }
                .btn-outline {
                    background: rgba(255,255,255,0.08);
                    color: #ffffff;
                    border: 1px solid rgba(197, 155, 95, 0.45);
                }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='badge'>&#10003; DEPLOYMENT SUKSES</div>
                <h1>Tema Crimson Botanical Siap Digunakan!</h1>
                <p>Tema baru berhasil ditambahkan dan disinkronkan ke database hosting beserta konfigurasi dan berkas asetnya.</p>
                <div class='details'>
                    <div class='details-row'><span class='details-label'>ID Tema:</span><span class='details-val'>" . e((string) $theme->id) . "</span></div>
                    <div class='details-row'><span class='details-label'>Nama Tema:</span><span class='details-val'>" . e((string) $theme->name) . "</span></div>
                    <div class='details-row'><span class='details-label'>Slug:</span><span class='details-val'>" . e((string) $theme->slug) . "</span></div>
                    <div class='details-row'><span class='details-label'>View File:</span><span class='details-val'>" . e((string) $theme->view_file) . "</span></div>
                    <div class='details-row'><span class='details-label'>Kategori:</span><span class='details-val'>" . e((string) ($theme->category ?? 'Floral')) . "</span></div>
                    <div class='details-row'><span class='details-label'>Jumlah Berkas Aset:</span><span class='details-val'>" . e((string) $assetCount) . " files</span></div>
                    <div class='details-row'><span class='details-label'>Status Migrasi:</span><span class='details-val' style='max-width:65%; text-align:right; word-break:break-word;'>" . e($migrationStatus) . "</span></div>
                    <div class='details-row'><span class='details-label'>Cache Status:</span><span class='details-val' style='color:#4ade80;'>" . e($cacheStatus) . "</span></div>
                </div>
                <div class='btn-group'>
                    <a href='/i/demo?theme=crimson-botanical' class='btn'>Preview Demo</a>
                    <a href='/admin/themes' class='btn btn-outline'>Kelola Tema</a>
                    <a href='/dashboard' class='btn btn-outline'>Dashboard</a>
                </div>
            </div>
        </body>
        </html>";

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
