<x-app-layout>
    <x-slot name="title">Time Telling</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <div class="flex items-center space-x-3 mb-3">
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-medium">Home</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-sm font-medium text-slate-600">Time Telling</span>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-800">🕐 Time Telling</h1>
            <p class="text-slate-500 mt-2 text-lg">Practise reading and setting clocks in multiple ways</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            <a href="{{ route('time.game') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 bg-sky-100 rounded-2xl flex items-center justify-center group-hover:bg-sky-600 transition-colors">
                        <span class="text-3xl font-black text-sky-600 group-hover:text-white transition-colors">🕐</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Available</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">Time Telling Game</h2>
                <p class="text-slate-500 mb-5">Set clock hands, read analog clocks, match digital and text time, and listen to spoken time in UK English.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-sky-50 text-sky-700 rounded-full text-xs font-semibold">Analog ↔ Digital</span>
                    <span class="px-3 py-1 bg-violet-50 text-violet-700 rounded-full text-xs font-semibold">Voice Mode</span>
                    <span class="px-3 py-1 bg-cyan-50 text-cyan-700 rounded-full text-xs font-semibold">Configurable Precision</span>
                </div>
                <div class="mt-6 flex items-center text-indigo-600 font-bold group-hover:text-indigo-800 transition-colors">
                    <span>Play Now</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </a>
        </div>
    </div>
</x-app-layout>
