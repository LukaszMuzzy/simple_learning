<div
    x-on:scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="min-h-screen bg-gradient-to-br from-sky-50 via-cyan-50 to-blue-50 py-6 px-4">
    <div class="max-w-4xl mx-auto">
        @once
        <script>
            window.timeClock = function (config) {
                return {
                    interactive: !!config.interactive,
                    hour: Number(config.hour || 12),
                    minute: Number(config.minute || 0),
                    step: Number(config.step || 1),
                    dragging: null,
                    setWire: config.setWire || null,

                    get minuteAngle() {
                        return this.minute * 6;
                    },

                    get hourAngle() {
                        return ((this.hour % 12) * 30) + (this.minute * 0.5);
                    },

                    sync() {
                        if (typeof this.setWire === 'function') {
                            this.setWire(this.hour, this.minute);
                        }
                    },

                    setTime(h, m) {
                        let hour = ((Number(h) % 12) + 12) % 12;
                        if (hour === 0) hour = 12;
                        let min = ((Number(m) % 60) + 60) % 60;
                        this.hour = hour;
                        this.minute = min;
                        this.sync();
                    },

                    adjust(type, delta) {
                        if (type === 'minute') {
                            const total = (this.hour % 12) * 60 + this.minute + delta;
                            const wrapped = ((total % 720) + 720) % 720;
                            const h = Math.floor(wrapped / 60);
                            const m = wrapped % 60;
                            this.setTime(h === 0 ? 12 : h, m);
                            return;
                        }
                        const next = this.hour + delta;
                        this.setTime(next, this.minute);
                    },

                    pointerToMinute(event) {
                        const svg = this.$refs.face;
                        if (!svg) return this.minute;
                        const rect = svg.getBoundingClientRect();
                        const cx = rect.left + rect.width / 2;
                        const cy = rect.top + rect.height / 2;
                        const x = event.clientX - cx;
                        const y = event.clientY - cy;
                        const angle = Math.atan2(y, x) * 180 / Math.PI;
                        const degrees = (angle + 90 + 360) % 360;
                        return Math.round(degrees / 6) % 60;
                    },

                    startDrag(event, hand) {
                        if (!this.interactive) return;
                        this.dragging = hand;
                        event.preventDefault();
                    },

                    move(event) {
                        if (!this.interactive || !this.dragging) return;
                        const rawMinute = this.pointerToMinute(event);
                        if (this.dragging === 'minute') {
                            const prev = this.minute;
                            this.minute = rawMinute;
                            if (prev > 45 && rawMinute < 15) this.adjust('hour', 1);
                            if (prev < 15 && rawMinute > 45) this.adjust('hour', -1);
                        } else {
                            const hour = Math.round(rawMinute / 5) || 12;
                            this.hour = hour;
                        }
                        this.sync();
                    },

                    endDrag() {
                        this.dragging = null;
                    }
                };
            };
        </script>
        @endonce

        @if($phase === 'setup')
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-sky-500 to-blue-600 px-8 py-7 text-white">
                <h1 class="text-3xl font-extrabold">🕐 Time Telling Game</h1>
                <p class="text-sky-100 mt-1">Build confidence with analog, digital, written, and spoken time</p>
            </div>

            <div class="p-6 sm:p-8 space-y-7">
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Number of Questions: <span class="text-blue-600">{{ $questionCount }}</span></label>
                    <div class="flex flex-wrap gap-2 mb-3">
                        @foreach([5, 10, 20] as $n)
                        <button wire:click="setQuestionCount({{ $n }})"
                            @if($questionCount === $n)
                                class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all border-blue-500 bg-blue-600 text-white"
                            @else
                                class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all border-slate-200 text-slate-600 hover:border-blue-300"
                            @endif>
                            {{ $n }}
                        </button>
                        @endforeach
                    </div>
                    <input type="number" wire:model.live="customQuestionCount" wire:change="applyCustomQuestionCount"
                        min="1" max="100"
                        class="w-24 text-center border-2 border-slate-200 rounded-lg py-2 font-bold text-slate-700 focus:border-blue-400 focus:outline-none focus:ring-2 focus:ring-blue-100"
                        placeholder="Custom">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">Time per Question</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach([[0, 'No Limit'], [10, '10s'], [20, '20s'], [30, '30s'], [45, '45s']] as [$t, $label])
                        <button wire:click="$set('timePerQuestion', {{ $t }})"
                            @if($timePerQuestion === $t)
                                class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all border-blue-500 bg-blue-600 text-white"
                            @else
                                class="px-4 py-2 rounded-lg border-2 font-bold text-sm transition-all border-slate-200 text-slate-600 hover:border-blue-300"
                            @endif>
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">
                        Time Precision
                        <span class="font-normal text-slate-400 ml-1 text-xs">select one or more</span>
                    </label>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        @foreach(['hour', 'half', 'quarter', 'twenty', 'ten', 'five', 'minute'] as $precision)
                        <button wire:click="togglePrecision('{{ $precision }}')"
                            @if(in_array($precision, $selectedPrecisions, true))
                                class="px-3 py-2 rounded-xl border-2 text-sm font-bold transition-all border-cyan-600 bg-cyan-600 text-white shadow-sm"
                            @else
                                class="px-3 py-2 rounded-xl border-2 text-sm font-bold transition-all border-slate-200 bg-white text-slate-600 hover:border-cyan-400 hover:bg-cyan-50"
                            @endif>
                            @if(in_array($precision, $selectedPrecisions, true))
                                ✓ {{ $this->precisionLabel($precision) }}
                            @else
                                {{ $this->precisionLabel($precision) }}
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-3">
                        Question Modes
                        <span class="font-normal text-slate-400 ml-1 text-xs">select one or more</span>
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        @foreach([
                            'digital_to_analog' => ['Digital → Analog', '🔢'],
                            'text_to_analog' => ['Text → Analog', '📝'],
                            'analog_to_digital' => ['Analog → Digital', '🕐'],
                            'analog_to_text' => ['Analog → Text', '💬'],
                            'voice_to_analog' => ['Voice → Analog', '🔊'],
                        ] as $key => [$modeLabel, $modeIcon])
                        <button wire:click="toggleMode('{{ $key }}')"
                            @if(in_array($key, $selectedModes, true))
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl border-2 font-bold transition-all border-sky-600 bg-sky-600 text-white shadow-sm"
                            @else
                                class="flex items-center space-x-3 px-4 py-3 rounded-xl border-2 font-bold transition-all border-slate-200 bg-white text-slate-600 hover:border-sky-300 hover:bg-sky-50"
                            @endif>
                            <span class="text-lg">{{ $modeIcon }}</span>
                            <span>{{ $modeLabel }}</span>
                            @if(in_array($key, $selectedModes, true))
                                <span class="ml-auto text-sm">✓</span>
                            @endif
                        </button>
                        @endforeach
                    </div>
                </div>

                @guest
                <div class="flex items-start space-x-3 p-4 bg-amber-50 border border-amber-200 rounded-xl">
                    <p class="text-sm text-amber-700">
                        <a href="{{ route('login') }}" class="font-bold underline">Log in</a> to save progress and review improvement over time.
                    </p>
                </div>
                @endguest

                <button wire:click="startGame"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-extrabold rounded-xl transition-all shadow-md">
                    Start Time Telling
                </button>
            </div>
        </div>
        @endif

        @if($phase === 'playing')
        <div class="space-y-5" @if($timePerQuestion > 0) wire:poll.1000ms="tick" @endif wire:key="time-playing-{{ $currentIndex }}">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-5">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-semibold text-slate-600">Question {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                    <span class="text-sm font-semibold text-blue-700">{{ $this->modeLabel($currentQuestion['mode']) }}</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3">
                    <div class="bg-blue-600 h-3 rounded-full transition-all duration-500"
                        style="width: {{ ($currentIndex / $questionCount) * 100 }}%"></div>
                </div>
            </div>

            @if($timePerQuestion > 0)
            @php
                $pctLeft = $timePerQuestion > 0 ? ($timeLeft / $timePerQuestion) * 100 : 100;
            @endphp
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 px-5 py-4">
                <div class="flex items-center justify-between mb-2">
                    <span class="text-sm font-semibold text-slate-500">Question time left</span>
                    <span class="text-2xl font-black {{ $timeLeft <= 5 ? 'text-red-600' : 'text-blue-700' }}">{{ $timeLeft }}s</span>
                </div>
                <div class="w-full bg-slate-100 rounded-full h-3 overflow-hidden">
                    <div class="{{ $timeLeft <= 5 ? 'bg-red-500' : 'bg-blue-500' }} h-3 rounded-full transition-all duration-900"
                        style="width: {{ $pctLeft }}%"></div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-3xl shadow-xl border border-slate-100 p-6 sm:p-8">
                @if($currentQuestion['mode'] === 'digital_to_analog')
                <h2 class="text-xl font-extrabold text-slate-800 mb-2">Set the clock to <span class="text-blue-600">{{ $currentQuestion['digital'] }}</span></h2>
                <p class="text-slate-500 mb-5">Drag the hands or use buttons to set the exact time.</p>
                @endif

                @if($currentQuestion['mode'] === 'text_to_analog')
                <h2 class="text-xl font-extrabold text-slate-800 mb-2">Set the clock to: <span class="text-blue-600">{{ $currentQuestion['words'] }}</span></h2>
                <p class="text-slate-500 mb-5">Use the written clue and set the analog clock.</p>
                @endif

                @if($currentQuestion['mode'] === 'voice_to_analog')
                <div
                    x-data="{
                        phrase: '{{ addslashes($currentQuestion['words']) }}',
                        speakAt(rate) {
                            if (!window.speechSynthesis) return;
                            speechSynthesis.cancel();
                            const say = () => {
                                const u = new SpeechSynthesisUtterance(this.phrase);
                                u.lang = 'en-GB';
                                u.rate = rate;
                                speechSynthesis.speak(u);
                            };
                            const voices = speechSynthesis.getVoices();
                            if (voices.length > 0) {
                                say();
                            } else {
                                speechSynthesis.addEventListener('voiceschanged', say, { once: true });
                                setTimeout(say, 300);
                            }
                        }
                    }"
                    x-init="$nextTick(() => setTimeout(() => speakAt(0.85), 400))">
                    <h2 class="text-xl font-extrabold text-slate-800 mb-2">Listen and set the clock</h2>
                    <p class="text-slate-500 mb-4">The time has been spoken in British English.</p>
                    <div class="flex flex-wrap gap-3 mb-5">
                        <button
                            x-on:click="speakAt(0.85)"
                            class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-violet-600 text-white font-bold hover:bg-violet-700 transition-colors shadow-sm">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                            </svg>
                            <span>🔊 Hear again</span>
                        </button>
                        <button
                            x-on:click="speakAt(0.45)"
                            class="inline-flex items-center space-x-2 px-5 py-2.5 rounded-xl bg-violet-100 text-violet-700 font-bold hover:bg-violet-200 transition-colors shadow-sm border border-violet-300">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/>
                            </svg>
                            <span>🐢 Hear slowly</span>
                        </button>
                    </div>
                </div>
                @endif

                @if(in_array($currentQuestion['mode'], ['digital_to_analog', 'text_to_analog', 'voice_to_analog'], true))
                @php
                    $stepMinutes = 1;
                    if (!in_array('minute', $selectedPrecisions, true)) {
                        if (in_array('five', $selectedPrecisions, true))   $stepMinutes = 5;
                        if (in_array('ten', $selectedPrecisions, true))    $stepMinutes = max($stepMinutes, 10);
                        if (in_array('twenty', $selectedPrecisions, true)) $stepMinutes = max($stepMinutes, 20);
                        if (!in_array('five', $selectedPrecisions, true) && !in_array('ten', $selectedPrecisions, true) && !in_array('twenty', $selectedPrecisions, true)) {
                            // only hour/half/quarter selected — snap to 15
                            if (in_array('quarter', $selectedPrecisions, true)) $stepMinutes = 15;
                            elseif (in_array('half', $selectedPrecisions, true)) $stepMinutes = 30;
                            elseif (in_array('hour', $selectedPrecisions, true)) $stepMinutes = 60;
                        }
                    }
                @endphp
                <div
                    x-data="timeClock({ interactive: true, hour: 12, minute: 0, step: {{ $stepMinutes }}, setWire: (h, m) => $wire.setClockAnswer(h, m) })"
                    @pointermove.window="move($event)"
                    @pointerup.window="endDrag()"
                    @pointercancel.window="endDrag()"
                    class="space-y-4">
                    <div class="flex justify-center">
                        <svg x-ref="face" viewBox="0 0 400 400" class="w-72 h-72 sm:w-[27rem] sm:h-[27rem] bg-sky-50 rounded-full border-4 border-sky-200 shadow-inner">
                            <circle cx="200" cy="200" r="170" fill="white" stroke="#93c5fd" stroke-width="6"></circle>
                            @for($i = 1; $i <= 12; $i++)
                                @php $angle = deg2rad(($i * 30) - 90); $x = 200 + cos($angle) * 130; $y = 200 + sin($angle) * 130; @endphp
                                <text x="{{ $x }}" y="{{ $y }}" text-anchor="middle" dominant-baseline="middle" class="fill-slate-700 font-bold text-xl">{{ $i }}</text>
                            @endfor
                            <line x1="200" y1="200" x2="200" y2="100" stroke="#0ea5e9" stroke-width="8" stroke-linecap="round"
                                :transform="`rotate(${hourAngle}, 200, 200)`" @pointerdown="startDrag($event, 'hour')" class="cursor-pointer"></line>
                            <line x1="200" y1="200" x2="200" y2="60" stroke="#1e293b" stroke-width="5" stroke-linecap="round"
                                :transform="`rotate(${minuteAngle}, 200, 200)`" @pointerdown="startDrag($event, 'minute')" class="cursor-pointer"></line>
                            <circle cx="200" cy="200" r="10" fill="#1e293b"></circle>
                        </svg>
                    </div>
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2">
                        <button x-on:click="adjust('minute', -step)"
                            class="py-2 rounded-lg border border-slate-200 font-bold text-slate-700 hover:bg-slate-50"
                            x-text="`− ${step} min`"></button>
                        <button x-on:click="adjust('minute', step)"
                            class="py-2 rounded-lg border border-slate-200 font-bold text-slate-700 hover:bg-slate-50"
                            x-text="`+ ${step} min`"></button>
                        <button x-on:click="adjust('hour', -1)"
                            class="py-2 rounded-lg border border-slate-200 font-bold text-slate-700 hover:bg-slate-50">− 1 hr</button>
                        <button x-on:click="adjust('hour', 1)"
                            class="py-2 rounded-lg border border-slate-200 font-bold text-slate-700 hover:bg-slate-50">+ 1 hr</button>
                    </div>
                    <div class="flex justify-center">
                        <button x-on:click="setTime(12, 0)"
                            class="px-4 py-2 rounded-lg border border-slate-200 font-bold text-slate-500 hover:bg-slate-50 hover:text-slate-700 text-sm">
                            ↺ Reset to 12:00
                        </button>
                    </div>
                </div>
                @endif

                @if($currentQuestion['mode'] === 'analog_to_digital')
                <h2 class="text-xl font-extrabold text-slate-800 mb-2">Read the clock — what time does it show?</h2>
                <p class="text-slate-500 mb-5">Enter the hours and minutes separately.</p>
                <div class="flex justify-center mb-6">
                    <div x-data="timeClock({ interactive: false, hour: {{ $currentQuestion['hour'] }}, minute: {{ $currentQuestion['minute'] }} })">
                        <svg viewBox="0 0 400 400" class="w-72 h-72 sm:w-[27rem] sm:h-[27rem] bg-sky-50 rounded-full border-4 border-sky-200 shadow-inner">
                            <circle cx="200" cy="200" r="170" fill="white" stroke="#93c5fd" stroke-width="6"></circle>
                            @for($i = 1; $i <= 12; $i++)
                                @php $angle = deg2rad(($i * 30) - 90); $x = 200 + cos($angle) * 130; $y = 200 + sin($angle) * 130; @endphp
                                <text x="{{ $x }}" y="{{ $y }}" text-anchor="middle" dominant-baseline="middle" class="fill-slate-700 font-bold text-xl">{{ $i }}</text>
                            @endfor
                            <line x1="200" y1="200" x2="200" y2="100" stroke="#0ea5e9" stroke-width="8" stroke-linecap="round"
                                :transform="`rotate(${hourAngle}, 200, 200)`"></line>
                            <line x1="200" y1="200" x2="200" y2="60" stroke="#1e293b" stroke-width="5" stroke-linecap="round"
                                :transform="`rotate(${minuteAngle}, 200, 200)`"></line>
                            <circle cx="200" cy="200" r="10" fill="#1e293b"></circle>
                        </svg>
                    </div>
                </div>
                <div
                    x-data
                    class="flex items-center justify-center gap-3"
                    wire:key="digital-input-{{ $currentIndex }}">
                    <div class="flex flex-col items-center gap-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Hours</label>
                        <input
                            type="number"
                            wire:model.live="digitalHours"
                            min="1" max="12"
                            placeholder="H"
                            x-init="$nextTick(() => $el.focus())"
                            x-on:keydown.enter="$el.closest('[x-data]').querySelector('[data-minutes]').focus()"
                            class="w-24 text-center text-4xl font-extrabold rounded-xl border-2 border-slate-200 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    </div>
                    <span class="text-5xl font-black text-slate-400 pb-1">:</span>
                    <div class="flex flex-col items-center gap-1">
                        <label class="text-xs font-bold text-slate-500 uppercase tracking-wide">Minutes</label>
                        <input
                            type="number"
                            wire:model.live="digitalMinutes"
                            min="0" max="59"
                            placeholder="MM"
                            data-minutes
                            x-on:keydown.enter="$wire.submitAnswer()"
                            class="w-24 text-center text-4xl font-extrabold rounded-xl border-2 border-slate-200 py-3 focus:border-blue-500 focus:ring-2 focus:ring-blue-100 focus:outline-none [appearance:textfield] [&::-webkit-outer-spin-button]:appearance-none [&::-webkit-inner-spin-button]:appearance-none">
                    </div>
                </div>
                @endif

                @if($currentQuestion['mode'] === 'analog_to_text')
                <h2 class="text-xl font-extrabold text-slate-800 mb-2">Read the analog clock and pick the correct phrase</h2>
                <div class="flex justify-center mb-5">
                    <div x-data="timeClock({ interactive: false, hour: {{ $currentQuestion['hour'] }}, minute: {{ $currentQuestion['minute'] }} })">
                        <svg viewBox="0 0 400 400" class="w-72 h-72 sm:w-[27rem] sm:h-[27rem] bg-sky-50 rounded-full border-4 border-sky-200 shadow-inner">
                            <circle cx="200" cy="200" r="170" fill="white" stroke="#93c5fd" stroke-width="6"></circle>
                            @for($i = 1; $i <= 12; $i++)
                                @php $angle = deg2rad(($i * 30) - 90); $x = 200 + cos($angle) * 130; $y = 200 + sin($angle) * 130; @endphp
                                <text x="{{ $x }}" y="{{ $y }}" text-anchor="middle" dominant-baseline="middle" class="fill-slate-700 font-bold text-xl">{{ $i }}</text>
                            @endfor
                            <line x1="200" y1="200" x2="200" y2="100" stroke="#0ea5e9" stroke-width="8" stroke-linecap="round"
                                :transform="`rotate(${hourAngle}, 200, 200)`"></line>
                            <line x1="200" y1="200" x2="200" y2="60" stroke="#1e293b" stroke-width="5" stroke-linecap="round"
                                :transform="`rotate(${minuteAngle}, 200, 200)`"></line>
                            <circle cx="200" cy="200" r="10" fill="#1e293b"></circle>
                        </svg>
                    </div>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                    @foreach($currentQuestion['text_options'] as $option)
                    <button wire:click="submitAnswer('{{ addslashes($option) }}')"
                        class="p-3 rounded-xl border-2 border-slate-200 hover:border-blue-300 hover:bg-blue-50 text-slate-700 font-bold text-left">
                        {{ $option }}
                    </button>
                    @endforeach
                </div>
                @endif

                @if($currentQuestion['mode'] !== 'analog_to_text')
                <button wire:click="submitAnswer"
                    class="w-full mt-6 py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-extrabold rounded-xl transition-all">
                    Check Answer
                </button>
                @endif
            </div>
        </div>
        @endif

        @if($phase === 'feedback')
        @php
            $feedbackEmojis = $lastCorrect
                ? ['🎉','⭐','🌟','✅','🥳','👏','🎯','💫']
                : ['😬','🤔','💪','😅','🙈','😮'];
            $feedbackEmoji = $feedbackEmojis[($currentIndex) % count($feedbackEmojis)];
            $feedbackMsg = $lastCorrect
                ? ['Brilliant!', 'Well done!', 'Spot on!', 'Fantastic!', 'You got it!', 'Nailed it!']
                : ['Not quite!', 'Keep trying!', 'Nearly there!', 'Have another look!', 'So close!'];
            $feedbackTitle = $feedbackMsg[($currentIndex) % count($feedbackMsg)];
        @endphp
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="px-8 py-8 text-white text-center {{ $lastCorrect ? 'bg-gradient-to-br from-emerald-400 to-green-600' : 'bg-gradient-to-br from-rose-400 to-red-600' }}">
                <div class="text-6xl mb-3">{{ $feedbackEmoji }}</div>
                <h2 class="text-3xl font-extrabold">{{ $feedbackTitle }}</h2>
                <div class="mt-4 inline-block bg-white/20 rounded-2xl px-6 py-3">
                    <p class="text-lg font-extrabold">{{ $currentQuestion['digital'] }}</p>
                    <p class="text-sm {{ $lastCorrect ? 'text-emerald-100' : 'text-rose-100' }}">{{ $currentQuestion['words'] }}</p>
                </div>
                @if(!$lastCorrect)
                <p class="mt-3 text-sm {{ $lastCorrect ? 'text-emerald-100' : 'text-rose-100' }}">
                    Your answer: <span class="font-bold line-through opacity-70">{{ $results[count($results) - 1]['user_answer'] }}</span>
                </p>
                @endif
            </div>
            <div class="p-6 space-y-4">
                {{-- Hear the correct time spoken --}}
                <div
                    x-data="{
                        phrase: '{{ addslashes($currentQuestion['words']) }}',
                        speakAt(rate) {
                            if (!window.speechSynthesis) return;
                            speechSynthesis.cancel();
                            const say = () => {
                                const u = new SpeechSynthesisUtterance(this.phrase);
                                u.lang = 'en-GB';
                                u.rate = rate;
                                speechSynthesis.speak(u);
                            };
                            const voices = speechSynthesis.getVoices();
                            if (voices.length > 0) { say(); } else {
                                speechSynthesis.addEventListener('voiceschanged', say, { once: true });
                                setTimeout(say, 300);
                            }
                        }
                    }"
                    class="flex flex-wrap gap-3">
                    <button
                        x-on:click="speakAt(0.85)"
                        class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                        </svg>
                        <span>🔊 Hear answer</span>
                    </button>
                    <button
                        x-on:click="speakAt(0.45)"
                        class="inline-flex items-center space-x-2 px-4 py-2 rounded-xl bg-slate-100 text-slate-700 font-bold hover:bg-slate-200 transition-colors border border-slate-200">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/>
                        </svg>
                        <span>🐢 Hear slowly</span>
                    </button>
                </div>
                <button wire:click="proceedToNext"
                    class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white text-lg font-extrabold rounded-xl transition-all">
                    {{ $currentIndex + 1 >= $questionCount ? 'See Results' : 'Next Question' }}
                </button>
            </div>
        </div>
        @endif

        @if($phase === 'summary')
        @php
            $pct = $questionCount > 0 ? round(($correctCount / $questionCount) * 100) : 0;
            [$summaryEmoji, $summaryTitle, $summaryMsg, $summaryGradient] = match(true) {
                $pct === 100 => ['🏆', 'Perfect score!',      'Amazing — you got every single one!',        'from-yellow-400 to-amber-500'],
                $pct >= 80   => ['🌟', 'Brilliant work!',     'You really know your clocks!',               'from-emerald-400 to-teal-500'],
                $pct >= 60   => ['😊', 'Good effort!',        'You\'re getting there — keep practising!',   'from-blue-400 to-cyan-500'],
                $pct >= 40   => ['💪', 'Keep going!',         'A bit more practice and you\'ll nail it!',   'from-orange-400 to-amber-500'],
                default      => ['🤗', 'Don\'t give up!',     'Clocks are tricky — try again!',             'from-slate-500 to-slate-600'],
            };
        @endphp
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br {{ $summaryGradient }} px-8 py-10 text-white text-center">
                <div class="text-7xl mb-4">{{ $summaryEmoji }}</div>
                <h2 class="text-3xl font-extrabold mb-1">{{ $summaryTitle }}</h2>
                <p class="text-white/80 mb-5">{{ $summaryMsg }}</p>
                <div class="grid grid-cols-3 gap-4 max-w-sm mx-auto">
                    <div class="bg-white/20 rounded-2xl py-4">
                        <p class="text-3xl font-extrabold">{{ $pct }}%</p>
                        <p class="text-xs text-white/80 font-semibold mt-0.5">Score</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4">
                        <p class="text-3xl font-extrabold">{{ $correctCount }}</p>
                        <p class="text-xs text-white/80 font-semibold mt-0.5">Correct</p>
                    </div>
                    <div class="bg-white/20 rounded-2xl py-4">
                        <p class="text-3xl font-extrabold">{{ $wrongCount }}</p>
                        <p class="text-xs text-white/80 font-semibold mt-0.5">Missed</p>
                    </div>
                </div>
                @php $mins = intdiv($totalTimeSeconds, 60); $secs = $totalTimeSeconds % 60; @endphp
                <p class="text-white/60 text-sm mt-4">Total time: {{ $mins > 0 ? "{$mins}m " : '' }}{{ $secs }}s</p>
            </div>

            <div class="p-6 sm:p-8 space-y-6">
                <div>
                    <h3 class="font-bold text-slate-700 mb-3">Question Review</h3>
                    <div class="space-y-2 max-h-80 overflow-y-auto pr-1">
                        @foreach($results as $r)
                        <div class="flex items-start gap-3 p-3 rounded-xl border {{ $r['is_correct'] ? 'bg-emerald-50 border-emerald-100' : 'bg-rose-50 border-rose-100' }}">
                            <span class="text-xl mt-0.5">{{ $r['is_correct'] ? '✅' : '❌' }}</span>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center justify-between gap-2">
                                    <span class="font-bold text-slate-700 text-sm">{{ $this->modeLabel($r['mode']) }}</span>
                                    <span class="text-xs text-slate-400 flex-shrink-0">{{ $r['time_taken'] }}s</span>
                                </div>
                                <p class="text-sm font-semibold text-slate-600 mt-0.5">{{ $r['target_digital'] }} — {{ $r['target_words'] }}</p>
                                @if(!$r['is_correct'])
                                <p class="text-xs text-rose-600 mt-0.5">Your answer: <span class="font-bold">{{ $r['user_answer'] }}</span></p>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="flex flex-col sm:flex-row gap-3">
                    <button wire:click="resetGame"
                        class="flex-1 py-4 bg-blue-600 hover:bg-blue-700 text-white font-extrabold text-lg rounded-xl transition-all">
                        🔄 Play Again
                    </button>
                    <a href="{{ route('time.index') }}"
                        class="flex-1 py-4 bg-slate-100 hover:bg-slate-200 text-slate-700 font-extrabold text-lg rounded-xl transition-all text-center">
                        ← Back
                    </a>
                    @auth
                    <a href="{{ route('progress.index') }}"
                        class="flex-1 py-4 bg-indigo-600 hover:bg-indigo-700 text-white font-extrabold text-lg rounded-xl transition-all text-center">
                        📊 My Progress
                    </a>
                    @endauth
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
