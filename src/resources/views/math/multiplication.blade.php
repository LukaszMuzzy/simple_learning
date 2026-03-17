<x-app-layout>
    <x-slot name="title">4th Class Multiplication</x-slot>

    <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="mb-8">
            <div class="flex items-center space-x-3 mb-3">
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-medium">Home</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <a href="{{ route('math.index') }}" class="text-slate-400 hover:text-indigo-600 transition-colors text-sm font-medium">Mathematics</a>
                <svg class="w-4 h-4 text-slate-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                </svg>
                <span class="text-sm font-medium text-slate-600">4th Class Multiplication</span>
            </div>
        </div>

        @livewire('multiplication-game')
    </div>
</x-app-layout>
