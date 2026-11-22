<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
    /**
     * Encode a storage path into an opaque URL token.
     */
    public static function encodePath(string $path): string
    {
        $key = config('app.key');
        $data = base64_encode($path);
        $hmac = hash_hmac('sha256', $data, $key);

        return rtrim(strtr(base64_encode($hmac.':'.$data), '+/', '-_'), '=');
    }

    /**
     * Decode a URL token back to a storage path. Returns null if invalid.
     */
    public static function decodePath(string $token): ?string
    {
        $key = config('app.key');
        $decoded = base64_decode(strtr($token, '-_', '+/'));

        if (! $decoded || ! str_contains($decoded, ':')) {
            return null;
        }

        [$hmac, $data] = explode(':', $decoded, 2);
        $expected = hash_hmac('sha256', $data, $key);

        if (! hash_equals($expected, $hmac)) {
            return null;
        }

        $path = base64_decode($data);

        return $path ?: null;
    }

    /**
     * Generate a protected URL for a storage path.
     */
    public static function url(string $path): string
    {
        if (empty($path)) {
            return '';
        }

        return route('image.show', ['token' => static::encodePath($path)]);
    }

    /**
     * Get or generate the cached watermarked WebP file path on disk.
     */
    public function getWatermarkedCachePath(string $relativePath, string $absolutePath, string $text = 'ExoInvite'): ?string
    {
        $cacheDir = storage_path('framework/cache/watermarked_images');
        if (! is_dir($cacheDir)) {
            @mkdir($cacheDir, 0755, true);
        }

        $cachePath = $cacheDir.DIRECTORY_SEPARATOR.sha1($relativePath.'|'.$text).'.webp';
        $sourceMtime = @filemtime($absolutePath) ?: 0;

        if (is_file($cachePath) && (@filemtime($cachePath) ?: 0) >= $sourceMtime && (@filesize($cachePath) ?: 0) > 0) {
            return $cachePath;
        }

        $watermarked = $this->addWatermark($absolutePath, $text);
        if (! $watermarked) {
            return null;
        }

        $tmpPath = $cachePath.'.'.bin2hex(random_bytes(6)).'.tmp';
        if (@file_put_contents($tmpPath, $watermarked) !== false) {
            if (@rename($tmpPath, $cachePath)) {
                return $cachePath;
            }
            @unlink($tmpPath);
        }

        if (@file_put_contents($cachePath, $watermarked, LOCK_EX) !== false && is_file($cachePath)) {
            return $cachePath;
        }

        return is_file($cachePath) ? $cachePath : null;
    }

    /**
     * Add a text watermark to an image and return WebP binary.
     */
    public function addWatermark(string $absolutePath, string $text = 'ExoInvite'): ?string
    {
        $extension = strtolower(pathinfo($absolutePath, PATHINFO_EXTENSION));

        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($absolutePath),
            'png' => @imagecreatefrompng($absolutePath),
            'gif' => @imagecreatefromgif($absolutePath),
            'webp' => @imagecreatefromwebp($absolutePath),
            'bmp' => @imagecreatefrombmp($absolutePath),
            default => false,
        };

        if (! $image) {
            return null;
        }

        // Convert palette images to true color (WebP doesn't support palette)
        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

        // Downscale oversized source images before watermarking so mobile loads stay fast
        $image = $this->resizeIfNeeded($image, 1280);

        $width = imagesx($image);
        $height = imagesy($image);

        // Semi-transparent white for watermark
        $color = imagecolorallocatealpha($image, 255, 255, 255, 80);
        $shadow = imagecolorallocatealpha($image, 0, 0, 0, 100);

        // Use built-in GD font (size 5 = largest)
        $font = 5;
        $charWidth = imagefontwidth($font);
        $charHeight = imagefontheight($font);
        $textWidth = $charWidth * strlen($text);

        // Place watermark at bottom-right with padding
        $x = $width - $textWidth - 15;
        $y = $height - $charHeight - 15;

        // Shadow
        imagestring($image, $font, $x + 1, $y + 1, $text, $shadow);
        // Main text
        imagestring($image, $font, $x, $y, $text, $color);

        // Also add diagonal repeated watermark for extra protection
        if ($width > 300 && $height > 300) {
            $diagonalColor = imagecolorallocatealpha($image, 255, 255, 255, 105);
            $spacing = 200;

            for ($dy = -$height; $dy < $height * 2; $dy += $spacing) {
                for ($dx = -$width; $dx < $width * 2; $dx += $spacing) {
                    $rx = (int) ($dx + $dy * 0.5);
                    $ry = (int) ($dy);
                    if ($rx > -$textWidth && $rx < $width && $ry > -$charHeight && $ry < $height) {
                        imagestring($image, 3, $rx, $ry, $text, $diagonalColor);
                    }
                }
            }
        }

        ob_start();
        imagewebp($image, null, 80);
        $output = ob_get_clean();
        imagedestroy($image);

        return $output;
    }

    /**
     * Downscale an image proportionally if its longest side exceeds maxDimension.
     */
    protected function resizeIfNeeded(\GdImage $image, int $maxDimension = 1280): \GdImage
    {
        $width = imagesx($image);
        $height = imagesy($image);
        $largest = max($width, $height);

        if ($maxDimension <= 0 || $largest <= $maxDimension) {
            return $image;
        }

        $scale = $maxDimension / $largest;
        $newWidth = max(1, (int) round($width * $scale));
        $newHeight = max(1, (int) round($height * $scale));

        $resized = imagecreatetruecolor($newWidth, $newHeight);
        imagealphablending($resized, false);
        imagesavealpha($resized, true);
        $transparent = imagecolorallocatealpha($resized, 0, 0, 0, 127);
        imagefilledrectangle($resized, 0, 0, $newWidth, $newHeight, $transparent);
        imagealphablending($resized, true);

        imagecopyresampled($resized, $image, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);
        imagedestroy($image);

        return $resized;
    }

    /**
     * Store an uploaded image, downscaling oversized images and converting to WebP.
     *
     * @param  UploadedFile  $file  The uploaded image file
     * @param  string  $directory  Storage directory (e.g. 'articles', 'invitations/covers')
     * @param  int  $quality  WebP quality (0-100)
     * @param  int  $maxDimension  Max width/height in pixels before downscaling
     * @return string|false The stored path relative to the disk, or false on failure
     */
    public function storeAsWebp(UploadedFile $file, string $directory, int $quality = 78, int $maxDimension = 1280): string|false
    {
        $extension = strtolower($file->getClientOriginalExtension());
        $realPath = $file->getRealPath();

        // Decode using GD (including WebP so oversized WebP uploads are also downscaled)
        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($realPath),
            'png' => @imagecreatefrompng($realPath),
            'gif' => @imagecreatefromgif($realPath),
            'webp' => @imagecreatefromwebp($realPath),
            'bmp' => @imagecreatefrombmp($realPath),
            default => false,
        };

        if (! $image) {
            // Unsupported or unreadable format — store as-is
            return $file->store($directory, 'public');
        }

        $origWidth = imagesx($image);
        $origHeight = imagesy($image);
        $fileSize = (int) ($file->getSize() ?: @filesize($realPath) ?: 0);

        // Skip re-encoding only when already WebP, within dimensions, and reasonably compact
        if ($extension === 'webp' && max($origWidth, $origHeight) <= $maxDimension && $fileSize > 0 && $fileSize <= 307200) {
            imagedestroy($image);

            return $file->store($directory, 'public');
        }

        // Convert palette images to true color (WebP doesn't support palette)
        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

        $image = $this->resizeIfNeeded($image, $maxDimension);

        $filename = Str::random(40).'.webp';
        $path = rtrim($directory, '/').'/'.$filename;

        // Write to temp, then move to storage
        $tempPath = tempnam(sys_get_temp_dir(), 'webp');
        imagewebp($image, $tempPath, $quality);
        imagedestroy($image);

        Storage::disk('public')->put($path, file_get_contents($tempPath));
        @unlink($tempPath);

        return $path;
    }
}
