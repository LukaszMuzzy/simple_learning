<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}Simple Learning</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="h-full bg-slate-50 font-['Nunito']">

    <!-- Navigation -->
    <nav class="bg-white shadow-sm border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <!-- Logo -->
                <div class="flex items-center space-x-3">
                    <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
                        <div class="w-9 h-9 bg-indigo-600 rounded-xl flex items-center justify-center shadow-md group-hover:bg-indigo-700 transition-colors">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z"/>
                            </svg>
                        </div>
                        <span class="text-xl font-extrabold text-slate-800 tracking-tight">Simple <span class="text-indigo-600">Learning</span></span>
                    </a>
                </div>

                <!-- Desktop Menu -->
                <div class="hidden md:flex items-center space-x-1">
                    <!-- Topics Dropdown -->
                    <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                        <button @click="open = !open"
                            class="flex items-center space-x-1 px-4 py-2 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 font-semibold transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                            </svg>
                            <span>Topics</span>
                            <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                        <div x-show="open" x-transition
                            class="absolute left-0 top-full mt-1 w-52 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                            <a href="{{ route('math.index') }}"
                                class="flex items-center space-x-3 px-4 py-2.5 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                <span class="text-xl">🔢</span>
                                <span class="font-semibold">Mathematics</span>
                            </a>
                            <a href="{{ route('english.index') }}"
                                class="flex items-center space-x-3 px-4 py-2.5 text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 transition-colors">
                                <span class="text-xl">📚</span>
                                <span class="font-semibold">English</span>
                            </a>
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('progress.index') }}"
                            class="flex items-center space-x-1 px-4 py-2 rounded-lg text-slate-600 hover:text-indigo-600 hover:bg-indigo-50 font-semibold transition-all duration-150">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                            </svg>
                            <span>My Progress</span>
                        </a>

                        <!-- User Dropdown -->
                        <div class="relative" x-data="{ open: false }" @click.outside="open = false">
                            <button @click="open = !open"
                                class="flex items-center space-x-2 px-4 py-2 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700 font-semibold transition-all duration-150">
                                <span>{{ Auth::user()->name }}</span>
                                <svg class="w-4 h-4 transition-transform" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                                </svg>
                            </button>
                            <div x-show="open" x-transition
                                class="absolute right-0 top-full mt-1 w-48 bg-white rounded-xl shadow-lg border border-slate-100 py-2 z-50">
                                <a href="{{ route('profile.edit') }}"
                                    class="flex items-center space-x-3 px-4 py-2.5 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                    <span class="font-medium">Profile</span>
                                </a>
                                <div class="border-t border-slate-100 my-1"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit"
                                        class="flex items-center space-x-3 w-full px-4 py-2.5 text-slate-700 hover:bg-red-50 hover:text-red-600 transition-colors text-left">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                        <span class="font-medium">Log Out</span>
                                    </button>
                                </form>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                            class="px-4 py-2 text-slate-600 hover:text-indigo-600 font-semibold transition-colors">
                            Log in
                        </a>
                        <a href="{{ route('register') }}"
                            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-semibold transition-all duration-150 shadow-sm">
                            Register
                        </a>
                    @endauth
                </div>

                <!-- Mobile Hamburger -->
                <div class="md:hidden" x-data="{ open: false }">
                    <button @click="open = !open"
                        class="p-2 rounded-lg text-slate-600 hover:bg-slate-100 transition-colors">
                        <svg x-show="!open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                        <svg x-show="open" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>

                    <!-- Mobile Menu -->
                    <div x-show="open" x-transition
                        class="absolute top-16 left-0 right-0 bg-white border-b border-slate-200 shadow-lg py-4 px-4 space-y-1">
                        <p class="text-xs font-bold text-slate-400 uppercase tracking-wider px-3 pb-1">Topics</p>
                        <a href="{{ route('math.index') }}"
                            class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 font-semibold">
                            <span>🔢</span><span>Mathematics</span>
                        </a>
                        <a href="{{ route('english.index') }}"
                            class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-emerald-50 hover:text-emerald-600 font-semibold">
                            <span>📚</span><span>English</span>
                        </a>
                        <div class="border-t border-slate-100 my-2"></div>
                        @auth
                            <a href="{{ route('progress.index') }}"
                                class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                                <span>My Progress</span>
                            </a>
                            <a href="{{ route('profile.edit') }}"
                                class="flex items-center space-x-3 px-3 py-2.5 rounded-lg text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 font-semibold">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <span>Profile</span>
                            </a>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit"
                                    class="flex items-center space-x-3 w-full px-3 py-2.5 rounded-lg text-red-600 hover:bg-red-50 font-semibold">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                    </svg>
                                    <span>Log Out</span>
                                </button>
                            </form>
                        @else
                            <a href="{{ route('login') }}"
                                class="block px-3 py-2.5 rounded-lg text-slate-700 hover:bg-indigo-50 font-semibold">Log in</a>
                            <a href="{{ route('register') }}"
                                class="block px-3 py-2.5 rounded-lg bg-indigo-600 text-white font-semibold text-center mt-2">Register</a>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <main>
        {{ $slot }}
    </main>

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 text-center text-sm text-slate-500">
            © {{ date('Y') }} Simple Learning — Making learning fun for everyone!
        </div>
    </footer>

    @livewireScripts
</body>
</html>
