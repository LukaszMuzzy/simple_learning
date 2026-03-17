<x-app-layout>
    <x-slot name="title">My Progress</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-slate-800">📊 My Progress</h1>
            <p class="text-slate-500 mt-2 text-lg">Track your learning journey across all games</p>
        </div>

        {{-- Stats Overview --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-10">
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
                <div class="text-4xl font-black text-indigo-600">{{ $stats['total_games'] }}</div>
                <div class="text-sm font-semibold text-slate-500 mt-1">Games Played</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
                <div class="text-4xl font-black text-green-600">
                    {{ $stats['total_questions'] > 0 ? round(($stats['total_correct'] / $stats['total_questions']) * 100) : 0 }}%
                </div>
                <div class="text-sm font-semibold text-slate-500 mt-1">Overall Score</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
                <div class="text-4xl font-black text-blue-600">{{ $stats['addition_subtraction'] }}</div>
                <div class="text-sm font-semibold text-slate-500 mt-1">Maths Games</div>
            </div>
            <div class="bg-white rounded-2xl p-5 shadow-sm border border-slate-100 text-center">
                <div class="text-4xl font-black text-emerald-600">{{ $stats['spelling'] ?? 0 }}</div>
                <div class="text-sm font-semibold text-slate-500 mt-1">Spelling Games</div>
            </div>
        </div>

        {{-- Recent Games --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="text-xl font-extrabold text-slate-800">Recent Games</h2>
            </div>

            @if($sessions->count() > 0)
            <div class="divide-y divide-slate-100">
                @foreach($sessions as $session)
                @php
                    $pct = $session->total_questions > 0
                        ? round(($session->correct_answers / $session->total_questions) * 100)
                        : 0;
                    $isCompleted = !is_null($session->completed_at);
                @endphp
                <div class="px-6 py-5 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div class="flex items-center space-x-4">
                        @php
                            $gameIcon = match($session->game_type) {
                                'multiplication'       => ['bg-green-100', '×'],
                                'addition_subtraction' => ['bg-blue-100', '±'],
                                'spelling'             => ['bg-emerald-100', '📝'],
                                default                => ['bg-slate-100', '?'],
                            };
                            $gameLabel = match($session->game_type) {
                                'multiplication'       => '4th Class Multiplication',
                                'addition_subtraction' => 'Addition & Subtraction',
                                'spelling'             => 'Spelling Practise',
                                default                => ucfirst($session->game_type),
                            };
                        @endphp
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center text-xl {{ $gameIcon[0] }}">
                            {{ $gameIcon[1] }}
                        </div>
                        <div>
                            <p class="font-bold text-slate-800">{{ $gameLabel }}</p>
                            <p class="text-sm text-slate-500">
                                {{ $session->created_at->format('d M Y, H:i') }}
                                @if($session->time_taken_seconds)
                                    · {{ $session->time_taken_seconds >= 60
                                        ? floor($session->time_taken_seconds / 60) . 'm ' . ($session->time_taken_seconds % 60) . 's'
                                        : $session->time_taken_seconds . 's' }}
                                @endif
                            </p>
                            @if($session->settings)
                            <div class="flex flex-wrap gap-1 mt-1">
                                @if(isset($session->settings['operation']))
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-medium">
                                    {{ ucfirst($session->settings['operation']) }}
                                </span>
                                @endif
                                @if(isset($session->settings['max_digits']))
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-medium">
                                    {{ $session->settings['max_digits'] }}-digit
                                </span>
                                @endif
                                @if(isset($session->settings['answer_mode']))
                                <span class="px-2 py-0.5 bg-slate-100 text-slate-600 rounded text-xs font-medium">
                                    {{ $session->settings['answer_mode'] === 'type' ? 'Typed' : 'Multiple Choice' }}
                                </span>
                                @endif
                                @if(isset($session->settings['word_list']))
                                <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 rounded text-xs font-medium">
                                    {{ \App\Data\WordLists::labels()[$session->settings['word_list']] ?? $session->settings['word_list'] }}
                                </span>
                                @endif
                                @if(!empty($session->settings['exam_mode']))
                                <span class="px-2 py-0.5 bg-amber-100 text-amber-700 rounded text-xs font-medium">Exam</span>
                                @endif
                            </div>
                            @endif
                        </div>
                    </div>
                    <div class="flex items-center space-x-4">
                        @if($isCompleted)
                        <div class="text-right">
                            <div class="text-2xl font-black {{ $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-yellow-500' : 'text-red-500') }}">
                                {{ $pct }}%
                            </div>
                            <div class="text-sm text-slate-500">
                                {{ $session->correct_answers }}/{{ $session->total_questions }}
                            </div>
                        </div>
                        <div class="w-16 h-16 relative flex items-center justify-center">
                            <svg class="w-16 h-16 transform -rotate-90" viewBox="0 0 36 36">
                                <circle cx="18" cy="18" r="15" fill="none" stroke="#e2e8f0" stroke-width="3"/>
                                <circle cx="18" cy="18" r="15" fill="none"
                                    stroke="{{ $pct >= 80 ? '#16a34a' : ($pct >= 60 ? '#ca8a04' : '#ef4444') }}"
                                    stroke-width="3"
                                    stroke-dasharray="{{ $pct * 0.942 }}, 94.2"
                                    stroke-linecap="round"/>
                            </svg>
                        </div>
                        @else
                        <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm font-medium">In Progress</span>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            {{ $sessions->links() }}
            @else
            <div class="px-6 py-16 text-center">
                <div class="text-5xl mb-4">🎮</div>
                <p class="text-slate-500 font-medium text-lg">No games played yet!</p>
                <p class="text-slate-400 mb-6">Play a game and your results will appear here.</p>
                <a href="{{ route('math.index') }}"
                    class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition-colors">
                    Start Playing
                </a>
            </div>
            @endif
        </div>
    </div>
</x-app-layout>
