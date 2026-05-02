<x-app-layout>
    <x-slot name="title">Dashboard</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <h1 class="text-3xl font-extrabold text-slate-800">
                Welcome back, {{ Auth::user()->name }}! 👋
            </h1>
            <p class="text-slate-500 mt-1">Ready to practise some maths today?</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-10">
            <a href="{{ route('math.addition-subtraction') }}"
                class="group bg-gradient-to-br from-blue-50 to-indigo-50 rounded-2xl p-6 border border-indigo-100 hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-indigo-600 rounded-xl flex items-center justify-center text-white text-xl font-black">±</div>
                    <svg class="w-5 h-5 text-indigo-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-800">Addition & Subtraction</h3>
                <p class="text-slate-500 text-sm mt-1">Practice with custom difficulty and timer</p>
            </a>
            <a href="{{ route('math.multiplication') }}"
                class="group bg-gradient-to-br from-green-50 to-teal-50 rounded-2xl p-6 border border-green-100 hover:shadow-lg transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-green-600 rounded-xl flex items-center justify-center text-white text-xl font-black">×</div>
                    <svg class="w-5 h-5 text-green-400 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
                <h3 class="text-lg font-extrabold text-slate-800">Multiplication Game</h3>
                <p class="text-slate-500 text-sm mt-1">Master your times tables 0–12</p>
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-xl font-extrabold text-slate-800">Recent Activity</h2>
                <a href="{{ route('progress.index') }}" class="text-indigo-600 hover:text-indigo-800 text-sm font-semibold">View all →</a>
            </div>
            @php
                $recentSessions = \App\Models\GameSession::where('user_id', auth()->id())
                    ->whereNotNull('completed_at')
                    ->orderByDesc('completed_at')
                    ->limit(5)
                    ->get();
            @endphp
            @if($recentSessions->count() > 0)
            <div class="space-y-3">
                @foreach($recentSessions as $session)
                @php $pct = $session->total_questions > 0 ? round(($session->correct_answers / $session->total_questions) * 100) : 0; @endphp
                <div class="flex items-center justify-between p-3 bg-slate-50 rounded-xl">
                    <div class="flex items-center space-x-3">
                        <span class="text-lg">{{ $session->game_type === 'multiplication' ? '×' : '±' }}</span>
                        <div>
                            <p class="font-semibold text-slate-700 text-sm">
                                {{ $session->game_type === 'multiplication' ? 'Multiplication' : 'Add/Sub' }}
                            </p>
                            <p class="text-xs text-slate-400">{{ $session->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="font-black text-lg {{ $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-yellow-500' : 'text-red-500') }}">
                        {{ $pct }}%
                    </span>
                </div>
                @endforeach
            </div>
            @else
            <p class="text-slate-400 text-center py-8">No games played yet. Start playing to see your progress here!</p>
            @endif
        </div>
    </div>
</x-app-layout>
