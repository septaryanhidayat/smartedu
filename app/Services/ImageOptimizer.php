<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;

class ImageOptimizer
{
    /**
     * Compress and save uploaded image to destination directory and return public path
     */
    public static function compress(?UploadedFile $file, string $destinationDir = 'uploads/cms', ?string $filename = null): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        try {
            $targetDir = public_path($destinationDir);
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            $ext = $file->getClientOriginalExtension() ?: 'jpg';
            $finalName = ($filename ? $filename : 'img_' . time()) . '.' . $ext;

            $file->move($targetDir, $finalName);

            return '/' . trim($destinationDir, '/') . '/' . $finalName;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ImageOptimizer compress failed: ' . $e->getMessage());
            return null;
        }
    }
}
