<?php

namespace App\Http\Controllers;

use App\Services\ImageService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function show(Request $request, string $token)
    {
        $path = ImageService::decodePath($token);

        if (! $path || ! Storage::disk('public')->exists($path)) {
            abort(404);
        }

        $fullPath = Storage::disk('public')->path($path);
        $mime = Storage::disk('public')->mimeType($path);

        // Non-image files (e.g. music) — serve directly
        if (! str_starts_with($mime, 'image/')) {
            return response()->file($fullPath, [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        $imageService = app(ImageService::class);
        $watermarked = $imageService->addWatermark($fullPath);

        if (! $watermarked) {
            return response()->file($fullPath, [
                'Cache-Control' => 'public, max-age=86400',
            ]);
        }

        return response($watermarked, 200, [
            'Content-Type' => 'image/webp',
            'Cache-Control' => 'public, max-age=86400',
            'Content-Disposition' => 'inline',
        ]);
    }
}
