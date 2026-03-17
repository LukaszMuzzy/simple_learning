<x-app-layout>
    <x-slot name="title">Welcome</x-slot>

    <!-- Hero -->
    <section class="relative bg-gradient-to-br from-indigo-600 via-indigo-700 to-purple-800 text-white overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 left-10 text-8xl font-black">+</div>
            <div class="absolute top-32 right-20 text-7xl font-black">×</div>
            <div class="absolute bottom-16 left-1/4 text-9xl font-black">−</div>
            <div class="absolute bottom-8 right-1/3 text-6xl font-black">÷</div>
        </div>
        <div class="relative max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 md:py-32 text-center">
            <div class="inline-flex items-center space-x-2 bg-white/20 backdrop-blur-sm rounded-full px-4 py-1.5 mb-6 text-sm font-semibold">
                <span class="w-2 h-2 bg-green-400 rounded-full animate-pulse"></span>
                <span>Free to play — No sign-up required</span>
            </div>
            <h1 class="text-4xl sm:text-5xl md:text-6xl font-extrabold tracking-tight mb-6 leading-tight">
                Learn Maths the<br>
                <span class="text-yellow-300">Fun Way</span>
            </h1>
            <p class="text-lg sm:text-xl text-indigo-100 max-w-2xl mx-auto mb-10">
                Interactive quizzes and games designed to build confidence with numbers — for all ages!
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="{{ route('math.index') }}"
                    class="px-8 py-4 bg-yellow-400 text-slate-900 rounded-xl font-bold text-lg hover:bg-yellow-300 transition-all duration-150 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    Start Playing Now 🚀
                </a>
                @guest
                <a href="{{ route('register') }}"
                    class="px-8 py-4 bg-white/20 backdrop-blur-sm text-white rounded-xl font-bold text-lg hover:bg-white/30 transition-all duration-150 border border-white/30">
                    Create Free Account
                </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Topics -->
    <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
        <div class="text-center mb-12">
            <h2 class="text-3xl font-extrabold text-slate-800 mb-3">Choose Your Topic</h2>
            <p class="text-slate-500 text-lg">Pick a subject and start practising today</p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Math Card -->
            <a href="{{ route('math.index') }}"
                class="group bg-white rounded-2xl p-8 shadow-sm border border-slate-100 hover:shadow-lg hover:border-indigo-200 transition-all duration-200 hover:-translate-y-1">
                <div class="w-16 h-16 bg-indigo-100 rounded-2xl flex items-center justify-center mb-5 group-hover:bg-indigo-600 transition-colors">
                    <span class="text-3xl">🔢</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">Mathematics</h3>
                <p class="text-slate-500 mb-4">Addition, subtraction, multiplication and more!</p>
                <div class="flex flex-wrap gap-2">
                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">2 games</span>
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">All ages</span>
                </div>
            </a>

            <!-- Coming Soon Cards -->
            @foreach([['📚', 'English', 'Spelling, grammar and vocabulary'], ['🌍', 'Geography', 'Countries, capitals and more']] as $topic)
            <div class="bg-white rounded-2xl p-8 shadow-sm border border-slate-100 opacity-60 cursor-not-allowed">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mb-5">
                    <span class="text-3xl">{{ $topic[0] }}</span>
                </div>
                <h3 class="text-xl font-bold text-slate-800 mb-2">{{ $topic[1] }}</h3>
                <p class="text-slate-500 mb-4">{{ $topic[2] }}</p>
                <span class="px-3 py-1 bg-slate-100 text-slate-500 rounded-full text-xs font-semibold">Coming Soon</span>
            </div>
            @endforeach
        </div>
    </section>

    <!-- Features -->
    <section class="bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8 text-center">
                @foreach([
                    ['🎯', 'Personalised Quizzes', 'Set your own difficulty, question count and time limits'],
                    ['📊', 'Track Your Progress', 'Create a free account to save results and see improvement over time'],
                    ['📱', 'Works Everywhere', 'Perfectly designed for phones, tablets and computers'],
                ] as $feature)
                <div>
                    <div class="text-4xl mb-4">{{ $feature[0] }}</div>
                    <h3 class="text-lg font-bold text-slate-800 mb-2">{{ $feature[1] }}</h3>
                    <p class="text-slate-500">{{ $feature[2] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- CTA -->
    @guest
    <section class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16 text-center">
        <h2 class="text-3xl font-extrabold text-slate-800 mb-4">Ready to track your progress?</h2>
        <p class="text-slate-500 text-lg mb-8">Sign up for free and unlock detailed progress tracking across all games.</p>
        <a href="{{ route('register') }}"
            class="inline-block px-8 py-4 bg-indigo-600 text-white rounded-xl font-bold text-lg hover:bg-indigo-700 transition-all duration-150 shadow-md hover:shadow-lg">
            Create Free Account →
        </a>
    </section>
    @endguest
</x-app-layout>
