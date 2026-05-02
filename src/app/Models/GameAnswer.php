<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GameAnswer extends Model
{
    protected $fillable = [
        'game_session_id',
        'question',
        'num1',
        'num2',
        'correct_answer',
        'user_answer',
        'is_correct',
        'time_taken_seconds',
    ];

    protected $casts = [
        'is_correct' => 'boolean',
    ];

    public function session(): BelongsTo
    {
        return $this->belongsTo(GameSession::class, 'game_session_id');
    }
}
