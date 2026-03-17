<div
    {{-- Only poll when a timer is active --}}
    @if($phase === 'showing' && $displayTime > 0)
        wire:poll.1000ms="tick"
    @elseif($phase === 'typing' && $timePerAnswer > 0)
        wire:poll.1000ms="tick"
    @endif
    x-on:scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="min-h-screen bg-gradient-to-br from-emerald-50 via-teal-50 to-cyan-50 py-6 px-4">

    <div class="max-w-2xl mx-auto">
        @once
        <script>
            window.spellingBoxes = function () {
                return {
                    wordLen: 0,
                    startIdx: 0,
                    firstLetter: '',
                    isFirstHint: false,
                    answer: '',

                    init() {
                        this.wordLen = Number(this.$el.dataset.wordLen || 0);
                        this.startIdx = Number(this.$el.dataset.startIdx || 0);
                        this.firstLetter = this.$el.dataset.firstLetter || '';
                        this.isFirstHint = this.$el.dataset.isFirstHint === 'true';
                        this.answer = this.isFirstHint ? this.firstLetter : '';

                        this.$nextTick(() => this.focusHidden());
                    },

                    focusHidden() {
                        const input = this.$refs.hiddenInput;
                        if (!input) return;

                        input.value = this.answer;
                        input.focus({ preventScroll: true });

                        const caret = Math.min(Math.max(this.answer.length, this.startIdx), this.wordLen);
                        input.setSelectionRange(caret, caret);
                    },

                    sanitize(value) {
                        let clean = (value || '')
                            .replace(/[^a-zA-Z\u00C0-\u024F']/g, '')
                            .slice(0, this.wordLen);

                        if (this.isFirstHint) {
                            clean = this.firstLetter + clean.replaceAll(this.firstLetter, '').slice(0, Math.max(this.wordLen - 1, 0));
                        }

                        return clean.slice(0, this.wordLen);
                    },

                    handleInput(event) {
                        this.answer = this.sanitize(event.target.value);
                        event.target.value = this.answer;

                        if (this.answer.length >= this.wordLen) {
                            this.submit();
                            return;
                        }

                        this.focusHidden();
                    },

                    handleClick() {
                        this.focusHidden();
                    },

                    handleKeydown(event) {
                        if (event.key === 'Backspace') {
                            if (this.isFirstHint && this.answer.length <= 1) {
                                event.preventDefault();
                            }
                            return;
                        }

                        if (event.key === 'Enter') {
                            this.submit();
                            event.preventDefault();
                        }
                    },

                    charAt(i) {
                        return this.answer.charAt(i) || '';
                    },

                    isActiveBox(i) {
                        const activeIndex = Math.min(
                            Math.max(this.answer.length, this.startIdx),
                            Math.max(this.wordLen - 1, 0)
                        );

                        return this.answer.length < this.wordLen && i === activeIndex;
                    },

                    submit() {
                        this.$wire.submitWithAnswer(this.answer);
                    },
                };
            };
        </script>
        @endonce

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SETUP PHASE                                                        --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'setup')
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-600 px-8 py-7">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">📝</div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white">Spelling Practise</h1>
                        <p class="text-emerald-100 text-sm mt-0.5">See it · Remember it · Spell it!</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                {{-- Word List --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Word List</label>
                    <select wire:model.live="wordListKey"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-semibold focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none bg-white">
                        @foreach($wordListLabels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5">{{ $wordListSize }} words available in this list</p>
                </div>

                {{-- Number of Words --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Number of Words</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach([5, 10, 15, 20, 25] as $n)
                        <button wire:click="$set('questionCount', {{ $n }})"
                            class="px-5 py-2.5 rounded-xl font-bold border-2 transition-all {{ $questionCount == $n ? 'bg-emerald-500 border-emerald-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-emerald-300' }}">
                            {{ $n }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Display Time --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Show word for…</label>
                    <p class="text-xs text-slate-400 mb-2">How long the word appears before it hides. "Manual" = hide when you're ready. "🔊 Audio only" = word is spoken but never shown at all.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([-1 => '🔊 Audio only', 0 => 'Manual', 2 => '2s', 3 => '3s', 4 => '4s', 5 => '5s', 8 => '8s', 10 => '10s'] as $t => $label)
                        <button wire:click="$set('displayTime', {{ $t }})"
                            class="px-4 py-2.5 rounded-xl font-bold border-2 transition-all
                                @if($t == -1)
                                    {{ $displayTime == -1 ? 'bg-violet-500 border-violet-500 text-white shadow-md' : 'bg-white border-violet-200 text-violet-600 hover:border-violet-400' }}
                                @else
                                    {{ $displayTime == $t ? 'bg-teal-500 border-teal-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-teal-300' }}
                                @endif
                            ">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Time to Answer --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Time to type your answer</label>
                    <p class="text-xs text-slate-400 mb-2">After the word hides, how long do you have to type it? Zero = unlimited.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([0 => 'No limit', 10 => '10s', 15 => '15s', 20 => '20s', 30 => '30s'] as $t => $label)
                        <button wire:click="$set('timePerAnswer', {{ $t }})"
                            class="px-4 py-2.5 rounded-xl font-bold border-2 transition-all {{ $timePerAnswer == $t ? 'bg-cyan-500 border-cyan-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-cyan-300' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Hint Type --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Hint while typing</label>
                    <p class="text-xs text-slate-400 mb-2">Shown after the word hides to help you remember the structure.</p>
                    <div class="flex flex-wrap gap-2">
                        <button wire:click="$set('hintType', 'none')"
                            class="px-4 py-2.5 rounded-xl font-bold border-2 transition-all {{ $hintType === 'none' ? 'bg-slate-600 border-slate-600 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">
                            No hint
                        </button>
                        <button wire:click="$set('hintType', 'blanks')"
                            class="px-4 py-2.5 rounded-xl font-bold border-2 transition-all {{ $hintType === 'blanks' ? 'bg-slate-600 border-slate-600 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-slate-400' }}">
                            _ _ _ _ (blanks)
                        </button>
                    </div>
                    @if($hintType === 'none')
                    <p class="mt-3 text-xs font-semibold text-amber-600">
                        Use using laptop or disable sugestion bar on your portable device.
                    </p>
                    @endif
                </div>

                {{-- Exam Mode --}}
                <div class="flex items-center justify-between p-4 rounded-2xl border-2 {{ $examMode ? 'border-amber-400 bg-amber-50' : 'border-slate-200 bg-slate-50' }} transition-all">
                    <div>
                        <p class="font-bold text-slate-700">Exam Mode</p>
                        <p class="text-xs text-slate-500 mt-0.5">No feedback after each word — results revealed at the end only</p>
                    </div>
                    <button wire:click="toggleExamMode"
                        class="relative inline-flex h-7 w-14 items-center rounded-full transition-colors {{ $examMode ? 'bg-amber-400' : 'bg-slate-300' }}">
                        <span class="inline-block h-5 w-5 rounded-full bg-white shadow transform transition-transform {{ $examMode ? 'translate-x-8' : 'translate-x-1' }}"></span>
                    </button>
                </div>

                {{-- Start --}}
                <button wire:click="startGame"
                    class="w-full py-4 rounded-2xl font-extrabold text-xl transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5
                        {{ $examMode ? 'bg-amber-400 hover:bg-amber-300 text-slate-900' : 'bg-emerald-500 hover:bg-emerald-400 text-white' }}">
                    {{ $examMode ? '🎓 Start Exam' : '✏️ Start Spelling!' }}
                </button>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SHOWING PHASE — word is visible                                    --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'showing')
        <div class="space-y-4">
            {{-- Progress bar --}}
            <div class="flex items-center justify-between text-sm font-semibold text-teal-700 px-1">
                <span>Word {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                @if($examMode)<span class="bg-amber-400 text-slate-900 text-xs font-bold px-3 py-1 rounded-full">EXAM</span>@endif
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            {{-- Word card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-gradient-to-br {{ $displayTime == -1 ? 'from-violet-500 to-purple-600' : 'from-emerald-400 to-teal-500' }} px-8 py-12 text-center"
                    x-data
                    x-init="
                        if (window.speechSynthesis) {
                            const u = new SpeechSynthesisUtterance('{{ $currentWord }}');
                            u.lang = 'en-GB';
                            u.rate = 0.85;
                            speechSynthesis.cancel();
                            speechSynthesis.speak(u);
                        }
                    ">

                    @if($displayTime == -1)
                        {{-- Audio-only mode: word is never shown --}}
                        <p class="text-violet-200 text-sm font-semibold mb-5 uppercase tracking-widest">Listen carefully!</p>
                        <div class="flex justify-center mb-3">
                            <div class="w-24 h-24 bg-white/20 rounded-full flex items-center justify-center">
                                <svg class="w-12 h-12 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                        d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                                </svg>
                            </div>
                        </div>
                        <p class="text-white/60 text-sm">The word has been spoken — can you spell it?</p>
                    @else
                        {{-- Normal mode: show the word --}}
                        <p class="text-emerald-100 text-sm font-semibold mb-4 uppercase tracking-widest">Remember this word</p>
                        <div class="text-5xl sm:text-7xl font-extrabold text-white tracking-wide drop-shadow-md">
                            {{ $currentWord }}
                        </div>

                        {{-- Display-time countdown bar --}}
                        @if($displayTime > 0)
                        <div class="mt-8 mx-auto max-w-xs">
                            <div class="w-full bg-white/30 rounded-full h-3">
                                <div class="bg-white h-3 rounded-full transition-all duration-1000"
                                    style="width: {{ $displayTime > 0 ? ($displayTimeLeft / $displayTime * 100) : 100 }}%"></div>
                            </div>
                            <p class="text-emerald-100 text-xs mt-2">Hiding in {{ $displayTimeLeft }}s…</p>
                        </div>
                        @endif
                    @endif
                </div>

                <div class="px-8 py-6 flex flex-col sm:flex-row gap-3 justify-center">
                    {{-- Speak again button --}}
                    <button
                        x-data
                        x-on:click="
                            if (window.speechSynthesis) {
                                const u = new SpeechSynthesisUtterance('{{ $currentWord }}');
                                u.lang = 'en-GB'; u.rate = 0.85;
                                speechSynthesis.cancel();
                                speechSynthesis.speak(u);
                            }
                        "
                        class="flex-1 flex items-center justify-center space-x-2 py-3 rounded-xl border-2 font-bold transition-all
                            {{ $displayTime == -1 ? 'border-violet-300 text-violet-700 hover:bg-violet-50' : 'border-teal-200 text-teal-700 hover:bg-teal-50' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                        </svg>
                        <span>{{ $displayTime == -1 ? 'Say it again 🔊' : 'Hear it again' }}</span>
                    </button>

                    {{-- I'm ready button --}}
                    <button wire:click="readyToType"
                        class="flex-1 py-3 rounded-xl font-extrabold transition-all shadow-md
                            {{ $displayTime == -1 ? 'bg-violet-500 hover:bg-violet-600 text-white' : 'bg-emerald-500 hover:bg-emerald-600 text-white' }}">
                        {{ $displayTime == -1 ? "I'm Ready — Let's Spell! ✏️" : "I'm Ready — Hide It! 🙈" }}
                    </button>
                </div>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- TYPING PHASE — word is hidden, user types                         --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'typing')
        <div class="space-y-4" wire:key="spelling-typing-{{ $currentIndex }}">
            {{-- Progress --}}
            <div class="flex items-center justify-between text-sm font-semibold text-teal-700 px-1">
                <span>Word {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                @if($examMode)<span class="bg-amber-400 text-slate-900 text-xs font-bold px-3 py-1 rounded-full">EXAM</span>@endif
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            {{-- Answer timer bar --}}
            @if($timePerAnswer > 0)
            <div class="w-full rounded-full h-3 {{ $answerTimeLeft <= 5 ? 'bg-red-100' : 'bg-slate-200' }}">
                <div class="h-3 rounded-full transition-all duration-1000
                    {{ $answerTimeLeft <= 5 ? 'bg-red-500' : ($answerTimeLeft <= 10 ? 'bg-amber-400' : 'bg-teal-500') }}"
                    style="width: {{ $timePerAnswer > 0 ? ($answerTimeLeft / $timePerAnswer * 100) : 100 }}%"></div>
            </div>
            <p class="text-center text-sm font-semibold {{ $answerTimeLeft <= 5 ? 'text-red-600' : 'text-slate-500' }}">
                {{ $answerTimeLeft }}s remaining
            </p>
            @endif

            {{-- Typing card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-slate-700 px-8 py-8 text-center">
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-widest mb-4">Spell the word!</p>

                    {{-- Hear it again (word hidden) --}}
                    <button
                        x-data
                        x-on:click="
                            if (window.speechSynthesis) {
                                const u = new SpeechSynthesisUtterance('{{ $currentWord }}');
                                u.lang = 'en-GB'; u.rate = 0.85;
                                speechSynthesis.cancel();
                                speechSynthesis.speak(u);
                            }
                        "
                        class="inline-flex items-center space-x-2 px-5 py-2.5 bg-white/10 hover:bg-white/20 rounded-xl text-white font-semibold transition-all border border-white/20">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M12 6v12m0 0l-3-3m3 3l3-3M6.343 6.343a8 8 0 000 11.314"/>
                        </svg>
                        <span>🔊 Hear the word</span>
                    </button>

                    {{-- Mode note (single input mode) --}}
                    @if($hintType === 'none')
                    <div class="mt-5 text-slate-300 text-sm font-semibold">
                        Single input mode. Best used on a laptop or with the suggestion bar disabled on your portable device.
                    </div>
                    @endif
                </div>

                {{-- ── Answer input ─────────────────────────────────────────── --}}
                @php
                    $wordLen    = mb_strlen($currentWord);
                    // Responsive box sizing based on word length
                    $boxSize = match(true) {
                        $wordLen <= 5  => 'h-16 text-3xl',
                        $wordLen <= 8  => 'h-14 text-2xl',
                        $wordLen <= 12 => 'h-12 text-xl',
                        default        => 'h-10 text-lg',
                    };
                @endphp

                @if($hintType === 'none')
                <div class="px-4 sm:px-8 py-8">
                    <div class="max-w-md mx-auto">
                        <input
                            type="text"
                            wire:model.live="userAnswer"
                            wire:keydown.enter.prevent="submitAnswer"
                            wire:key="spelling-single-input-{{ $currentIndex }}"
                            x-data
                            x-init="$nextTick(() => $el.focus())"
                            inputmode="text"
                            autocomplete="off"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            class="w-full h-16 rounded-2xl border-2 border-slate-200 bg-white px-5 text-center text-3xl font-extrabold text-slate-800 outline-none transition-all focus:border-emerald-500 focus:ring-2 focus:ring-emerald-100"
                            placeholder="Type the whole word">
                    </div>

                    <button type="button" wire:click="submitAnswer"
                        class="mt-6 w-full py-4 rounded-2xl font-extrabold text-xl bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        Check ✓
                    </button>
                </div>
                @else
                <div class="px-4 sm:px-8 py-8"
                    x-data="window.spellingBoxes()"
                    data-word-len="{{ $wordLen }}"
                    data-start-idx="0"
                    data-first-letter=""
                    data-is-first-hint="false">

                    {{-- Boxes grid --}}
                    <div class="relative flex justify-center cursor-text"
                        style="display:grid; grid-template-columns: repeat({{ $wordLen }}, 1fr); gap: clamp(3px, 1.5vw, 10px); max-width: min(100%, {{ $wordLen * 56 }}px); margin: 0 auto;">
                        <input
                            x-ref="hiddenInput"
                            type="text"
                            maxlength="{{ $wordLen }}"
                            inputmode="text"
                            enterkeyhint="done"
                            autocomplete="off"
                            autocorrect="off"
                            autocapitalize="off"
                            spellcheck="false"
                            aria-label="Spell the word"
                            class="absolute inset-0 z-10 w-full h-full opacity-0 cursor-text"
                            x-on:click="handleClick()"
                            x-on:input="handleInput($event)"
                            x-on:keydown="handleKeydown($event)">
                        @for($i = 0; $i < $wordLen; $i++)
                        <div
                            class="{{ $boxSize }} w-full flex items-center justify-center text-center leading-none font-extrabold rounded-xl border-2 outline-none transition-all bg-white border-slate-200 text-slate-800"
                            x-bind:class="isActiveBox({{ $i }}) ? 'ring-2 ring-teal-200 border-teal-500' : ''">
                            <span class="block leading-none" x-text="charAt({{ $i }})"></span>
                        </div>
                        @endfor
                    </div>

                    <button type="button" x-on:click.prevent="submit()"
                        class="mt-6 w-full py-4 rounded-2xl font-extrabold text-xl bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        Check ✓
                    </button>
                </div>
                @endif
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- FEEDBACK PHASE                                                     --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'feedback')
        <div class="space-y-4">
            {{-- Progress --}}
            <div class="flex items-center justify-between text-sm font-semibold text-teal-700 px-1">
                <span>Word {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-emerald-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? (($currentIndex) / $questionCount * 100) : 0 }}%"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($lastCorrect)
                <div class="bg-gradient-to-br from-emerald-400 to-green-500 px-8 py-10 text-center">
                    <div class="text-6xl mb-3">🎉</div>
                    <p class="text-4xl font-extrabold text-white mb-2">Correct!</p>
                    <p class="text-emerald-100 text-lg font-semibold">
                        "{{ $currentWord }}" — well done!
                    </p>
                </div>
                @else
                <div class="bg-gradient-to-br from-red-400 to-rose-500 px-8 py-10 text-center">
                    <div class="text-6xl mb-3">😬</div>
                    <p class="text-3xl font-extrabold text-white mb-2">Not quite!</p>
                    <div class="mt-4 space-y-2">
                        <p class="text-red-100 text-sm font-semibold uppercase tracking-wider">You typed:</p>
                        <p class="text-2xl font-bold text-white/70 line-through">
                            {{ $results[count($results) - 1]['user_answer'] ?: '(nothing)' }}
                        </p>
                        <p class="text-red-100 text-sm font-semibold uppercase tracking-wider mt-3">Correct spelling:</p>
                        <p class="text-4xl font-extrabold text-white tracking-wide">{{ $currentWord }}</p>
                    </div>
                </div>
                @endif

                <div class="px-8 py-6">
                    <button wire:click="proceedToNext"
                        class="w-full py-4 rounded-2xl font-extrabold text-xl
                            {{ $lastCorrect ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-slate-800 hover:bg-slate-700 text-white' }}
                            transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                        @if($currentIndex + 1 >= $questionCount)
                            See Results 🏁
                        @else
                            Next Word →
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
            {{-- Score card --}}
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
                <p class="text-white/80 mb-6">{{ $examMode ? 'Exam complete' : 'Spelling complete' }}</p>

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
                    <p class="text-slate-500 text-sm">Tap any word to hear it pronounced</p>
                </div>
                <div class="divide-y divide-slate-50">
                    @foreach($results as $r)
                    <div class="flex items-center px-6 py-4 {{ $r['is_correct'] ? 'hover:bg-emerald-50' : 'hover:bg-red-50' }} transition-colors">
                        <span class="text-xl mr-3">{{ $r['is_correct'] ? '✅' : '❌' }}</span>

                        <div class="flex-1 min-w-0">
                            <button
                                x-data
                                x-on:click="
                                    if (window.speechSynthesis) {
                                        const u = new SpeechSynthesisUtterance('{{ $r['word'] }}');
                                        u.lang = 'en-GB'; u.rate = 0.85;
                                        speechSynthesis.cancel();
                                        speechSynthesis.speak(u);
                                    }
                                "
                                class="font-bold text-slate-800 text-left hover:text-teal-600 transition-colors flex items-center space-x-1.5">
                                <span class="text-base">{{ $r['word'] }}</span>
                                <svg class="w-3.5 h-3.5 text-teal-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/>
                                </svg>
                            </button>
                            @if(!$r['is_correct'])
                            <p class="text-xs text-red-400 mt-0.5">
                                You wrote: <span class="font-semibold line-through">{{ $r['user_answer'] ?: '—' }}</span>
                            </p>
                            @endif
                        </div>

                        <span class="text-xs text-slate-400 ml-2 flex-shrink-0">{{ $r['time_taken'] }}s</span>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- Buttons --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="resetGame"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-emerald-500 hover:bg-emerald-600 text-white transition-all shadow-md hover:shadow-lg">
                    ✏️ Play Again
                </button>
                <a href="{{ route('english.index') }}"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-white border-2 border-slate-200 text-slate-700 hover:border-teal-300 hover:text-teal-600 transition-all text-center">
                    ← English Menu
                </a>
            </div>

            @auth
            <div class="text-center">
                <a href="{{ route('progress.index') }}" class="text-sm text-teal-600 hover:text-teal-700 font-semibold underline underline-offset-2">
                    View all my progress →
                </a>
            </div>
            @endauth
        </div>
        @endif

    </div>
</div>
