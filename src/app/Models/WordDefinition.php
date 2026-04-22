<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WordDefinition extends Model
{
    protected $fillable = ['word', 'definition', 'difficulty'];

    public static array $difficulties = ['easy', 'medium', 'hard'];

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(
            WordDefinitionGroup::class,
            'definition_group_members',
            'word_definition_id',
            'group_id'
        );
    }

    public function getDifficultyLabelAttribute(): string
    {
        return match ($this->difficulty) {
            'easy'   => '⭐ Easy',
            'medium' => '⭐⭐ Medium',
            'hard'   => '⭐⭐⭐ Hard',
            default  => $this->difficulty,
        };
    }
}
