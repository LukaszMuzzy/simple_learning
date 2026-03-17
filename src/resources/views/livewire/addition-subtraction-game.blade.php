<div>
    @if($phase === 'setup')
    {{-- ======================== SETUP PHASE ======================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-indigo-600 px-8 py-6 text-white">
            <h1 class="text-2xl sm:text-3xl font-extrabold">± Addition & Subtraction</h1>
            <p class="text-blue-100 mt-1">Configure your quiz and start playing!</p>
        </div>

        <div class="p-6 sm:p-8 space-y-7">

            {{-- Operation --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Operation</label>
                <div class="grid grid-cols-3 gap-3">
                    @foreach([['add', '+', 'Addition', 'blue'], ['subtract', '−', 'Subtraction', 'orange'], ['mix', '±', 'Mix Both', 'purple']] as [$val, $sym, $label, $color])
                    <button wire:click="$set('operation', '{{ $val }}')"
                        class="flex flex-col items-center p-4 rounded-xl border-2 font-semibold transition-all duration-150
                               {{ $operation === $val
                                  ? 'border-indigo-500 bg-indigo-50 text-indigo-700'
                                  : 'border-slate-200 text-slate-600 hover:border-indigo-300 hover:bg-slate-50' }}">
                        <span class="text-3xl font-black mb-1">{{ $sym }}</span>
                        <span class="text-xs">{{ $label }}</span>
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Number of questions --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Number of Questions: <span class="text-indigo-600">{{ $questionCount }}</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 25, 30] as $n)
                    <button wire:click="$set('questionCount', {{ $n }})"
                        class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all duration-150
                               {{ $questionCount === $n
                                  ? 'border-indigo-500 bg-indigo-600 text-white'
                                  : 'border-slate-200 text-slate-600 hover:border-indigo-300' }}">
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Time per question --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Time per Question:
                    <span class="text-indigo-600">{{ $timePerQuestion === 0 ? 'No Limit' : $timePerQuestion . 's' }}</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0, '∞ No Limit'], [5, '5s'], [10, '10s'], [15, '15s'], [30, '30s'], [60, '60s']] as [$t, $label])
                    <button wire:click="$set('timePerQuestion', {{ $t }})"
                        class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all duration-150
                               {{ $timePerQuestion === $t
                                  ? 'border-indigo-500 bg-indigo-600 text-white'
                                  : 'border-slate-200 text-slate-600 hover:border-indigo-300' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Number size (digits) --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">
                    Number Size: <span class="text-indigo-600">{{ $maxDigits }} digit{{ $maxDigits > 1 ? 's' : '' }} (up to {{ pow(10, $maxDigits) - 1 }})</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[1, '1 digit (0–9)'], [2, '2 digits (10–99)'], [3, '3 digits (100–999)'], [4, '4 digits (1000–9999)']] as [$d, $label])
                    <button wire:click="$set('maxDigits', {{ $d }})"
                        class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all duration-150
                               {{ $maxDigits === $d
                                  ? 'border-indigo-500 bg-indigo-600 text-white'
                                  : 'border-slate-200 text-slate-600 hover:border-indigo-300' }}">
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            {{-- Allow negative --}}
            <div class="flex items-center justify-between p-4 bg-slate-50 rounded-xl">
                <div>
                    <p class="font-bold text-slate-700">Allow Negative Results</p>
                    <p class="text-sm text-slate-500">e.g. 3 − 8 = −5</p>
                </div>
                <button wire:click="$set('allowNegative', {{ $allowNegative ? 'false' : 'true' }})"
                    class="relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200 focus:outline-none
                           {{ $allowNegative ? 'bg-indigo-600' : 'bg-slate-300' }}">
                    <span class="inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200
                                 {{ $allowNegative ? 'translate-x-6' : 'translate-x-1' }}"></span>
                </button>
            </div>

            {{-- Answer mode --}}
            <div>
                <label class="block text-sm font-bold text-slate-700 mb-3">Answer Method</label>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    <button wire:click="$set('answerMode', 'type')"
                        class="flex items-center space-x-3 p-4 rounded-xl border-2 transition-all duration-150
                               {{ $answerMode === 'type'
                                  ? 'border-indigo-500 bg-indigo-50'
                                  : 'border-slate-200 hover:border-indigo-300' }}">
                        <div class="w-10 h-10 rounded-lg {{ $answerMode === 'type' ? 'bg-indigo-600' : 'bg-slate-200' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $answerMode === 'type' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold {{ $answerMode === 'type' ? 'text-indigo-700' : 'text-slate-700' }}">Type Answer</p>
                            <p class="text-xs text-slate-500">Enter your answer in a text box</p>
                        </div>
                    </button>
                    <button wire:click="$set('answerMode', 'multiple_choice')"
                        class="flex items-center space-x-3 p-4 rounded-xl border-2 transition-all duration-150
                               {{ $answerMode === 'multiple_choice'
                                  ? 'border-indigo-500 bg-indigo-50'
                                  : 'border-slate-200 hover:border-indigo-300' }}">
                        <div class="w-10 h-10 rounded-lg {{ $answerMode === 'multiple_choice' ? 'bg-indigo-600' : 'bg-slate-200' }} flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 {{ $answerMode === 'multiple_choice' ? 'text-white' : 'text-slate-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <div class="text-left">
                            <p class="font-bold {{ $answerMode === 'multiple_choice' ? 'text-indigo-700' : 'text-slate-700' }}">Multiple Choice</p>
                            <p class="text-xs text-slate-500">Pick from 4 possible answers</p>
                        </div>
                    </button>
                </div>
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
                class="w-full py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-extrabold rounded-xl transition-all duration-150 shadow-md hover:shadow-lg transform hover:-translate-y-0.5">
                🚀 Start Quiz
            </button>
        </div>
    </div>

    @elseif($phase === 'playing')
    {{-- ======================== PLAYING PHASE ======================== --}}
    <div class="space-y-5" @if($timePerQuestion > 0) wire:poll.1000ms="tick" @endif>

        {{-- Progress bar --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
            <div class="flex justify-between text-sm font-semibold text-slate-600 mb-2">
                <span>Question {{ $currentQuestion + 1 }} of {{ $questionCount }}</span>
                <span class="text-green-600">✓ {{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-100 rounded-full h-3">
                <div class="bg-indigo-600 h-3 rounded-full transition-all duration-500"
                     style="width: {{ ($currentQuestion / $questionCount) * 100 }}%"></div>
            </div>
        </div>

        {{-- Timer bar --}}
        @if($timePerQuestion > 0)
        @php
            $pctLeft    = $timePerQuestion > 0 ? ($timeLeft / $timePerQuestion) * 100 : 100;
            $timerColor = $timeLeft <= 3 ? 'bg-red-500' : ($timeLeft <= 5 ? 'bg-orange-400' : 'bg-indigo-500');
            $textColor  = $timeLeft <= 3 ? 'text-red-600' : ($timeLeft <= 5 ? 'text-orange-500' : 'text-indigo-700');
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
            @if($answered)
            <p class="text-xs text-slate-400 mt-2 text-center">Next question in {{ $timeLeft }}s…</p>
            @endif
        </div>
        @endif

        {{-- Question Card --}}
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 sm:p-10">

            {{-- Feedback Banner --}}
            @if($feedback === 'correct')
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center space-x-3">
                <span class="text-2xl">🎉</span>
                <div>
                    <p class="font-bold text-green-700 text-lg">Correct!</p>
                    <p class="text-green-600 text-sm">
                        Well done! Keep it up.
                        @if($timePerQuestion > 0) · Next in {{ $timeLeft }}s @endif
                    </p>
                </div>
            </div>
            @elseif($feedback === 'incorrect')
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl flex items-center space-x-3">
                <span class="text-2xl">❌</span>
                <div>
                    <p class="font-bold text-red-700 text-lg">Not quite!</p>
                    <p class="text-red-600 text-sm">
                        The correct answer was <strong>{{ $correctAnswer }}</strong>
                        @if($timePerQuestion > 0) · Next in {{ $timeLeft }}s @endif
                    </p>
                </div>
            </div>
            @endif

            {{-- The Math Problem --}}
            <div class="text-center mb-8">
                <div class="inline-block bg-slate-50 rounded-2xl px-8 sm:px-16 py-6 border border-slate-200">
                    <div class="font-mono">
                        <div class="text-5xl sm:text-6xl font-black text-slate-800 text-right">{{ $num1 }}</div>
                        <div class="flex items-center justify-between text-5xl sm:text-6xl font-black text-slate-800 mt-1">
                            <span class="{{ $currentOperation === '+' ? 'text-blue-600' : 'text-orange-600' }}">{{ $currentOperation }}</span>
                            <span>{{ $num2 }}</span>
                        </div>
                        <div class="border-b-4 border-slate-700 my-3"></div>
                        @if($answerMode === 'type')
                            @if(!$answered)
                            {{-- x-init focuses the input on every new question automatically --}}
                            <div class="mt-2" wire:key="addsub-input-{{ $currentQuestion }}"
                                 x-data x-init="$nextTick(() => $el.querySelector('input')?.focus())">
                                <input
                                    type="number"
                                    wire:model.live="userAnswer"
                                    wire:keydown.enter="submitAndNext"
                                    placeholder="?"
                                    class="w-full text-4xl sm:text-5xl font-black text-center bg-white border-2 border-indigo-300 rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-4 focus:ring-indigo-100 text-slate-800 transition-all"
                                >
                            </div>
                            @else
                            <div class="text-5xl sm:text-6xl font-black text-right {{ $feedback === 'correct' ? 'text-green-600' : 'text-red-500' }}">
                                {{ $userAnswer }}
                            </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            {{-- Multiple Choice Buttons --}}
            @if($answerMode === 'multiple_choice')
            <div class="grid grid-cols-2 gap-3 mb-6">
                @foreach($choices as $choice)
                {{-- wire:key forces a full DOM replacement on every new question so Alpine re-attaches listeners --}}
                <button
                    wire:key="addsub-choice-{{ $currentQuestion }}-{{ $loop->index }}"
                    @if(!$answered) wire:click="submitAndNext({{ $choice }})" @endif
                    {{ $answered ? 'disabled' : '' }}
                    class="py-5 text-3xl font-black rounded-xl border-2 transition-all duration-150
                           @if($answered)
                               @if($choice === $correctAnswer)
                                   border-green-500 bg-green-100 text-green-700
                               @elseif($userAnswer !== '' && (int)$userAnswer === $choice)
                                   border-red-500 bg-red-100 text-red-600
                               @else
                                   border-slate-200 bg-slate-50 text-slate-400 opacity-50
                               @endif
                           @else
                               border-slate-200 bg-white text-slate-800 hover:border-indigo-400 hover:bg-indigo-50 hover:text-indigo-700 cursor-pointer
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
                    class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white text-lg font-extrabold rounded-xl transition-all duration-150">
                    ✓ Submit Answer
                </button>
                @endif

                {{-- Finish button only needed when timer auto-submits the last question --}}
                @if($answered)
                <button wire:click="nextQuestion"
                    class="flex-1 py-4 bg-green-600 hover:bg-green-700 text-white text-lg font-extrabold rounded-xl transition-all duration-150">
                    🏁 Finish
                </button>
                @endif
            </div>
        </div>
    </div>

    @elseif($phase === 'summary')
    {{-- ======================== SUMMARY PHASE ======================== --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="bg-gradient-to-r from-indigo-500 to-purple-600 px-8 py-8 text-white text-center">
            @php $pct = $questionCount > 0 ? round(($correctCount / $questionCount) * 100) : 0; @endphp
            <div class="text-6xl mb-3">
                @if($pct >= 80) 🏆 @elseif($pct >= 60) 🎯 @else 💪 @endif
            </div>
            <h2 class="text-3xl font-extrabold mb-1">Quiz Complete!</h2>
            <p class="text-indigo-200">Here's how you did</p>
        </div>

        <div class="p-6 sm:p-8">
            {{-- Score --}}
            <div class="text-center mb-8">
                <div class="text-7xl font-black {{ $pct >= 80 ? 'text-green-600' : ($pct >= 60 ? 'text-yellow-500' : 'text-red-500') }}">
                    {{ $pct }}%
                </div>
                <p class="text-slate-500 mt-1 text-lg">{{ $correctCount }} out of {{ $questionCount }} correct</p>
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
                <h3 class="font-bold text-slate-700 mb-3">Question Review</h3>
                <div class="space-y-2 max-h-72 overflow-y-auto pr-1">
                    @foreach($results as $i => $r)
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
                    class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-lg rounded-xl transition-all duration-150">
                    🔄 Play Again
                </button>
                <a href="{{ route('math.index') }}"
                    class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-lg rounded-xl transition-all duration-150 text-center">
                    ← Back to Math
                </a>
                @auth
                <a href="{{ route('progress.index') }}"
                    class="flex-1 py-4 bg-green-600 hover:bg-green-700 text-white font-extrabold text-lg rounded-xl transition-all duration-150 text-center">
                    📊 My Progress
                </a>
                @endauth
            </div>
        </div>
    </div>
    @endif
</div>
