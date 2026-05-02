<x-app-layout>
    <x-slot name="title">My Progress</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <h1 class="text-4xl font-extrabold text-slate-800">📊 My Progress</h1>
            <p class="text-slate-500 mt-2 text-lg">Track your learning journey across all games</p>
        </div>

        @if(session('success'))
        <div class="flex items-center space-x-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-semibold mb-6">
            <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

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

        {{-- Trickiest Multiplication Tables --}}
        @if($trickyTables->count() > 0)
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="px-6 py-5 border-b border-slate-100 flex flex-wrap items-center justify-between gap-3">
                <div>
                    <h2 class="text-xl font-extrabold text-slate-800">🔥 Trickiest Multiplication Tables</h2>
                    <p class="text-sm text-slate-400 mt-0.5">Based on your full history — sorted by most wrong answers</p>
                </div>
                <div class="flex items-center gap-3">
                    {{-- Practice Trickiest button --}}
                    @if(!empty($trickyNums))
                    <a href="{{ route('math.multiplication') }}?focus={{ implode(',', $trickyNums) }}"
                        class="inline-flex items-center gap-1.5 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-bold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Practise Trickiest
                    </a>
                    @endif
                    {{-- Reset button --}}
                    <form method="POST" action="{{ route('progress.tricky-tables.reset') }}"
                        x-data
                        @submit.prevent="if(confirm('This will delete all your Trickiest Tables history and start fresh. Are you sure?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="inline-flex items-center gap-1.5 px-4 py-2 bg-slate-100 hover:bg-red-50 text-slate-500 hover:text-red-600 text-sm font-bold rounded-lg border border-slate-200 hover:border-red-200 transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                            </svg>
                            Reset History
                        </button>
                    </form>
                </div>
            </div>
            <div class="divide-y divide-slate-100">
                @foreach($trickyTables as $row)
                @php
                    $rowPct = $row->attempts > 0
                        ? round((($row->attempts - $row->wrong_count) / $row->attempts) * 100)
                        : 100;
                @endphp
                <div class="px-6 py-4 flex items-center justify-between gap-4">
                    {{-- Equation --}}
                    <span class="font-mono font-black text-slate-800 text-xl w-32 shrink-0">
                        {{ $row->num1 }} × {{ $row->num2 }} = {{ $row->num1 * $row->num2 }}
                    </span>

                    {{-- Progress bar --}}
                    <div class="flex-1 hidden sm:block">
                        <div class="flex items-center gap-2">
                            <div class="flex-1 bg-slate-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full {{ $rowPct >= 80 ? 'bg-green-500' : ($rowPct >= 50 ? 'bg-yellow-400' : 'bg-red-500') }}"
                                     style="width: {{ $rowPct }}%"></div>
                            </div>
                            <span class="text-sm font-bold w-12 text-right {{ $rowPct >= 80 ? 'text-green-600' : ($rowPct >= 50 ? 'text-yellow-600' : 'text-red-600') }}">
                                {{ $rowPct }}%
                            </span>
                        </div>
                    </div>

                    {{-- Detail chips --}}
                    <div class="flex items-center gap-3 shrink-0 text-sm">
                        <span class="px-2.5 py-1 bg-red-50 text-red-600 font-semibold rounded-lg">
                            {{ $row->wrong_count }} wrong
                        </span>
                        <span class="px-2.5 py-1 bg-slate-50 text-slate-500 font-medium rounded-lg">
                            {{ $row->attempts }} tries
                        </span>
                        <span class="px-2.5 py-1 bg-purple-50 text-purple-600 font-medium rounded-lg hidden sm:inline">
                            ~{{ round($row->avg_time, 1) }}s avg
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

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
                                'multiplication'       => 'Multiplication Game',
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
                        <div class="flex items-center space-x-2">
                            <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-sm font-medium">In Progress</span>
                            <form method="POST" action="{{ route('progress.destroy', $session) }}"
                                x-data
                                @submit.prevent="if(confirm('Remove this session from your history?')) $el.submit()">
                                @csrf @method('DELETE')
                                <button type="submit"
                                    title="Delete this session"
                                    class="p-1.5 rounded-lg text-slate-400 hover:text-red-500 hover:bg-red-50 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
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
