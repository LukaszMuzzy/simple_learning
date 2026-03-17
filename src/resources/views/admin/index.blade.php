<x-admin-layout>
    <x-slot name="title">Dashboard</x-slot>

    {{-- Stat cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        @foreach([
            ['Total Users',      $stats['total_users'],     'bg-indigo-500',  'M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z'],
            ['Admin Users',      $stats['admin_users'],     'bg-amber-500',   'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
            ['Games Completed',  $stats['total_sessions'],  'bg-emerald-500', 'M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z'],
            ['Questions Answered',$stats['total_questions'],'bg-cyan-500',    'M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
        ] as [$label, $value, $color, $icon])
        <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100">
            <div class="flex items-center justify-between mb-3">
                <p class="text-sm font-semibold text-slate-500">{{ $label }}</p>
                <div class="w-9 h-9 {{ $color }} rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $icon }}"/>
                    </svg>
                </div>
            </div>
            <p class="text-3xl font-black text-slate-800">{{ number_format($value) }}</p>
        </div>
        @endforeach
    </div>

    {{-- Games breakdown --}}
    @if(!empty($stats['by_game']))
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 mb-8">
        <h2 class="font-extrabold text-slate-800 mb-4">Games by Type</h2>
        <div class="flex flex-wrap gap-3">
            @php
                $gameLabels = ['addition_subtraction' => '± Addition & Subtraction', 'multiplication' => '× Multiplication', 'spelling' => '📝 Spelling'];
                $gameColors = ['addition_subtraction' => 'bg-blue-100 text-blue-800', 'multiplication' => 'bg-green-100 text-green-800', 'spelling' => 'bg-emerald-100 text-emerald-800'];
            @endphp
            @foreach($stats['by_game'] as $type => $count)
            <div class="flex items-center space-x-2 px-4 py-2.5 rounded-xl {{ $gameColors[$type] ?? 'bg-slate-100 text-slate-700' }}">
                <span class="font-extrabold text-xl">{{ $count }}</span>
                <span class="font-semibold text-sm">{{ $gameLabels[$type] ?? ucfirst($type) }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        {{-- Recent users --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="flex items-center justify-between px-6 py-4 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Recent Users</h2>
                <a href="{{ route('admin.users.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">View all →</a>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($recentUsers as $user)
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 bg-indigo-100 rounded-full flex items-center justify-center font-bold text-indigo-600 text-sm">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800 text-sm">{{ $user->name }}</p>
                            <p class="text-xs text-slate-400">&#64;{{ $user->username }}</p>
                        </div>
                    </div>
                    <div class="flex items-center space-x-2">
                        @if($user->is_admin)
                        <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-bold">Admin</span>
                        @endif
                        <span class="text-xs text-slate-400">{{ $user->created_at->diffForHumans() }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Recent sessions --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Recent Activity</h2>
            </div>
            <div class="divide-y divide-slate-50">
                @foreach($recentSessions as $session)
                @php
                    $pct = $session->total_questions > 0 ? round($session->correct_answers / $session->total_questions * 100) : 0;
                    $typeIcon = ['multiplication' => '×', 'addition_subtraction' => '±', 'spelling' => '📝'][$session->game_type] ?? '?';
                @endphp
                <div class="flex items-center justify-between px-6 py-3.5">
                    <div class="flex items-center space-x-3">
                        <span class="w-8 h-8 bg-slate-100 rounded-lg flex items-center justify-center text-sm font-bold text-slate-600">{{ $typeIcon }}</span>
                        <div>
                            <p class="font-semibold text-slate-800 text-sm">{{ $session->user?->name ?? 'Guest' }}</p>
                            <p class="text-xs text-slate-400">{{ $session->completed_at->diffForHumans() }}</p>
                        </div>
                    </div>
                    <span class="font-extrabold text-sm {{ $pct >= 80 ? 'text-emerald-600' : ($pct >= 60 ? 'text-amber-500' : 'text-red-500') }}">
                        {{ $pct }}%
                    </span>
                </div>
                @endforeach
                @if($recentSessions->isEmpty())
                <p class="px-6 py-8 text-center text-slate-400 text-sm">No completed games yet.</p>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>
