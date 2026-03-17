<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class WordList extends Model
{
    protected $fillable = ['slug', 'label', 'description', 'sort_order', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function words(): HasMany
    {
        return $this->hasMany(Word::class)->orderBy('word');
    }

    /** Sorted array of word strings for use in the game. */
    public function wordArray(): array
    {
        return $this->words()->pluck('word')->all();
    }
}
