<x-app-layout>
    <x-slot name="title">Mathematics</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-10">
            <div class="flex items-center space-x-3 mb-3">
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-medium">Home</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-sm font-medium text-slate-600">Mathematics</span>
            </div>
            <h1 class="text-4xl font-extrabold text-slate-800">🔢 Mathematics</h1>
            <p class="text-slate-500 mt-2 text-lg">Choose a game to start practising</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">

            <!-- Addition & Subtraction -->
            <a href="{{ route('math.addition-subtraction') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center group-hover:bg-blue-600 transition-colors">
                        <span class="text-3xl font-black text-blue-600 group-hover:text-white transition-colors">±</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Available</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">Addition & Subtraction</h2>
                <p class="text-slate-500 mb-5">Practice adding and subtracting numbers. Choose your difficulty, number size, and answer style.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-xs font-semibold">+ Addition</span>
                    <span class="px-3 py-1 bg-orange-50 text-orange-700 rounded-full text-xs font-semibold">− Subtraction</span>
                    <span class="px-3 py-1 bg-purple-50 text-purple-700 rounded-full text-xs font-semibold">Mix Mode</span>
                    <span class="px-3 py-1 bg-slate-50 text-slate-600 rounded-full text-xs font-semibold">Custom Timer</span>
                </div>
                <div class="mt-6 flex items-center text-indigo-600 font-bold group-hover:text-indigo-800 transition-colors">
                    <span>Play Now</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </a>

            <!-- Multiplication -->
            <a href="{{ route('math.multiplication') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center group-hover:bg-green-600 transition-colors">
                        <span class="text-3xl font-black text-green-600 group-hover:text-white transition-colors">×</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Available</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">4th Class Multiplication</h2>
                <p class="text-slate-500 mb-5">Master your multiplication tables from 0 to 12. Perfect for building speed and confidence!</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-green-50 text-green-700 rounded-full text-xs font-semibold">Tables 0–12</span>
                    <span class="px-3 py-1 bg-yellow-50 text-yellow-700 rounded-full text-xs font-semibold">Timed Mode</span>
                    <span class="px-3 py-1 bg-slate-50 text-slate-600 rounded-full text-xs font-semibold">Multiple Choice</span>
                </div>
                <div class="mt-6 flex items-center text-indigo-600 font-bold group-hover:text-indigo-800 transition-colors">
                    <span>Play Now</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </a>

            <!-- Number Bonds -->
            <a href="{{ route('math.number-bonds') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-teal-200 transition-all duration-200 hover:-translate-y-1">
                <div class="flex items-start justify-between mb-5">
                    <div class="w-16 h-16 bg-teal-100 rounded-2xl flex items-center justify-center group-hover:bg-teal-600 transition-colors">
                        <span class="text-3xl font-black text-teal-600 group-hover:text-white transition-colors">🔗</span>
                    </div>
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold uppercase tracking-wide">Available</span>
                </div>
                <h2 class="text-2xl font-extrabold text-slate-800 mb-2">Number Bonds</h2>
                <p class="text-slate-500 mb-5">Fill in the missing number on the bond tree. Practise the relationship between a whole and its two parts.</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-teal-50 text-teal-700 rounded-full text-xs font-semibold">Totals up to 100</span>
                    <span class="px-3 py-1 bg-cyan-50 text-cyan-700 rounded-full text-xs font-semibold">Missing any part</span>
                    <span class="px-3 py-1 bg-slate-50 text-slate-600 rounded-full text-xs font-semibold">Multiple Choice</span>
                </div>
                <div class="mt-6 flex items-center text-teal-600 font-bold group-hover:text-teal-800 transition-colors">
                    <span>Play Now</span>
                    <svg class="w-5 h-5 ml-2 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
                    </svg>
                </div>
            </a>

        </div>
    </div>
</x-app-layout>
