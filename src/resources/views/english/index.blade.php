<x-app-layout>
    <x-slot name="title">English</x-slot>

    <!-- Header -->
    <section class="bg-gradient-to-br from-emerald-500 via-teal-600 to-cyan-700 text-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-20">
            <div class="flex items-center space-x-4 mb-4">
                <a href="{{ route('home') }}" class="text-emerald-200 hover:text-white text-sm font-semibold transition-colors">Home</a>
                <svg class="w-4 h-4 text-emerald-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-emerald-100 text-sm font-semibold">English</span>
            </div>
            <div class="flex items-center space-x-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center text-4xl">📚</div>
                <div>
                    <h1 class="text-4xl font-extrabold">English</h1>
                    <p class="text-emerald-100 mt-1">Spelling, vocabulary and language skills</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Games -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <h2 class="text-2xl font-extrabold text-slate-800 mb-6">Choose a Game</h2>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Spelling game -->
            <a href="{{ route('english.spelling') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-emerald-200 transition-all duration-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-emerald-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-emerald-500 transition-colors">
                    <span class="text-3xl">📝</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Spelling Practise</h3>
                <p class="text-slate-500 mb-4 text-sm">See it, remember it, spell it from memory — just like Spelling Shed!</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-semibold">7 word lists</span>
                    <span class="px-3 py-1 bg-teal-100 text-teal-700 rounded-full text-xs font-semibold">Year 1–6</span>
                    <span class="px-3 py-1 bg-cyan-100 text-cyan-700 rounded-full text-xs font-semibold">🔊 Audio</span>
                </div>
            </a>

            <!-- Anagram game -->
            <a href="{{ route('english.anagram') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-amber-200 transition-all duration-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-amber-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-amber-500 transition-colors">
                    <span class="text-3xl">🔤</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Anagram Challenge</h3>
                <p class="text-slate-500 mb-4 text-sm">Unscramble jumbled letters to find the hidden word!</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-semibold">Drag &amp; tap tiles</span>
                    <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-full text-xs font-semibold">All word lists</span>
                    <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-semibold">💡 Hints</span>
                </div>
            </a>

            <!-- Word definitions game -->
            <a href="{{ route('english.word-definitions') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-sky-200 transition-all duration-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-sky-500 transition-colors">
                    <span class="text-3xl">📖</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Word Definitions</h3>
                <p class="text-slate-500 mb-4 text-sm">See a word, pick the correct meaning from four choices.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-sky-100 text-sky-700 rounded-full text-xs font-semibold">Multiple choice</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">3 difficulty levels</span>
                    <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-semibold">🔊 Audio</span>
                </div>
            </a>
        </div>
    </section>
</x-app-layout>
