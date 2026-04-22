<x-admin-layout>
    <x-slot name="title">Edit Definition — {{ $wordDefinition->word }}</x-slot>

    <div class="max-w-xl">
        <div class="mb-6">
            <a href="{{ route('admin.word-definitions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Word Definitions
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Edit Definition</h2>
                <p class="text-slate-500 text-sm mt-0.5">
                    Changes apply to all future Word Definitions games immediately.
                </p>
            </div>

            <form method="POST" action="{{ route('admin.word-definitions.update', $wordDefinition) }}" class="p-6 space-y-5">
                @csrf @method('PATCH')

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Word <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="word" value="{{ old('word', $wordDefinition->word) }}" required autofocus
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none font-semibold">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Definition <span class="text-red-500">*</span>
                    </label>
                    <textarea name="definition" rows="3" required
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none resize-none">{{ old('definition', $wordDefinition->definition) }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">Max 500 characters.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Difficulty <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3">
                        @foreach(['easy' => ['⭐ Easy', 'emerald'], 'medium' => ['⭐⭐ Medium', 'amber'], 'hard' => ['⭐⭐⭐ Hard', 'red']] as $value => [$label, $colour])
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="difficulty" value="{{ $value }}"
                                {{ old('difficulty', $wordDefinition->difficulty) === $value ? 'checked' : '' }}
                                class="sr-only peer">
                            <div class="text-center px-3 py-3 rounded-xl border-2 font-bold text-sm transition-all
                                border-slate-200 text-slate-500
                                peer-checked:border-{{ $colour }}-500 peer-checked:bg-{{ $colour }}-50 peer-checked:text-{{ $colour }}-700">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center space-x-3">
                        <button type="submit"
                            class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                            Save Changes
                        </button>
                        <a href="{{ route('admin.word-definitions.index') }}"
                            class="px-4 py-2.5 text-slate-500 hover:text-slate-700 font-semibold text-sm">
                            Cancel
                        </a>
                    </div>

                    {{-- Danger zone --}}
                    <form method="POST" action="{{ route('admin.word-definitions.destroy', $wordDefinition) }}"
                        x-data
                        @submit.prevent="if(confirm('Delete definition for \"{{ $wordDefinition->word }}\"?')) $el.submit()">
                        @csrf @method('DELETE')
                        <button type="submit"
                            class="flex items-center space-x-1.5 px-4 py-2.5 text-red-500 hover:text-red-600 hover:bg-red-50 rounded-xl font-bold text-sm transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            <span>Delete</span>
                        </button>
                    </form>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
