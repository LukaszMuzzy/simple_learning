<?php

namespace App\Http\Controllers;

use App\Models\GameAnswer;
use App\Models\GameSession;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $uid = $request->user()->id;

        $sessions = GameSession::where('user_id', $uid)
            ->with('answers')
            ->orderByDesc('created_at')
            ->paginate(15);

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

        // Trickiest multiplication pairs across all history — only pairs with at least one wrong answer
        $trickyTables = GameAnswer::query()
            ->join('game_sessions', 'game_answers.game_session_id', '=', 'game_sessions.id')
            ->where('game_sessions.user_id', $uid)
            ->where('game_sessions.game_type', 'multiplication')
            ->whereNotNull('game_answers.num1')
            ->whereNotNull('game_answers.num2')
            ->selectRaw('
                game_answers.num1,
                game_answers.num2,
                COUNT(*) AS attempts,
                SUM(CASE WHEN game_answers.is_correct = false THEN 1 ELSE 0 END) AS wrong_count,
                AVG(game_answers.time_taken_seconds::float) AS avg_time
            ')
            ->groupBy('game_answers.num1', 'game_answers.num2')
            ->havingRaw('SUM(CASE WHEN game_answers.is_correct = false THEN 1 ELSE 0 END) > 0')
            ->orderByRaw('wrong_count DESC, avg_time DESC')
            ->limit(10)
            ->get();

        // Unique num1 values that have at least one wrong answer — used for "Practice Trickiest" link
        $trickyNums = $trickyTables
            ->where('wrong_count', '>', 0)
            ->pluck('num1')
            ->unique()
            ->sort()
            ->values()
            ->all();

        return view('progress.index', compact('sessions', 'stats', 'trickyTables', 'trickyNums'));
    }

    public function resetTrickyTables(Request $request)
    {
        $uid = $request->user()->id;

        // Delete all game_answers with num1/num2 filled in for this user's multiplication sessions
        GameAnswer::query()
            ->join('game_sessions', 'game_answers.game_session_id', '=', 'game_sessions.id')
            ->where('game_sessions.user_id', $uid)
            ->where('game_sessions.game_type', 'multiplication')
            ->whereNotNull('game_answers.num1')
            ->delete();

        return back()->with('success', 'Trickiest Tables history has been reset.');
    }

    public function destroy(Request $request, GameSession $session)
    {
        // Ensure the session belongs to the authenticated user
        abort_unless($session->user_id === $request->user()->id, 403);

        $session->delete();

        return back()->with('success', 'Session removed.');
    }
}
