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
        imagewebp($image, null, 85);
        $output = ob_get_clean();
        imagedestroy($image);

        return $output;
    }

    /**
     * Store an uploaded image, converting to WebP if it isn't already.
     *
     * @param  UploadedFile  $file  The uploaded image file
     * @param  string  $directory  Storage directory (e.g. 'articles', 'invitations/covers')
     * @param  int  $quality  WebP quality (0-100)
     * @return string|false The stored path relative to the disk, or false on failure
     */
    public function storeAsWebp(UploadedFile $file, string $directory, int $quality = 80): string|false
    {
        $extension = strtolower($file->getClientOriginalExtension());

        // Already WebP — store directly
        if ($extension === 'webp') {
            return $file->store($directory, 'public');
        }

        // Convert to WebP using GD
        $image = match ($extension) {
            'jpg', 'jpeg' => @imagecreatefromjpeg($file->getRealPath()),
            'png' => @imagecreatefrompng($file->getRealPath()),
            'gif' => @imagecreatefromgif($file->getRealPath()),
            'bmp' => @imagecreatefrombmp($file->getRealPath()),
            default => false,
        };

        if (! $image) {
            // Unsupported format — store as-is
            return $file->store($directory, 'public');
        }

        // Convert palette images to true color (WebP doesn't support palette)
        if (! imageistruecolor($image)) {
            imagepalettetotruecolor($image);
        }
        imagealphablending($image, true);
        imagesavealpha($image, true);

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
