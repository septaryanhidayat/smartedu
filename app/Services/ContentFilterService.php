<?php

namespace App\Services;

class ContentFilterService
{
    /**
     * Daftar kata-kata terlarang (Judi Online, Slot Gacor, Pinjol ilegal, SARA, Pornografi, LGBT)
     */
    protected static array $forbiddenWords = [
        'slot', 'gacor', 'judi', 'judol', 'casino', 'taruhan', 'poker', 'togel', 
        'sbobet', 'pragmatic', 'maxwin', 'zeus', 'deposit pulsa', 'situs judi',
        'pinjol', 'pinjaman online ilegal', 'bokep', 'porn', 'porno', 'sex', 
        'dewasa 18+', 'lgbt', 'gay', 'lesbian'
    ];

    /**
     * Cek apakah teks atau parameter artikel aman dari konten terlarang
     */
    public static function isSafe(string $title = '', ?string $excerpt = '', ?string $content = '', ?string $category = ''): bool
    {
        $combinedText = strtolower($title . ' ' . $excerpt . ' ' . $content . ' ' . $category);

        foreach (self::$forbiddenWords as $word) {
            // Match whole word or exact pattern
            if (str_contains($combinedText, strtolower($word))) {
                return false;
            }
        }

        return true;
    }

    /**
     * Filter koleksi atau array berita/artikel dari item yang mengandung kata terlarang
     */
    public static function filterCollection(iterable $items): array|\Illuminate\Support\Collection
    {
        $isLaravelCollection = $items instanceof \Illuminate\Support\Collection;
        $filtered = [];

        foreach ($items as $item) {
            $title = is_array($item) ? ($item['title'] ?? '') : ($item->title ?? '');
            $excerpt = is_array($item) ? ($item['excerpt'] ?? '') : ($item->excerpt ?? '');
            $content = is_array($item) ? ($item['content'] ?? '') : ($item->content ?? '');
            $category = is_array($item) ? ($item['category'] ?? '') : ($item->category ?? '');

            if (self::isSafe($title, $excerpt, $content, $category)) {
                $filtered[] = $item;
            }
        }

        return $isLaravelCollection ? collect($filtered) : $filtered;
    }
}
