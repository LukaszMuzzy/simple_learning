<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class WordDefinitionGroup extends Model
{
    protected $fillable = ['label', 'slug', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function definitions(): BelongsToMany
    {
        return $this->belongsToMany(
            WordDefinition::class,
            'definition_group_members',
            'group_id',
            'word_definition_id'
        )->orderBy('difficulty')->orderBy('word');
    }
}
