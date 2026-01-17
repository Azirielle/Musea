<?php

namespace App\Services;

class ColorExtractor
{
    /**
     * Extract the dominant color from an image file.
     * Returns a HEX string (e.g., #FF0000).
     */
    public static function getDominantColor($filePath)
    {
        try {
            // Get image info
            $info = \getimagesize($filePath);
            if (!$info)
                return null;

            $mime = $info['mime'];

            // Create image resource from file
            $image = null;
            switch ($mime) {
                case 'image/jpeg':
                    $image = \imagecreatefromjpeg($filePath);
                    break;
                case 'image/png':
                    $image = \imagecreatefrompng($filePath);
                    break;
                case 'image/webp':
                    $image = \imagecreatefromwebp($filePath);
                    break;
                case 'image/gif':
                    $image = \imagecreatefromgif($filePath);
                    break;
            }

            if (!$image)
                return null;

            // Resize to 1x1 pixel to get the average color
            $width = 1;
            $height = 1;
            $resized = \imagecreatetruecolor($width, $height);

            // Copy and resize
            \imagecopyresampled($resized, $image, 0, 0, 0, 0, $width, $height, \imagesx($image), \imagesy($image));

            // Get color of the single pixel
            $rgb = \imagecolorat($resized, 0, 0);
            $r = ($rgb >> 16) & 0xFF;
            $g = ($rgb >> 8) & 0xFF;
            $b = $rgb & 0xFF;

            // Cleanup
            \imagedestroy($image);
            \imagedestroy($resized);

            // Convert to HEX
            return sprintf("#%02x%02x%02x", $r, $g, $b);

        } catch (\Exception $e) {
            // Log error if needed, or fail silently
            return null;
        }
    }
}
