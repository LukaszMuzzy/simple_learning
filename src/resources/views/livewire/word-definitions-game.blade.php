<div
    x-on:scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="min-h-screen bg-gradient-to-br from-sky-50 via-blue-50 to-indigo-50 py-6 px-4">

    <div class="max-w-2xl mx-auto">

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SETUP PHASE                                                        --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'setup')
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-8 py-7">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">📖</div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white">Word Definitions</h1>
                        <p class="text-sky-100 text-sm mt-0.5">Read the word — choose the correct meaning!</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                {{-- Word Set --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Word Set</label>
                    <select wire:model.live="wordSet"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-semibold focus:ring-2 focus:ring-sky-400 focus:border-sky-400 outline-none bg-white">
                        @foreach($sourceOptions as $groupLabel => $options)
                        <optgroup label="{{ $groupLabel }}">
                            @foreach($options as $value => $label)
                            <option value="{{ $value }}">{{ $label }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5">{{ $maxCount }} {{ $maxCount === 1 ? 'word' : 'words' }} available in this set</p>
                </div>

                {{-- Number of Questions --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach([5, 10, 15, 20] as $n)
                        <button wire:click="$set('questionCount', {{ $n }})"
                            class="px-5 py-2.5 rounded-xl font-bold border-2 transition-all {{ $questionCount == $n ? 'bg-sky-500 border-sky-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-sky-300' }}">
                            {{ $n }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <button wire:click="startGame"
                    class="w-full py-4 rounded-2xl font-extrabold text-xl bg-sky-500 hover:bg-sky-400 text-white transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    📖 Start Learning!
                </button>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- QUESTION PHASE                                                     --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'question')
        <div class="space-y-4" wire:key="definition-question-{{ $currentIndex }}">
            {{-- Progress --}}
            <div class="flex items-center justify-between text-sm font-semibold text-sky-700 px-1">
                <span>Question {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-sky-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            {{-- Question card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                {{-- Word display --}}
                <div class="bg-gradient-to-br from-sky-500 to-blue-600 px-8 py-12 text-center"
                    x-data
                    x-init="
                        if (window.speechSynthesis) {
                            const u = new SpeechSynthesisUtterance('{{ $currentQuestion['word'] }}');
                            u.lang = 'en-GB'; u.rate = 0.85;
                            speechSynthesis.cancel();
                            speechSynthesis.speak(u);
                        }
                    ">
                    <p class="text-sky-100 text-sm font-semibold uppercase tracking-widest mb-4">What does this word mean?</p>
                    <div class="text-5xl sm:text-7xl font-extrabold text-white tracking-wide drop-shadow-md mb-6">
                        {{ $currentQuestion['word'] }}
                    </div>
                    <button
                        x-data
                        x-on:click="if(window.speechSynthesis){const u=new SpeechSynthesisUtterance('{{ $currentQuestion['word'] }}');u.lang='en-GB';u.rate=0.85;speechSynthesis.cancel();speechSynthesis.speak(u);}"
                        class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white/15 hover:bg-white/25 rounded-xl text-white font-semibold transition-all border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                        </svg>
                        <span>Hear it again 🔊</span>
                    </button>
                </div>

                {{-- Options --}}
                <div class="p-6 space-y-3">
                    @foreach($currentQuestion['options'] as $i => $option)
                    <button wire:click="selectAnswer({{ $i }})"
                        class="w-full text-left px-5 py-4 rounded-2xl border-2 font-semibold transition-all hover:shadow-md
                            border-slate-200 bg-white text-slate-700 hover:border-sky-400 hover:bg-sky-50">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg bg-sky-100 text-sky-700 font-extrabold text-sm mr-3 flex-shrink-0">{{ chr(65 + $i) }}</span>
                        {{ $option }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- FEEDBACK PHASE                                                     --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'feedback')
        @php $result = end($results); @endphp
        <div class="space-y-4" wire:key="definition-feedback-{{ $currentIndex }}">
            <div class="flex items-center justify-between text-sm font-semibold text-sky-700 px-1">
                <span>Question {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-sky-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                {{-- Result header --}}
                @if($lastCorrect)
                <div class="bg-gradient-to-br from-emerald-400 to-green-500 px-8 py-8 text-center">
                    <div class="text-5xl mb-2">🎉</div>
                    <p class="text-3xl font-extrabold text-white">Correct!</p>
                </div>
                @else
                <div class="bg-gradient-to-br from-red-400 to-rose-500 px-8 py-8 text-center">
                    <div class="text-5xl mb-2">😬</div>
                    <p class="text-3xl font-extrabold text-white">Not quite!</p>
                </div>
                @endif

                {{-- Word + definition review --}}
                <div class="px-6 py-6">
                    <div class="text-center mb-5">
                        <p class="text-3xl font-extrabold text-slate-800">{{ $currentQuestion['word'] }}</p>
                        <p class="text-slate-500 mt-2 text-sm italic">"{{ $currentQuestion['definition'] }}"</p>
                    </div>

                    {{-- Show all options with correct/wrong highlighting --}}
                    <div class="space-y-2 mb-5">
                        @foreach($currentQuestion['options'] as $i => $option)
                        @php
                            $isCorrectOption = $i === $currentQuestion['answer_idx'];
                            $isSelectedOption = $i === $selectedOption;
                        @endphp
                        <div class="w-full text-left px-5 py-3.5 rounded-2xl border-2 font-semibold
                            {{ $isCorrectOption ? 'border-emerald-400 bg-emerald-50 text-emerald-800' : ($isSelectedOption && !$isCorrectOption ? 'border-red-300 bg-red-50 text-red-700' : 'border-slate-100 bg-slate-50 text-slate-400') }}">
                            <span class="inline-flex items-center justify-center w-7 h-7 rounded-lg mr-3 font-extrabold text-sm flex-shrink-0
                                {{ $isCorrectOption ? 'bg-emerald-500 text-white' : ($isSelectedOption && !$isCorrectOption ? 'bg-red-400 text-white' : 'bg-slate-200 text-slate-500') }}">
                                {{ $isCorrectOption ? '✓' : ($isSelectedOption ? '✗' : chr(65 + $i)) }}
                            </span>
                            {{ $option }}
                        </div>
                        @endforeach
                    </div>

                    <button wire:click="proceedToNext"
                        class="w-full py-4 rounded-2xl font-extrabold text-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5
                            {{ $lastCorrect ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-slate-800 hover:bg-slate-700 text-white' }}">
                        @if($currentIndex + 1 >= $questionCount)
                            See Results 🏁
                        @else
                            Next Question →
                        @endif
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SUMMARY PHASE                                                      --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'summary')
        <div class="space-y-6">
            @php
                $pct = $questionCount > 0 ? round($correctCount / $questionCount * 100) : 0;
                $grade = $pct >= 90 ? ['🏆','Outstanding!','from-yellow-400 to-amber-500'] :
                        ($pct >= 70 ? ['⭐','Great job!','from-emerald-400 to-teal-500'] :
                        ($pct >= 50 ? ['👍','Good effort!','from-blue-400 to-cyan-500'] :
                                     ['💪','Keep practising!','from-slate-500 to-slate-700']));
            @endphp

            <div class="bg-gradient-to-br {{ $grade[2] }} rounded-3xl shadow-xl px-8 py-10 text-center text-white">
                <div class="text-6xl mb-3">{{ $grade[0] }}</div>
                <h2 class="text-3xl font-extrabold mb-1">{{ $grade[1] }}</h2>
                <p class="text-white/80 mb-6">Word definitions complete</p>

                <div class="grid grid-cols-3 gap-4 max-w-sm mx-auto mb-6">
                    <div class="bg-white/20 rounded-2xl py-4">
                        <div class="text-3xl font-extrabold">{{ $pct }}%</div>
                        <div class="text-xs text-white/80 font-semibold mt-0.5">Score</div>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4">
                        <div class="text-3xl font-extrabold">{{ $correctCount }}</div>
                        <div class="text-xs text-white/80 font-semibold mt-0.5">Correct</div>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4">
                        <div class="text-3xl font-extrabold">{{ $wrongCount }}</div>
                        <div class="text-xs text-white/80 font-semibold mt-0.5">Missed</div>
                    </div>
                </div>

                @php $mins = intdiv($totalTimeSeconds, 60); $secs = $totalTimeSeconds % 60; @endphp
                <p class="text-white/70 text-sm">Total time: {{ $mins > 0 ? "{$mins}m " : '' }}{{ $secs }}s</p>
            </div>

            {{-- Word review --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="px-6 py-5 border-b border-slate-100">
                    <h3 class="font-extrabold text-slate-800 text-lg">Word Review</h3>
                    <p class="text-slate-500 text-sm">Review every word and its definition</p>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($results as $r)
                    <div class="px-6 py-5 {{ $r['is_correct'] ? 'hover:bg-emerald-50' : 'hover:bg-red-50' }} transition-colors">
                        <div class="flex items-start space-x-3">
                            <span class="text-xl mt-0.5 flex-shrink-0">{{ $r['is_correct'] ? '✅' : '❌' }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2 mb-1">
                                    <button
                                        x-data
                                        x-on:click="if(window.speechSynthesis){const u=new SpeechSynthesisUtterance('{{ $r['word'] }}');u.lang='en-GB';u.rate=0.85;speechSynthesis.cancel();speechSynthesis.speak(u);}"
                                        class="font-extrabold text-slate-800 hover:text-sky-600 transition-colors flex items-center space-x-1">
                                        <span>{{ $r['word'] }}</span>
                                        <svg class="w-3.5 h-3.5 text-sky-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/>
                                        </svg>
                                    </button>
                                </div>
                                <p class="text-sm text-slate-500 italic">"{{ $r['definition'] }}"</p>
                                @if(!$r['is_correct'])
                                <p class="text-xs text-red-400 mt-1">
                                    You chose: <span class="font-semibold">{{ $r['options'][$r['selected_idx']] ?? '—' }}</span>
                                </p>
                                @endif
                            </div>
                            <span class="text-xs text-slate-400 flex-shrink-0 mt-1">{{ $r['time_taken'] }}s</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="resetGame"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-sky-500 hover:bg-sky-400 text-white transition-all shadow-md hover:shadow-lg">
                    📖 Play Again
                </button>
                <a href="{{ route('english.index') }}"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-white border-2 border-slate-200 text-slate-700 hover:border-sky-300 hover:text-sky-600 transition-all text-center">
                    ← English Menu
                </a>
            </div>

            @auth
            <div class="text-center">
                <a href="{{ route('progress.index') }}" class="text-sm text-sky-600 hover:text-sky-700 font-semibold underline underline-offset-2">
                    View all my progress →
                </a>
            </div>
            @endauth
        </div>
        @endif

    </div>
</div>
