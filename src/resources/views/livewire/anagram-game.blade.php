<div
    @if($phase === 'playing' && $timePerWord > 0)
        wire:poll.1000ms="tick"
    @endif
    x-on:scroll-to-top.window="window.scrollTo({ top: 0, behavior: 'smooth' })"
    class="min-h-screen bg-gradient-to-br from-amber-50 via-orange-50 to-yellow-50 py-6 px-4">

    <div class="max-w-2xl mx-auto">
        @once
        <script>
            window.spellingPuzzle = window.spellingPuzzle || (function () {
                return function () {
                    return {
                        tiles: [],
                        slots: [],
                        submitted: false,
                        tileClass: '',
                        dragging: null,
                        dragOverSlotId: null,
                        selectedId: null,
                        pendingTap: null,
                        DRAG_THRESHOLD: 8,

                        init() {
                            const word     = this.$el.dataset.word || '';
                            const letters  = [...word];
                            this.tileClass = this.$el.dataset.tileSize || 'h-12 w-12 text-xl';
                            this.slots = letters.map((_, i) => ({ id: i, tileId: null }));
                            let arr = letters.map((l, i) => ({ id: i, letter: l.toUpperCase() }));
                            if (arr.length > 1) {
                                let attempts = 0, inOrder;
                                do {
                                    for (let i = arr.length - 1; i > 0; i--) {
                                        const j = Math.floor(Math.random() * (i + 1));
                                        [arr[i], arr[j]] = [arr[j], arr[i]];
                                    }
                                    inOrder = arr.every((t, i) => t.letter === letters[i].toUpperCase());
                                    attempts++;
                                } while (inOrder && attempts < 10);
                            }
                            this.tiles = arr;
                        },
                        slotOf(tileId) { return this.slots.find(s => s.tileId === tileId) ?? null; },
                        tileInSlot(slotId) {
                            const id = this.slots[slotId]?.tileId;
                            if (id == null) return null;
                            return this.tiles.find(t => t.id === id) ?? null;
                        },
                        onBankTilePointerDown(event, tileId) {
                            if (this.submitted) return;
                            this.pendingTap = { tileId, isFromSlot: false, el: event.currentTarget, startX: event.clientX, startY: event.clientY };
                        },
                        onSlotPointerDown(event, slotId) {
                            if (this.submitted) return;
                            const slot = this.slots[slotId];
                            if (!slot) return;
                            if (slot.tileId != null) {
                                this.pendingTap = { tileId: slot.tileId, isFromSlot: true, fromSlotId: slotId, el: event.currentTarget, startX: event.clientX, startY: event.clientY };
                            } else if (this.selectedId !== null) {
                                this.pendingTap = { tileId: null, isFromSlot: false, targetSlotId: slotId, el: null, startX: event.clientX, startY: event.clientY };
                            }
                        },
                        moveDrag(event) {
                            if (this.pendingTap && !this.dragging) {
                                const dx = event.clientX - this.pendingTap.startX;
                                const dy = event.clientY - this.pendingTap.startY;
                                if (Math.sqrt(dx*dx + dy*dy) > this.DRAG_THRESHOLD && this.pendingTap.tileId !== null) {
                                    const { tileId, isFromSlot, fromSlotId, el } = this.pendingTap;
                                    const rect = el.getBoundingClientRect();
                                    const clone = el.cloneNode(true);
                                    ['x-bind:class','@pointerdown','@pointerdown.prevent','x-show'].forEach(a => clone.removeAttribute(a));
                                    clone.style.cssText = ['position:fixed',`left:${rect.left}px`,`top:${rect.top}px`,`width:${rect.width}px`,`height:${rect.height}px`,'z-index:9999','pointer-events:none','opacity:0.92','transform:scale(1.12)','transition:none','margin:0','cursor:grabbing','user-select:none'].join(';');
                                    document.body.appendChild(clone);
                                    if (isFromSlot) this.slots[fromSlotId].tileId = null;
                                    this.selectedId = null;
                                    this.pendingTap = null;
                                    this.dragging = { tileId, clone, offsetX: event.clientX - rect.left, offsetY: event.clientY - rect.top };
                                }
                                return;
                            }
                            if (!this.dragging) return;
                            const { clone, offsetX, offsetY } = this.dragging;
                            clone.style.left = (event.clientX - offsetX) + 'px';
                            clone.style.top  = (event.clientY - offsetY) + 'px';
                            clone.style.display = 'none';
                            const els = document.elementsFromPoint(event.clientX, event.clientY);
                            clone.style.display = '';
                            const slotEl = els.find(el => el.hasAttribute && el.hasAttribute('data-slot-id'));
                            this.dragOverSlotId = slotEl ? parseInt(slotEl.dataset.slotId) : null;
                        },
                        endInteraction(event) {
                            if (this.dragging) {
                                const { tileId, clone } = this.dragging;
                                clone.style.display = 'none';
                                const els = document.elementsFromPoint(event.clientX, event.clientY);
                                clone.style.display = '';
                                clone.remove();
                                this.dragging = null; this.dragOverSlotId = null;
                                const slotEl = els.find(el => el.hasAttribute && el.hasAttribute('data-slot-id'));
                                if (slotEl) { this.slots[parseInt(slotEl.dataset.slotId)].tileId = tileId; this._checkAutoSubmit(); }
                                return;
                            }
                            if (!this.pendingTap) return;
                            const pt = this.pendingTap; this.pendingTap = null;
                            if (pt.tileId !== null) {
                                if (pt.isFromSlot) {
                                    const slot = this.slots[pt.fromSlotId];
                                    if (this.selectedId !== null) { slot.tileId = this.selectedId; this.selectedId = pt.tileId; }
                                    else { slot.tileId = null; this.selectedId = pt.tileId; }
                                } else { this.selectedId = (this.selectedId === pt.tileId) ? null : pt.tileId; }
                            } else if (pt.targetSlotId !== undefined) {
                                const slot = this.slots[pt.targetSlotId];
                                if (slot && this.selectedId !== null) { slot.tileId = this.selectedId; this.selectedId = null; this._checkAutoSubmit(); }
                            }
                        },
                        cancelDrag() {
                            if (this.dragging) { this.dragging.clone.remove(); this.dragging = null; this.dragOverSlotId = null; }
                            this.pendingTap = null;
                        },
                        _checkAutoSubmit() {
                            if (!this.submitted && this.selectedId === null && this.slots.every(s => s.tileId !== null)) {
                                this.submitted = true;
                                const word = this.assembledWord;
                                setTimeout(() => this.$wire.submitWithAnswer(word), 700);
                            }
                        },
                        clearAll() {
                            if (this.submitted) return;
                            if (this.dragging) this.cancelDrag();
                            this.slots.forEach(s => { s.tileId = null; });
                            this.selectedId = null; this.pendingTap = null;
                        },
                        get assembledWord() {
                            return this.slots.map(s => { if (s.tileId == null) return ''; const t = this.tiles.find(t => t.id === s.tileId); return t ? t.letter : ''; }).join('');
                        },
                        get isComplete() { return this.slots.every(s => s.tileId !== null); },
                        manualSubmit() {
                            if (!this.isComplete || this.submitted) return;
                            this.submitted = true; this.selectedId = null;
                            this.$wire.submitWithAnswer(this.assembledWord);
                        },
                    };
                };
            })();
        </script>
        @endonce

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- SETUP PHASE                                                        --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'setup')
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-8 py-7">
                <div class="flex items-center space-x-3">
                    <div class="w-12 h-12 bg-white/20 rounded-2xl flex items-center justify-center text-2xl">🔤</div>
                    <div>
                        <h1 class="text-2xl font-extrabold text-white">Anagram Challenge</h1>
                        <p class="text-amber-100 text-sm mt-0.5">Unscramble the letters to find the word!</p>
                    </div>
                </div>
            </div>

            <div class="p-8 space-y-6">
                {{-- Word List --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Word List</label>
                    <select wire:model.live="wordListKey"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-slate-700 font-semibold focus:ring-2 focus:ring-amber-400 focus:border-amber-400 outline-none bg-white">
                        @foreach($wordListLabels as $key => $label)
                            <option value="{{ $key }}">{{ $label }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-1.5">{{ $wordListSize }} words available</p>
                </div>

                {{-- Number of Words --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">Number of Words</label>
                    <div class="flex flex-wrap gap-2">
                        @foreach([5, 10, 15, 20, 25] as $n)
                        <button wire:click="$set('questionCount', {{ $n }})"
                            class="px-5 py-2.5 rounded-xl font-bold border-2 transition-all {{ $questionCount == $n ? 'bg-amber-500 border-amber-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-amber-300' }}">
                            {{ $n }}
                        </button>
                        @endforeach
                    </div>
                </div>

                {{-- Time per Word --}}
                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1">Time per word</label>
                    <p class="text-xs text-slate-400 mb-2">How long to unscramble each word. Zero = unlimited time.</p>
                    <div class="flex flex-wrap gap-2">
                        @foreach([0 => 'No limit', 15 => '15s', 20 => '20s', 30 => '30s', 45 => '45s', 60 => '60s'] as $t => $label)
                        <button wire:click="$set('timePerWord', {{ $t }})"
                            class="px-4 py-2.5 rounded-xl font-bold border-2 transition-all {{ $timePerWord == $t ? 'bg-orange-500 border-orange-500 text-white shadow-md' : 'bg-white border-slate-200 text-slate-600 hover:border-orange-300' }}">
                            {{ $label }}
                        </button>
                        @endforeach
                    </div>
                </div>

                <button wire:click="startGame"
                    class="w-full py-4 rounded-2xl font-extrabold text-xl bg-amber-500 hover:bg-amber-400 text-white transition-all shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                    🔤 Start Unscrambling!
                </button>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- PLAYING PHASE                                                      --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'playing')
        <div class="space-y-4" wire:key="anagram-playing-{{ $currentIndex }}">
            {{-- Progress --}}
            <div class="flex items-center justify-between text-sm font-semibold text-amber-700 px-1">
                <span>Word {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-amber-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            {{-- Timer --}}
            @if($timePerWord > 0)
            <div class="w-full rounded-full h-3 {{ $answerTimeLeft <= 5 ? 'bg-red-100' : 'bg-slate-200' }}">
                <div class="h-3 rounded-full transition-all duration-1000
                    {{ $answerTimeLeft <= 5 ? 'bg-red-500' : ($answerTimeLeft <= 10 ? 'bg-amber-400' : 'bg-orange-400') }}"
                    style="width: {{ $timePerWord > 0 ? ($answerTimeLeft / $timePerWord * 100) : 100 }}%"></div>
            </div>
            <p class="text-center text-sm font-semibold {{ $answerTimeLeft <= 5 ? 'text-red-600' : 'text-slate-500' }}">
                {{ $answerTimeLeft }}s remaining
            </p>
            @endif

            {{-- Puzzle card --}}
            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                <div class="bg-slate-700 px-8 py-7 text-center">
                    <p class="text-slate-400 text-sm font-semibold uppercase tracking-widest mb-1">Unscramble the letters!</p>
                    <p class="text-slate-300 text-sm">
                        {{ mb_strlen($currentWord) }} {{ mb_strlen($currentWord) === 1 ? 'letter' : 'letters' }}
                    </p>

                    @if($usedHint && $hintLetter)
                    <div class="mt-3 inline-flex items-center space-x-2 bg-amber-500/20 border border-amber-400/40 rounded-xl px-4 py-2">
                        <span class="text-amber-300 text-sm font-bold">💡 First letter:</span>
                        <span class="text-amber-200 text-2xl font-extrabold">{{ $hintLetter }}</span>
                    </div>
                    @endif
                </div>

                {{-- Tile puzzle --}}
                @php
                    $wordLen = mb_strlen($currentWord);
                    $puzzleTileSize = match(true) {
                        $wordLen <= 5  => 'h-14 w-14 text-2xl',
                        $wordLen <= 7  => 'h-12 w-12 text-xl',
                        $wordLen <= 10 => 'h-11 w-11 text-lg',
                        default        => 'h-9 w-9 text-base',
                    };
                @endphp

                <div class="px-4 sm:px-8 py-8"
                    x-data="spellingPuzzle()"
                    data-word="{{ $currentWord }}"
                    data-tile-size="{{ $puzzleTileSize }}"
                    wire:key="anagram-puzzle-{{ $currentIndex }}"
                    @pointermove.window="moveDrag($event)"
                    @pointerup.window="endInteraction($event)"
                    @pointercancel.window="cancelDrag()">

                    <p class="text-center text-slate-400 text-xs font-semibold uppercase tracking-widest mb-5">
                        Drag or tap letters into the boxes
                    </p>

                    {{-- Target slots --}}
                    <div class="flex flex-wrap justify-center gap-2 mb-6">
                        <template x-for="slot in slots" :key="slot.id">
                            <div
                                :data-slot-id="slot.id"
                                class="flex items-center justify-center rounded-xl border-2 font-extrabold leading-none transition-all select-none touch-none"
                                :class="[
                                    tileClass,
                                    submitted
                                        ? 'bg-amber-200 border-amber-500 text-amber-900 cursor-default'
                                        : (dragOverSlotId === slot.id
                                            ? 'border-amber-500 bg-amber-100 scale-105 shadow-md cursor-copy'
                                            : (slot.tileId !== null
                                                ? 'bg-amber-100 border-amber-400 text-amber-800 cursor-grab active:cursor-grabbing'
                                                : (selectedId !== null
                                                    ? 'border-amber-400 bg-amber-50 cursor-pointer'
                                                    : 'border-dashed border-slate-300 bg-slate-50')))
                                ]"
                                @pointerdown.prevent="onSlotPointerDown($event, slot.id)">
                                <span
                                    :data-slot-id="slot.id"
                                    x-text="tileInSlot(slot.id)?.letter ?? ''"
                                    class="block leading-none pointer-events-none"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Divider --}}
                    <div class="flex items-center gap-3 mb-4">
                        <div class="flex-1 h-px bg-slate-200"></div>
                        <span class="text-xs text-slate-400 font-semibold uppercase tracking-wide">Letters</span>
                        <div class="flex-1 h-px bg-slate-200"></div>
                    </div>

                    {{-- Bank --}}
                    <div class="flex flex-wrap justify-center gap-2 min-h-14 mb-6">
                        <template x-for="tile in tiles" :key="tile.id">
                            <div
                                x-show="slotOf(tile.id) === null"
                                @pointerdown.prevent="onBankTilePointerDown($event, tile.id)"
                                class="flex items-center justify-center rounded-xl border-2 font-extrabold leading-none transition-all select-none touch-none"
                                :class="[
                                    tileClass,
                                    dragging && dragging.tileId === tile.id
                                        ? 'opacity-30 cursor-grabbing'
                                        : (selectedId === tile.id
                                            ? 'bg-amber-500 border-amber-600 text-white ring-4 ring-amber-200 scale-105 shadow-md cursor-grab'
                                            : 'bg-white border-amber-200 text-amber-700 hover:border-amber-400 hover:bg-amber-50 cursor-grab active:cursor-grabbing')
                                ]">
                                <span x-text="tile.letter" class="block leading-none pointer-events-none"></span>
                            </div>
                        </template>
                    </div>

                    {{-- Actions --}}
                    <div class="flex gap-3">
                        <button type="button" @click="clearAll()" :disabled="submitted"
                            class="px-5 py-3 rounded-xl border-2 border-slate-200 text-slate-500 font-bold transition-all hover:border-red-300 hover:text-red-500 disabled:opacity-40 disabled:cursor-not-allowed">
                            🗑 Clear
                        </button>
                        <button type="button" @click="manualSubmit()" :disabled="!isComplete || submitted"
                            class="flex-1 py-3 rounded-2xl font-extrabold text-xl transition-all shadow-md"
                            :class="isComplete && !submitted
                                ? 'bg-emerald-500 hover:bg-emerald-600 text-white hover:shadow-lg hover:-translate-y-0.5'
                                : 'bg-slate-100 text-slate-400 cursor-not-allowed'">
                            Check ✓
                        </button>
                    </div>
                </div>
            </div>

            {{-- Hint + Give up --}}
            <div class="flex gap-3">
                @if(!$usedHint)
                <button wire:click="useHint"
                    class="flex-1 py-3 rounded-2xl border-2 border-amber-300 text-amber-700 font-bold hover:bg-amber-50 transition-all">
                    💡 Hint — reveal first letter
                </button>
                @else
                <div class="flex-1 py-3 rounded-2xl border-2 border-amber-200 text-amber-400 font-bold text-center bg-amber-50 cursor-default">
                    💡 Hint used
                </div>
                @endif
                <button wire:click="giveUp"
                    class="px-6 py-3 rounded-2xl border-2 border-slate-200 text-slate-500 font-bold hover:border-red-300 hover:text-red-500 transition-all">
                    🏳 Give up
                </button>
            </div>
        </div>
        @endif

        {{-- ══════════════════════════════════════════════════════════════════ --}}
        {{-- FEEDBACK PHASE                                                     --}}
        {{-- ══════════════════════════════════════════════════════════════════ --}}
        @if($phase === 'feedback')
        <div class="space-y-4">
            <div class="flex items-center justify-between text-sm font-semibold text-amber-700 px-1">
                <span>Word {{ $currentIndex + 1 }} of {{ $questionCount }}</span>
                <span>{{ $correctCount }} correct</span>
            </div>
            <div class="w-full bg-slate-200 rounded-full h-2.5">
                <div class="bg-amber-500 h-2.5 rounded-full transition-all duration-500"
                    style="width: {{ $questionCount > 0 ? ($currentIndex / $questionCount * 100) : 0 }}%"></div>
            </div>

            <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
                @if($lastCorrect)
                <div class="bg-gradient-to-br from-emerald-400 to-green-500 px-8 py-10 text-center">
                    <div class="text-6xl mb-3">🎉</div>
                    <p class="text-4xl font-extrabold text-white mb-2">Brilliant!</p>
                    <p class="text-emerald-100 text-lg font-semibold">
                        "{{ $currentWord }}" — you unscrambled it!
                    </p>
                    @if($usedHint)
                    <p class="mt-2 text-emerald-200 text-sm">💡 Hint was used</p>
                    @endif
                </div>
                @else
                <div class="bg-gradient-to-br from-red-400 to-rose-500 px-8 py-10 text-center">
                    <div class="text-6xl mb-3">🤔</div>
                    <p class="text-3xl font-extrabold text-white mb-4">Not quite!</p>
                    <div class="space-y-2">
                        @if($userAnswer)
                        <p class="text-red-100 text-sm font-semibold uppercase tracking-wider">You answered:</p>
                        <p class="text-2xl font-bold text-white/70 line-through">{{ $userAnswer }}</p>
                        @endif
                        <p class="text-red-100 text-sm font-semibold uppercase tracking-wider mt-3">The word was:</p>
                        <p class="text-4xl font-extrabold text-white tracking-wide">{{ $currentWord }}</p>
                    </div>
                </div>
                @endif

                <div class="px-8 py-6">
                    <button wire:click="proceedToNext"
                        class="w-full py-4 rounded-2xl font-extrabold text-xl transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5
                            {{ $lastCorrect ? 'bg-emerald-500 hover:bg-emerald-600 text-white' : 'bg-slate-800 hover:bg-slate-700 text-white' }}">
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
                <p class="text-white/80 mb-6">Anagram challenge complete</p>

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
                        <div class="text-3xl font-extrabold">{{ $hintUsedCount }}</div>
                        <div class="text-xs text-white/80 font-semibold mt-0.5">Hints</div>
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
                                x-on:click="if(window.speechSynthesis){const u=new SpeechSynthesisUtterance('{{ $r['word'] }}');u.lang='en-GB';u.rate=0.85;speechSynthesis.cancel();speechSynthesis.speak(u);}"
                                class="font-bold text-slate-800 text-left hover:text-amber-600 transition-colors flex items-center space-x-1.5">
                                <span class="text-base">{{ $r['word'] }}</span>
                                <svg class="w-3.5 h-3.5 text-amber-400 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464a5 5 0 010 7.072M6.343 6.343a8 8 0 000 11.314"/>
                                </svg>
                            </button>
                            @if(!$r['is_correct'] && $r['user_answer'])
                            <p class="text-xs text-red-400 mt-0.5">You tried: <span class="font-semibold line-through">{{ $r['user_answer'] }}</span></p>
                            @endif
                            @if($r['used_hint'])
                            <p class="text-xs text-amber-500 mt-0.5">💡 hint used</p>
                            @endif
                        </div>
                        <span class="text-xs text-slate-400 ml-2 flex-shrink-0">{{ $r['time_taken'] }}s</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3">
                <button wire:click="resetGame"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-amber-500 hover:bg-amber-400 text-white transition-all shadow-md hover:shadow-lg">
                    🔤 Play Again
                </button>
                <a href="{{ route('english.index') }}"
                    class="flex-1 py-4 rounded-2xl font-extrabold text-lg bg-white border-2 border-slate-200 text-slate-700 hover:border-amber-300 hover:text-amber-600 transition-all text-center">
                    ← English Menu
                </a>
            </div>

            @auth
            <div class="text-center">
                <a href="{{ route('progress.index') }}" class="text-sm text-amber-600 hover:text-amber-700 font-semibold underline underline-offset-2">
                    View all my progress →
                </a>
            </div>
            @endauth
        </div>
        @endif

    </div>
</div>
