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

        // Non-image files (e.g. music) — serve directly with ETag & long cache
        if (! str_starts_with($mime, 'image/')) {
            return $this->serveFileWithCache($request, $fullPath, $mime, 86400);
        }

        $imageService = app(ImageService::class);
        $cachedPath = $imageService->getWatermarkedCachePath($path, $fullPath);

        if ($cachedPath && is_file($cachedPath)) {
            return $this->serveFileWithCache($request, $cachedPath, 'image/webp', 31536000, true);
        }

        return $this->serveFileWithCache($request, $fullPath, $mime, 86400);
    }

    private function serveFileWithCache(
        Request $request,
        string $filePath,
        string $contentType,
        int $maxAge,
        bool $immutable = false
    ) {
        $mtime = @filemtime($filePath) ?: 0;
        $size = @filesize($filePath) ?: 0;
        $etag = '"'.sha1($filePath.'|'.$mtime.'|'.$size).'"';

        $cacheControl = 'public, max-age='.$maxAge.($immutable ? ', immutable' : '');
        $headers = [
            'Content-Type' => $contentType,
            'Cache-Control' => $cacheControl,
            'Content-Disposition' => 'inline',
            'ETag' => $etag,
        ];

        $ifNoneMatch = trim((string) $request->header('If-None-Match', ''));
        if ($ifNoneMatch !== '' && ($ifNoneMatch === $etag || $ifNoneMatch === '*')) {
            return response('', 304, [
                'Cache-Control' => $cacheControl,
                'ETag' => $etag,
            ]);
        }

        return response()->file($filePath, $headers);
    }
}
