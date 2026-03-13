<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageService
{
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

        // Preserve transparency for PNG/GIF
        if (in_array($extension, ['png', 'gif'])) {
            imagepalettetotruecolor($image);
            imagealphablending($image, true);
            imagesavealpha($image, true);
        }

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
