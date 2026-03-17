<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class GameSession extends Model
{
    protected $fillable = [
        'user_id',
        'game_type',
        'settings',
        'total_questions',
        'correct_answers',
        'wrong_answers',
        'time_taken_seconds',
        'completed_at',
    ];

    protected $casts = [
        'settings' => 'array',
        'completed_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function answers(): HasMany
    {
        return $this->hasMany(GameAnswer::class);
    }

    public function getScorePercentageAttribute(): float
    {
        if ($this->total_questions === 0) {
            return 0;
        }
        return round(($this->correct_answers / $this->total_questions) * 100, 1);
    }
}
