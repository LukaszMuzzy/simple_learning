<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GameSession;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'     => User::count(),
            'admin_users'     => User::where('is_admin', true)->count(),
            'total_sessions'  => GameSession::whereNotNull('completed_at')->count(),
            'total_questions' => GameSession::sum('total_questions'),
            'by_game'         => GameSession::whereNotNull('completed_at')
                ->select('game_type', DB::raw('count(*) as count'))
                ->groupBy('game_type')
                ->pluck('count', 'game_type')
                ->toArray(),
        ];

        $recentUsers = User::orderByDesc('created_at')->limit(5)->get();

        $recentSessions = GameSession::with('user')
            ->whereNotNull('completed_at')
            ->orderByDesc('completed_at')
            ->limit(8)
            ->get();

        return view('admin.index', compact('stats', 'recentUsers', 'recentSessions'));
    }
}
