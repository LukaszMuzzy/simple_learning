<div>
    @if($phase === 'setup')
    {{-- ======================== SETUP PHASE ======================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 px-8 py-6 text-white">
            <h1 class="text-2xl sm:text-3xl font-extrabold">× 4th Class Multiplication</h1>
            <p class="text-green-100 mt-1">Tables 0–12 · Configure your exam below</p>
        </div>

        <div class="p-6 sm:p-8 space-y-7">

            {{-- Number of questions --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Number of Questions: <span class="text-green-600">{{ $questionCount }}</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('questionCount', {{ $n }})"
                        class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all duration-150
                               {{ $questionCount === $n
                                  ? 'border-green-500 bg-green-600 text-white'
                                  : 'border-slate-200 text-slate-600 hover:border-green-300' }}">
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Time per question --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Time per Question:
                    <span class="text-green-600">{{ $timePerQuestion === 0 ? 'No Limit' : $timePerQuestion . 's' }}</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0, '∞ No Limit'], [6, '6s'], [10, '10s'], [15, '15s'], [30, '30s'], [60, '60s']] as [$t, $label])
                    <button wire:click="$set('timePerQuestion', {{ $t }})"
                        class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all duration-150
                               {{ $timePerQuestion === $t
                                  ? 'border-green-500 bg-green-600 text-white'
                                  : 'border-slate-200 text-slate-600 hover:border-green-300' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Answer mode --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Answer Method</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button wire:click="$set('answerMode', 'type')"
                        class="flex items-center space-x-3 p-4 rounded-xl border-2 transition-all duration-150
                               {{ $answerMode === 'type' ? 'border-green-500 bg-green-50' : 'border-slate-200 hover:border-green-300' }}">
                        <div class="w-10 h-10 rounded-lg {{ $answerMode === 'type' ? 'bg-green-600' : 'bg-slate-200' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $answerMode === 'type' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold {{ $answerMode === 'type' ? 'text-green-700' : 'text-slate-700' }}">Type Answer</p>
                            <p class="text-xs text-slate-500">Enter your answer in a text box</p>
                        </div>
                    </button>
                    <button wire:click="$set('answerMode', 'multiple_choice')"
                        class="flex items-center space-x-3 p-4 rounded-xl border-2 transition-all duration-150
                               {{ $answerMode === 'multiple_choice' ? 'border-green-500 bg-green-50' : 'border-slate-200 hover:border-green-300' }}">
                        <div class="w-10 h-10 rounded-lg {{ $answerMode === 'multiple_choice' ? 'bg-green-600' : 'bg-slate-200' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $answerMode === 'multiple_choice' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold {{ $answerMode === 'multiple_choice' ? 'text-green-700' : 'text-slate-700' }}">Multiple Choice</p>
                            <p class="text-xs text-slate-500">Pick from 4 possible answers</p>
                        </div>
                    </button>
                </div>
            </div>

            {{-- Exam mode toggle --}}
            <div class="border-2 rounded-xl transition-all duration-150
                        {{ $examMode ? 'border-amber-400 bg-amber-50' : 'border-slate-200 bg-slate-50' }}">
                <button wire:click="toggleExamMode"
                    class="flex items-center justify-between w-full p-4 text-left">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 rounded-lg {{ $examMode ? 'bg-amber-500' : 'bg-slate-300' }} flex items-center justify-center flex-shrink-0 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                        </div>
                        <div>
                            <p class="font-bold {{ $examMode ? 'text-amber-800' : 'text-slate-700' }}">
                                Exam Mode
                                @if($examMode)
                                <span class="ml-2 px-2 py-0.5 bg-amber-500 text-white text-xs rounded-full">ON</span>
                                @endif
                            </p>
                            <p class="text-xs text-slate-500">No feedback during the exam — results revealed at the end only</p>
                        </div>
                    </div>
                    <div class="flex-shrink-0 ml-4">
                        <div class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200
                                    {{ $examMode ? 'bg-amber-500' : 'bg-slate-300' }}">
                            <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200
                                         {{ $examMode ? 'translate-x-6' : 'translate-x-1' }}"></span>
                        </div>
                    </div>
                </button>
            </div>

            @guest
            <div class="flex items-start space-x-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                <p class="text-sm text-amber-700">
                    <a href="{{ route('login') }}" class="font-bold underline">Log in</a> or
                    <a href="{{ route('register') }}" class="font-bold underline">register</a> to save your results and track progress!
                </p>
            </div>
            @endguest

            <button wire:click="startGame"
                class="w-full py-4 {{ $examMode ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-lg font-extrabold rounded-xl transition-all duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                🚀 {{ $examMode ? 'Start Exam' : 'Start Practice' }}
            </button>
        </div>
    </div>

    @elseif($phase === 'playing')
    {{-- ======================== PLAYING PHASE ======================== --}}
    <div class="space-y-5" @if($timePerQuestion > 0) wire:poll.1000ms="tick" @endif>

        {{-- Progress bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-semibold text-slate-600">
                    Question {{ $currentQuestion + 1 }} of {{ $questionCount }}
                </span>
                <div class="flex items-center gap-3">
                    @if(!$examMode)
                    <span class="text-sm font-semibold text-green-600">✓ {{ $correctCount }}</span>
                    @endif
                    @if($examMode)
                    <span class="px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">EXAM</span>
                    @endif
                </div>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3">
                <div class="{{ $examMode ? 'bg-amber-500' : 'bg-green-600' }} h-3 rounded-full transition-all duration-500"
                     style="width: {{ ($currentQuestion / $questionCount) * 100 }}%"></div>
            </div>
        </div>

        {{-- Timer bar --}}
        @if($timePerQuestion > 0)
        @php
            $pctLeft    = $timePerQuestion > 0 ? ($timeLeft / $timePerQuestion) * 100 : 100;
            $timerColor = $timeLeft <= 3 ? 'bg-red-500' : ($timeLeft <= 5 ? 'bg-orange-400' : 'bg-green-500');
            $textColor  = $timeLeft <= 3 ? 'text-red-600' : ($timeLeft <= 5 ? 'text-orange-500' : 'text-green-700');
        @endphp
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-5 py-4">
            <div class="flex items-center justify-between mb-2">
                <span class="text-sm font-semibold text-slate-500">Time remaining</span>
                <span class="text-2xl font-black {{ $textColor }}">{{ $timeLeft }}s</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                <div class="{{ $timerColor }} h-3 rounded-full transition-all duration-900"
                     style="width: {{ $pctLeft }}%"></div>
            </div>
        </div>
        @endif

        {{-- Question Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-10">

            {{-- Feedback Banner — hidden in exam mode --}}
            @if(!$examMode)
                @if($feedback === 'correct')
                <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center space-x-3">
                    <span class="text-2xl">🎉</span>
                    <div>
                        <p class="font-bold text-green-700 text-lg">Correct!</p>
                        <p class="text-green-600 text-sm">{{ $num1 }} × {{ $num2 }} = {{ $correctAnswer }}</p>
                    </div>
                </div>
                @elseif($feedback === 'incorrect')
                <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center space-x-3">
                    <span class="text-2xl">❌</span>
                    <div>
                        <p class="font-bold text-red-700 text-lg">Not quite!</p>
                        <p class="text-red-600 text-sm">The correct answer was <strong>{{ $num1 }} × {{ $num2 }} = {{ $correctAnswer }}</strong></p>
                    </div>
                </div>
                @endif
            @elseif($answered)
                {{-- Exam mode: neutral acknowledgement only --}}
                <div class="mb-6 p-4 bg-slate-50 border border-slate-200 rounded-xl flex items-center space-x-3">
                    <span class="text-2xl">📝</span>
                    <p class="font-semibold text-slate-600">Answer recorded — results revealed at the end</p>
                </div>
            @endif

            {{-- The Math Problem --}}
            <div class="text-center mb-8">
                <div class="inline-block bg-slate-50 rounded-2xl px-8 sm:px-16 py-8 border border-slate-200">
                    <div class="font-mono">
                        <div class="text-6xl sm:text-7xl font-black text-slate-800">
                            {{ $num1 }} <span class="{{ $examMode ? 'text-amber-500' : 'text-green-600' }}">×</span> {{ $num2 }}
                        </div>
                        <div class="border-b-4 border-slate-700 my-4"></div>
                        @if($answerMode === 'type')
                            @if(!$answered)
                            {{-- x-init focuses the input on every new question automatically --}}
                            <div class="mt-2" wire:key="mult-input-{{ $currentQuestion }}"
                                 x-data x-init="$nextTick(() => $el.querySelector('input')?.focus())">
                                <input
                                    type="number"
                                    wire:model.live="userAnswer"
                                    wire:keydown.enter="submitAndNext"
                                    placeholder="= ?"
                                    class="w-full text-5xl sm:text-6xl font-black text-center bg-white border-2 {{ $examMode ? 'border-amber-300 focus:border-amber-500 focus:ring-amber-100' : 'border-green-300 focus:border-green-500 focus:ring-green-100' }} rounded-xl px-4 py-3 focus:outline-none focus:ring-4 text-slate-800 transition-all"
                                >
                            </div>
                            @else
                            {{-- After answering: neutral in exam mode, coloured in normal mode --}}
                            <div class="text-5xl sm:text-6xl font-black
                                {{ $examMode ? 'text-slate-500' : ($feedback === 'correct' ? 'text-green-600' : 'text-red-500') }}">
                                = {{ $userAnswer }}
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Multiple Choice Buttons --}}
            @if($answerMode === 'multiple_choice')
            <div class="grid grid-cols-2 gap-4 mb-6">
                @foreach($choices as $choice)
                {{-- wire:key forces a full DOM replacement on every new question so Alpine re-attaches listeners --}}
                <button
                    wire:key="mult-choice-{{ $currentQuestion }}-{{ $loop->index }}"
                    @if(!$answered) wire:click="submitAndNext({{ $choice }})" @endif
                    {{ $answered ? 'disabled' : '' }}
                    class="py-6 text-4xl font-black rounded-xl border-2 transition-all duration-150
                           @if($answered)
                               @if($examMode)
                                   {{ $userAnswer !== '' && (int)$userAnswer === $choice
                                       ? 'border-slate-400 bg-slate-100 text-slate-600'
                                       : 'border-slate-200 bg-slate-50 text-slate-400 opacity-50' }}
                               @else
                                   @if($choice === $correctAnswer)
                                       border-green-500 bg-green-100 text-green-700
                                   @elseif($userAnswer !== '' && (int)$userAnswer === $choice)
                                       border-red-500 bg-red-100 text-red-600
                                   @else
                                       border-slate-200 bg-slate-50 text-slate-400 opacity-50
                                   @endif
                               @endif
                           @else
                               border-slate-200 bg-white text-slate-800 hover:border-green-400 hover:bg-green-50 hover:text-green-700 cursor-pointer shadow-sm hover:shadow-md
                           @endif">
                    {{ $choice }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- Action Buttons --}}
            <div class="flex gap-3">
                @if($answerMode === 'type' && !$answered)
                {{-- Submit + immediately advance --}}
                <button wire:click="submitAndNext"
                    class="flex-1 py-4 {{ $examMode ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-lg font-extrabold rounded-xl transition-all duration-150">
                    ✓ Submit Answer
                </button>
                @endif

                {{-- Finish button only appears on the very last question after timer auto-submits --}}
                @if($answered)
                <button wire:click="nextQuestion"
                    class="flex-1 py-5 {{ $examMode ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white text-xl font-extrabold rounded-xl transition-all duration-150 shadow-md">
                    🏁 Finish
                </button>
                @endif
            </div>
        </div>
    </div>

    @elseif($phase === 'summary')
    {{-- ======================== SUMMARY PHASE ======================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r {{ $examMode ? 'from-amber-500 to-orange-600' : 'from-green-500 to-teal-600' }} px-8 py-8 text-white text-center">
            @php $pct = $questionCount > 0 ? round(($correctCount / $questionCount) * 100) : 0; @endphp
            <div class="text-6xl mb-3">
                @if($pct >= 80) 🏆 @elseif($pct >= 60) 🎯 @else 💪 @endif
            </div>
            <h2 class="text-3xl font-extrabold mb-1">
                {{ $examMode ? 'Exam Complete!' : 'Practice Complete!' }}
            </h2>
            @if($examMode)
            <span class="inline-block px-3 py-1 bg-white/20 rounded-full text-sm font-bold mt-1">📝 Exam Mode</span>
            @endif
        </div>

        <div class="p-6 sm:p-8">
            {{-- Score --}}
            <div class="text-center mb-8">
                <div class="text-7xl font-black {{ $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-yellow-500' : 'text-red-500') }}">
                    {{ $pct }}%
                </div>
                <p class="text-slate-500 mt-1 text-lg">{{ $correctCount }} out of {{ $questionCount }} correct</p>
                @if($pct >= 80)
                    <p class="text-green-600 font-bold mt-2">Excellent work! 🌟</p>
                @elseif($pct >= 60)
                    <p class="text-yellow-600 font-bold mt-2">Good job, keep practising!</p>
                @else
                    <p class="text-red-600 font-bold mt-2">Keep going — practice makes perfect!</p>
                @endif
            </div>

            {{-- Stats --}}
            <div class="grid grid-cols-3 gap-4 mb-8">
                <div class="text-center p-4 bg-green-50 rounded-xl">
                    <div class="text-3xl font-black text-green-600">{{ $correctCount }}</div>
                    <div class="text-xs font-semibold text-green-700 mt-1">Correct</div>
                </div>
                <div class="text-center p-4 bg-red-50 rounded-xl">
                    <div class="text-3xl font-black text-red-500">{{ $wrongCount }}</div>
                    <div class="text-xs font-semibold text-red-700 mt-1">Wrong</div>
                </div>
                <div class="text-center p-4 bg-blue-50 rounded-xl">
                    <div class="text-3xl font-black text-blue-600">
                        @if($totalTimeSeconds >= 60)
                            {{ floor($totalTimeSeconds / 60) }}m{{ $totalTimeSeconds % 60 }}s
                        @else
                            {{ $totalTimeSeconds }}s
                        @endif
                    </div>
                    <div class="text-xs font-semibold text-blue-700 mt-1">Total Time</div>
                </div>
            </div>

            {{-- Answers Review --}}
            <div class="mb-8">
                <h3 class="font-bold text-slate-700 mb-3">
                    Question Review
                    @if($examMode)
                    <span class="ml-2 text-xs font-normal text-amber-600">(full results now revealed)</span>
                    @endif
                </h3>
                <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                    @foreach($results as $r)
                    <div class="flex items-center justify-between p-3 rounded-xl {{ $r['is_correct'] ? 'bg-green-50 border border-green-100' : 'bg-red-50 border border-red-100' }}">
                        <div class="flex items-center space-x-3">
                            <span class="text-lg">{{ $r['is_correct'] ? '✓' : '✗' }}</span>
                            <span class="font-mono font-bold text-slate-700">{{ $r['question'] }} = {{ $r['correct_answer'] }}</span>
                        </div>
                        @if(!$r['is_correct'])
                        <span class="text-sm text-red-600 font-semibold">
                            You: {{ $r['user_answer'] !== null ? $r['user_answer'] : '—' }}
                        </span>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="resetGame"
                    class="flex-1 py-4 {{ $examMode ? 'bg-amber-500 hover:bg-amber-600' : 'bg-green-600 hover:bg-green-700' }} text-white font-extrabold text-lg rounded-xl transition-all duration-150">
                    🔄 Go Again
                </button>
                <a href="{{ route('math.index') }}"
                    class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-lg rounded-xl transition-all duration-150 text-center">
                    ← Back to Math
                </a>
                @auth
                <a href="{{ route('progress.index') }}"
                    class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-lg rounded-xl transition-all duration-150 text-center">
                    📊 My Progress
                </a>
                @endauth
            </div>
        </div>
    </div>
    @endif
</div>
