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
