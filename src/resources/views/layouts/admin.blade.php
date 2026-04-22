<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ isset($title) ? $title . ' — ' : '' }}Admin · Simple Learning</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=nunito:400,500,600,700,800&display=swap" rel="stylesheet" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="h-full bg-slate-100 font-['Nunito']" x-data="{ sidebarOpen: false }">

    <div class="min-h-full flex">

        {{-- ── Sidebar ──────────────────────────────────────────────────── --}}
        {{-- Overlay (mobile) --}}
        <div x-show="sidebarOpen" x-transition.opacity
            @click="sidebarOpen = false"
            class="fixed inset-0 bg-black/50 z-20 md:hidden"></div>

        <aside
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-white flex flex-col
                   transform transition-transform duration-200 ease-in-out
                   md:relative md:translate-x-0 md:flex md:flex-shrink-0">

            {{-- Logo --}}
            <div class="h-16 flex items-center px-6 border-b border-slate-700">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-indigo-500 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                    </div>
                    <div>
                        <div class="font-extrabold text-sm leading-tight">Simple Learning</div>
                        <div class="text-xs text-slate-400 font-semibold">Admin Panel</div>
                    </div>
                </a>
            </div>

            {{-- Nav --}}
            <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
                <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-3 mb-3">Menu</p>

                <a href="{{ route('admin.dashboard') }}"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-semibold transition-colors
                        {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                    <span>Dashboard</span>
                </a>

                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-semibold transition-colors
                        {{ request()->routeIs('admin.users.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Users</span>
                </a>

                <a href="{{ route('admin.word-lists.index') }}"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-semibold transition-colors
                        {{ request()->routeIs('admin.word-lists.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Word Lists</span>
                </a>

                <a href="{{ route('admin.word-definitions.index') }}"
                    class="flex items-center space-x-3 px-3 py-2.5 rounded-xl font-semibold transition-colors
                        {{ request()->routeIs('admin.word-definitions.*') ? 'bg-indigo-600 text-white' : 'text-slate-300 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    <span>Word Definitions</span>
                </a>

                <a href="{{ route('admin.definition-groups.index') }}"
                    class="flex items-center space-x-3 pl-10 pr-3 py-2 rounded-xl font-semibold transition-colors text-sm
                        {{ request()->routeIs('admin.definition-groups.*') ? 'bg-indigo-600 text-white' : 'text-slate-400 hover:bg-slate-800 hover:text-white' }}">
                    <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                    </svg>
                    <span>Definition Groups</span>
                </a>

                <div class="pt-4 mt-4 border-t border-slate-700">
                    <p class="text-xs font-bold text-slate-500 uppercase tracking-widest px-3 mb-3">Site</p>
                    <a href="{{ route('home') }}" target="_blank"
                        class="flex items-center space-x-3 px-3 py-2.5 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white font-semibold transition-colors">
                        <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                        </svg>
                        <span>View Site</span>
                    </a>
                </div>
            </nav>

            {{-- Bottom user --}}
            <div class="border-t border-slate-700 p-4">
                <div class="flex items-center space-x-3">
                    <div class="w-9 h-9 bg-indigo-500 rounded-full flex items-center justify-center font-bold text-sm">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">&#64;{{ Auth::user()->username }}</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" title="Log out"
                            class="text-slate-400 hover:text-red-400 transition-colors p-1">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        {{-- ── Main content ──────────────────────────────────────────────── --}}
        <div class="flex-1 flex flex-col min-w-0">
            {{-- Top bar --}}
            <header class="h-16 bg-white border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 flex-shrink-0">
                <button @click="sidebarOpen = !sidebarOpen"
                    class="md:hidden p-2 rounded-lg text-slate-500 hover:bg-slate-100 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>

                <div class="flex items-center space-x-2">
                    @if(isset($title))
                    <h1 class="text-lg font-extrabold text-slate-800">{{ $title }}</h1>
                    @endif
                </div>

                <div class="flex items-center space-x-2">
                    <span class="hidden sm:inline px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">
                        Admin
                    </span>
                    <span class="text-sm font-semibold text-slate-600 hidden sm:inline">{{ Auth::user()->name }}</span>
                </div>
            </header>

            {{-- Flash messages --}}
            <div class="px-4 sm:px-6 pt-4 space-y-2">
                @if(session('success'))
                <div class="flex items-center space-x-3 bg-emerald-50 border border-emerald-200 text-emerald-800 rounded-xl px-4 py-3 text-sm font-semibold">
                    <svg class="w-5 h-5 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('success') }}</span>
                </div>
                @endif
                @if(session('error'))
                <div class="flex items-center space-x-3 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm font-semibold">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <span>{{ session('error') }}</span>
                </div>
                @endif
                @if($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">
                    <ul class="list-disc list-inside space-y-1">
                        @foreach($errors->all() as $error)
                            <li class="font-semibold">{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif
            </div>

            <main class="flex-1 px-4 sm:px-6 py-6">
                {{ $slot }}
            </main>
        </div>
    </div>

</body>
</html>
