<?php

namespace App\Data;

use App\Models\WordDefinition;
use App\Models\WordDefinitionGroup;

class WordDefinitions
{
    /**
     * Generate randomised multiple-choice questions.
     *
     * $source format:
     *   'difficulty:easy'   — all Easy words
     *   'difficulty:medium' — all Medium words
     *   'difficulty:hard'   — all Hard words
     *   'difficulty:all'    — every word regardless of difficulty
     *   'group:42'          — words assigned to group with id 42
     */
    public static function generateQuestions(string $source, int $count): array
    {
        $defs = self::resolveSource($source);

        if ($defs->isEmpty()) {
            return [];
        }

        $pool           = $defs->pluck('definition', 'word')->all();
        $allDefinitions = array_values($pool);
        $wordKeys       = array_keys($pool);
        shuffle($wordKeys);
        $selected = array_slice($wordKeys, 0, min($count, count($wordKeys)));

        $questions = [];
        foreach ($selected as $word) {
            $correctDef  = $pool[$word];
            $distractors = array_values(array_filter($allDefinitions, fn ($d) => $d !== $correctDef));
            shuffle($distractors);
            $options   = array_merge([$correctDef], array_slice($distractors, 0, 3));
            shuffle($options);
            $answerIdx = array_search($correctDef, $options, true);

            $questions[] = [
                'word'       => $word,
                'definition' => $correctDef,
                'options'    => array_values($options),
                'answer_idx' => $answerIdx,
            ];
        }

        return $questions;
    }

    public static function maxCount(string $source): int
    {
        return self::resolveSource($source)->count();
    }

    /**
     * Returns grouped source options for the game's setup dropdown.
     * Structure: [ 'Group Label' => [ 'source:key' => 'Display Name', … ], … ]
     */
    public static function sourceOptions(): array
    {
        $options = [
            'By Difficulty' => [
                'difficulty:easy'   => '⭐ Easy (' . WordDefinition::where('difficulty', 'easy')->count() . ' words)',
                'difficulty:medium' => '⭐⭐ Medium (' . WordDefinition::where('difficulty', 'medium')->count() . ' words)',
                'difficulty:hard'   => '⭐⭐⭐ Hard (' . WordDefinition::where('difficulty', 'hard')->count() . ' words)',
                'difficulty:all'    => '🌟 All Mixed (' . WordDefinition::count() . ' words)',
            ],
        ];

        $groups = WordDefinitionGroup::where('is_active', true)
            ->withCount('definitions')
            ->orderBy('sort_order')
            ->orderBy('label')
            ->get();

        if ($groups->isNotEmpty()) {
            $groupOptions = [];
            foreach ($groups as $g) {
                $groupOptions["group:{$g->id}"] = "📚 {$g->label} ({$g->definitions_count} words)";
            }
            $options['Custom Groups'] = $groupOptions;
        }

        return $options;
    }

    public static function difficulties(): array
    {
        return [
            'easy'   => '⭐ Easy',
            'medium' => '⭐⭐ Medium',
            'hard'   => '⭐⭐⭐ Hard',
            'all'    => '🌟 Mixed',
        ];
    }

    // ────────────────────────────────────────────────────────────────────────────

    private static function resolveSource(string $source)
    {
        if (str_starts_with($source, 'group:')) {
            $groupId = (int) substr($source, 6);
            return WordDefinitionGroup::find($groupId)?->definitions ?? collect();
        }

        $difficulty = str_starts_with($source, 'difficulty:') ? substr($source, 11) : 'all';

        return $difficulty === 'all'
            ? WordDefinition::all()
            : WordDefinition::where('difficulty', $difficulty)->get();
    }
}
