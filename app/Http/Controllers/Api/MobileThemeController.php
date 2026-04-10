<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;

class MobileThemeController extends Controller
{
    /**
     * GET /api/mobile/themes
     * Returns all active themes for the mobile catalog.
     * Public endpoint — no authentication required.
     */
    public function index(): JsonResponse
    {
        $themes = Theme::where('is_active', true)
            ->orderBy('is_premium')        // free first
            ->orderBy('name')
            ->get()
            ->map(fn (Theme $theme) => [
                'id'            => $theme->id,
                'name'          => $theme->name,
                'slug'          => $theme->slug,
                'is_premium'    => $theme->is_premium,
                'thumbnail_url' => $theme->protected_thumbnail,
                'preview_url'   => $theme->slug
                    ? url("/preview/{$theme->slug}")
                    : null,
                'colors' => [
                    'primary'    => $theme->primary_color,
                    'secondary'  => $theme->secondary_color,
                    'accent'     => $theme->accent_color,
                    'background' => $theme->background_color,
                ],
            ]);

        return response()->json([
            'data' => $themes,
        ]);
    }
}
