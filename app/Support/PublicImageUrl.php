<?php

namespace App\Support;

/**
 * Build a web URL for an image path from the database.
 * Supports: public disk paths (products/…, parts/…), legacy public/uploads/…, and full URLs.
 */
class PublicImageUrl
{
    public static function fromPath(?string $imagePath, string $emptyFallback = 'assets/no-image.png'): string
    {
        if ($imagePath === null || trim($imagePath) === '') {
            return asset($emptyFallback);
        }

        $path = str_replace('\\', '/', trim($imagePath));

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        if (str_starts_with($path, 'uploads/')) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }
}
