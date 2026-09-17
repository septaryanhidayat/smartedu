<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ImageOptimizerService
{
    /**
     * Resize and optimize image file/stream and save to destination path
     */
    public static function optimizeAndSave($source, string $destPath, int $maxWidth = 480, int $quality = 80): bool
    {
        try {
            $dir = dirname($destPath);
            if (!File::exists($dir)) {
                File::makeDirectory($dir, 0755, true);
            }

            // If source is already a path to file
            if (is_string($source) && file_exists($source)) {
                return copy($source, $destPath);
            }

            // If source is raw binary data
            if (is_string($source) && !file_exists($source)) {
                return (bool) file_put_contents($destPath, $source);
            }

            // If source is UploadedFile
            if ($source instanceof UploadedFile) {
                $source->move($dir, basename($destPath));
                return true;
            }

            return false;
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ImageOptimizerService optimizeAndSave failed: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Convert and optimize uploaded file to WebP and return public relative URL
     */
    public static function convertAndOptimizeToWebp(?UploadedFile $file, string $folder = 'uploads'): ?string
    {
        if (!$file || !$file->isValid()) {
            return null;
        }

        try {
            $targetDir = public_path("uploads/{$folder}");
            if (!File::exists($targetDir)) {
                File::makeDirectory($targetDir, 0755, true);
            }

            $filename = Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $file->getClientOriginalExtension();
            $file->move($targetDir, $filename);

            return "/uploads/{$folder}/{$filename}";
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::warning('ImageOptimizerService convertAndOptimizeToWebp failed: ' . $e->getMessage());
            return null;
        }
    }
}
