<?php

namespace App\Http\Controllers;

use App\Models\GameSession;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $sessions = GameSession::where('user_id', $request->user()->id)
            ->with('answers')
            ->orderByDesc('created_at')
            ->paginate(15);

        $stats = [
            'total_games' => GameSession::where('user_id', $request->user()->id)->whereNotNull('completed_at')->count(),
            'total_correct' => GameSession::where('user_id', $request->user()->id)->sum('correct_answers'),
            'total_questions' => GameSession::where('user_id', $request->user()->id)->sum('total_questions'),
            'addition_subtraction' => GameSession::where('user_id', $request->user()->id)
                ->where('game_type', 'addition_subtraction')
                ->whereNotNull('completed_at')
                ->count(),
            'multiplication' => GameSession::where('user_id', $request->user()->id)
                ->where('game_type', 'multiplication')
                ->whereNotNull('completed_at')
                ->count(),
        ];

        return view('progress.index', compact('sessions', 'stats'));
    }
}
