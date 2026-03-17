<x-admin-layout>
    <x-slot name="title">{{ $wordList->label }}</x-slot>

    <div class="mb-6">
        <a href="{{ route('admin.word-lists.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
            ← Back to Word Lists
        </a>
    </div>

    <div class="grid grid-cols-1 xl:grid-cols-5 gap-6 items-start">

        {{-- ── Left: list details ─────────────────────────────────────────── --}}
        <div class="xl:col-span-2 space-y-4">

            {{-- Details form --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="font-extrabold text-slate-800">List Details</h2>
                </div>

                <form method="POST" action="{{ route('admin.word-lists.update', $wordList) }}" class="p-6 space-y-4">
                    @csrf @method('PATCH')

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Display Name</label>
                        <input type="text" name="label" value="{{ old('label', $wordList->label) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Slug</label>
                        <input type="text" name="slug" value="{{ old('slug', $wordList->slug) }}" required
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Description</label>
                        <textarea name="description" rows="2"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none resize-none">{{ old('description', $wordList->description) }}</textarea>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-bold text-slate-700 mb-1.5">Sort Order</label>
                            <input type="number" name="sort_order" value="{{ old('sort_order', $wordList->sort_order) }}" min="0"
                                class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                        </div>
                        <div class="flex items-end pb-1">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="hidden" name="is_active" value="0">
                                <input type="checkbox" name="is_active" value="1" {{ $wordList->is_active ? 'checked' : '' }}
                                    class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400">
                                <span class="text-sm font-bold text-slate-700">Active</span>
                            </label>
                        </div>
                    </div>

                    <button type="submit"
                        class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors">
                        Save Details
                    </button>
                </form>
            </div>

            {{-- Add words --}}
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
                <div class="px-6 py-4 border-b border-slate-100">
                    <h2 class="font-extrabold text-slate-800">Add Words</h2>
                    <p class="text-slate-500 text-xs mt-0.5">One word per line, or separate by commas. Duplicates are ignored.</p>
                </div>

                <form method="POST" action="{{ route('admin.word-lists.add-words', $wordList) }}" class="p-6 space-y-3">
                    @csrf

                    <textarea name="words_input" rows="7" required
                        placeholder="apple&#10;banana&#10;cherry&#10;or: apple, banana, cherry"
                        class="w-full border border-slate-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-emerald-400 focus:border-emerald-400 outline-none resize-y font-mono"></textarea>

                    <button type="submit"
                        class="w-full py-2.5 bg-emerald-500 hover:bg-emerald-600 text-white rounded-xl font-bold text-sm transition-colors">
                        + Add Words
                    </button>
                </form>
            </div>

            {{-- Danger zone --}}
            <div class="bg-white rounded-2xl shadow-sm border border-red-100">
                <div class="px-6 py-4 border-b border-red-100">
                    <h2 class="font-extrabold text-red-700 text-sm">Danger Zone</h2>
                </div>
                <div class="p-6">
                    <p class="text-xs text-slate-500 mb-3">Permanently delete this list and all {{ $words->count() }} words. Cannot be undone.</p>
                    <form method="POST" action="{{ route('admin.word-lists.destroy', $wordList) }}"
                        x-data
                        @submit.prevent="if(confirm('Delete the entire \"{{ $wordList->label }}\" list?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="w-full py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-bold text-sm transition-colors">
                            Delete List
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- ── Right: word browser ─────────────────────────────────────────── --}}
        <div class="xl:col-span-3" x-data="{ search: '' }">
            <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
                <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between gap-3">
                    <div>
                        <h2 class="font-extrabold text-slate-800">Words
                            <span class="ml-2 px-2.5 py-0.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-bold">
                                {{ $words->count() }}
                            </span>
                        </h2>
                    </div>
                    <div class="relative flex-1 max-w-xs">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        <input type="text" x-model="search" placeholder="Filter words…"
                            class="w-full pl-9 pr-4 py-2 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-indigo-400 outline-none">
                    </div>
                </div>

                @if($words->count())
                <div class="p-5 flex flex-wrap gap-2 max-h-[60vh] overflow-y-auto">
                    @foreach($words as $word)
                    <div x-show="search === '' || '{{ strtolower($word->word) }}'.includes(search.toLowerCase())"
                        class="group flex items-center space-x-1.5 px-3 py-1.5 bg-slate-100 hover:bg-red-50 rounded-xl transition-colors">
                        <span class="text-sm font-semibold text-slate-700 group-hover:text-red-600">{{ $word->word }}</span>
                        <form method="POST" action="{{ route('admin.word-lists.destroy-word', [$wordList, $word]) }}">
                            @csrf @method('DELETE')
                            <button type="submit"
                                title="Remove"
                                class="text-slate-300 group-hover:text-red-400 hover:text-red-600 transition-colors leading-none"
                                onclick="return confirm('Remove \'{{ $word->word }}\' from this list?')">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                <div class="px-5 pb-4 text-xs text-slate-400 font-semibold">
                    Hover a word chip and click × to remove it.
                </div>
                @else
                <div class="py-16 text-center text-slate-400">
                    <div class="text-4xl mb-2">📭</div>
                    <p class="font-semibold">No words yet.</p>
                    <p class="text-sm mt-1">Use the form on the left to add words.</p>
                </div>
                @endif
            </div>
        </div>

    </div>
</x-admin-layout>
