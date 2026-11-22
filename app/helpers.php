<?php

use App\Services\ImageService;

if (! function_exists('img_url')) {
    /**
     * Generate a protected image URL that hides the actual file path.
     */
    function img_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        return ImageService::url($path);
    }
}

if (! function_exists('og_img_url')) {
    /**
     * Generate a public JPEG Open Graph image URL suitable for social/WhatsApp previews.
     */
    function og_img_url(?string $path): string
    {
        if (empty($path)) {
            return '';
        }

        return ImageService::ogUrl($path);
    }
}
