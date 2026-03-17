<?php

namespace App\Data;

use App\Models\WordList;

class WordLists
{
    /** slug => label map for active lists, ordered by sort_order. */
    public static function labels(): array
    {
        return WordList::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('label')
            ->pluck('label', 'slug')
            ->all();
    }

    /** Returns an array of word strings for the given slug. */
    public static function get(string $slug): array
    {
        $list = WordList::where('slug', $slug)
            ->where('is_active', true)
            ->first();

        if (!$list) {
            return [];
        }

        return $list->words()->pluck('word')->all();
    }
}
