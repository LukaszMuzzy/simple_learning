<x-admin-layout>
    <x-slot name="title">New Word Definition</x-slot>

    <div class="max-w-xl">
        <div class="mb-6">
            <a href="{{ route('admin.word-definitions.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Word Definitions
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Add Word Definition</h2>
                <p class="text-slate-500 text-sm mt-0.5">This word will be available in the Word Definitions game.</p>
            </div>

            <form method="POST" action="{{ route('admin.word-definitions.store') }}" class="p-6 space-y-5">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Word <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="word" value="{{ old('word') }}" required autofocus
                        placeholder="e.g. resilient"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none font-semibold">
                    <p class="text-xs text-slate-400 mt-1">Each word must be unique across all difficulties.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Definition <span class="text-red-500">*</span>
                    </label>
                    <textarea name="definition" rows="3" required
                        placeholder="e.g. able to recover quickly after difficulties or setbacks"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none resize-none">{{ old('definition') }}</textarea>
                    <p class="text-xs text-slate-400 mt-1">Keep it clear and child-friendly. Max 500 characters.</p>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Difficulty <span class="text-red-500">*</span>
                    </label>
                    <div class="flex gap-3">
                        @foreach(['easy' => ['⭐ Easy', 'emerald'], 'medium' => ['⭐⭐ Medium', 'amber'], 'hard' => ['⭐⭐⭐ Hard', 'red']] as $value => [$label, $colour])
                        <label class="flex-1 cursor-pointer">
                            <input type="radio" name="difficulty" value="{{ $value }}" {{ old('difficulty', 'medium') === $value ? 'checked' : '' }} class="sr-only peer">
                            <div class="text-center px-3 py-3 rounded-xl border-2 font-bold text-sm transition-all
                                border-slate-200 text-slate-500
                                peer-checked:border-{{ $colour }}-500 peer-checked:bg-{{ $colour }}-50 peer-checked:text-{{ $colour }}-700">
                                {{ $label }}
                            </div>
                        </label>
                        @endforeach
                    </div>
                </div>

                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                        Save Definition →
                    </button>
                    <a href="{{ route('admin.word-definitions.index') }}"
                        class="px-4 py-2.5 text-slate-500 hover:text-slate-700 font-semibold text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
