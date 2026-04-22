<x-admin-layout>
    <x-slot name="title">Word Definitions</x-slot>

    {{-- Toolbar --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        {{-- Filters --}}
        <div class="flex flex-wrap items-center gap-2">
            @foreach(['all' => "All ({$counts['all']})", 'easy' => "⭐ Easy ({$counts['easy']})", 'medium' => "⭐⭐ Medium ({$counts['medium']})", 'hard' => "⭐⭐⭐ Hard ({$counts['hard']})"] as $key => $label)
            <a href="{{ route('admin.word-definitions.index', array_merge(request()->except('difficulty'), ['difficulty' => $key])) }}"
                class="px-3 py-1.5 rounded-xl text-xs font-bold border-2 transition-all
                    {{ $difficulty === $key ? 'bg-indigo-600 border-indigo-600 text-white' : 'bg-white border-slate-200 text-slate-600 hover:border-indigo-300' }}">
                {{ $label }}
            </a>
            @endforeach

            {{-- Search --}}
            <form method="GET" action="{{ route('admin.word-definitions.index') }}" class="flex items-center">
                <input type="hidden" name="difficulty" value="{{ $difficulty }}">
                <input type="search" name="search" value="{{ $search }}" placeholder="Search words…"
                    class="border border-slate-200 rounded-xl px-3 py-1.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none w-40">
            </form>
        </div>

        <a href="{{ route('admin.word-definitions.create') }}"
            class="flex items-center space-x-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm flex-shrink-0">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            <span>New Definition</span>
        </a>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-100">
                        <th class="text-left px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Word</th>
                        <th class="text-left px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Definition</th>
                        <th class="text-center px-4 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Level</th>
                        <th class="text-right px-6 py-3.5 font-bold text-slate-600 text-xs uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($definitions as $def)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4">
                            <span class="font-extrabold text-slate-800">{{ $def->word }}</span>
                        </td>
                        <td class="px-4 py-4 text-slate-500 max-w-xs">
                            <span class="line-clamp-2">{{ $def->definition }}</span>
                        </td>
                        <td class="px-4 py-4 text-center">
                            @php
                                $badge = match($def->difficulty) {
                                    'easy'   => 'bg-emerald-100 text-emerald-700',
                                    'medium' => 'bg-amber-100 text-amber-700',
                                    'hard'   => 'bg-red-100 text-red-700',
                                    default  => 'bg-slate-100 text-slate-600',
                                };
                                $label = match($def->difficulty) {
                                    'easy'   => '⭐ Easy',
                                    'medium' => '⭐⭐ Medium',
                                    'hard'   => '⭐⭐⭐ Hard',
                                    default  => $def->difficulty,
                                };
                            @endphp
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badge }}">
                                {{ $label }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-end space-x-1">
                                <a href="{{ route('admin.word-definitions.edit', $def) }}"
                                    class="p-2 rounded-lg text-indigo-500 hover:bg-indigo-50 transition-colors" title="Edit">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                </a>
                                <form method="POST" action="{{ route('admin.word-definitions.destroy', $def) }}"
                                    x-data
                                    @submit.prevent="if(confirm('Delete definition for \"{{ $def->word }}\"?')) $el.submit()">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="p-2 rounded-lg text-red-400 hover:bg-red-50 transition-colors" title="Delete">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-16 text-center text-slate-400">
                            <div class="text-4xl mb-2">📖</div>
                            <p class="font-semibold">No definitions found.</p>
                            @if($search)
                            <p class="text-sm mt-1">Try a different search term.</p>
                            @else
                            <a href="{{ route('admin.word-definitions.create') }}" class="text-indigo-600 hover:underline font-semibold mt-1 inline-block">
                                Add the first definition →
                            </a>
                            @endif
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</x-admin-layout>
