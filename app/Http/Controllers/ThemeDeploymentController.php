<?php

namespace App\Http\Controllers;

use App\Models\Theme;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class ThemeDeploymentController extends Controller
{
    /**
     * Add or update the 3D Adat Bugis 02 theme in database and clear caches on hosting.
     */
    public function addAdatBugisTheme(Request $request)
    {
        // 1. Jalankan migrasi jika ada yang belum berjalan di hosting
        $migrationStatus = 'Sudah up-to-date';
        try {
            Artisan::call('migrate', ['--force' => true]);
            $migrationStatus = trim(Artisan::output()) ?: 'Migrasi sukses / up-to-date';
        } catch (\Throwable $e) {
            $migrationStatus = 'Error migrasi: ' . $e->getMessage();
        }

        // 2. Data konfigurasi tema 3D Adat Bugis 02
        $themeData = [
            'name' => '3D Adat Bugis 02',
            'slug' => '3d-adat-bugis-02',
            'view_file' => 'themes.3d-adat-bugis-02',
            'thumbnail_url' => '/themes/3d-adat-bugis-02/assets/3d-adat-bugis-02-compressed-1.webp',
            'is_active' => true,
            'is_premium' => true,
            'category' => 'Adat',
            'description' => 'Tema 3D Adat Bugis dengan animasi video 3D Baju Bodo adat Bugis maroon gold, ornamen khas, countdown, galeri, amplop digital, dan RSVP.',
            'primary_color' => '#5c0919',
            'secondary_color' => '#36050e',
            'accent_color' => '#dfb76c',
            'text_color' => '#ffffff',
            'heading_color' => '#dfb76c',
            'background_color' => '#5c0919',
            'heading_font' => 'Cinzel',
            'body_font' => 'Poppins',
            'accent_font' => 'Great Vibes',
            'container_max_width' => 480,
            'heading_size' => 42,
            'border_radius' => '16px',
            'button_style' => 'rounded',
            'overlay_opacity' => 70,
            'sections_config' => [
                'sections' => [
                    ['id' => 'cover', 'enabled' => true, 'order' => 0, 'config' => [
                        'title_text' => 'The Wedding Of',
                        'button_text' => 'Buka Undangan',
                    ]],
                    ['id' => 'hero3d', 'enabled' => true, 'order' => 1, 'config' => [
                        'video_url' => '/themes/3d-adat-bugis-02/assets/BUGIS-MAROON.mp4',
                        'fallback_image' => '/themes/3d-adat-bugis-02/assets/BUGISMAROONFALLBACK.jpg',
                    ]],
                    ['id' => 'quote', 'enabled' => true, 'order' => 2, 'config' => [
                        'quote_title' => 'QS. Ar-Rum 21',
                        'quote_text' => 'Dan di antara tanda-tanda (kebesaran)-Nya ialah Dia menciptakan pasangan-pasangan untukmu dari jenismu sendiri, agar kamu cenderung dan merasa tenteram kepadanya, dan Dia menjadikan di antaramu rasa kasih dan sayang.',
                    ]],
                    ['id' => 'couple', 'enabled' => true, 'order' => 3, 'config' => []],
                    ['id' => 'events', 'enabled' => true, 'order' => 4, 'config' => []],
                    ['id' => 'countdown', 'enabled' => true, 'order' => 5, 'config' => []],
                    ['id' => 'gallery', 'enabled' => true, 'order' => 6, 'config' => []],
                    ['id' => 'lovestory', 'enabled' => true, 'order' => 7, 'config' => []],
                    ['id' => 'gift', 'enabled' => true, 'order' => 8, 'config' => []],
                    ['id' => 'rsvp', 'enabled' => true, 'order' => 9, 'config' => []],
                    ['id' => 'closing', 'enabled' => true, 'order' => 10, 'config' => []],
                ],
                'frame' => [
                    'tl' => '/themes/3d-adat-bugis-02/assets/bunga-bugis.png',
                    'tr' => '/themes/3d-adat-bugis-02/assets/bunga-bugis.png',
                    'bl' => '/themes/3d-adat-bugis-02/assets/bunga-bugis.png',
                    'br' => '/themes/3d-adat-bugis-02/assets/bunga-bugis.png',
                    'center' => '/themes/3d-adat-bugis-02/assets/dan-bugis-1.png',
                ],
                'nav' => [
                    'bg_color' => 'rgba(92, 9, 25, 0.95)',
                    'active_color' => '#dfb76c',
                    'inactive_color' => '#ffffff',
                ],
            ],
        ];

        // 3. Simpan atau perbarui data di database
        $theme = Theme::updateOrCreate(
            ['slug' => '3d-adat-bugis-02'],
            $themeData
        );

        // 4. Pastikan folder aset public tersedia
        $publicAssetPath = public_path('themes/3d-adat-bugis-02/assets');
        $rootAssetPath = base_path('3d-adat-bugis-02/assets');
        $assetCount = 0;

        if (!File::isDirectory($publicAssetPath) && File::isDirectory($rootAssetPath)) {
            File::ensureDirectoryExists($publicAssetPath);
            File::copyDirectory($rootAssetPath, $publicAssetPath);
            $assetCount = count(File::files($publicAssetPath));
        } elseif (File::isDirectory($publicAssetPath)) {
            $assetCount = count(File::files($publicAssetPath));
        }

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
                'message' => 'Tema 3D Adat Bugis 02 berhasil ditambahkan/diperbarui di hosting.',
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

        // 7. Response HTML
        $html = "<!DOCTYPE html>
        <html lang='id'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Deployment Tema 3D Adat Bugis 02 Berhasil</title>
            <style>
                body {
                    font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
                    background: #1e0207;
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
                    background: rgba(45, 4, 12, 0.95);
                    border: 1px solid rgba(223, 183, 108, 0.5);
                    border-radius: 18px;
                    padding: 32px 28px;
                    box-shadow: 0 15px 45px rgba(0,0,0,0.7), inset 0 0 15px rgba(223, 183, 108, 0.1);
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
                    color: #dfb76c;
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
                    background: rgba(0,0,0,0.35);
                    border: 1px solid rgba(223, 183, 108, 0.2);
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
                .details-row:last-child {
                    border-bottom: none;
                }
                .details-label {
                    color: rgba(255,255,255,0.6);
                }
                .details-val {
                    color: #dfb76c;
                    font-weight: 600;
                }
                .btn-group {
                    display: flex;
                    flex-wrap: wrap;
                    gap: 10px;
                    justify-content: center;
                    margin-top: 24px;
                }
                .btn {
                    background: #dfb76c;
                    color: #32030c;
                    font-weight: 700;
                    text-decoration: none;
                    padding: 10px 22px;
                    border-radius: 10px;
                    font-size: 13px;
                    transition: all 0.2s;
                }
                .btn:hover {
                    background: #f0cb86;
                    transform: translateY(-1px);
                }
                .btn-outline {
                    background: rgba(255,255,255,0.08);
                    color: #ffffff;
                    border: 1px solid rgba(223, 183, 108, 0.4);
                }
                .btn-outline:hover {
                    background: rgba(255,255,255,0.15);
                }
            </style>
        </head>
        <body>
            <div class='card'>
                <div class='badge'>✓ DEPLOYMENT SUKSES</div>
                <h1>Tema 3D Adat Bugis 02 Siap Digunakan!</h1>
                <p>Tema baru telah berhasil ditambahkan dan disinkronkan ke dalam database hosting beserta seluruh konfigurasinya.</p>
                
                <div class='details'>
                    <div class='details-row'>
                        <span class='details-label'>ID Tema:</span>
                        <span class='details-val'>{$theme->id}</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>Nama Tema:</span>
                        <span class='details-val'>{$theme->name}</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>Slug:</span>
                        <span class='details-val'>{$theme->slug}</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>View File:</span>
                        <span class='details-val'>{$theme->view_file}</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>Kategori:</span>
                        <span class='details-val'>{$theme->category}</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>Jumlah Berkas Aset:</span>
                        <span class='details-val'>{$assetCount} files</span>
                    </div>
                    <div class='details-row'>
                        <span class='details-label'>Cache Status:</span>
                        <span class='details-val' style='color:#4ade80;'>{$cacheStatus}</span>
                    </div>
                </div>

                <div class='btn-group'>
                    <a href='/admin/themes' class='btn'>Kelola Tema (Admin)</a>
                    <a href='/dashboard' class='btn btn-outline'>Dashboard</a>
                </div>
            </div>
        </body>
        </html>";

        return response($html, 200, ['Content-Type' => 'text/html; charset=utf-8']);
    }
}
