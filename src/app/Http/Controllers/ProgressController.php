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

        $uid = $request->user()->id;

        $stats = [
            'total_games'          => GameSession::where('user_id', $uid)->whereNotNull('completed_at')->count(),
            'total_correct'        => GameSession::where('user_id', $uid)->sum('correct_answers'),
            'total_questions'      => GameSession::where('user_id', $uid)->sum('total_questions'),
            'addition_subtraction' => GameSession::where('user_id', $uid)
                ->whereIn('game_type', ['addition_subtraction', 'multiplication'])
                ->whereNotNull('completed_at')
                ->count(),
            'spelling'             => GameSession::where('user_id', $uid)
                ->where('game_type', 'spelling')
                ->whereNotNull('completed_at')
                ->count(),
        ];

        return view('progress.index', compact('sessions', 'stats'));
    }
}
