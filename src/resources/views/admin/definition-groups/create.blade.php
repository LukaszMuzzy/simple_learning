<x-admin-layout>
    <x-slot name="title">New Definition Group</x-slot>

    <div class="max-w-xl">
        <div class="mb-6">
            <a href="{{ route('admin.definition-groups.index') }}" class="text-sm text-indigo-600 hover:text-indigo-700 font-semibold">
                ← Back to Definition Groups
            </a>
        </div>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100">
            <div class="px-6 py-5 border-b border-slate-100">
                <h2 class="font-extrabold text-slate-800">Create Definition Group</h2>
                <p class="text-slate-500 text-sm mt-0.5">After creating the group you can add definitions to it from the edit page.</p>
            </div>

            <form method="POST" action="{{ route('admin.definition-groups.store') }}" class="p-6 space-y-5"
                x-data="{ label: '', slug: '' }">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Display Name <span class="text-red-500">*</span></label>
                    <input type="text" name="label" value="{{ old('label') }}" required autofocus
                        x-model="label"
                        x-on:input="slug = label.toLowerCase().replace(/[^a-z0-9]+/g, '_').replace(/^_|_$/g, '')"
                        placeholder="e.g. Year 4 Vocabulary"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">
                        Slug <span class="text-red-500">*</span>
                        <span class="text-slate-400 font-normal text-xs ml-1">— unique identifier</span>
                    </label>
                    <input type="text" name="slug" value="{{ old('slug') }}" required
                        x-model="slug"
                        placeholder="e.g. year4_vocab"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm font-mono focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-1.5">Description <span class="text-slate-400 font-normal">(optional)</span></label>
                    <textarea name="description" rows="2"
                        placeholder="Brief description of this group…"
                        class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none resize-none">{{ old('description') }}</textarea>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-1.5">Sort Order</label>
                        <input type="number" name="sort_order" value="{{ old('sort_order', 99) }}" min="0"
                            class="w-full border border-slate-200 rounded-xl px-4 py-2.5 text-sm focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 outline-none">
                        <p class="text-xs text-slate-400 mt-1">Lower = appears first in dropdown</p>
                    </div>
                    <div class="flex items-center pt-6">
                        <label class="flex items-center space-x-3 cursor-pointer">
                            <input type="hidden" name="is_active" value="0">
                            <input type="checkbox" name="is_active" value="1" checked
                                class="w-4 h-4 text-indigo-600 rounded border-slate-300 focus:ring-indigo-400">
                            <span class="text-sm font-bold text-slate-700">Active (visible in game)</span>
                        </label>
                    </div>
                </div>

                <div class="flex items-center space-x-3 pt-2">
                    <button type="submit"
                        class="px-6 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl font-bold text-sm transition-colors shadow-sm">
                        Create Group →
                    </button>
                    <a href="{{ route('admin.definition-groups.index') }}"
                        class="px-4 py-2.5 text-slate-500 hover:text-slate-700 font-semibold text-sm">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-admin-layout>
