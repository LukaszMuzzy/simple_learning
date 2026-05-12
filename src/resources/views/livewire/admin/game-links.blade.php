<div class="space-y-6">

    {{-- ── Game Selector ───────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="font-extrabold text-slate-800 mb-4 flex items-center gap-2">
            <span class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"/>
                </svg>
            </span>
            Choose a Game
        </h2>
        <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-3">

            <button wire:click="$set('selectedGame', 'multiplication')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-green-500 bg-green-600 text-white'  => $selectedGame === 'multiplication',
                    'border-green-200 bg-green-50 text-green-800 hover:opacity-80' => $selectedGame !== 'multiplication'])>
                <span class="text-2xl">×</span><span>Multiplication</span>
            </button>

            <button wire:click="$set('selectedGame', 'addition')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-blue-500 bg-blue-600 text-white'    => $selectedGame === 'addition',
                    'border-blue-200 bg-blue-50 text-blue-800 hover:opacity-80' => $selectedGame !== 'addition'])>
                <span class="text-2xl">±</span><span>Addition/Subtraction</span>
            </button>

            <button wire:click="$set('selectedGame', 'number-bonds')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-purple-500 bg-purple-600 text-white'  => $selectedGame === 'number-bonds',
                    'border-purple-200 bg-purple-50 text-purple-800 hover:opacity-80' => $selectedGame !== 'number-bonds'])>
                <span class="text-2xl">○</span><span>Number Bonds</span>
            </button>

            <button wire:click="$set('selectedGame', 'time')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-orange-500 bg-orange-600 text-white'  => $selectedGame === 'time',
                    'border-orange-200 bg-orange-50 text-orange-800 hover:opacity-80' => $selectedGame !== 'time'])>
                <span class="text-2xl">🕐</span><span>Time Telling</span>
            </button>

            <button wire:click="$set('selectedGame', 'spelling')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-emerald-500 bg-emerald-600 text-white'  => $selectedGame === 'spelling',
                    'border-emerald-200 bg-emerald-50 text-emerald-800 hover:opacity-80' => $selectedGame !== 'spelling'])>
                <span class="text-2xl">📝</span><span>Spelling</span>
            </button>

            <button wire:click="$set('selectedGame', 'anagram')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-cyan-500 bg-cyan-600 text-white'  => $selectedGame === 'anagram',
                    'border-cyan-200 bg-cyan-50 text-cyan-800 hover:opacity-80' => $selectedGame !== 'anagram'])>
                <span class="text-2xl">🔀</span><span>Anagram</span>
            </button>

            <button wire:click="$set('selectedGame', 'word-definitions')"
                @class(['flex flex-col items-center gap-2 px-4 py-4 rounded-xl border-2 font-bold text-sm transition-all duration-150',
                    'border-rose-500 bg-rose-600 text-white'  => $selectedGame === 'word-definitions',
                    'border-rose-200 bg-rose-50 text-rose-800 hover:opacity-80' => $selectedGame !== 'word-definitions'])>
                <span class="text-2xl">📖</span><span>Word Definitions</span>
            </button>

        </div>
    </div>

    {{-- ── Settings Panel ──────────────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6">
        <h2 class="font-extrabold text-slate-800 mb-5 flex items-center gap-2">
            <span class="w-8 h-8 bg-slate-700 rounded-lg flex items-center justify-center">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </span>
            Configure Settings
        </h2>

        {{-- ═══ MULTIPLICATION ═══════════════════════════════════════════════ --}}
        @if($selectedGame === 'multiplication')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Number of Questions: <span class="text-green-600">{{ $mult_questionCount }}</span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('mult_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => $mult_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-green-300' => $mult_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Question</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ No Limit'],[6,'6s'],[10,'10s'],[15,'15s'],[30,'30s'],[60,'60s']] as [$t,$lbl])
                    <button wire:click="$set('mult_timePerQuestion', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => $mult_timePerQuestion === $t,
                            'border-slate-200 text-slate-600 hover:border-green-300' => $mult_timePerQuestion !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Game</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ No Limit'],[60,'1 min'],[120,'2 min'],[180,'3 min'],[240,'4 min'],[300,'5 min']] as [$t,$lbl])
                    <button wire:click="$set('mult_timePerGame', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => $mult_timePerGame === $t,
                            'border-slate-200 text-slate-600 hover:border-green-300' => $mult_timePerGame !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">
                    Practice Numbers
                    <span class="font-normal text-slate-400 text-xs ml-1">
                        {{ empty($mult_selectedNumbers) ? '(all 0–12)' : '(' . implode(', ', $mult_selectedNumbers) . ')' }}
                    </span>
                </label>
                <div class="flex flex-wrap gap-2">
                    @foreach(range(0, 12) as $n)
                    <button wire:click="toggleMultNumber({{ $n }})"
                        @class(['w-10 h-10 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => in_array($n, $mult_selectedNumbers),
                            'border-slate-200 text-slate-600 hover:border-green-300' => !in_array($n, $mult_selectedNumbers)])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
                @if(!empty($mult_selectedNumbers))
                <button wire:click="$set('mult_selectedNumbers', [])" class="mt-1 text-xs text-slate-400 hover:text-red-500 underline">Clear (use all)</button>
                @endif
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Answer Method</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('mult_answerMode', 'type')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => $mult_answerMode === 'type',
                            'border-slate-200 text-slate-600 hover:border-green-300' => $mult_answerMode !== 'type'])>
                        ✏️ Type Answer
                    </button>
                    <button wire:click="$set('mult_answerMode', 'multiple_choice')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-green-500 bg-green-600 text-white' => $mult_answerMode === 'multiple_choice',
                            'border-slate-200 text-slate-600 hover:border-green-300' => $mult_answerMode !== 'multiple_choice'])>
                        ☑️ Multiple Choice
                    </button>
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="$set('mult_examMode', {{ $mult_examMode ? 'false' : 'true' }})"
                    @class(['relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200',
                        'bg-amber-500' => $mult_examMode, 'bg-slate-300' => !$mult_examMode])>
                    <span @class(['inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200',
                        'translate-x-6' => $mult_examMode, 'translate-x-1' => !$mult_examMode])></span>
                </button>
                <div>
                    <span class="text-sm font-bold text-slate-700">Exam Mode</span>
                    @if($mult_examMode)<span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">ON</span>@endif
                    <p class="text-xs text-slate-400">No feedback during the game — results revealed at the end</p>
                </div>
            </div>
        </div>

        {{-- ═══ ADDITION / SUBTRACTION ══════════════════════════════════════ --}}
        @elseif($selectedGame === 'addition')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Operation</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['mix' => 'Mix (±)', 'add' => 'Addition (+)', 'subtract' => 'Subtraction (−)'] as $val => $lbl)
                    <button wire:click="$set('add_operation', '{{ $val }}')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_operation === $val,
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_operation !== $val])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-blue-600">{{ $add_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('add_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Digits</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([1 => '1-digit', 2 => '2-digit', 3 => '3-digit'] as $d => $lbl)
                    <button wire:click="$set('add_maxDigits', {{ $d }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_maxDigits === $d,
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_maxDigits !== $d])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Question</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ No Limit'],[6,'6s'],[10,'10s'],[15,'15s'],[30,'30s'],[60,'60s']] as [$t,$lbl])
                    <button wire:click="$set('add_timePerQuestion', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_timePerQuestion === $t,
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_timePerQuestion !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="$set('add_allowNegative', {{ $add_allowNegative ? 'false' : 'true' }})"
                    @class(['relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200',
                        'bg-blue-500' => $add_allowNegative, 'bg-slate-300' => !$add_allowNegative])>
                    <span @class(['inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200',
                        'translate-x-6' => $add_allowNegative, 'translate-x-1' => !$add_allowNegative])></span>
                </button>
                <span class="text-sm font-semibold text-slate-700">Allow Negative Numbers</span>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Answer Method</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('add_answerMode', 'type')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_answerMode === 'type',
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_answerMode !== 'type'])>
                        ✏️ Type Answer
                    </button>
                    <button wire:click="$set('add_answerMode', 'multiple_choice')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-blue-500 bg-blue-600 text-white' => $add_answerMode === 'multiple_choice',
                            'border-slate-200 text-slate-600 hover:border-blue-300' => $add_answerMode !== 'multiple_choice'])>
                        ☑️ Multiple Choice
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ NUMBER BONDS ════════════════════════════════════════════════ --}}
        @elseif($selectedGame === 'number-bonds')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Total Max: <span class="text-purple-600">{{ $nb_totalMax }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 50, 100] as $m)
                    <button wire:click="$set('nb_totalMax', {{ $m }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_totalMax === $m,
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_totalMax !== $m])>
                        {{ $m }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-purple-600">{{ $nb_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('nb_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Missing Number Position</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['random' => 'Random', 'top' => 'Total (top)', 'parts' => 'Parts (sides)'] as $val => $lbl)
                    <button wire:click="$set('nb_missingPosition', '{{ $val }}')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_missingPosition === $val,
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_missingPosition !== $val])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Question</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ No Limit'],[6,'6s'],[10,'10s'],[15,'15s'],[30,'30s'],[60,'60s']] as [$t,$lbl])
                    <button wire:click="$set('nb_timePerQuestion', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_timePerQuestion === $t,
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_timePerQuestion !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Answer Method</label>
                <div class="flex flex-wrap gap-2">
                    <button wire:click="$set('nb_answerMode', 'type')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_answerMode === 'type',
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_answerMode !== 'type'])>
                        ✏️ Type Answer
                    </button>
                    <button wire:click="$set('nb_answerMode', 'multiple_choice')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-purple-500 bg-purple-600 text-white' => $nb_answerMode === 'multiple_choice',
                            'border-slate-200 text-slate-600 hover:border-purple-300' => $nb_answerMode !== 'multiple_choice'])>
                        ☑️ Multiple Choice
                    </button>
                </div>
            </div>
        </div>

        {{-- ═══ TIME TELLING ════════════════════════════════════════════════ --}}
        @elseif($selectedGame === 'time')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-orange-600">{{ $time_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('time_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-orange-500 bg-orange-600 text-white' => $time_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-orange-300' => $time_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Question</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ No Limit'],[10,'10s'],[15,'15s'],[30,'30s'],[60,'60s']] as [$t,$lbl])
                    <button wire:click="$set('time_timePerQuestion', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-orange-500 bg-orange-600 text-white' => $time_timePerQuestion === $t,
                            'border-slate-200 text-slate-600 hover:border-orange-300' => $time_timePerQuestion !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Precision Levels</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['hour' => 'Full Hours', 'half' => 'Half Hours', 'quarter' => 'Quarters', 'twenty' => '20 Minutes', 'ten' => '10 Minutes', 'five' => '5 Minutes', 'minute' => 'Every Minute'] as $val => $lbl)
                    <button wire:click="toggleTimePrecision('{{ $val }}')"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-semibold text-sm transition-all',
                            'border-orange-500 bg-orange-600 text-white' => in_array($val, $time_selectedPrecisions),
                            'border-slate-200 text-slate-600 hover:border-orange-300' => !in_array($val, $time_selectedPrecisions)])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Question Modes</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['digital_to_analog' => 'Digital → Analog', 'text_to_analog' => 'Text → Analog', 'analog_to_digital' => 'Analog → Digital', 'analog_to_text' => 'Analog → Text', 'voice_to_analog' => 'Voice → Analog'] as $val => $lbl)
                    <button wire:click="toggleTimeMode('{{ $val }}')"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-semibold text-sm transition-all',
                            'border-orange-500 bg-orange-600 text-white' => in_array($val, $time_selectedModes),
                            'border-slate-200 text-slate-600 hover:border-orange-300' => !in_array($val, $time_selectedModes)])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══ SPELLING ════════════════════════════════════════════════════ --}}
        @elseif($selectedGame === 'spelling')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Word List</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($wordListLabels as $slug => $label)
                    <button wire:click="$set('spell_wordListKey', '{{ $slug }}')"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-semibold text-sm transition-all',
                            'border-emerald-500 bg-emerald-600 text-white' => $spell_wordListKey === $slug,
                            'border-slate-200 text-slate-600 hover:border-emerald-300' => $spell_wordListKey !== $slug])>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-emerald-600">{{ $spell_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('spell_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-emerald-500 bg-emerald-600 text-white' => $spell_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-emerald-300' => $spell_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Display Time (seconds to show word)</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'Manual'],[2,'2s'],[4,'4s'],[6,'6s'],[10,'10s']] as [$t,$lbl])
                    <button wire:click="$set('spell_displayTime', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-emerald-500 bg-emerald-600 text-white' => $spell_displayTime === $t,
                            'border-slate-200 text-slate-600 hover:border-emerald-300' => $spell_displayTime !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time to Answer</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ Unlimited'],[10,'10s'],[20,'20s'],[30,'30s'],[60,'60s']] as [$t,$lbl])
                    <button wire:click="$set('spell_timePerAnswer', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-emerald-500 bg-emerald-600 text-white' => $spell_timePerAnswer === $t,
                            'border-slate-200 text-slate-600 hover:border-emerald-300' => $spell_timePerAnswer !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Hint Type</label>
                <div class="flex flex-wrap gap-2">
                    @foreach(['none' => 'No Hint', 'blanks' => 'Blank Lines', 'puzzle' => 'Puzzle Letters'] as $val => $lbl)
                    <button wire:click="$set('spell_hintType', '{{ $val }}')"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-emerald-500 bg-emerald-600 text-white' => $spell_hintType === $val,
                            'border-slate-200 text-slate-600 hover:border-emerald-300' => $spell_hintType !== $val])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div class="flex items-center gap-3">
                <button wire:click="$set('spell_examMode', {{ $spell_examMode ? 'false' : 'true' }})"
                    @class(['relative inline-flex h-7 w-12 items-center rounded-full transition-colors duration-200',
                        'bg-amber-500' => $spell_examMode, 'bg-slate-300' => !$spell_examMode])>
                    <span @class(['inline-block h-5 w-5 transform rounded-full bg-white shadow transition-transform duration-200',
                        'translate-x-6' => $spell_examMode, 'translate-x-1' => !$spell_examMode])></span>
                </button>
                <div>
                    <span class="text-sm font-bold text-slate-700">Exam Mode</span>
                    @if($spell_examMode)<span class="ml-2 px-2 py-0.5 bg-amber-100 text-amber-700 text-xs font-bold rounded-full">ON</span>@endif
                    <p class="text-xs text-slate-400">No feedback during the game — results revealed at the end</p>
                </div>
            </div>
        </div>

        {{-- ═══ ANAGRAM ═════════════════════════════════════════════════════ --}}
        @elseif($selectedGame === 'anagram')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Word List</label>
                <div class="flex flex-wrap gap-2">
                    @foreach($wordListLabels as $slug => $label)
                    <button wire:click="$set('ana_wordListKey', '{{ $slug }}')"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-semibold text-sm transition-all',
                            'border-cyan-500 bg-cyan-600 text-white' => $ana_wordListKey === $slug,
                            'border-slate-200 text-slate-600 hover:border-cyan-300' => $ana_wordListKey !== $slug])>
                        {{ $label }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-cyan-600">{{ $ana_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('ana_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-cyan-500 bg-cyan-600 text-white' => $ana_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-cyan-300' => $ana_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Time per Word</label>
                <div class="flex flex-wrap gap-2">
                    @foreach([[0,'∞ Unlimited'],[15,'15s'],[30,'30s'],[60,'60s'],[120,'2 min']] as [$t,$lbl])
                    <button wire:click="$set('ana_timePerWord', {{ $t }})"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-cyan-500 bg-cyan-600 text-white' => $ana_timePerWord === $t,
                            'border-slate-200 text-slate-600 hover:border-cyan-300' => $ana_timePerWord !== $t])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>

        {{-- ═══ WORD DEFINITIONS ════════════════════════════════════════════ --}}
        @elseif($selectedGame === 'word-definitions')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Word Set</label>
                @foreach($defSourceOptions as $groupLabel => $options)
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wide mt-3 mb-2">{{ $groupLabel }}</p>
                <div class="flex flex-wrap gap-2">
                    @foreach($options as $val => $lbl)
                    <button wire:click="$set('def_wordSet', '{{ $val }}')"
                        @class(['px-3 py-1.5 rounded-lg border-2 font-semibold text-sm transition-all',
                            'border-rose-500 bg-rose-600 text-white' => $def_wordSet === $val,
                            'border-slate-200 text-slate-600 hover:border-rose-300' => $def_wordSet !== $val])>
                        {{ $lbl }}
                    </button>
                    @endforeach
                </div>
                @endforeach
            </div>

            <div>
                <label class="block text-sm font-bold text-slate-700 mb-2">Number of Questions: <span class="text-rose-600">{{ $def_questionCount }}</span></label>
                <div class="flex flex-wrap gap-2">
                    @foreach([5, 10, 15, 20, 30, 50] as $n)
                    <button wire:click="$set('def_questionCount', {{ $n }})"
                        @class(['px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all',
                            'border-rose-500 bg-rose-600 text-white' => $def_questionCount === $n,
                            'border-slate-200 text-slate-600 hover:border-rose-300' => $def_questionCount !== $n])>
                        {{ $n }}
                    </button>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- ── Shareable Link & QR Code ────────────────────────────────────────── --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-6 space-y-6">

        <h2 class="font-extrabold text-slate-800 flex items-center gap-2">
            <span class="w-8 h-8 bg-emerald-500 rounded-lg flex items-center justify-center flex-shrink-0">
                <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                </svg>
            </span>
            Share with Pupils
        </h2>

        <div class="flex flex-col lg:flex-row gap-6">

            {{-- ── Left: URL + sharing buttons ─────────────────────────── --}}
            <div class="flex-1 space-y-4">

                {{-- URL row --}}
                <div>
                    <label class="block text-sm font-semibold text-slate-500 mb-1.5">Game link — pupils need no login</label>
                    <div class="flex items-stretch gap-2">
                        <input type="text" readonly id="shareable-url-input" value="{{ $shareableUrl }}"
                            class="flex-1 bg-slate-50 border-2 border-slate-200 rounded-xl px-4 py-2.5 font-mono text-sm text-slate-700 focus:outline-none focus:border-indigo-400 min-w-0">
                        <button onclick="copyGameUrl()"
                            class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold rounded-xl transition-colors flex items-center gap-2 whitespace-nowrap flex-shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"/>
                            </svg>
                            <span id="copy-label">Copy</span>
                        </button>
                        <a href="{{ $shareableUrl }}" target="_blank"
                            class="px-3 py-2.5 bg-slate-100 hover:bg-slate-200 text-slate-600 rounded-xl transition-colors flex items-center flex-shrink-0" title="Open link">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                            </svg>
                        </a>
                    </div>
                </div>

                {{-- Share via apps --}}
                <div>
                    <p class="text-sm font-semibold text-slate-500 mb-2">Send via</p>
                    <div class="flex flex-wrap gap-2" id="share-buttons">

                        {{-- Native Share (mobile / OS share sheet) --}}
                        <button onclick="nativeShare()"
                            id="btn-native-share"
                            class="hidden items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-slate-200 bg-slate-50 hover:bg-slate-100 text-slate-700 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/>
                            </svg>
                            Share…
                        </button>

                        {{-- WhatsApp --}}
                        <button onclick="shareVia('whatsapp')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-green-200 bg-green-50 hover:bg-green-100 text-green-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347z"/>
                                <path d="M12 0C5.373 0 0 5.373 0 12c0 2.127.558 4.126 1.533 5.859L.057 23.386a.75.75 0 00.918.918l5.527-1.476A11.952 11.952 0 0012 24c6.627 0 12-5.373 12-12S18.627 0 12 0zm0 21.75a9.732 9.732 0 01-4.964-1.36l-.355-.213-3.681.982.983-3.594-.232-.37A9.718 9.718 0 012.25 12C2.25 6.615 6.615 2.25 12 2.25S21.75 6.615 21.75 12 17.385 21.75 12 21.75z"/>
                            </svg>
                            WhatsApp
                        </button>

                        {{-- Email --}}
                        <button onclick="shareVia('email')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-blue-200 bg-blue-50 hover:bg-blue-100 text-blue-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            Email
                        </button>

                        {{-- Telegram --}}
                        <button onclick="shareVia('telegram')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-sky-200 bg-sky-50 hover:bg-sky-100 text-sky-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M11.944 0A12 12 0 0 0 0 12a12 12 0 0 0 12 12 12 12 0 0 0 12-12A12 12 0 0 0 12 0a12 12 0 0 0-.056 0zm4.962 7.224c.1-.002.321.023.465.14a.506.506 0 0 1 .171.325c.016.093.036.306.02.472-.18 1.898-.962 6.502-1.36 8.627-.168.9-.499 1.201-.82 1.23-.696.065-1.225-.46-1.9-.902-1.056-.693-1.653-1.124-2.678-1.8-1.185-.78-.417-1.21.258-1.91.177-.184 3.247-2.977 3.307-3.23.007-.032.014-.15-.056-.212s-.174-.041-.249-.024c-.106.024-1.793 1.14-5.061 3.345-.48.33-.913.49-1.302.48-.428-.008-1.252-.241-1.865-.44-.752-.245-1.349-.374-1.297-.789.027-.216.325-.437.893-.663 3.498-1.524 5.83-2.529 6.998-3.014 3.332-1.386 4.025-1.627 4.476-1.635z"/>
                            </svg>
                            Telegram
                        </button>

                        {{-- SMS --}}
                        <button onclick="shareVia('sms')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-violet-200 bg-violet-50 hover:bg-violet-100 text-violet-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            SMS / Text
                        </button>

                        {{-- Microsoft Teams --}}
                        <button onclick="shareVia('teams')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-indigo-200 bg-indigo-50 hover:bg-indigo-100 text-indigo-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.625 7.5h-5.25A.375.375 0 0015 7.875v8.25c0 .207.168.375.375.375h5.25A.375.375 0 0021 16.125v-8.25a.375.375 0 00-.375-.375zm-12.75 0H2.625A.375.375 0 002.25 7.875v8.25c0 .207.168.375.375.375h5.25A.375.375 0 008.25 16.125v-8.25A.375.375 0 007.875 7.5zM12 3a2.625 2.625 0 100 5.25A2.625 2.625 0 0012 3zm5.625 3a1.875 1.875 0 100 3.75 1.875 1.875 0 000-3.75zM5.25 6a1.875 1.875 0 100 3.75A1.875 1.875 0 005.25 6zm4.5 11.625H9v-6.75h1.5v6.75zm4.5 0h-1.5v-6.75H14.25v6.75z"/>
                            </svg>
                            Teams
                        </button>

                        {{-- Google Classroom --}}
                        <button onclick="shareVia('classroom')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-emerald-200 bg-emerald-50 hover:bg-emerald-100 text-emerald-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 3L1 9l11 6 9-4.91V17h2V9L12 3zM5 13.18v4L12 21l7-3.82v-4L12 17l-7-3.82z"/>
                            </svg>
                            Classroom
                        </button>

                        {{-- Facebook --}}
                        <button onclick="shareVia('facebook')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-blue-300 bg-blue-50 hover:bg-blue-100 text-blue-900 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M24 12.073C24 5.404 18.627 0 12 0S0 5.404 0 12.073C0 18.1 4.388 23.094 10.125 24v-8.437H7.078v-3.49h3.047V9.41c0-3.025 1.792-4.697 4.533-4.697 1.312 0 2.686.236 2.686.236v2.97h-1.513c-1.491 0-1.956.93-1.956 1.886v2.268h3.328l-.532 3.49h-2.796V24C19.612 23.094 24 18.1 24 12.073z"/>
                            </svg>
                            Facebook
                        </button>

                        {{-- LinkedIn --}}
                        <button onclick="shareVia('linkedin')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-sky-300 bg-sky-50 hover:bg-sky-100 text-sky-900 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433a2.062 2.062 0 01-2.063-2.065 2.064 2.064 0 112.063 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                            LinkedIn
                        </button>

                        {{-- Instagram (copy link — Instagram doesn't support direct URL share) --}}
                        <button onclick="shareVia('instagram')"
                            class="flex items-center gap-2 px-4 py-2.5 rounded-xl border-2 border-pink-200 bg-pink-50 hover:bg-pink-100 text-pink-800 font-semibold text-sm transition-all">
                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"/>
                            </svg>
                            Instagram
                        </button>

                    </div>
                </div>

            </div>

            {{-- ── Right: QR Code ───────────────────────────────────────── --}}
            <div class="flex flex-col items-center gap-3 flex-shrink-0">
                <label class="text-sm font-semibold text-slate-500">Scan to play</label>
                <div id="qr-container"
                    wire:ignore
                    class="bg-white border-2 border-slate-200 rounded-2xl p-3 shadow-sm flex items-center justify-center"
                    style="width: 182px; height: 182px;">
                </div>
                <button onclick="downloadGameQr()" class="text-xs text-indigo-600 hover:text-indigo-800 font-semibold underline">
                    ⬇ Download QR PNG
                </button>
                <button onclick="printQrCards()"
                    class="flex items-center gap-2 px-4 py-2.5 bg-slate-800 hover:bg-slate-900 text-white font-bold rounded-xl transition-colors text-sm w-full justify-center">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Print Cards (×6)
                </button>
            </div>
        </div>
    </div>

    {{-- Hidden span: Livewire morphs these attributes on every update --}}
    <span id="livewire-url"
        data-url="{{ $shareableUrl }}"
        data-label="{{ $gameLabel }}"
        data-description="{{ $gameDescription }}"
        class="hidden"></span>

</div>

{{-- QR library + helpers — outside the Livewire root so Livewire never touches them --}}
@once
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"
    integrity="sha512-CNgIRecGo7nphbeZ04Sc13ka07paqdeTu0WR1IM4kNcpmBAUSHSQX0FslNhTDadL4O5SAGapGt4FodqL8My0mA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<script>
    function renderGameQr() {
        const container = document.getElementById('qr-container');
        const urlEl     = document.getElementById('livewire-url');
        if (!container || !urlEl || typeof QRCode === 'undefined') return;
        const url = urlEl.dataset.url || window.location.href;
        container.innerHTML = '';
        new QRCode(container, {
            text: url,
            width: 156,
            height: 156,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.M,
        });
        // Also sync the readonly input
        const input = document.getElementById('shareable-url-input');
        if (input) input.value = url;
    }

    function getGameUrl() {
        return document.getElementById('livewire-url')?.dataset.url ?? window.location.href;
    }

    function getGameTitle() {
        const game = document.querySelector('[id^="shareable-url-input"]')?.closest('.space-y-6')
            ?.querySelector('button.border-green-500, button.border-blue-500, button.border-purple-500, button.border-orange-500, button.border-emerald-500, button.border-cyan-500, button.border-rose-500');
        return 'Play a learning game: ';
    }

    function copyGameUrl() {
        const url   = getGameUrl();
        const label = document.getElementById('copy-label');
        navigator.clipboard.writeText(url).catch(() => {
            const input = document.getElementById('shareable-url-input');
            if (input) { input.select(); document.execCommand('copy'); }
        }).finally(() => {
            if (label) { label.textContent = '✓ Copied!'; setTimeout(() => label.textContent = 'Copy', 2500); }
        });
    }

    function shareVia(platform) {
        const url  = encodeURIComponent(getGameUrl());
        const text = encodeURIComponent('Play this learning game — no login needed! ');
        const links = {
            whatsapp:  `https://api.whatsapp.com/send?text=${text}${url}`,
            email:     `mailto:?subject=${encodeURIComponent('Learning game link')}&body=${text}${url}`,
            telegram:  `https://t.me/share/url?url=${url}&text=${text}`,
            sms:       `sms:?body=${text}${url}`,
            teams:     `https://teams.microsoft.com/share?href=${url}&msgText=${text}`,
            classroom: `https://classroom.google.com/share?url=${url}`,
            facebook:  `https://www.facebook.com/sharer/sharer.php?u=${url}`,
            linkedin:  `https://www.linkedin.com/sharing/share-offsite/?url=${url}`,
        };

        // Instagram has no web share API — copy to clipboard and guide the user
        if (platform === 'instagram') {
            navigator.clipboard.writeText(decodeURIComponent(getGameUrl())).catch(() => {});
            const btn = document.querySelector('button[onclick="shareVia(\'instagram\')"]');
            const orig = btn ? btn.innerHTML : '';
            if (btn) {
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Link copied — paste in Instagram';
                setTimeout(() => { btn.innerHTML = orig; }, 3000);
            }
            return;
        }

        const href = links[platform];
        if (href) window.open(href, '_blank', 'noopener,noreferrer');
    }

    function nativeShare() {
        if (!navigator.share) return;
        navigator.share({
            title: 'Learning game',
            text:  'Play this learning game — no login needed!',
            url:   getGameUrl(),
        }).catch(() => {});
    }

    function downloadGameQr() {
        const canvas = document.querySelector('#qr-container canvas');
        if (!canvas) return;
        const link = document.createElement('a');
        link.download = 'game-qr.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }

    // ── Print 6 QR cards on A4 ────────────────────────────────────────────────
    function printQrCards() {
        const urlEl = document.getElementById('livewire-url');
        const url   = urlEl?.dataset.url   ?? getGameUrl();
        const label = urlEl?.dataset.label ?? 'Learning Game';
        const desc  = urlEl?.dataset.description ?? '';

        // Colour accent per game keyword
        const accent = label.includes('Multiplication') ? '#16a34a'
            : label.includes('Addition')               ? '#2563eb'
            : label.includes('Number')                 ? '#9333ea'
            : label.includes('Time')                   ? '#ea580c'
            : label.includes('Spelling')               ? '#059669'
            : label.includes('Anagram')                ? '#0891b2'
            : label.includes('Word')                   ? '#e11d48'
            : '#4f46e5';

        // We generate QR images for all 6 cards using a temp off-screen container
        const tmpDiv = document.createElement('div');
        tmpDiv.style.cssText = 'position:absolute;left:-9999px;top:-9999px;';
        document.body.appendChild(tmpDiv);

        new QRCode(tmpDiv, {
            text: url,
            width: 260,
            height: 260,
            colorDark: '#1e293b',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H,
        });

        // Wait a tick for QRCode to render the canvas
        setTimeout(() => {
            const canvas = tmpDiv.querySelector('canvas');
            const qrDataUrl = canvas ? canvas.toDataURL('image/png') : '';
            document.body.removeChild(tmpDiv);

            const cardHtml = buildCard(label, desc, qrDataUrl, url, accent);
            const sixCards = Array(6).fill(cardHtml).join('');

            const win = window.open('', '_blank', 'width=900,height=700');
            win.document.write(`<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>QR Cards – ${label}</title>
<style>
  * { margin: 0; padding: 0; box-sizing: border-box; }
  @page { size: A4 portrait; margin: 12mm; }
  body {
    font-family: 'Segoe UI', Arial, sans-serif;
    background: #fff;
    -webkit-print-color-adjust: exact;
    print-color-adjust: exact;
  }
  .grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    grid-template-rows: repeat(3, 1fr);
    gap: 8mm;
    width: 100%;
    height: 100%;
  }
  .card {
    border: 2.5px dashed #cbd5e1;
    border-radius: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    overflow: hidden;
    page-break-inside: avoid;
  }
  .card-header {
    width: 100%;
    background: ${accent};
    color: #fff;
    padding: 7px 10px 6px;
    text-align: center;
  }
  .card-header .logo {
    font-size: 9px;
    font-weight: 600;
    letter-spacing: .04em;
    opacity: .85;
    margin-bottom: 1px;
  }
  .card-header h2 {
    font-size: 14px;
    font-weight: 800;
    line-height: 1.2;
    letter-spacing: -.01em;
  }
  .card-body {
    flex: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 8px 4px 4px;
  }
  .card-body img {
    width: 110px;
    height: 110px;
    display: block;
  }
  .card-footer {
    width: 100%;
    padding: 4px 10px 8px;
    text-align: center;
  }
  .card-footer .desc {
    font-size: 8.5px;
    color: #475569;
    font-weight: 600;
    margin-bottom: 4px;
    line-height: 1.4;
  }
  .card-footer .url {
    font-size: 7px;
    color: #94a3b8;
    word-break: break-all;
    font-family: monospace;
    margin-bottom: 4px;
  }
  .card-footer .cta {
    display: inline-block;
    background: ${accent};
    color: #fff;
    font-size: 8px;
    font-weight: 700;
    border-radius: 20px;
    padding: 3px 10px;
    letter-spacing: .03em;
  }
  .scissors {
    text-align: center;
    font-size: 10px;
    color: #94a3b8;
    margin-bottom: 6mm;
    letter-spacing: .05em;
  }
  @media print {
    .no-print { display: none !important; }
    body { background: white; }
  }
</style>
</head>
<body>
  <p class="scissors no-print" style="padding:8px 0 10px;font-family:sans-serif;">
    ✂ &nbsp; Cut along the dashed lines and stick into pupils' notebooks
  </p>
  <div class="grid">${sixCards}</div>
  <p class="no-print" style="text-align:center;margin-top:14px;font-family:sans-serif;font-size:13px;color:#64748b;">
    <button onclick="window.print()" style="background:#1e293b;color:#fff;border:none;padding:10px 28px;border-radius:8px;font-size:14px;font-weight:700;cursor:pointer;">🖨 Print</button>
  </p>
  <script>
    // Auto-open print dialog after a short delay so images load
    setTimeout(() => window.print(), 600);
  <\/script>
</body>
</html>`);
            win.document.close();
        }, 120);
    }

    function buildCard(label, desc, qrDataUrl, url, accent) {
        const shortUrl = url.length > 65 ? url.substring(0, 62) + '…' : url;
        const imgTag = qrDataUrl
            ? `<img src="${qrDataUrl}" alt="QR code">`
            : `<div style="width:110px;height:110px;background:#f1f5f9;border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:10px;color:#94a3b8;">QR unavailable</div>`;
        return `
        <div class="card">
          <div class="card-header">
            <div class="logo">Simple Learning</div>
            <h2>${label}</h2>
          </div>
          <div class="card-body">${imgTag}</div>
          <div class="card-footer">
            <div class="desc">${desc}</div>
            <div class="url">${shortUrl}</div>
            <span class="cta">Scan to play — no login needed</span>
          </div>
        </div>`;
    }

    // Show native share button on devices/browsers that support it
    function initShareButtons() {
        if (navigator.share) {
            const btn = document.getElementById('btn-native-share');
            if (btn) btn.classList.replace('hidden', 'flex');
        }
    }

    // Initial render once QR lib is loaded
    document.addEventListener('DOMContentLoaded', () => {
        initShareButtons();
        if (typeof QRCode !== 'undefined') {
            renderGameQr();
        } else {
            const t = setInterval(() => {
                if (typeof QRCode !== 'undefined') { clearInterval(t); renderGameQr(); }
            }, 50);
        }
    });

    // Re-render and sync URL input after every Livewire update (settings change)
    document.addEventListener('livewire:updated', renderGameQr);
</script>
@endonce
